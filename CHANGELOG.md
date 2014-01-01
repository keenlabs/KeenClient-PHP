Change Log
==========

Version 2.0
-----------

* Now allow to use all methods with master key only.

* [BC] You now don't need to pass the api key to create a scoped key, it reuses the one from the client.

* [BC] A new Service class has been created. It is a thin service layer around the Guzzle client to allow simpler
usage. Also, all the scoped key methods have been moved to the service.

* [BC] `deleteEvent` method has been renamed `deleteEvents`, to better reflect its use case
