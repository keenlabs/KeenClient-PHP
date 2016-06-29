<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?shell#getting-saved-query-results
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$queries = $client->getSavedQueries();

$query_name = $queries[0]['query_name'];

$results = $client->getSavedQueryResults(['query_name' => $query_name]);

print_r($results);