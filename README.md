[![Build Status](https://travis-ci.org/keenlabs/KeenClient-PHP.png)](https://travis-ci.org/keenlabs/KeenClient-PHP.png)

Keen IO PHP Library
================================
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

KeenIO::configure($projectId, $apiKey);
```

Send a new event
```php
KeenIO::addEvent('purchases', array(
    'purchase' => array(
        'item' => 'Golden Elephant'
    ),
));
```
