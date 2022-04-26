# Home assigment Commission Calculator

## Installation

1) clone the repository
2) navigate to project folder
3) run ```composer install```
4) copy .env.example as .env
5) run (optional) ```php artisan config:cache```

## Running
```shell
php artisan calculate:commission input.csv
```

Output 
```
0.60
3.00
0.00
0.06
1.50
0
0.70
0.30
0.30
3.00
0.00
0.00
8612

```

## Run test

```shell
php artisan test
```

## Code styles

### PHPStan
```
./vendor/bin/phpstan analyze 
```
if necessary add memory limit 

```shell
./vendor/bin/phpstan analyze --memory-limit=1G
```

### PHPCs

```shell
 ./vendor/bin/phpcs
```
