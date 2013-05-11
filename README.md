Keen IO PHP Library
===================
[![Build Status](https://travis-ci.org/keenlabs/KeenClient-PHP.png)](https://travis-ci.org/keenlabs/KeenClient-PHP) [![Dependency Status](https://www.versioneye.com/package/php--keen-io--keen-io/badge.png)](https://www.versioneye.com/package/php--keen-io--keen-io) 

This is a library to abstract the Keen IO API addEvent method

Installation
------------
  1. edit `composer.json` file with following contents:

     ```json
     "require": {
        "keen-io/keen-io": "dev-master"
     }
     ```
  2. install composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  3. run `php composer.phar install`

Use
---
Configure the service
```php
use KeenIO\Service\KeenIO;

KeenIO::configure($projectId, $writeKey, $readKey);
```

Send a new event
```php
KeenIO::addEvent('purchases', array(
    'purchase' => array(
        'item' => 'Golden Elephant'
    ),
));
```

Create a scoped key
```php
$filter = array(
    'property_name' => 'id', 
    'operator' => 'eq', 
    'property_value' => '123'
);
$filters = array($filter);
$allowed_operations = array('read')

$scopedKey = KeenIO::getScopedKey($filters, $allowed_operations);
```

