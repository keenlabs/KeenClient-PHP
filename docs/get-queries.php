<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?shell#getting-all-saved-query-definitions
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$results = $client->getQueries();

print_r($results);