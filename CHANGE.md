Change Log
==========

Version 2.1
-----------

* Fix a bug when using analytics method (first parameter is now considered the event collection, as before)

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
