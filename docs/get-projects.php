<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?php#inspect-all-projects
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'masterKey' => $master_key
]);

$results = $client->getProjects();

print_r($results);