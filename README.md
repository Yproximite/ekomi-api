ekomi-api
=========

PHP Library to get v3 API

* [Usage](#usage)

Usage
-----

```php
use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Service\ServiceAggregator;
use Yproximite\Ekomi\Api\Message\Order\OrderListMessage;

$client = new Client(
    HttpClient $httpClient,
    string $clientId = '999999',
    string $secretKey = 'xxxxxxxxxxxxxx',
    string $baseUrl = Client::BASE_URL,
    MessageFactory $messageFactory = null,
    CacheItemPoolInterface $cache = null
    string $cacheKey = null
);

$api = new ServiceAggregator($client);

$message = new OrderListMessage();
$message->setOffset(5);
$message->setLimit(10);
$message->setOrderBy(OrderListMessage::ORDER_BY_CREATED);
$message->setOrderDirection(OrderListMessage::ORDER_DIRECTION_DESC);
$message->setWithFeedbackOnly(true);
$message->setCreatedFrom(new \DateTime('2016-10-06 00:00:10'));
$message->setCreatedTill(new \DateTime('2016-11-06 00:14:29'));
$message->setShopId(11);
$message->setCustomDataFilter(['vendor_id' => 123]);

// Yproximite\Ekomi\Api\Model\Order\Order[]
$response = $api->order()->getOrders($message);
```

Test
-----

```bash
./bin/phpspec run
```
