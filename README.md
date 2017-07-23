PHP framework agnostic library for working with BillPlz API v3 and beyond...
==============

[![Build Status](https://travis-ci.org/jomweb/billplz.svg?branch=master)](https://travis-ci.org/jomweb/billplz)
[![Latest Stable Version](https://poser.pugx.org/jomweb/billplz/version)](https://packagist.org/packages/jomweb/billplz)
[![Total Downloads](https://poser.pugx.org/jomweb/billplz/downloads)](https://packagist.org/packages/jomweb/billplz)
[![Latest Unstable Version](https://poser.pugx.org/jomweb/billplz/v/unstable)](//packagist.org/packages/jomweb/billplz)
[![License](https://poser.pugx.org/jomweb/billplz/license)](https://packagist.org/packages/jomweb/billplz)

* [Installation](#installation)
* [Usages](#usages)
  - [Creating Billplz Client](#creating-billplz-client)
  - [Creating Collection Request](#creating-collection-request)
  - [Creating Bill Request](#creating-bill-request)
* [Handling Response](#handling-response)
  - [Checking the Response HTTP Status](#checking-the-response-http-status)
  - [Checking the Response Header](#checking-the-response-header)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "jomweb/billplz": "^0.6",
        "php-http/guzzle6-adapter": "^1.1"
    }
}
```

### HTTP Adapter

Instead of utilizing `php-http/guzzle6-adapter` you might want to use any other adapter that implements `php-http/client-implementation`. Check [Clients & Adapters](http://docs.php-http.org/en/latest/clients.html) for PHP-HTTP.

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

You could also use `php-http/discovery` to automatically pick available adapter installed via composer:

```php
<?php

use Billplz\Client;

$billplz = Client::make('your-api-key');
```

#### Using Sandbox

You can set to use development/sandbox environment by adding the following code:

```php
$billplz->useSandbox();
```

#### Using different API Version

By default `jomweb/billplz` would use `v3` API version for any request, however you can customize this in future when new API version is available.

```php
$billplz->useVersion('v4');
```

### Creating Collection Request

Now you can create an instance of Collection:

```php
$collection = $billplz->collection();
```

> You can also manually set the API version by doing `$billplz->collection('v3');`. You can also use `$billplz->resource('Collection');` to get the same result.

#### Create a Collection

You can add a new collection by calling the following code:

```php
$response = $collection->create('My First API Collection');

var_dump($response->toArray());
```

```php
return [
  "id" => "inbmmepb",
  "title" => "My First API Collection",
  "logo" => [
    "thumb_url" => null,
    "avatar_url" => null
  ],
  "split_payment" => [
    "email" => null,
    "fixed_cut" => null,
    "variable_cut" => null
  ]
];
```

You can also create new collection with optional parameters:

```php
$response = $collection->create('My First API Collection', [
    'logo' => '@/Users/Billplz/Documents/uploadPhoto.png',
    'split_payment' => [
      'email' => 'verified@account.com',
      'fixed_cut' => \Money\Money::MYR(100),
    ],
]);

var_dump($response->toArray());
```

```php
return [
  "id" => "inbmmepb",
  "title" => "My First API Collection",
  "logo" => [
    "thumb_url" => "https://sample.net/assets/uploadPhoto.png",
    "avatar_url" => "https://sample.net/assets/uploadPhoto.png"
  ],
  "split_payment" => [
    "email" => "verified@account.com",
    "fixed_cut" => \Money\Money::MYR(100),
    "variable_cut" => null
  ]
]
```

#### Create an Open Collection

```php
$response = $collection->createOpen(
    'My First API Collection', 
    'Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.',
    Money\Money::MYR(299)
);

var_dump($response->toArray());
```

```php
return [
  "id" => "0pp87t_6",
  "title" => "MY FIRST API OPEN COLLECTION",
  "description" => "Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.",
  "reference_1_label" => null,
  "reference_2_label" => null,
  "email_link" => null,
  "amount" => \Money\Money::MYR(299),
  "fixed_amount" => true,
  "tax" => null,
  "fixed_quantity" => true,
  "payment_button" => "pay",
  "photo" => [
    "retina_url" =>  null,
    "avatar_url" =>  null
  ],
  "split_payment" => [
    "email" => null,
    "fixed_cut" => null,
    "variable_cut" => null
  ],
  "url" => "https://www.billplz.com/0pp87t_6"
]
```


### Creating Bill Request 

Now you can create an instance of Bill:

```php
$bill = $billplz->bill();
```

> You can also manually set the API version by doing `$billplz->bill('v3');`. You can also use `$billplz->resource('Bill');` to get the same result.

#### Create a Bill

You can add a new bill by calling the following code:

```php
$response = $bill->create(
  'inbmmepb',
  'api@billplz.com',
  null,
  'Michael API V3',
  Money\Money::MYR(200),
  'http://example.com/webhook/',
  'Maecenas eu placerat ante.'
);

var_dump($response->toArray());
```

```php
return [
  "id" => "8X0Iyzaw",
  "collection_id" => "inbmmepb",
  "paid" => false,
  "state" => "overdue",
  "amount" => \Money\Money::MYR(200),
  "paid_amount" => \Money\Money::MYR(0),
  "due_at" => \DateTime::createFromFormat('Y-m-d', "2015-3-9"),
  "email" => "api@billplz.com",
  "mobile" => null,
  "name" => "MICHAEL API V3",
  "url" => "https://www.billplz.com/bills/8X0Iyzaw",
  "reference_1_label" => "Reference 1",
  "reference_1" => null,
  "reference_2_label" => "Reference 2",
  "reference_2" => null,
  "redirect_url" => null,
  "callback_url" => "http://example.com/webhook/",
  "description" => "Maecenas eu placerat ante."
];
```

#### Payment Completion

You can setup a webhook to receive POST request from Billplz. In order to accept the response all you to do is write the following.

```php
$data = $billplz->webhook($_POST);
```

```php
return [
  'id' => 'W_79pJDk',
  'collection_id' => 'inbmmepb',
  'paid' => true,
  'state' => 'paid',
  'amount' => \Money\Money::MYR(200),
  'paid_amount' => \Money\Money::MYR(0),
  'due_at' => \Carbon\Carbon::parse('2020-12-31'),
  'email' => 'api@billplz.com',
  'mobile' => '+60112223333',
  'name' => 'MICHAEL API',
  'metadata' => [
    'id' => 9999,
    'description' => 'This is to test bill creation',
  ],
  'url' => 'https://billplz.dev/bills/W_79pJDk',
  'paid_at' => \Carbon\Carbon::parse('2015-03-09 16:23:59 +0800'),
];
```

#### Get a Bill

```php
$response = $bill->show('8X0Iyzaw');

var_dump($response->toArray());
```

```php
return [
  "id" => "8X0Iyzaw",
  "collection_id" => "inbmmepb",
  "paid" => false,
  "state" => "due",
  "amount" => \Money\Money::MYR(200),
  "paid_amount" => \Money\Money::MYR(0),
  "due_at" => \Carbon\Carbon::parse("2020-12-31"),
  "email" => "api@billplz.com",
  "mobile" => "+60112223333",
  "name" => "MICHAEL API V3",
  "url" => "https://www.billplz.com/bills/8X0Iyzaw",
  "reference_1_label" => "First Name",
  "reference_1" => "Jordan",
  "reference_2_label" => "Last Name",
  "reference_2" => "Michael",
  "redirect_url" => "http://example.com/redirect/",
  "callback_url" => "http://example.com/webhook/",
  "description" => "Maecenas eu placerat ante."
]
```

#### Delete a Bill

```php
$response = $bill->destroy('8X0Iyzaw');

var_dump($response->toArray());
```

```php
[]
```

## Handling Response

Every request made to Billplz would return `\Laravie\Codex\Response` which can fallback to `\Psr\Http\Message\ResponseInterface` which would allow developer to further inspect the response.

### Getting the Response

You can get the raw response using the following:

```php
$response->getBody();
```

However we also create a method to parse the return JSON string to array.

```php
$response->toArray();
```

### Checking the Response HTTP Status

You can get the response status code via:

```php
if ($response->getStatusCode() !== 200) {
  throw new SomethingHasGoneReallyBadException();
}
```

### Checking the Response Header

You can also check the response header via the following code:

```php
$response->getHeaders(); // get all headers as array.
$response->hasHeader('Content-Type'); // check if `Content-Type` header exist.
$response->getHeader('Content-Type'); // get `Content-Type` header.
```
