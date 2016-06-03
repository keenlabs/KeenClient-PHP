<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?php#inspect-a-single-project
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'organizationId'  => $org_id,
    'organizationKey' => $org_key
]);

$results = $client->createProject(['name' => 'Awesome Project', 'users' => ['email' => 'test@test.com']]);

print_r($results);