<?php

namespace KeenIO\Client\Operations;

class OperationsKeys
{
    const GET_RESOURCES = 'getResources';
    const CREATE_PROJECT = 'createProject';
    const GET_PROJECTS = 'getProjects';
    const GET_PROJECT = 'getProject';

    const GET_SAVED_QUERIES = 'getSavedQueries';
    const GET_SAVED_QUERY = 'getSavedQuery';
    const CREATE_SAVED_QUERY = 'createSavedQuery';
    const UPDATE_SAVED_QUERY = 'updateSavedQuery';
    const DELETE_SAVED_QUERY = 'deleteSavedQuery';
    const GET_SAVED_QUERY_RESULTS = 'getSavedQueryResults';

    const GET_COLLECTIONS = 'getCollections';
    const GET_COLLECTION = 'getCollection';

    const GET_EVENT_SCHEMAS = 'getEventSchemas';
    const ADD_EVENTS = 'addEvents';
    const ADD_EVENT = 'addEvent';
    const DELETE_EVENTS = 'deleteEvents';
    const DELETE_EVENT_PROPERTIES = 'deleteEventProperties';

    const GET_PROPERTY = 'getProperty';

    const COUNT = 'count';
    const COUNT_UNIQUE = 'countUnique';
    const MINIMUM = 'minimum';
    const MAXIMUM = 'maximum';
    const AVERAGE = 'average';
    const SUM = 'sum';
    const SELECT_UNIQUE = 'selectUnique';
    const FUNNEL = 'funnel';
    const MULTI_ANALYSIS = 'multiAnalysis';
    const EXTRACTION = 'extraction';
    const MEDIAN = 'median';
    const PERCINTILE = 'percentile';

    const CREATE_ACCESS_KEY = 'createAccessKey';
    const LIST_ACCESS_KEYS = 'listAccessKeys';
    const GET_ACCESS_KEY = 'getAccessKey';
    const UPDATE_ACCESS_KEY = 'updateAccessKey';
    const REVOKE_ACCESS_KEY = 'revokeAccessKey';
    const UN_REVOKE_ACCESS_KEY = 'unRevokeAccessKey';

}