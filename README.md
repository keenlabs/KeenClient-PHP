Keen IO PHP Library
===================
The Keen IO API lets developers build analytics features directly into their apps.

[![Build Status](https://travis-ci.org/keenlabs/KeenClient-PHP.png?branch=master)](https://travis-ci.org/keenlabs/KeenClient-PHP)

Installation with Composer
--------------------------
```sh
$ php composer.phar require keen-io/keen-io:~2.5
```
For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

Integrated Frameworks
---------------------
For easier usage the following community developed integrations are also available:

* [Zend Framework 2](https://github.com/keenlabs/KeenClient-PHP-ZF2)
* [Symfony2 Framework](https://github.com/keenlabs/KeenClient-PHP-SF2)
* [Laravel 5](https://github.com/garethtdavies/keen-io-laravel)

Changes
-------
Please review the change log ( [CHANGE.md](CHANGE.md) ) before upgrading!

Usage
-----

This client was built using [Guzzle](http://guzzlephp.org/), a PHP HTTP client & framework for building RESTful web service clients.

When you first create a new `KeenIOClient` instance you can pass configuration settings like your Project ID and API Keys in an array
to the factory method. These are optional and can be later specified through Setter methods.

For certain API Resources, the Master API Key is required and can also be passed to the factory method in the configuration array.
Please read the [Security Documentation](https://keen.io/docs/security/) regarding this Master API key.

For Requests, the `KeenIOClient` will determine what API Key should be passed based on the type of Request and configuration in the
[Service Description](/src/Client/Resources/keen-io-3_0.php). The API Key is passed in the `Authorization` header of the request.

For a list of required and available parameters for the different API Endpoints, please consult the Keen IO
[API Reference Docs](https://keen.io/docs/api/reference/).


#### Configuring the Client

The factory method accepts an array of configuration settings for the Keen IO Webservice Client.

Setting | Property Name | Description
--- | --- | ---
Project ID | `projectId` | The Keen IO Project ID for your specific project
Master API Key | `masterKey` | The Keen IO Master API Key - the one API key to rule them all
Read API Key | `readKey` | The Read API Key - used for access to read only GET or HEAD operations of the API
Write API Key | `writeKey` | The Write API Key - used for write PUT or POST Requests operations of the API
API Version | `version` | The API Version.  Currently used to version the API URL and Service Description

When passing `version` to the factory method or using the `setVersion()` method, the Client will try to load a client Service Description
that matches that version. That Service Description defines the operations available to the Webservice Client.

Currently the Keen IO Webservice Client only supports - and automatically defaults - to the current version (`3.0`) of the API.

##### More details about the client

Since the Keen client extends the [Guzzle](http://guzzlephp.org/) client, you get all the power and flexibility of that behind the scenes. If you need more complex logging, backoff/retry handling, or asynchronous requests, check out their [Plugins](http://guzzle3.readthedocs.io/docs.html#plugins).

###### Example
```php
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $projectId,
    'writeKey'  => $writeKey,
    'readKey'   => $readKey
]);
```

For more options see [Guzzle Client documentation](http://docs.guzzlephp.org/en/stable/quickstart.html#creating-a-client)
Please notice that _all other options passed to the constructor are used as default request options with every request created by the client_.

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
$newReadKey = $client->createScopedKey($filters, $allowedOperations);
$client->setReadKey($newReadKey);

```

#### Send an event to Keen - ([Changed in 2.0!](CHANGE.md))
Once you've created a `KeenIOClient`, sending events is simple:

###### Example
```php
$event = ['purchase' => ['item' => 'Golden Elephant']];

$client->addEvent('purchases', $event);
```

#### Data Enrichment
A data enrichment is a powerful add-on to enrich the data you're already streaming to Keen IO by pre-processing the data and adding helpful data properties. To activate add-ons, you simply add some new properties within the "keen" namespace in your events. Detailed documentation for the configuration of our add-ons is available [here](https://keen.io/docs/api/?php#data-enrichment).

Here is an example of using the [URL parser](https://keen.io/docs/streams/url-enrichment/):

```php
$client->addEvent('requests', [
    'page_url' => 'http://my-website.com/cool/link?source=twitter&foo=bar/#title',
    'keen' => [
        'addons' => [
            [
                'name' => 'keen:url_parser',
                'input' => [
                    'url' => 'page_url'
                ],
                'output' => 'parsed_page_url'
            ]
        ]
    ]
]);
```

Keen IO will parse the URL for you and that would equivalent to:

```php
$client->addEvent('requests', [
    'page_url' => 'http://my-website.com/cool/link?source=twitter&foo=bar/#title',
    'parsed_page_url' => [
        'protocol' => 'http',
        'domain' => 'my-website.com',
        'path' => '/cool/link',
        'anchor' => 'title',
        'query_string' => [
            'source' => 'twitter',
            'foo' => 'bar'
        ]
    ]
]);
```

Here is another example of using the [Datetime parser](https://keen.io/docs/streams/datetime-enrichment/). Let's assume you want to do a deeper analysis on the "purchases" event by day of the week (Monday, Tuesday, Wednesday, etc.) and other interesting Datetime components. You can use "keen.timestamp" property that is included in your event automatically.

```php
$client->addEvent('purchases', [
    'keen' => [
        'addons' => [
            [
                'name' => 'keen:date_time_parser',
                'input' => [
                    'date_time' => 'keen.timestamp'
                ],
                'output' => 'timestamp_info'
            ]
        ]
    ],
    'price' => 500
]);
```

Other Data Enrichment add-ons are located in the [API reference docs](https://keen.io/docs/api/?php#data-enrichment).

#### Send batched events to Keen  - ([Changed in 2.0!](CHANGE.md))
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

$client->addEvents(['purchases' => $purchases, 'sign_ups' => $signUps]);
```

#### Get Analysis on Events
All Analysis Endpoints should be supported.  See the [API Reference Docs](https://keen.io/docs/api/reference/) for required parameters.
You can also check the [Service Description](/src/Client/Resources/keen-io-3_0.php) for configured API Endpoints.

Below are a few example calls to some of the Analysis methods available.

Note: Once the API acknowledges that your event has been stored, it may take up to 10 seconds before it will appear in query results.

###### Example

```php
//Count
$totalPurchases = $client->count('purchases', ['timeframe' => 'this_14_days']);

//Count Unqiue
$totalItems = $client->countUnique('purchases', ['target_property' => 'purchase.item', 'timeframe' => 'this_14_days']);

//Select Unique
$items = $client->selectUnique('purchases', ['target_property' => 'purchase.item', 'timeframe' => 'this_14_days']);

//Multi Analysis
$analyses = [
    'clicks'        => ['analysis_type' => 'count'],
    'average price' => ['analysis_type' => 'average', 'target_property' => 'purchase.price'],
    'timeframe'     => 'this_14_days'
];
$stats = $client->multiAnalysis('purchases', ['analyses' => $analyses]);

//Using Filters in your Analysis
$filters = [
    ['property_name' => 'item.price', 'operator' => 'gt', 'property_value' => 10]
];

$client->count('purchases', ['filters' => $filters, 'timeframe' => 'this_14_days']);
```

#### Create a Scoped Key
Scoped keys allow you to secure the requests to the API Endpoints and are especially useful when you are providing
access to multiple clients or applications. You should read the Keen IO docs concerning [Scoped Keys](https://keen.io/docs/security/#scoped-key)
for more details.

###### Example
```php
$filter = [
    'property_name'  => 'user_id',
    'operator'       => 'eq',
    'property_value' => '123'
];

$filters = [$filter];
$allowedOperations = ['read'];

$scopedKey = $client->createScopedKey($filters, $allowedOperations);
```

#### Using saved queries

[Saved Queries](https://keen.io/docs/api/?php#saved-queries) allow you to perform with exactly the same parameters every time with minimal effort. It's effectively a bookmark or macro to analysis that you can jump to or share without configuring each time. While you can create and access them via the Dashboard, the PHP library gives you the same ability.

###### Example: Creating a Saved Query
```php
$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$query = [
    "analysis_type" => "count",
    "event_collection" => "api_requests",
    "filters" =>
        [
            [
                "property_name" => "user_agent",
                "operator" => "ne",
                "property_value"=> "Pingdom.com_bot_version_1.4_(http://www.pingdom.com/)"
            ]
        ],
    "timeframe" => "this_2_weeks"
];

$results = $client->createSavedQuery(['query_name' => 'total-API-requests', 'query' => $query]);
```

###### Example: Retrieving a Saved Query
```php
$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getSavedQuery(['query_name' => 'total-API-requests']);
```

#### Using Cached queries

By [Caching a Query](https://keen.io/docs/api/?php#creating-a-cached-query), you are adding a `refresh_rate` property to a query payload.

Cached Queries helps you to automatically refresh a saved query within a particular time. This allows you to get an immediate result using the saved query for a subsequent trip. 

You can either cache a query while [creating a saved query](https://keen.io/docs/api/#creating-a-saved-query) or [updating a saved query](https://keen.io/docs/api/#updating-saved-queries).

 While you can create this via the Dashboard, the PHP library gives you the same ability.


###### Example: Caching a query when creating Saved Query
```php
$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$query = [
    "analysis_type" => "count",
    "event_collection" => "api_requests",
    "filters" =>
        [
            [
                "property_name" => "user_agent",
                "operator" => "ne",
                "property_value"=> "Pingdom.com_bot_version_1.4_(http://www.pingdom.com/)"
            ]
        ],
    "timeframe" => "this_2_weeks",
    "refresh_rate" => 14400

];

$client->createSavedQuery(['query_name' => 'total-API-requests', 'query' => $query]);
```


###### Example: Caching a query when updating a saved Query
```php
$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$query = [
    "analysis_type" => "count",
    "event_collection" => "api_requests",
    "filters" =>
        [
            [
                "property_name" => "user_agent",
                "operator" => "ne",
                "property_value"=> "Pingdom.com_bot_version_1.4_(http://www.pingdom.com/)"
            ]
        ],
    "timeframe" => "this_2_weeks",
    "refresh_rate" => 14400

];

$results = $client->updateSavedQuery(['query_name' => 'total-API-requests', 'query' => $query]);
```

###### Example: Retrieving a Cached Query
```php
$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getSavedQuery(['query_name' => 'total-API-requests']);
```


Troubleshooting
---------------

When your client sends a request that the API rejects with an HTTP 400
BAD REQUEST or similar error, it is translated to an exception. You
might inspect this exception object's getMessage() method, like this:

```php
try {
  $client->addEvent( "php-events" , ['broken.purchase' => ['item' => 'Golden Elephant', 'amount' => 42.50]] );
} catch( Exception $e ) {
  print $e->getMessage();
}
```

This won't give you much in the way of useful details, though:

```
Client error response
[status code] 400
[reason phrase] Bad Request
[url] ...
```

Instead, use the getResponse() method, which will give you the entire HTTP response:

```php
try {
  $client->addEvent( "php-events" , ['broken.purchase' => ['item' => 'Golden Elephant', 'amount' => 42.50]] );
} catch( Exception $e ) {
  print $e->getResponse();
}
```

```
HTTP/1.1 400 Bad Request
... headers ...

{"message": "Property name is invalid. Must be <= 256 characters, cannot contain the '.' symbol anywhere. You specified: 'broken.purchase'.",
"error_code": "InvalidPropertyNameError"}
```


Questions & Support
-------------------
If you have any questions, bugs, or suggestions, please
report them via Github Issues. Or, come chat with us anytime
at [users.keen.io](http://users.keen.io). We'd love to hear your feedback and ideas!

Contributing
------------
This is an open source project and we love involvement from the community! Hit us up with pull requests and issues.

Local development
-----------------
1. Start with installation of [composer](https://getcomposer.org/download/)
2. Download dependencies: `$ php composer.phar install`
3. You can verify whether tests pass by running `$ vendor/bin/phpunit`
