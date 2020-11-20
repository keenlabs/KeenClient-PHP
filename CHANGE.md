Change Log
==========

VERSION 2.7.0
-------------
* Added support for including metadata of requests through include_metadata parameter

VERSION 2.6.0
-------------
* Added collection deletion - added collection deletion by name 

VERSION 2.5.14
-------------
* Updated README.md - added basic info regarding local development setup
* composer.phar added to .gitignore file

VERSION 2.5.13
-------------
* Fix for invalid request "Missing required organizationKey" when using createProject
* Fix for no body content being sent when using createProject

VERSION 2.5.12
-------------
* Fix a bug that caused arrays in the POST body to be encoded as strings 

VERSION 2.5.11
-------------
* Fix a few headers in the README.md
* Update for PSR-2 compliance 

VERSION 2.5.10
-------------
* Remove mcrypt dependency (deprecated in PHP 7.1) and replace it with openssl for scoped key support.

VERSION 2.5.9
-------------
* Update the querying methods to use a POST request instead of a GET request

VERSION 2.5.8
-------------
* Fixes a bug with the return type of queries (reverting back to associated arrays)

Version 2.5.7
-------------
* Added a new HTTP Header that tracks the SDK version

Version 2.5.6
-------------
* Added Saved Queries and Project Creation
* Updated minimum php and phpunit versions
* Added use of organizationID

Version 2.1
-----------

* Fix a bug when using analytics method (first parameter is now considered the event collection, as before)
* Allow to specify absolute timeframe

Version 2.0
-----------

* All commands can now be used with a master key.
* [BC] Method `deleteEvent` has been renamed to `deleteEvents` as it allows to delete multiple events.
* [BC] `getScopedKey` has been renamed `createScopedKey` to better reflect its purpose.
* [BC] `createScopedKey` and `decryptScopedKey` no longer need an API key: it just reuses the one set in the client.
* [BC] The client provides shortcut for the `addEvent` and `addEvents` methods. Instead of:

```php
$client->addEvent(array('event_collection' => 'bar', 'data' => array('my' => 'data')));
```

Just use it like this:

```php
$client->addEvent('bar', array('my' => 'data'));
```

If you still want to use the command manually, you must get the commands manually.
