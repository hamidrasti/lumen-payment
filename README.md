# lumen-payment
A Lumen package for connecting to payment gateways.

By this  package we are able to connect to all Iranian bank with one unique API.

Please inform us once you've encountered [bug](https://github.com/hamraa/lumen-payment/issues) or [issue](https://github.com/hamraa/lumen-payment/issues).

Available Banks:
 1. MELLAT
 2. SADAD (MELLI)
 3. SAMAN
 4. PARSIAN
 5. PASARGAD
 6. ZARINPAL
 7. PAYPAL (**New**)
 8. ASAN PARDAKHT (**New**)
 9. PAY.IR (**New**) (to use : new \Payir())
----------


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

## Usage
You can make connection to bank in several ways (Facade , Service container):

```php
try {
       
   $gateway = \Gateway::make(new \Mellat());

   // $gateway->setCallback(url('/path/to/callback/route')); You can also change the callback
   $gateway
        ->price(1000)
        // setShipmentPrice(10) // optional - just for paypal
        // setProductName("My Product") // optional - just for paypal
        ->ready();

   $refId =  $gateway->refId(); // شماره ارجاع بانک
   $transID = $gateway->transactionId(); // شماره تراکنش

  // در اینجا
  //  شماره تراکنش  بانک را با توجه به نوع ساختار دیتابیس تان 
  //  در جداول مورد نیاز و بسته به نیاز سیستم تان
  // ذخیره کنید .
  
   return $gateway->redirect();
       
} catch (\Exception $e) {
    // نمایش خطای تراکنش
    echo $e->getMessage();
}
```

You can call the gateway by these ways :
 1. Gateway::make(new Mellat());
 1. Gateway::mellat()
 2. app('gateway')->make(new Mellat());
 3. app('gateway')->mellat();

Instead of MELLAT you can enter other banks Name as we introduced above .

In `price` method you should enter the price in IRR (RIAL) 

and in your callback :

```php
try { 
       
   $gateway = \Gateway::verify();
   $trackingCode = $gateway->trackingCode();
   $refId = $gateway->refId();
   $cardNumber = $gateway->cardNumber();
   
    // تراکنش با موفقیت سمت بانک تایید گردید
    // در این مرحله عملیات خرید کاربر را تکمیل میکنیم
    
} catch (\Hamraa\Payment\Exceptions\RetryException $e) {

    // تراکنش قبلا سمت بانک تاییده شده است و
    // کاربر احتمالا صفحه را مجددا رفرش کرده است
    // لذا تنها فاکتور خرید قبل را مجدد به کاربر نمایش میدهیم
    
    echo $e->getMessage() . "<br>";
    
} catch (\Exception $e) {

    // نمایش خطای بانک
    echo $e->getMessage();
}
```
## Credits 
This package is based on [larabook/gateway](https://github.com/larabook/gateway) (payment package for laravel)
with some major change to compatible with Lumen framework and some code improvments.

## Thanks
Special thanks to [larabook team](http://larabook.ir).