<?php

namespace App\Services\Commissions;

use App\Services\Currency\CurrencyConfiguratorInterface;
use App\Services\Rates\RatesInterface;
use App\Services\Transaction\TransactionInterface;
use App\Services\Transactions\TransactionList;
use App\Services\Transactions\TransactionListInterface;
use Carbon\Carbon;
use Exception;

final class CommissionCalculator implements CommissionCalculatorInterface
{
    private RatesInterface $ratesService;
    private CurrencyConfiguratorInterface $currencyConfigurator;
    /**
     * @var array<string,mixed>
     */
    private array $transactionsStatistic = [];

    /**
     * @param RatesInterface $ratesService
     * @param CurrencyConfiguratorInterface $currencyConfigurator
     */
    public function __construct(RatesInterface $ratesService, CurrencyConfiguratorInterface $currencyConfigurator)
    {
        $this->ratesService = $ratesService;
        $this->currencyConfigurator = $currencyConfigurator;
    }

    /**
     * @param TransactionList $transactionList
     * @return TransactionList
     * @throws Exception
     */
    public function calculate(
        TransactionListInterface $transactionList
    ): TransactionListInterface {
        $transactionList1 = $transactionList;
        foreach ($transactionList1 as $transaction) {
            $scale = $this->currencyConfigurator->getScaleForCurrency($transaction->getCurrency());
            $fee = $this->roundUp(
                $this->getFee($transaction),
                $scale
            );
            $transaction->setCommission($fee);
        }
        return $transactionList1;
    }

    /**
     * @param float $number
     * @param int $scale
     * @return float
     */
    public function roundUp(float $number, int $scale): float
    {
        $scaleNum = pow(10, $scale);
        return ceil($number * $scaleNum) / $scaleNum;
    }

    /**
     * @throws Exception
     */
    private function getFee(TransactionInterface $transaction): float
    {
        if ('deposit' === strtolower($transaction->getTransactionType())) {
            return $transaction->getAmount() * (config('commissions.deposit') / 100);
        }
        if ('business' === strtolower($transaction->getClientType())) {
            return $transaction->getAmount() * (config('commissions.withdraw_business') / 100);
        }
        if (
            'withdraw' === strtolower($transaction->getTransactionType())
            && 'private' === strtolower($transaction->getClientType())
        ) {
            $statistic = $this->getWeeklyStatisticForTransaction($transaction);
//            dump($transaction, $statistic);
            $maxFreeWeeklyAmount = config('commissions.week_free_of_charge_amount_sum');
            if ($statistic['sum'] > $maxFreeWeeklyAmount && $statistic['amount_subtracted']) {
                return $transaction->getAmount() * (config('commissions.withdraw_private') / 100);
            }
            if ($statistic['sum'] > $maxFreeWeeklyAmount && !$statistic['amount_subtracted']) {
                $feeInEur = ($statistic['sum'] - $maxFreeWeeklyAmount)
                    * (config('commissions.withdraw_private') / 100);
                return $feeInEur * $this->ratesService->getRate($transaction->getCurrency());
            }
            if ($statistic['count'] > config('commissions.week_free_of_charge_tr_count')) {
                return $transaction->getAmount() * (config('commissions.withdraw_private') / 100);
            }
            return 0.0;
        }
        throw new Exception("No fee rule for transaction");
    }

    /**
     * @param TransactionInterface $transaction
     * @return array<string,float|bool>
     */
    private function getWeeklyStatisticForTransaction(TransactionInterface $transaction): array
    {
        $startOfWeek = Carbon::createFromFormat('Y-m-d', $transaction->getDate())->startOfWeek();
        $endOfWeek = Carbon::createFromFormat('Y-m-d', $transaction->getDate())->endOfWeek();
        $weekKey = $startOfWeek->format('Y-m-d') . '-' . $endOfWeek->format('Y-m-d');
        $type = $transaction->getTransactionType();
        $clientType = $transaction->getClientType();
        $clientId = $transaction->getClientId();
        $rate = $this->ratesService->getRate($transaction->getCurrency());
        $amountInEur = $transaction->getAmount() / $rate;
        $maxFreeWeeklyAmount = config('commissions.week_free_of_charge_amount_sum');

        if (!key_exists($weekKey, $this->transactionsStatistic)) {
            $this->transactionsStatistic[$weekKey] = [];
        }
        if (!key_exists($type, $this->transactionsStatistic[$weekKey])) {
            $this->transactionsStatistic[$weekKey][$type] = [];
        }
        if (!key_exists($clientType, $this->transactionsStatistic[$weekKey][$type])) {
            $this->transactionsStatistic[$weekKey][$type][$clientType] = [];
        }
        if (!key_exists($clientId, $this->transactionsStatistic[$weekKey][$type][$clientType])) {
            $this->transactionsStatistic[$weekKey][$type][$clientType][$clientId] = [
                'count' => 0,
                'sum' => 0,
                'amount_subtracted' => false
            ];
        }
        if ($this->transactionsStatistic[$weekKey][$type][$clientType][$clientId]['sum'] > $maxFreeWeeklyAmount) {
            $this->transactionsStatistic[$weekKey][$type][$clientType][$clientId]['amount_subtracted'] = true;
        }
        $this->transactionsStatistic[$weekKey][$type][$clientType][$clientId]['count'] += 1;
        $this->transactionsStatistic[$weekKey][$type][$clientType][$clientId]['sum'] += $amountInEur;
        return $this->transactionsStatistic[$weekKey][$type][$clientType][$clientId];
    }
}
