Change Log
==========

Version 2.0
-----------

####addEvent method - ( [source](src/KeenIO/Client/KeenIOClient.php#L112) )

Previously, this method required nesting your event data under an extra `data` property - which is no longer necessary.

The `data` property has been renamed in the [service description](src/KeenIO/Resources/config/keen-io-3_0.json#L71) to `keen_io_event`, to effectively namespace this property. 

#####Changed from:
    $client->addEvent($collection, array('data' => $event));

#####To:
    $client->addEvent($collection, $event);

####addEvents method - ( [source](src/KeenIO/Client/KeenIOClient.php#L132) )

Previously, this method required nesting your array of events under an extra `data` property - which is no longer necessary.

The `data` property has been renamed in the [service description](src/KeenIO/Resources/config/keen-io-3_0.json#L89) to `keen_io_events`, to effectively namespace this property. 

#####Changed from:
    $client->addEvents(array('data' => $events));

#####To:
    $client->addEvents($events);

---
