# LariumPay

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Larium/larium-pay/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Larium/larium-pay/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Larium/larium-pay/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Larium/larium-pay/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/Larium/larium-pay/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Larium/larium-pay/build-status/master)

A unified API to access different payment gateways.

## Basic usage

```php
<?php
use Larium\CreditCard\CreditCard;
use Larium\Pay\Gateway\MyGateway; //Use a gateway
use Larium\Pay\Transaction\PurchaseTransaction;

# Set up a Card object
$card = new CreditCard([
    "holderName" => "John Doe",
    "number" => "41111111111111",
    "month" => "12",
    "year" => "2020",
    "cvv" => "123"
]);

# Set up a transaction
$amount = 1000; # Amount in cents.
$txn = new PurchaseTransaction($amount, $card);

# Set up the gateway
$options = [
    'login'=>'user-login',
    'password'=>'SeCRetPasSwoRd',
];
$gateway = new MyGateway($options);

$response = $gateway->execute($txn);

$response->isSuccess(); # true or false

echo $response->getTransactionId(); # Get unique id reference.
```
