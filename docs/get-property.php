<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?php#inspect-a-single-property
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getProperty(['event_collection' => 'account', 'property_name' => 'account_id']);

print_r($results);