<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?shell#getting-a-saved-query-definition
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$queries = $client->getQueries();

$query_name = $queries[0]['query_name'];

$results = $client->getQuery(['query_name' => $query_name]);

print_r($results);