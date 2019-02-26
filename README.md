PHP framework agnostic library for working with BillPlz API v3 and beyond...
==============

[![Build Status](https://travis-ci.org/jomweb/billplz.svg?branch=master)](https://travis-ci.org/jomweb/billplz)
[![Latest Stable Version](https://poser.pugx.org/jomweb/billplz/version)](https://packagist.org/packages/jomweb/billplz)
[![Total Downloads](https://poser.pugx.org/jomweb/billplz/downloads)](https://packagist.org/packages/jomweb/billplz)
[![Latest Unstable Version](https://poser.pugx.org/jomweb/billplz/v/unstable)](//packagist.org/packages/jomweb/billplz)
[![License](https://poser.pugx.org/jomweb/billplz/license)](https://packagist.org/packages/jomweb/billplz)
[![Coverage Status](https://coveralls.io/repos/github/jomweb/billplz/badge.svg?branch=master)](https://coveralls.io/github/jomweb/billplz?branch=master)

* [Installation](#installation)
* [Getting Started](#getting-started)
    - [Creating Client](#creating-client)
    - [Using Sandbox](#using-sandbox)
    - [API Version](#api-version)
* [Usages](#usages)
    - [Collection](#collection)
        + [Create a Collection](#create-a-collection)
        + [Get Existing Collection](#get-existing-collection)
    - [Open Collection](#open-collection)
        + [Create an Open Collection](#create-an-open-collection)
    - [Bill](#bill)
        + [Create a Bill](#create-a-bill)
        + [Get Existing Bill](#get-existing-bill)
        + [Delete a Bill](#delete-a-bill)
    - [Payment Completion](#payment-completion)
        + [Redirect](#redirect)
        + [Callback](#callback)
    - [Transaction](#transaction)
        + [List of Transactions](#list-of-transactions)
    - [Banking](#banking)
        + [Registration Check by Bank Account](#registration-check-by-bank-account)
        + [Get FPX Banks List](#get-fpx-banks-list)
* [Handling Response](#handling-response)
    - [Getting the Response](#getting-the-response)
    - [Checking the Response HTTP Status](#checking-the-response-http-status)
    - [Checking the Response Header](#checking-the-response-header)

## Installation

### Composer

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "jomweb/billplz": "^3.0",
        "php-http/guzzle6-adapter": "^1.1"
    }
}
```

#### HTTP Adapter

Instead of utilizing `php-http/guzzle6-adapter` you might want to use any other adapter that implements `php-http/client-implementation`. Check [Clients & Adapters](http://docs.php-http.org/en/latest/clients.html) for PHP-HTTP.


### PHAR

The preferred method of installation is to use the Billplz PHAR which can be downloaded from the most recent [GitHub Release](https://github.com/jomweb/billplz/releases). This method ensures you will not have any dependency conflict issue.

You should received `billplz.phar` file which you can include to your project.

```php
<?php

require "billplz.phar";

$client = Billplz\Client::make('your-api-key', 'your-x-signature-key');
```

## Getting Started

<a name="creating-billplz-client"></a>
### Creating Client

You can start by creating a Billplz client by using the following code (which uses `php-http/guzzle6-adapter` and `php-http/discovery` to automatically pick available adapter installed via composer):

```php
<?php

use Billplz\Client;

$billplz = Client::make('your-api-key');
```

You can also send `X-Signature` key by doing the following:

```php
<?php

use Billplz\Client;

$billplz = Client::make('your-api-key', 'your-x-signature-key');
```

Alternatively, you could also manually configure `Http\Client\Common\HttpMethodsClient` directly:

```php
<?php

use Billplz\Client;
use Laravie\Codex\Discovery;

$http = Discovery::client();

$billplz = new Client($http, 'your-api-key', 'your-x-signature-key');
```

### Using Sandbox

You can set to use development/sandbox environment by adding the following code:

```php
$billplz->useSandbox();
```

<a name="using-different-api-version"></a>
### API Version

By default `jomweb/billplz` would use `v4` API version for any request, however you can customize this in future when new API version is available.

```php
$billplz->useVersion('v3');
```

## Usages

<a name="creating-collection-request"></a>
### Collection

Now you can create an instance of Collection:

```php
$collection = $billplz->collection();
```

> You can also manually set the API version by doing `$billplz->collection('v3');`. You can also use `$billplz->uses('Collection');` to get the same result.

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
        'fixed_cut' => \Duit\MYR::given(100),
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
        "fixed_cut" => \Duit\MYR::given(100),
        "variable_cut" => null
    ]
]
```

#### Get Existing Collection

You can get existing collection by calling the following code:

```php
$response = $collection->get('inbmmepb');

var_dump($response->toArray());
```

```php
return [
    "id" => "inbmmepb"
    "title" => "My First API Collection"
    "logo" => [
        "thumb_url" => null,
        "avatar_url" => null,
    ],
    "split_header" => null,
    "split_payments" => [
        [
            "email" => "verified@account.com",
            "fixed_cut" => 100,
            "variable_cut" => 2,
            "stack_order" => 0,
        ],
        [
            "email" => "verified2@account.com",
            "fixed_cut" => 200,
            "variable_cut" => 3,
            "stack_order" => 1,
        ],
    ],
    "status" => "active"
];
```

#### Deactivate a collection

To use `activate()` and `deactivate()` function, you must explicitely use version `v3`.
You can deactivate a collection by calling the following code:

```php
$response = $collection->deactivate('inbmmepb');
```

#### Activate a collection

You can deactivate a collection by calling the following code:

```php
$response = $collection->deactivate('inbmmepb');
```

### Open Collection

Now you can create an instance of Collection:

```php
$collection = $billplz->openCollection();
```

> You can also manually set the API version by doing `$billplz->openCollection('v3');`. You can also use `$billplz->uses('OpenCollection');` to get the same result.
> 
#### Create an Open Collection

```php
$response = $collection->create(
    'My First API Collection',
    'Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.',
    \Duit\MYR::given(299)
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
    "amount" => \Duit\MYR::given(299),
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

<a name="creating-bill-request"></a>
### Bill

Now you can create an instance of Bill:

```php
$bill = $billplz->bill();
```

> You can also manually set the API version by doing `$billplz->bill('v3');`. You can also use `$billplz->uses('Bill');` to get the same result.

#### Create a Bill

You can add a new bill by calling the following code:

```php
$response = $bill->create(
    'inbmmepb',
    'api@billplz.com',
    null,
    'Michael API V3',
    \Duit\MYR::given(200),
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
    "amount" => \Duit\MYR::given(200),
    "paid_amount" => \Duit\MYR::given(0),
    "due_at" => new \DateTime('Y-m-d', "2015-3-9"),
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

> `redirect_url` can be passed on the 8th parameters which is `(array) $optional`

##### Alternative Way to Set Redirect URL

```php
$response = $bill->create(
    'inbmmepb',
    'api@billplz.com',
    null,
    'Michael API V3',
    \Duit\MYR::given(200),
    ['callback_url' => 'http://example.com/webhook/', 'redirect_url' => 'http://example.com/redirect/'],
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
    "amount" => \Duit\MYR::given(200),
    "paid_amount" => \Duit\MYR::given(0),
    "due_at" => new \DateTime('Y-m-d', "2015-3-9"),
    "email" => "api@billplz.com",
    "mobile" => null,
    "name" => "MICHAEL API V3",
    "url" => "https://www.billplz.com/bills/8X0Iyzaw",
    "reference_1_label" => "Reference 1",
    "reference_1" => null,
    "reference_2_label" => "Reference 2",
    "reference_2" => null,
    "redirect_url" => "http://example.com/redirect/",
    "callback_url" => "http://example.com/webhook/",
    "description" => "Maecenas eu placerat ante."
];
```

<a name="get-a-bill"></a>
#### Get Existing Bill

```php
$response = $bill->get('8X0Iyzaw');

var_dump($response->toArray());
```

```php
return [
    "id" => "8X0Iyzaw",
    "collection_id" => "inbmmepb",
    "paid" => false,
    "state" => "due",
    "amount" => \Duit\MYR::given(200),
    "paid_amount" => \Duit\MYR::given(0),
    "due_at" => new \DateTime("2020-12-31"),
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

### Payment Completion

#### Redirect

You can setup a redirect page where user will be redirected after payment is completed. Billplz will redirect user to your specified redirect page along with few URL parameters. In order to capture all the URL parameters, do the following.

```php
$data = $bill->redirect($_GET);
```

```php
return [
    'id' => 'W_79pJDk',
    'paid' => true,
    'paid_at' => new \DateTime('2015-03-09 16:23:59 +0800'),
];
```

#### Callback

You can setup a webhook to receive POST request from Billplz. In order to accept the response, all you to do is write the following.

```php
$data = $bill->webhook($_POST);
```

```php
return [
    'id' => 'W_79pJDk',
    'collection_id' => 'inbmmepb',
    'paid' => true,
    'state' => 'paid',
    'amount' => \Duit\MYR::given(200),
    'paid_amount' => \Duit\MYR::given(0),
    'due_at' => new \DateTime('2020-12-31'),
    'email' => 'api@billplz.com',
    'mobile' => '+60112223333',
    'name' => 'MICHAEL API',
    'metadata' => [
        'id' => 9999,
        'description' => 'This is to test bill creation',
    ],
    'url' => 'https://billplz.dev/bills/W_79pJDk',
    'paid_at' => new \DateTime('2015-03-09 16:23:59 +0800'),
];
```

<a name="creating-transaction-request"></a>
### Transaction

Now you can create an instance of Transaction:

```php
$transaction = $billplz->transaction();
```

> You can also manually set the API version by doing `$billplz->transaction('v3');`. You can also use `$billplz->uses('Bill.Transaction');` to get the same result.

<a name="get-transaction-index"></a>
#### List of Transactions

You can get Transaction index by calling following code:

```php
$response = $transaction->show('inbmmepb');

var_dump($response->toArray());
```

```php
return [
    "bill_id" => "inbmmepb"
    "transactions" => [
        [
            "id": "60793D4707CD",
            "status": "completed",
            "completed_at": "2017-02-23T12:49:23.612+08:00",
            "payment_channel": "FPX"
        ],
        [
            "id" => "28F3D3194138",
            "status" => "failed",
            "completed_at" => ,
            "payment_channel" => "FPX"
        ]
    ],
    "page" => 1
]
```

You also can provide optional parameters (page, status):

```php
$response = $transaction->show('8X0Iyzaw', [
    'page' => 1,
    'status' => 'completed'
]);

var_dump($response->toArray());
```

```php
return [
    "bill_id" => "8X0Iyzaw"
    "transactions" => [
        [
            "id" => "60793D4707CD",
            "status" => "completed",
            "completed_at" => "2017-02-23T12:49:23.612+08:00",
            "payment_channel" => "FPX"
        ]
    ],
    "page" => 1
]
```

<a name="creating-bank-request"></a>
### Banking

Now you can create an instance of Bank:

```php
$bank = $billplz->bank();
```

> You can also manually set the API version by doing `$billplz->bank('v3');`. You can also use `$billplz->uses('Bank');` to get the same result.

#### Create A Bank Account

Request Bank Account Direct Verification Service by creating bank records thru this API.

```php
$response = $bank->createAccount('Insan Jaya', '91234567890', '999988887777', 'MBBEMYKL', true);

var_dump($response->toArray());
```

```php
return [
    "name" => "Insan Jaya",
    "id_no" => "91234567890",
    "acc_no" => "999988887777",
    "code" => "MBBEMYKL",
    "organization" => true,
    "authorization_date" => "2017-07-03",
    "status" => "pending",
    "processed_at" => null,
    "rejected_desc" => null
]
```

#### Get A Bank Account

Query Billplz Bank Account Direct Verification Service by passing single account number arguement. This API will only return latest, single matched bank account.

```php
$response = $bank->get('1234567890');

var_dump($response->toArray());
```

```php
return [
    "name" => "sara",
    "id_no" => "820909101001",
    "acc_no" => "1234567890",
    "code" => "MBBEMYKL",
    "organization" => false,
    "authorization_date" => "2015-12-03",
    "status" => "pending",
    "processed_at" => null,
    "rejected_desc" => null
]
```

<a name="check-bank-account-registration-status"></a>
#### Registration Check by Bank Account

At any given time, you can request to check on a registration status by bank account number.

```php
$response = $bank->checkAccount('1234567890');

var_dump($response->toArray());
```

```php
return [
    "name" => "verified"
]
```

#### Get FPX Banks List

If you want to use Bank Direct Feature in Billplz, you need list of FPX Banks to send in create bill request.

You can get supported bank for FPX by calling following code: 

```php
$list = $bank->supportedForFpx();

var_dump($list->toArray());
```

```php
return [
    "banks" => [
        [
            "name" => "PBB0233",
            "active" => true,
        ],
        [
            "name" => "MBB0227",
            "active" => true,
        ],
        [
            "name" => "MBB0228",
            "active" => true,
        ],
    ],
];
```

> **Note**: You will hit 401, Invalid access error if you have not enabled Bank Direct by Billplz. Contact Billplz for information.

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
