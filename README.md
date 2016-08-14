PHP library for working with BillPlz API
==============

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "jomweb/billplz": "^0.1",
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

### Creating Collection Instance

Now you can create an instance of Collection:

```php
use Billplz\Collection;

$collection = new Collection($billplz);
```


#### Create a Collection

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
    "thumb_url": "https://sample.net/assets/uploadPhoto.png",
    "avatar_url": "https://sample.net/assets/uploadPhoto.png"
  },
  "split_payment": 
  {
    "email": "verified@account.com",
    "fixed_cut": 100,
    "variable_cut": null
  }
}
```

#### Create an Open Collection

```php
$response = $collection->createOpen(
    'My First API Collection', 
    'Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.',
    Money\Money::MYR(299)
);

$body = json_decode($response->getBody(), true);

var_dump($body);
```

```json
{
  "id": "0pp87t_6",
  "title": "MY FIRST API OPEN COLLECTION",
  "description": "Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.",
  "reference_1_label": null,
  "reference_2_label": null,
  "email_link": null,
  "amount": 299,
  "fixed_amount": true,
  "tax": null,
  "fixed_quantity": true,
  "payment_button": "pay",
  "photo":
  {
    "retina_url":  null,
    "avatar_url":  null
  },
  "split_payment": 
  {
    "email": null,
    "fixed_cut": null,
    "variable_cut": null
  },
  "url": "https://www.billplz.com/0pp87t_6"
}
```


### Creating Bill Instance 

Now you can create an instance of Bill:

```php
use Billplz\Bill;

$bill = new Bill($billplz);
```

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

$body = json_decode($response->getBody(), true);

var_dump($body);
```

```json
{
  "id": "8X0Iyzaw",
  "collection_id": "inbmmepb",
  "paid": false,
  "state": "overdue",
  "amount": 200 ,
  "paid_amount": 0,
  "due_at": "2015-3-9",
  "email" :"api@billplz.com",
  "mobile": null,
  "name": "MICHAEL API V3",
  "url": "https://www.billplz.com/bills/8X0Iyzaw",
  "reference_1_label": "Reference 1",
  "reference_1": null,
  "reference_2_label": "Reference 2",
  "reference_2": null,
  "redirect_url": null,
  "callback_url": "http://example.com/webhook/",
  "description": "Maecenas eu placerat ante."
}
```

#### Get a Bill

```php
$response = $bill->show('8X0Iyzaw');

$body = json_decode($response->getBody(), true);

var_dump($body);
```

```json
{
  "id": "8X0Iyzaw",
  "collection_id": "inbmmepb",
  "paid": false,
  "state": "due",
  "amount": 200 ,
  "paid_amount": 0,
  "due_at": "2020-12-31",
  "email" :"api@billplz.com",
  "mobile": "+60112223333",
  "name": "MICHAEL API V3",
  "url": "https://www.billplz.com/bills/8X0Iyzaw",
  "reference_1_label": "First Name",
  "reference_1": "Jordan",
  "reference_2_label": "Last Name",
  "reference_2": "Michael",
  "redirect_url": "http://example.com/redirect/",
  "callback_url": "http://example.com/webhook/",
  "description": "Maecenas eu placerat ante."
}
```

#### Delete a Bill

```php
$response = $bill->destroy('8X0Iyzaw');

$body = json_decode($response->getBody(), true);

var_dump($body);
```

```json
{}
```
