<?php

include '../creds.php';
include '../vendor/autoload.php';

// https://keen.io/docs/api/?php#updating-saved-queries
use KeenIO\Client\KeenIOClient;

$client = KeenIOClient::factory([
    'projectId' => $project_id,
    'masterKey' => $master_key
]);

$query = [
    "analysis_type" => "count",
    "event_collection" => "api_request",
    "filters" =>
        [
            [
                "property_name" => "user_agent",
                "operator" => "ne",
                "property_value"=> "Pingdom.com_bot_version_1.4_(http://www.pingdom.com/)"
            ]
        ],
    "timeframe" => "this_1_weeks"
];

$results = $client->updateQuery(['query_name' => 'updated query', 'query' => $query]);

print_r($results);