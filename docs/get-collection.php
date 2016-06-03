<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?shell#inspect-a-single-collection
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getCollection(['event_collection' => 'account']);

print_r($results);