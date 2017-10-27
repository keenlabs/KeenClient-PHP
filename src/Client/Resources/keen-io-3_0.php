<?php

use \KeenIO\Client\Operations\Parameters\DefaultParameters;
use KeenIO\Client\Operations\Parameters\ParametersKey;
use \KeenIO\Client\Operations\OperationsKeys;
use \KeenIO\Client\Operations\OperationKeys;

return array(
    'name'        => 'KeenIO',
    'baseUri'     => 'https://api.keen.io/3.0/',
    'apiVersion'  => '3.0',
    'operations'  => array(
        OperationsKeys::GET_RESOURCES => array(
            OperationKeys::URI => '/',
            OperationKeys::DESCRIPTION => 'Returns the available child resources. Currently, the only child '
                            . 'resource is the Projects Resource.',
            OperationKeys::HTTP_METHOD  => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
            ),
        ),

        OperationsKeys::CREATE_PROJECT => array(
            OperationKeys::URI => 'organizations/{organizationId}/projects',
            OperationKeys::DESCRIPTION => 'Creates a project for the specified organization and returns the '
                            . 'project id for later usage.',
            OperationKeys::HTTP_METHOD  => 'POST',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::ORGANIZATION_ID  => array(
                    'location'    => 'uri',
                    'type'        => 'string'
                ),
                ParametersKey::ORGANIZATION_KEY => array(
                    'location'    => 'header',
                    'description' => 'The Organization Key.',
                    'sentAs'      => 'Authorization',
                    'pattern'     => '/^([[:alnum:]])+$/',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::PROJECT_DATA => array(
                    'location' => 'json',
                    'type'     => 'array',
                ),
            ),
        ),

        OperationsKeys::GET_PROJECTS => array(
            OperationKeys::URI => 'projects',
            OperationKeys::DESCRIPTION => 'Returns the projects accessible to the API user, as well as '
                            . 'links to project sub-resources for discovery.',
            OperationKeys::HTTP_METHOD  => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
            ),
        ),

        OperationsKeys::GET_PROJECT => array(
            OperationKeys::URI         => 'projects/{projectId}',
            OperationKeys::DESCRIPTION => 'GET returns detailed information about the specific project, '
                            . 'as well as links to related resources.',
            OperationKeys::HTTP_METHOD  => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),

        OperationsKeys::GET_SAVED_QUERIES => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved',
            OperationKeys::DESCRIPTION  => 'Returns the saved queries accessible to the API user on the specified project.',
            OperationKeys::HTTP_METHOD  => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),

        OperationsKeys::GET_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            OperationKeys::DESCRIPTION => 'Returns the detailed information about the specified query, as '
                            . 'well as links to retrieve results.',
            OperationKeys::HTTP_METHOD => 'GET',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    'location'    => 'uri',
                    'description' => 'The saved query.',
                    'required'    => true,
                ),
            ),
        ),

        OperationsKeys::CREATE_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            OperationKeys::DESCRIPTION => 'Creates the described query.',
            OperationKeys::HTTP_METHOD  => 'PUT',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
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

        OperationsKeys::UPDATE_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            // TODO is it correct description?
            OperationKeys::DESCRIPTION => 'Creates the described query.',
            OperationKeys::HTTP_METHOD => 'PUT',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
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

        OperationsKeys::DELETE_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            OperationKeys::DESCRIPTION => 'Deletes the specified query.',
            OperationKeys::HTTP_METHOD => 'DELETE',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    'location'    => 'uri',
                    'description' => 'The saved query.',
                    'required'    => true,
                ),
            ),
        ),

        OperationsKeys::GET_SAVED_QUERY_RESULTS => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}/result',
            OperationKeys::DESCRIPTION => 'Returns the results of executing the specified query.',
            OperationKeys::HTTP_METHOD => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    'location'    => 'uri',
                    'description' => 'The saved query.',
                    'required'    => true,
                ),
            ),
        ),

        OperationsKeys::GET_COLLECTIONS => array(
            OperationKeys::URI         => 'projects/{projectId}/events',
            OperationKeys::DESCRIPTION => 'GET returns schema information for all the event collections in this project, '
                            . 'including properties and their type. It also returns links to sub-resources.',
            OperationKeys::HTTP_METHOD  => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),

        OperationsKeys::GET_EVENT_SCHEMAS => array(
            OperationKeys::KEY_EXTENDS => 'getCollections'
        ),

        OperationsKeys::GET_COLLECTION => array(
            OperationKeys::URI  => 'projects/{projectId}/events/{event_collection}',
            OperationKeys::DESCRIPTION => 'GET returns available schema information for this event collection, including '
                            . 'properties and their type. It also returns links to sub-resources.',
            OperationKeys::HTTP_METHOD => 'GET',
            OperationKeys::HTTP_METHOD => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri
            ),
        ),

        OperationsKeys::GET_PROPERTY => array(
            OperationKeys::URI         => 'projects/{projectId}/events/{event_collection}/properties/{property_name}',
            OperationKeys::DESCRIPTION => 'GET returns the property name, type, and a link to sub-resources.',
            OperationKeys::HTTP_METHOD  => 'GET',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
                ParametersKey::PROPERTY_NAME => array(
                    'location'    => 'uri',
                    'description' => 'The property name to inspect',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        OperationsKeys::ADD_EVENT => array(
            OperationKeys::URI => 'projects/{projectId}/events/{event_collection}',
            OperationKeys::DESCRIPTION => 'POST inserts an event into the specified collection.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::WRITE_KEY => DefaultParameters::$writeKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
            ),
            OperationKeys::ADDITIONAL_PARAMETERS => array(
                'location' => 'json'
            ),
        ),

        OperationsKeys::ADD_EVENTS => array(
            OperationKeys::URI => 'projects/{projectId}/events',
            OperationKeys::DESCRIPTION => 'POST inserts multiple events in one or more collections, in a single request. The API '
                            . 'expects a JSON object whose keys are the names of each event collection you want to '
                            . 'insert into. Each key should point to a list of events to insert for that event '
                            . 'collection.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::WRITE_KEY  => DefaultParameters::$writeKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
            ),
            OperationKeys::ADDITIONAL_PARAMETERS => array(
                'location' => 'json',
            ),
        ),

        OperationsKeys::DELETE_EVENTS => array(
            OperationKeys::URI => 'projects/{projectId}/events/{event_collection}',
            OperationKeys::DESCRIPTION => 'DELETE one or multiple events from a collection. You can optionally add filters, '
                            . 'timeframe or timezone. You can delete up to 50,000 events using one method call',
            OperationKeys::HTTP_METHOD => 'DELETE',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
                ParametersKey::FILTERS => array(
                    'location'    => 'query',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'query',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'filters'     => array('KeenIO\Client\Filter\MultiTypeFiltering::encodeValue'),
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'query',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::DELETE_EVENT_PROPERTIES => array(
            OperationKeys::URI => 'projects/{projectId}/events/{event_collection}/properties/{property_name}',
            OperationKeys::DESCRIPTION => 'DELETE one property for events. This only works for properties with less than 10,000 '
                            . 'events.',
            OperationKeys::HTTP_METHOD => 'DELETE',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::WRITE_KEY => DefaultParameters::$writeKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
                ParametersKey::PROPERTY_NAME => array(
                    'location'    => 'uri',
                    'description' => 'Name of the property to delete.',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),

        OperationsKeys::COUNT => array(
            OperationKeys::URI => 'projects/{projectId}/queries/count',
            OperationKeys::DESCRIPTION => 'POST returns the number of resources in the event collection matching the given criteria.'
                            . ' The response will be a simple JSON object with one key: a numeric result.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::COUNT_UNIQUE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/count_unique',
            OperationKeys::DESCRIPTION => 'POST returns the number of UNIQUE resources in the event collection matching the given '
                            . 'criteria. The response will be a simple JSON object with one key: result, which maps '
                            . 'to the numeric result described previously.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::MINIMUM => array(
            OperationKeys::URI => 'projects/{projectId}/queries/minimum',
            OperationKeys::DESCRIPTION => 'POST returns the minimum numeric value for the target property in the event collection '
                            . 'matching the given criteria. Non-numeric values are ignored. The response will be a '
                            . 'simple JSON object with one key: result, which maps to the numeric result described '
                            . 'previously.',
            OperationKeys::HTTP_METHOD  => 'POST',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::MAXIMUM => array(
            OperationKeys::URI => 'projects/{projectId}/queries/maximum',
            OperationKeys::DESCRIPTION => 'POST returns the maximum numeric value for the target property in the event collection '
                            . 'matching the given criteria. Non-numeric values are ignored. The response will be a '
                            . 'simple JSON object with one key: result, which maps to the numeric result described '
                            . 'previously.',
            OperationKeys::HTTP_METHOD  => 'POST',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::AVERAGE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/average',
            OperationKeys::DESCRIPTION => 'POST returns the average across all numeric values for the target property in the event '
                            . 'collection matching the given criteria. Non-numeric values are ignored. The response '
                            . 'will be a simple JSON object with one key: result, which maps to the numeric result '
                            . 'described previously.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY  => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request '
                                    . 'based on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of '
                                    . 'time. If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::SUM => array(
            OperationKeys::URI => 'projects/{projectId}/queries/sum',
            OperationKeys::DESCRIPTION => 'POST returns the sum if all numeric values for the target property in the event '
                            . 'collection matching the given criteria. Non-numeric values are ignored. The '
                            . 'response will be a simple JSON object with one key: result, which maps to the '
                            . 'numeric result described previously.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based '
                                    . 'on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would like '
                                    . 'to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::SELECT_UNIQUE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/select_unique',
            OperationKeys::DESCRIPTION => 'POST returns a list of UNIQUE resources in the event collection matching the given '
                            . 'criteria. The response will be a simple JSON object with one key: result, which '
                            . 'maps to an array of unique property values.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY  => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based '
                                    . 'on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
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
            ),
        ),

        OperationsKeys::FUNNEL => array(
            OperationKeys::URI => 'projects/{projectId}/queries/funnel',
            OperationKeys::DESCRIPTION => 'Funnels count relevant events in succession.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::STEPS     => array(
                    'location'    => 'json',
                    'description' => 'A URL encoded JSON Array defining the Steps in the Funnel.',
                    'type'        => 'array',
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::MULTI_ANALYSIS => array(
            OperationKeys::URI => 'projects/{projectId}/queries/multi_analysis',
            OperationKeys::DESCRIPTION => 'Multi-analysis lets you run multiple types of analysis over the same data. Performing a '
                            . 'multi-analysis call is very similar to a Metric or a Series.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY  => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::ANALYSIS => array(
                    'location'    => 'json',
                    'description' => 'A URL encoded JSON object that defines the multiple types of analyses to '
                                    . 'perform.',
                    'type'        => 'array',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request '
                                    . 'based on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => array('string', 'number'),
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::EXTRACTION => array(
            OperationKeys::URI => 'projects/{projectId}/queries/extraction',
            OperationKeys::DESCRIPTION => 'POST creates an extraction request for full-form event data with all '
                            . 'property values. If the query string parameter email is specified, then the extraction '
                            . 'will be processed asynchronously and an e-mail will be sent to the specified address '
                            . 'when it completes. The email will include a link to a downloadable CSV file. '
                            . 'If email is omitted, then the extraction will be processed in-line and JSON results '
                            . 'will be returned in the GET request.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request '
                                    . ' based on event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::EMAIL => array(
                    'location'    => 'json',
                    'description' => 'Email that will be notified when your extraction is ready for download.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::LATEST => array(
                    'location'    => 'json',
                    'description' => 'An integer containing the number of most recent events to extract.',
                    'type'        => 'number',
                    'required'    => false,
                ),
                ParametersKey::PROPERTY_NAMES => array(
                    'location'    => 'json',
                    'description' => 'A URL-encoded array of strings containing properties you wish to extract. If '
                                    . 'this parameter is omitted, all properties will be returned.',
                    'type'        => 'array',
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::MEDIAN => array(
            OperationKeys::URI => 'projects/{projectId}/queries/median',
            OperationKeys::DESCRIPTION => 'POST returns the median across all numeric values for the target property in the event '
                            . 'collection matching the given criteria. Non-numeric values are ignored. The response '
                            . 'will be a simple JSON object with one key: result, which maps to the numeric result '
                            . 'described previously.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY  => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . 'If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => 'number',
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
                    'location'    => 'json',
                    'description' => 'The group_by parameter specifies the name of a property by which you would '
                                    . 'like to group the results.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
            ),
        ),

        OperationsKeys::PERCINTILE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/percentile',
            OperationKeys::DESCRIPTION => 'POST returns the Xth percentile value across all numeric values for the target property '
                            . 'in the event collection matching the given criteria. Non-numeric values are ignored. '
                            . 'The response will be a simple JSON object with one key: result, which maps to the '
                            . 'numeric result described previously.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    'location'    => 'json',
                    'description' => 'The name of the property you are analyzing.',
                    'type'        => 'string',
                    'required'    => true,
                ),
                ParametersKey::FILTERS => array(
                    'location'    => 'json',
                    'description' => 'Filters are used to narrow down the events used in an analysis request based on '
                                    . 'event property values.',
                    'type'        => 'array',
                    'required'    => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    'location'    => 'json',
                    'description' => 'A Timeframe specifies the events to use for analysis based on a window of time. '
                                    . ' If no timeframe is specified, all events will be counted.',
                    'type'        => array('string', 'array'),
                    'required'    => false,
                ),
                ParametersKey::INTERVAL => array(
                    'location'    => 'json',
                    'description' => 'Intervals are used when creating a Series API call. The interval specifies the '
                                    . 'length of each sub-timeframe in a Series.',
                    'type'        => 'string',
                    'required'    => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    'location'    => 'json',
                    'description' => 'Modifies the timeframe filters for Relative Timeframes to match a specific '
                                    . 'timezone.',
                    'type'        => 'number',
                    'required'    => false,
                ),
                ParametersKey::GROUP_BY => array(
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
            ),
        ),
        
        OperationsKeys::CREATE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys',
            OperationKeys::DESCRIPTION => 'Creates a project access key.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::NAME => array(
                    'location' => 'json',
                    'description' => 'API Key Name. Limited to 256 characters.',
                    'type' => 'string',
                ),
                ParametersKey::IS_ACTIVE => array(
                    'location' => 'json',
                    'description' => 'Indicates if the key is currently active or revoked',
                    'type' => 'boolean'
                ),
                ParametersKey::PERMITTED => array(
                    'location' => 'json',
                    'description' => 'A list of high level actions this key can perform',
                    'type' => 'array'
                ),
                ParametersKey::OPTIONS => array(
                    'location' => 'json',
                    'description' => 'A list of high level actions this key can perform',
                    'type' => 'array'
                )
            ),
        ),
        
        OperationsKeys::LIST_ACCESS_KEYS => array(
            OperationKeys::URI => 'projects/{projectId}/keys',
            OperationKeys::DESCRIPTION => 'Returns all project access keys.',
            OperationKeys::HTTP_METHOD => 'GET',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),
        
        OperationsKeys::GET_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}',
            OperationKeys::DESCRIPTION => 'Returns a project access key.',
            OperationKeys::HTTP_METHOD => 'GET',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
            ),
        ),
        
        OperationsKeys::UPDATE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}',
            OperationKeys::DESCRIPTION => 'Updates a project access key.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
                ParametersKey::NAME => [
                    'location' => 'json',
                    'description' => 'API Key Name. Limited to 256 characters.',
                    'type' => 'string'
                ],
                ParametersKey::IS_ACTIVE => array(
                    'location' => 'json',
                    'description' => 'Indicates if the key is currently active or revoked',
                    'type' => 'boolean'
                ),
                ParametersKey::PERMITTED => array(
                    'location' => 'json',
                    'description' => 'A list of high level actions this key can perform',
                    'type' => 'array'
                ),
                ParametersKey::OPTIONS => array(
                    'location' => 'json',
                    'description' => 'A list of high level actions this key can perform',
                    'type' => 'array'
                )
            )
        ),
        
        OperationsKeys::REVOKE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}/revoke',
            OperationKeys::DESCRIPTION => 'Revokes a project access key.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
            ),
        ),
        
        OperationsKeys::UN_REVOKE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}/unrevoke',
            OperationKeys::DESCRIPTION => 'Unrevokes a project access key.',
            OperationKeys::HTTP_METHOD => 'POST',
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
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
