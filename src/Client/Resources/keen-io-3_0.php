<?php

use \KeenIO\Client\Operations\Parameters\DefaultParameters;
use \KeenIO\Client\Operations\Parameters\ParametersKey;
use \KeenIO\Client\Operations\Parameters\ParameterKey;
use \KeenIO\Client\Operations\OperationsKeys;
use \KeenIO\Client\Operations\OperationKeys;
use \KeenIO\Client\Operations\Parameters\ValueLocation;
use \KeenIO\Client\Operations\Parameters\ValueHttpMethod;
use \KeenIO\Client\Operations\Parameters\ValueType;

return array(
    'name'        => 'KeenIO',
    'baseUri'     => 'https://api.keen.io/3.0/',
    'apiVersion'  => '3.0',
    'operations'  => array(
        OperationsKeys::GET_RESOURCES => array(
            OperationKeys::URI => '/',
            OperationKeys::DESCRIPTION => 'Returns the available child resources. Currently, the only child '
                            . 'resource is the Projects Resource.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
            ),
        ),

        OperationsKeys::CREATE_PROJECT => array(
            OperationKeys::URI => 'organizations/{organizationId}/projects',
            OperationKeys::DESCRIPTION => 'Creates a project for the specified organization and returns the '
                            . 'project id for later usage.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::ORGANIZATION_ID  => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::TYPE => ValueType::TYPE_STRING
                ),
                ParametersKey::ORGANIZATION_KEY => array(
                    ParameterKey::LOCATION => ValueLocation::HEADER,
                    ParameterKey::DESCRIPTION => 'The Organization Key.',
                    ParameterKey::SENT_AS => 'Authorization',
                    ParameterKey::PATTERN => '/^([[:alnum:]])+$/',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::PROJECT_DATA => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                ),
            ),
        ),

        OperationsKeys::GET_PROJECTS => array(
            OperationKeys::URI => 'projects',
            OperationKeys::DESCRIPTION => 'Returns the projects accessible to the API user, as well as '
                            . 'links to project sub-resources for discovery.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
            ),
        ),

        OperationsKeys::GET_PROJECT => array(
            OperationKeys::URI         => 'projects/{projectId}',
            OperationKeys::DESCRIPTION => 'GET returns detailed information about the specific project, '
                            . 'as well as links to related resources.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),

        OperationsKeys::GET_SAVED_QUERIES => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved',
            OperationKeys::DESCRIPTION  => 'Returns the saved queries accessible to the API user on the '
                            . 'specified project.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),

        OperationsKeys::GET_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            OperationKeys::DESCRIPTION => 'Returns the detailed information about the specified query, as '
                            . 'well as links to retrieve results.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'The saved query.',
                    ParameterKey::REQUIRED => true,
                ),
            ),
        ),

        OperationsKeys::CREATE_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            OperationKeys::DESCRIPTION => 'Creates the described query.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::PUT,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'The desired name of the query.',
                    ParameterKey::FILTERS => array([
                        "method" => '\KeenIO\Client\KeenIOClient::cleanQueryName',
                        "args" => ["@value"]
                    ]),
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::QUERY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                ),
            ),
        ),

        OperationsKeys::UPDATE_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            // TODO is it correct description?
            OperationKeys::DESCRIPTION => 'Creates the described query.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::PUT,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'The desired name of the query.',
                    ParameterKey::FILTERS => array([
                        "method" => '\KeenIO\Client\KeenIOClient::cleanQueryName',
                        "args" => ["@value"]
                    ]),
                    'required' => true,
                ),
                ParametersKey::QUERY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                ),
            ),
        ),

        OperationsKeys::DELETE_SAVED_QUERY => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}',
            OperationKeys::DESCRIPTION => 'Deletes the specified query.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::DELETE,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'The saved query.',
                    ParameterKey::REQUIRED => true,
                ),
            ),
        ),

        OperationsKeys::GET_SAVED_QUERY_RESULTS => array(
            OperationKeys::URI => 'projects/{projectId}/queries/saved/{query_name}/result',
            OperationKeys::DESCRIPTION => 'Returns the results of executing the specified query.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::QUERY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'The saved query.',
                    ParameterKey::REQUIRED => true,
                ),
            ),
        ),

        OperationsKeys::GET_COLLECTIONS => array(
            OperationKeys::URI         => 'projects/{projectId}/events',
            OperationKeys::DESCRIPTION => 'GET returns schema information for all the event collections in this '
                            . 'project, including properties and their type. It also returns links to sub-resources.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::GET,
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
            OperationKeys::DESCRIPTION => 'GET returns available schema information for this event collection, '
                            . 'including properties and their type. It also returns links to sub-resources.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::GET,
            OperationKeys::HTTP_METHOD => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri
            ),
        ),

        OperationsKeys::GET_PROPERTY => array(
            OperationKeys::URI         => 'projects/{projectId}/events/{event_collection}/properties/{property_name}',
            OperationKeys::DESCRIPTION => 'GET returns the property name, type, and a link to sub-resources.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
                ParametersKey::PROPERTY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'The property name to inspect',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
            ),
        ),

        OperationsKeys::ADD_EVENT => array(
            OperationKeys::URI => 'projects/{projectId}/events/{event_collection}',
            OperationKeys::DESCRIPTION => 'POST inserts an event into the specified collection.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::WRITE_KEY => DefaultParameters::$writeKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
            ),
            OperationKeys::ADDITIONAL_PARAMETERS => array(
                ParameterKey::LOCATION => ValueLocation::JSON
            ),
        ),

        OperationsKeys::ADD_EVENTS => array(
            OperationKeys::URI => 'projects/{projectId}/events',
            OperationKeys::DESCRIPTION => 'POST inserts multiple events in one or more collections, '
                            . 'in a single request. The API expects a JSON object whose keys are the names of each '
                            . 'event collection you want to insert into. Each key should point to a list of events to '
                            . 'insert for that event collection.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::WRITE_KEY  => DefaultParameters::$writeKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
            ),
            OperationKeys::ADDITIONAL_PARAMETERS => array(
                ParameterKey::LOCATION => ValueLocation::JSON,
            ),
        ),

        OperationsKeys::DELETE_EVENTS => array(
            OperationKeys::URI => 'projects/{projectId}/events/{event_collection}',
            OperationKeys::DESCRIPTION => 'DELETE one or multiple events from a collection. You can optionally add '
                            . 'filters, timeframe or timezone. You can delete up to 50,000 events using '
                            . 'one method call',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::DELETE,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION    => ValueLocation::QUERY,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::QUERY,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::FILTERS => array('KeenIO\Client\Filter\MultiTypeFiltering::encodeValue'),
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::QUERY,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::DELETE_EVENT_PROPERTIES => array(
            OperationKeys::URI => 'projects/{projectId}/events/{event_collection}/properties/{property_name}',
            OperationKeys::DESCRIPTION => 'DELETE one property for events. This only works for properties with '
                            . 'less than 10,000 events.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::DELETE,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::WRITE_KEY => DefaultParameters::$writeKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionUri,
                ParametersKey::PROPERTY_NAME => array(
                    ParameterKey::LOCATION => ValueLocation::URI,
                    ParameterKey::DESCRIPTION => 'Name of the property to delete.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
            ),
        ),

        OperationsKeys::COUNT => array(
            OperationKeys::URI => 'projects/{projectId}/queries/count',
            OperationKeys::DESCRIPTION => 'POST returns the number of resources in the event collection matching '
                            . 'the given criteria. The response will be a simple JSON object with one key: '
                            . 'a numeric result.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION  => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. '
                                    . 'The interval specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::COUNT_UNIQUE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/count_unique',
            OperationKeys::DESCRIPTION => 'POST returns the number of UNIQUE resources in the event collection '
                            . 'matching the given criteria. The response will be a simple JSON object with one key: '
                            . 'result, which maps to the numeric result described previously.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION  => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by '
                                    . 'which you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::MINIMUM => array(
            OperationKeys::URI => 'projects/{projectId}/queries/minimum',
            OperationKeys::DESCRIPTION => 'POST returns the minimum numeric value for the target property in the '
                            . 'event collection matching the given criteria. Non-numeric values are ignored. '
                            . 'The response will be a simple JSON object with one key: result, which maps to '
                            . 'the numeric result described previously.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::MAXIMUM => array(
            OperationKeys::URI => 'projects/{projectId}/queries/maximum',
            OperationKeys::DESCRIPTION => 'POST returns the maximum numeric value for the target property in '
                            . 'the event collection matching the given criteria. Non-numeric values are ignored. '
                            . 'The response will be a simple JSON object with one key: result, which maps to '
                            . 'the numeric result described previously.',
            OperationKeys::HTTP_METHOD  => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. '
                                    . 'The interval specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes '
                                    . 'to match a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by '
                                    . 'which you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::AVERAGE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/average',
            OperationKeys::DESCRIPTION => 'POST returns the average across all numeric values for the target '
                            . 'property in the event collection matching the given criteria. Non-numeric values '
                            . 'are ignored. The response will be a simple JSON object with one key: result, which '
                            . 'maps to the numeric result described previously.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY  => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in '
                                    . 'an analysis request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::SUM => array(
            OperationKeys::URI => 'projects/{projectId}/queries/sum',
            OperationKeys::DESCRIPTION => 'POST returns the sum if all numeric values for the target property '
                            . 'in the event collection matching the given criteria. Non-numeric values are ignored. '
                            . 'The response will be a simple JSON object with one key: result, which maps to the '
                            . 'numeric result described previously.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::SELECT_UNIQUE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/select_unique',
            OperationKeys::DESCRIPTION => 'POST returns a list of UNIQUE resources in the event collection '
                            . 'matching the given criteria. The response will be a simple JSON object with one key: '
                            . 'result, which maps to an array of unique property values.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY  => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::FUNNEL => array(
            OperationKeys::URI => 'projects/{projectId}/queries/funnel',
            OperationKeys::DESCRIPTION => 'Funnels count relevant events in succession.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::STEPS     => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A URL encoded JSON Array defining the Steps in the Funnel.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::MULTI_ANALYSIS => array(
            OperationKeys::URI => 'projects/{projectId}/queries/multi_analysis',
            OperationKeys::DESCRIPTION => 'Multi-analysis lets you run multiple types of analysis over the same data. '
                            . 'Performing a multi-analysis call is very similar to a Metric or a Series.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY  => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::ANALYSIS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A URL encoded JSON object that defines the multiple types of '
                                    . 'analyses to perform.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in '
                                    . 'an analysis request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
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
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request  based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::EMAIL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Email that will be notified when your extraction is ready '
                                    . 'for download.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::LATEST => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'An integer containing the number of most recent events to extract.',
                    ParameterKey::TYPE => ValueType::TYPE_NUMBER,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::PROPERTY_NAMES => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A URL-encoded array of strings containing properties you wish '
                                    . 'to extract. If this parameter is omitted, all properties will be returned.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::MEDIAN => array(
            OperationKeys::URI => 'projects/{projectId}/queries/median',
            OperationKeys::DESCRIPTION => 'POST returns the median across all numeric values for the target property '
                            . 'in the event collection matching the given criteria. Non-numeric values are ignored. '
                            . 'The response will be a simple JSON object with one key: result, which maps to '
                            . 'the numeric result described previously.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY  => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes '
                                    . 'to match a specific timezone.',
                    ParameterKey::TYPE => ValueType::TYPE_NUMBER,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
            ),
        ),

        OperationsKeys::PERCINTILE => array(
            OperationKeys::URI => 'projects/{projectId}/queries/percentile',
            OperationKeys::DESCRIPTION => 'POST returns the Xth percentile value across all numeric values for '
                            . 'the target property in the event collection matching the given criteria. Non-numeric '
                            . 'values are ignored. The response will be a simple JSON object with one key: result, '
                            . 'which maps to the numeric result described previously.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::READ_KEY => DefaultParameters::$readKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::EVENT_COLLECTION => DefaultParameters::$eventCollectionJson,
                ParametersKey::TARGET_PROPERTY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The name of the property you are analyzing.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => true,
                ),
                ParametersKey::FILTERS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Filters are used to narrow down the events used in an analysis '
                                    . 'request based on event property values.',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_FRAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A Timeframe specifies the events to use for analysis based on '
                                    . 'a window of time. If no timeframe is specified, all events will be counted.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::INTERVAL => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Intervals are used when creating a Series API call. The interval '
                                    . 'specifies the length of each sub-timeframe in a Series.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::TIME_ZONE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Modifies the timeframe filters for Relative Timeframes to match '
                                    . 'a specific timezone.',
                    ParameterKey::TYPE => ValueType::TYPE_NUMBER,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::GROUP_BY => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The group_by parameter specifies the name of a property by which '
                                    . 'you would like to group the results.',
                    ParameterKey::TYPE => ValueType::SET_STRING_ARRAY,
                    ParameterKey::REQUIRED => false,
                ),
                ParametersKey::PERCENTILE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'The desired Xth percentile you want to get in your analysis.',
                    ParameterKey::TYPE => ValueType::TYPE_NUMBER,
                    ParameterKey::REQUIRED => true,
                ),
            ),
        ),
        
        OperationsKeys::CREATE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys',
            OperationKeys::DESCRIPTION => 'Creates a project access key.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::NAME => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'API Key Name. Limited to 256 characters.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING,
                ),
                ParametersKey::IS_ACTIVE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Indicates if the key is currently active or revoked',
                    ParameterKey::TYPE => ValueType::TYPE_BOOLEAN
                ),
                ParametersKey::PERMITTED => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A list of high level actions this key can perform',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY
                ),
                ParametersKey::OPTIONS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A list of high level actions this key can perform',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY
                )
            ),
        ),
        
        OperationsKeys::LIST_ACCESS_KEYS => array(
            OperationKeys::URI => 'projects/{projectId}/keys',
            OperationKeys::DESCRIPTION => 'Returns all project access keys.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId
            ),
        ),
        
        OperationsKeys::GET_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}',
            OperationKeys::DESCRIPTION => 'Returns a project access key.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::GET,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
            ),
        ),
        
        OperationsKeys::UPDATE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}',
            OperationKeys::DESCRIPTION => 'Updates a project access key.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
                ParametersKey::NAME => [
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'API Key Name. Limited to 256 characters.',
                    ParameterKey::TYPE => ValueType::TYPE_STRING
                ],
                ParametersKey::IS_ACTIVE => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'Indicates if the key is currently active or revoked',
                    ParameterKey::TYPE => ValueType::TYPE_BOOLEAN
                ),
                ParametersKey::PERMITTED => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A list of high level actions this key can perform',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY
                ),
                ParametersKey::OPTIONS => array(
                    ParameterKey::LOCATION => ValueLocation::JSON,
                    ParameterKey::DESCRIPTION => 'A list of high level actions this key can perform',
                    ParameterKey::TYPE => ValueType::TYPE_ARRAY
                )
            )
        ),
        
        OperationsKeys::REVOKE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}/revoke',
            OperationKeys::DESCRIPTION => 'Revokes a project access key.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
            OperationKeys::PARAMETERS  => array(
                ParametersKey::MASTER_KEY => DefaultParameters::$masterKey,
                ParametersKey::PROJECT_ID => DefaultParameters::$projectId,
                ParametersKey::KEY => DefaultParameters::$accessKey,
            ),
        ),
        
        OperationsKeys::UN_REVOKE_ACCESS_KEY => array(
            OperationKeys::URI => 'projects/{projectId}/keys/{key}/unrevoke',
            OperationKeys::DESCRIPTION => 'Unrevokes a project access key.',
            OperationKeys::HTTP_METHOD => ValueHttpMethod::POST,
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
