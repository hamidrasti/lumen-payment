# lumen-payment
A Lumen package for connecting to payment gateways

## Installation

First, install the package via Composer:

``` bash
composer require hamraa/lumen-payment
```

Copy the required files:

```bash
mkdir -p config
cp vendor/hamraa/lumen-payment/config/payment.php config/payment.php
cp vendor/hamraa/lumen-payment/database/migrations/create_payment_transactions_table.php.stub database/migrations/2016_01_01_000000_create_payment_transactions_table.php
cp vendor/hamraa/lumen-payment/database/migrations/create_payment_transactions_status_logs_table.php.stub database/migrations/2016_01_01_000000_create_payment_transactions_status_logs_table.php
```


Then, in `bootstrap/app.php` : 

- register the alias:

```php
$app->withFacades(true, [
    'Hamraa\Payment\Gateway' => 'Gateway',
]);
```

- register the config file:

```php
$app->configure('payment');
```

- register the service provider:

```php
$app->register(\Hamraa\Payment\PaymentServiceProvider::class);
```

Now, run your migrations:

```bash
php artisan migrate
```

Done!