<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?shell#inspect-all-collections
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getCollections();

print_r($results);