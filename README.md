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
