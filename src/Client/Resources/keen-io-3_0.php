<?php

return array(
    'name'        => 'KeenIO',
    'baseUri'     => 'https://api.keen.io/3.0/',
    'apiVersion'  => '3.0',
    'operations'  => array(
        'getResources' => array(
            'uri'         => '/',
            'description' => 'Returns the available child resources. Currently, the only child '
                            . 'resource is the Projects Resource.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master Api Key',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'createProject' => array(
            'uri'         => 'organizations/{organizationId}/projects',
            'description' => 'Creates a project for the specified organization and returns the '
                            . 'project id for later usage.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'organizationId'  => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'organizationKey' => array(
                    'location'    => 'header',
                    'description' => 'The Organization Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
            'additionalParameters'       => array(
                'location' => 'json'
            ),
        ),

        'getProjects' => array(
            'uri'         => 'projects',
            'description' => 'Returns the projects accessible to the API user, as well as '
                            . 'links to project sub-resources for discovery.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'getProject' => array(
            'uri'         => 'projects/{projectId}',
            'description' => 'GET returns detailed information about the specific project, '
                            . 'as well as links to related resources.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'getSavedQueries' => array(
            'uri'         => 'projects/{projectId}/queries/saved',
            'description' => 'Returns the saved queries accessible to the API user on the specified project.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'getSavedQuery' => array(
            'uri'         => 'projects/{projectId}/queries/saved/{query_name}',
            'description' => 'Returns the detailed information about the specified query, as '
                            . 'well as links to retrieve results.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'query_name' => array(
                    'location'    => 'uri',
                    'description' => 'The saved query.',
                    'required'    => true,
                ),
            ),
        ),

        'createSavedQuery' => array(
            'uri'         => 'projects/{projectId}/queries/saved/{query_name}',
            'description' => 'Creates the described query.',
            'httpMethod'  => 'PUT',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'query_name' => array(
                    'location'    => 'uri',
                    'description' => 'The desired name of the query.',
                    'filters'     => array([
                        "method" => '\KeenIO\Client\KeenIOClient::cleanQueryName',
                        "args" => ["@value"]
                    ]),
                    'required'    => true,
                ),
                'query'       => array(
                    'location' => 'json',
                    'type'     => 'array',
                ),
            ),
        ),

        'updateSavedQuery' => array(
            'uri'         => 'projects/{projectId}/queries/saved/{query_name}',
            'description' => 'Creates the described query.',
            'httpMethod'  => 'PUT',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'query_name' => array(
                    'location'    => 'uri',
                    'description' => 'The desired name of the query.',
                    'filters'     => array([
                        "method" => '\KeenIO\Client\KeenIOClient::cleanQueryName',
                        "args" => ["@value"]
                    ]),
                    'required'    => true,
                ),
                'query'       => array(
                    'location' => 'json',
                    'type'     => 'array',
                ),
            ),
        ),

        'deleteSavedQuery' => array(
            'uri'         => 'projects/{projectId}/queries/saved/{query_name}',
            'description' => 'Deletes the specified query.',
            'httpMethod'  => 'DELETE',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'query_name' => array(
                    'location'    => 'uri',
                    'description' => 'The saved query.',
                    'required'    => true,
                ),
            ),
        ),

        'getSavedQueryResults' => array(
            'uri'         => 'projects/{projectId}/queries/saved/{query_name}/result',
            'description' => 'Returns the results of executing the specified query.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'query_name' => array(
                    'location'    => 'uri',
                    'description' => 'The saved query.',
                    'required'    => true,
                ),
            ),
        ),

        'getCollections' => array(
            'uri'         => 'projects/{projectId}/events',
            'description' => 'GET returns schema information for all the event collections in this project, '
                            . 'including properties and their type. It also returns links to sub-resources.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'getEventSchemas' => array(
            'extends'     => 'getCollections'
        ),

        'getCollection' => array(
            'uri'         => 'projects/{projectId}/events/{event_collection}',
            'description' => 'GET returns available schema information for this event collection, including '
                            . 'properties and their type. It also returns links to sub-resources.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'event_collection' => array(
                    'location'    => 'uri',
                    'description' => 'The event collection.',
                    'required'    => true,
                ),
            ),
        ),

        'deleteCollection' => array(
            'uri'         => 'projects/{projectId}/events/{collection_name}',
            'description' => 'Deletes the specified collection.',
            'httpMethod'  => 'DELETE',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'collection_name' => array(
                    'location'    => 'uri',
                    'description' => 'The collection name.',
                    'required'    => true,
                ),
            ),
        ),

        'getProperty' => array(
            'uri'         => 'projects/{projectId}/events/{event_collection}/properties/{property_name}',
            'description' => 'GET returns the property name, type, and a link to sub-resources.',
            'httpMethod'  => 'GET',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey' => array(
                    'location'    => 'header',
                    'description' => 'The Master API Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'event_collection' => array(
                    'location'    => 'uri',
                    'description' => 'The event collection.',
                    'required'    => true,
                ),
                'property_name' => array(
                    'location'    => 'uri',
                    'description' => 'The property name to inspect',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'addEvent' => array(
            'uri'         => 'projects/{projectId}/events/{event_collection}',
            'description' => 'POST inserts an event into the specified collection.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'writeKey'         => array(
                    'location'    => 'header',
                    'description' => 'The Write Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'uri',
                    'description' => 'The event collection.',
                    'required'    => true,
                ),
            ),
            'additionalParameters'       => array(
                'location' => 'json'
            ),
        ),

        'addEvents' => array(
            'uri'         => 'projects/{projectId}/events',
            'description' => 'POST inserts multiple events in one or more collections, in a single request. The API '
                            . 'expects a JSON object whose keys are the names of each event collection you want to '
                            . 'insert into. Each key should point to a list of events to insert for that event '
                            . 'collection.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'writeKey'  => array(
                    'location'    => 'header',
                    'description' => 'The Write Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
            ),
            'additionalParameters' => array(
                'location' => 'json',
            ),
        ),

        'deleteEvents' => array(
            'uri'         => 'projects/{projectId}/events/{event_collection}',
            'description' => 'DELETE one or multiple events from a collection. You can optionally add filters, '
                            . 'timeframe or timezone. You can delete up to 50,000 events using one method call',
            'httpMethod'  => 'DELETE',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'masterKey'        => array(
                    'location'    => 'header',
                    'description' => 'The Master API key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'event_collection' => array(
                    'location'    => 'uri',
                    'description' => 'The event collection.',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'query',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'query',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'filters'     => array('KeenIO\Client\Filter\MultiTypeFiltering::encodeValue'),
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'query',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
            ),
        ),

        'deleteEventProperties' => array(
            'uri'         => 'projects/{projectId}/events/{event_collection}/properties/{property_name}',
            'description' => 'DELETE one property for events. This only works for properties with less than 10,000 '
                            . 'events.',
            'httpMethod'  => 'DELETE',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'writeKey'         => array(
                    'location'    => 'header',
                    'description' => 'The Write Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'uri',
                    'description' => 'The event collection.',
                    'required'    => true,
                ),
                'property_name'    => array(
                    'location'    => 'uri',
                    'description' => 'Name of the property to delete.',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        'count' => array(
            'uri'         => 'projects/{projectId}/queries/count',
            'description' => 'POST returns the number of resources in the event collection matching the given criteria.'
                            . ' The response will be a simple JSON object with one key: a numeric result.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'countUnique' => array(
            'uri'         => 'projects/{projectId}/queries/count_unique',
            'description' => 'POST returns the number of UNIQUE resources in the event collection matching the given '
                            . 'criteria. The response will be a simple JSON object with one key: result, which maps '
                            . 'to the numeric result described previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'minimum' => array(
            'uri'         => 'projects/{projectId}/queries/minimum',
            'description' => 'POST returns the minimum numeric value for the target property in the event collection '
                            . 'matching the given criteria. Non-numeric values are ignored. The response will be a '
                            . 'simple JSON object with one key: result, which maps to the numeric result described '
                            . 'previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'maximum' => array(
            'uri'         => 'projects/{projectId}/queries/maximum',
            'description' => 'POST returns the maximum numeric value for the target property in the event collection '
                            . 'matching the given criteria. Non-numeric values are ignored. The response will be a '
                            . 'simple JSON object with one key: result, which maps to the numeric result described '
                            . 'previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'average' => array(
            'uri'         => 'projects/{projectId}/queries/average',
            'description' => 'POST returns the average across all numeric values for the target property in the event '
                            . 'collection matching the given criteria. Non-numeric values are ignored. The response '
                            . 'will be a simple JSON object with one key: result, which maps to the numeric result '
                            . 'described previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request '
                                    . 'based on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of '
                                    . 'time. If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'sum' => array(
            'uri'         => 'projects/{projectId}/queries/sum',
            'description' => 'POST returns the sum if all numeric values for the target property in the event '
                            . 'collection matching the given criteria. Non-numeric values are ignored. The '
                            . 'response will be a simple JSON object with one key: result, which maps to the '
                            . 'numeric result described previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based '
                                    . 'on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'selectUnique' => array(
            'uri'         => 'projects/{projectId}/queries/select_unique',
            'description' => 'POST returns a list of UNIQUE resources in the event collection matching the given '
                            . 'criteria. The response will be a simple JSON object with one key: result, which '
                            . 'maps to an array of unique property values.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based '
                                    . 'on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'funnel' => array(
            'uri'         => 'projects/{projectId}/queries/funnel',
            'description' => 'Funnels count relevant events in succession.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'   => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'steps'     => array(
                    'location'    => 'json',
                    'description' => 'A URL encoded JSON Array defining the Steps in the Funnel.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'multiAnalysis' => array(
            'uri'         => 'projects/{projectId}/queries/multi_analysis',
            'description' => 'Multi-analysis lets you run multiple types of analysis over the same data. Performing a '
                            . 'multi-analysis call is very similar to a Metric or a Series.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'analyses'         => array(
                    'location'    => 'json',
                    'description' => 'A URL encoded JSON object that defines the multiple types of analyses to '
                                    . 'perform.',
                    'type'        => 'array',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request '
                                    . 'based on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'extraction' => array(
            'uri'         => 'projects/{projectId}/queries/extraction',
            'description' => 'POST creates an extraction request for full-form event data with all property values. '
                            . 'If the query string parameter email is specified, then the extraction will be '
                            . 'processed asynchronously and an e-mail will be sent to the specified address when it '
                            . 'completes. The email will include a link to a downloadable CSV file. If email is '
                            . 'omitted, then the extraction will be processed in-line and JSON results will be '
                            . 'returned in the GET request.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request '
                                    . ' based on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'email'            => array(
                    'location'    => 'json',
                    'description' => 'Email that will be notified when your extraction is ready for download.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'latest'           => array(
                    'location'    => 'json',
                    'description' => 'An integer containing the number of most recent events to extract.',
                    'type'        => 'number',
                    'required'    => false,
                ),
                'property_names'   => array(
                    'location'    => 'json',
                    'description' => 'A URL-encoded array of strings containing properties you wish to extract. If '
                                    . 'this parameter is omitted, all properties will be returned.',
                    'type'        => 'array',
                    'required'    => false,
                ),
            ),
        ),

        'median' => array(
            'uri'         => 'projects/{projectId}/queries/median',
            'description' => 'POST returns the median across all numeric values for the target property in the event '
                            . 'collection matching the given criteria. Non-numeric values are ignored. The response '
                            . 'will be a simple JSON object with one key: result, which maps to the numeric result '
                            . 'described previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => 'number',
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),

        'percentile' => array(
            'uri'         => 'projects/{projectId}/queries/percentile',
            'description' => 'POST returns the Xth percentile value across all numeric values for the target property '
                            . 'in the event collection matching the given criteria. Non-numeric values are ignored. '
                            . 'The response will be a simple JSON object with one key: result, which maps to the '
                            . 'numeric result described previously.',
            'httpMethod'  => 'POST',
            'parameters'  => array(
                'projectId'        => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                'readKey'          => array(
                    'location'    => 'header',
                    'description' => 'The Read Key for the project.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'event_collection' => array(
                    'location'    => 'json',
                    'description' => 'The name of the event collection you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'target_property'  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'filters'          => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                'timeframe'        => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . ' If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'interval'         => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                'timezone'         => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => 'number',
                    'required'    => false,
                ),
                'group_by'         => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                'percentile'         => array(
                    'location'    => 'json',
                    'description' => 'The desired Xth percentile you want to get in your analysis.',
                    'type'        => 'number',
                    'required'    => true,
                ),
                'include_metadata' => array(
                    'location' => 'json',
                    'description' => 'Specifies whether to enrich query results with execution metadata or not.',
                    'type' => 'boolean',
                    'required' => false,
                ),
            ),
        ),
    ),
    'models' => array(
        'getResponse' => array(
            'type' => 'object',
            'addtionalProperties' => array(
                'location' => 'json'
            )
        )
    )
);
