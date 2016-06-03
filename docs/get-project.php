<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?php#inspect-a-single-project
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getProject();

print_r($results);