Keen IO PHP Library
===================
The Keen IO API lets developers build analytics features directly into their apps.

[![Build Status](https://travis-ci.org/keenlabs/KeenClient-PHP.png?branch=master)](https://travis-ci.org/keenlabs/KeenClient-PHP)

Installation with Composer
--------------------------
  1. Install composer via via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  2. Edit your `composer.json` file with following contents:

     ```json
     "require": {
        "keen-io/keen-io": "~1.1"
     }
     ```
  3. Run `php composer.phar install`

Usage
-----

This client was built using [Guzzle](http://guzzlephp.org/), a PHP HTTP client & framework for building RESTful web service clients.

When you first create a new `KeenIOClient` instance you can pass configuration settings like your Project ID and API Keys in an array
to the factory method. These are optional and can be later specified through Setter methods.

For certain API Resources, the Master API Key is required and can also be passed to the factory method in the configuration array.
Please read the [Security Documentation](https://keen.io/docs/security/) regarding this Master API key.

For Requests, the `KeenIOClient` will determine what API Key should be passed based on the type of Request and configuration in the
[Service Description](/src/KeenIO/Resources/config/keen-io-3_0.json). The API Key is passed in the `Authorization` header of the request.

For a list of required and available parameters for the different API Endpoints, please consult the Keen IO
[API Reference Docs](https://keen.io/docs/api/reference/).


#### Configuring the Client

The factory method accepts an array of configuration settings for the Keen IO Webservice Client.

Setting | Property Name | Description
--- | --- | ---
Project ID | `projectId` | The Keen IO Project ID for your specific project
Master API Key | `masterKey` | The Keen IO Master API Key - the one API key to rule them all
Read API Key | `readKey` | The Read API Key - used for access to read only GET|HEAD operations of the API
Write API Key | `writeKey` | The Write API Key - used for write PUT|POST Requests operations of the API
API Version | `version` | The API Version.  Currently used to version the API URL and Service Description

When passing `version` to the factory method or using the `setVersion()` method, the Client will try to load a client Service Description
that matches that version. That Service Description defines the operations available to the Webservice Client.

Currently the Keen IO Webservice Client only supports - and automatically defaults - to the current version (`3.0`) of the API.

###### Example
```php
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $projectId,
    'writeKey'  => $writeKey,
    'readKey'   => $readKey
]);
```

#### Configuration can be updated to reuse the same Client:
You can reconfigure the Keen IO Client configuration options through available getters and setters. You can get and set the following options:
`projectId`, `readKey`, `writeKey`, `masterKey`, & `version`.

###### Example
```php

//Get the current Project Id
$client->getProjectId();

//Set a new Project Id
$client->setProjectId($someNewProjectId);

//Get the current Read Key
$client->getReadKey();

//Set a new Read Key
$newReadKey = $client->getScopedKey($masterKey, $filters, $allowedOperations);
$client->setReadKey($newReadKey);

```

####Send an event to Keen
Once you've created a `KeenIOClient`, sending events is simple:

######Example
```php
$event = ['purchase' => ['item' => 'Golden Elephant']];

$client->addEvent('purchases', ['data' => $event]);
```

#### Send batched events to Keen
You can upload multiple Events to multiple Event Collections at once!

In the example below, we will create two new purchase events in the `purchases` event collection and a single
new event in the `sign_ups` event collection. Note that the keys of the `data` array specify the `event_collection`
where those events should be stored.

###### Example
```php
$purchases = [
    ['purchase' => ['item' => 'Golden Elephant']],
    ['purchase' => ['item' => 'Magenta Elephant']]
];
$signUps = [
    ['name' => 'foo', 'email' => 'bar@baz.com']
];

$client->addEvents(['data' => ['purchases' => $purchases, 'sign_ups' => $signUps]]);
```

#### Get Analysis on Events
All Analysis Endpoints should be supported.  See the [API Reference Docs](https://keen.io/docs/api/reference/) for required parameters.
You can also check the [Service Description](/src/KeenIO/Resources/config/keen-io-3_0.json) for configured API Endpoints.

Below are a few example calls to some of the Analysis methods available.

###### Example

```php
//Count
$totalPurchases = $client->count('purchases');

//Count Unqiue
$totalItems = $client->countUnique('purchases', ['target_property' => 'purchase.item']);

//Select Unique
$items = $client->selectUnique('purchases', ['target_property' => 'purchase.item']);

//Multi Analysis
$analyses = [
    'clicks'        => ['analysis_type' => 'count'],
    'average price' => ['analysis_type' => 'average', 'target_property' => 'purchase.price']
];
$stats = $client->multiAnalysis('purchases', ['analyses' => $analyses]);
```

### Create a Scoped Key

Scoped keys allow you to secure the requests to the API Endpoints and are especially useful when you are providing
access to multiple clients or applications. You should read the Keen IO docs concerning [Scoped Keys](https://keen.io/docs/security/#scoped-key)
for more details.

######Example
```php
$filter = [
    'property_name'  => 'user_id',
    'operator'       => 'eq',
    'property_value' => '123'
];

$filters = [$filter];
$allowed_operations = ['read'];

$scopedKey = $client->getScopedKey($masterKey, $filters, $allowedOperations);
```
### Questions & Support

If you have any questions, bugs, or suggestions, please
report them via Github Issues. Or, come chat with us anytime
at [users.keen.io](http://users.keen.io). We'd love to hear your feedback and ideas!

### Contributing
This is an open source project and we love involvement from the community! Hit us up with pull requests and issues.
