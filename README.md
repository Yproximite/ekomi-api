ekomi-api
=========

PHP Library to get v1 API

* [Usage](#usage)

Usage
-----

```php
use Yproximite\Ekomi\Api\Client;
use Yproximite\Ekomi\Api\Model\OrderCollection;

$client = new Client(
    HttpClient $httpClient,
    $clientId = '999999',
    $secretKey = 'xxxxxxxxxxxxxx',
    MessageFactory $messageFactory = null
);

$request = new GetOrdersRequest();
$request
    ->setOffset(5)
    ->setLimit(10)
    ->setCreatedFrom(new \DateTime('2016-10-06 00:00:10'))
;

// @var OrderCollection
$response = $client->sendRequest($request);
```
