<?php

namespace KeenIO\Service;

use Zend\Json\Json;

use KeenIO\Service\AbstractService;
use KeenIO\Service\Projct;
use KeenIO\Service\SavedQuery;

final class Collection extends AbstractService
{
    private $project;

    public function getProject()
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Send an event
     */
    public function send($values)
    {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/events/' . $this->getName());
        $http->setMethod('POST');
        $http->getRequest()->setContent(Json::encode($values));

        $json = $this->sendHttpRequest($http);

        return $json->created;
    }

    public function getSavedQuery($name)
    {
        $query = new SavedQuery($this->getApiKey());
        $query->setCollection($this);
        $query->setName($name);
        $query->get();

        return $query;
    }

    public function getSavedQueries()
    {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/saved_queries');
        $http->setMethod('GET');

        return $this->sendHttpRequest($http);
    }

    public function count($options = array())
    {
        $this->validateOptions(array('filters', 'timeframe'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/count');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function countUnique($targetProperty, $options = array())
    {
        $this->validateOptions(array('filters', 'timeframe', 'group_by'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/count_unique');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $options['target_property'] = $targetProperty;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function minimum($targetProperty, $options = array())
    {
        $this->validateOptions(array('filters', 'timeframe', 'group_by'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/minimum');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $options['target_property'] = $targetProperty;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function maximum($targetProperty, $options = array())
    {
        $this->validateOptions(array('filters', 'timeframe', 'group_by'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/maximum');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $options['target_property'] = $targetProperty;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function average($targetProperty, $options = array())
    {
        $this->validateOptions(array('filters', 'timeframe', 'group_by'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/average');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $options['target_property'] = $targetProperty;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function sum($targetProperty, $options = array())
    {
        $this->validateOptions(array('filters', 'timeframe', 'group_by'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/sum');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $options['target_property'] = $targetProperty;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function selectUnique($targetProperty, $options = array())
    {
        $this->validateOptions(array('filters', 'timeframe'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/select_unique');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $options['target_property'] = $targetProperty;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function extraction($options = array())
    {
        $this->validateOptions(array('filters', 'timeframe', 'email_address', 'latest'), $options);

        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/extraction');
        $http->setMethod('GET');

        $options['event_collection'] = $this->getName();
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }

    public function funnel($steps)
    {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getProject()->getName() . '/queries/funnel');
        $http->setMethod('GET');

        $options = array();
        $options['steps'] = $steps;
        $http->setParameterGet($options);

        return $this->sendHttpRequest($http);
    }
}