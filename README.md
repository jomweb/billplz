PHP library for working with BillPlz API
==============

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "crynobone/billplz": "~1.0",
        "php-http/guzzle6-adapter": "^1.1"
    }
}
```

### HTTP Adapter

Instead of utilizing `php-http/curl-client` you might want to use any other adapter that implements `php-http/client-implementation`. Check [Clients & Adapters](http://docs.php-http.org/en/latest/clients.html) for PHP-HTTP.

## Usages

### Creating Billplz Client

You can start by creating a Billplz client by using the following code (which uses `php-http/guzzle6-adapter`):

```php
<?php

use Billplz\Client;
use Http\Client\Common\HttpMethodsClient;
use Http\Adapter\Guzzle6\Client as GuzzleHttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;

$http = new HttpMethodsClient(
    new GuzzleHttpClient(), 
    new GuzzleMessageFactory()
);


$billplz = new Client($http, 'your-api-key');
```

### Creating Collection instance

Now you can create an instance of Collection:

```php
use Billplz\Collection;

$collection = new Collection($billplz);
```


#### Adding new Collection

You can add a new collection by calling the following code:

```php
$response = $collection->create('My First API Collection');

$body = json_decode($response->getBody(), true);

var_dump($body);
```

```json
{
  "id": "inbmmepb",
  "title": "My First API Collection",
  "logo": 
  {
    "thumb_url": null,
    "avatar_url": null
  },
  "split_payment": 
  {
    "email": null,
    "fixed_cut": null,
    "variable_cut": null
  }
}
```

You can also create new collection with optional parameters:

```php
$response = $collection->create('My First API Collection', [
    'logo' => '@/Users/Billplz/Documents/uploadPhoto.png',
    'split_payment[email]' => 'verified@account.com',
    'split_payment[fixed_cut]' => 100,
]);

$body = json_decode($response->getBody(), true);

var_dump($body);
```

```json
{
  "id": "inbmmepb",
  "title": "My First API Collection",
  "logo":
  {
    "thumb_url": https://sample.net/assets/uploadPhoto.png,
    "avatar_url": https://sample.net/assets/uploadPhoto.png
  },
  "split_payment": 
  {
    "email": "verified@account.com",
    "fixed_cut": 100,
    "variable_cut": null
  }
}
```
