<?php

namespace KeenIO\Service;

use Zend\Json\Json;

use KeenIO\Service\AbstractService;
use KeenIO\Service\Collection;

final class SavedQuery extends AbstractService
{
    private $collection;
    private $analysisType;
    private $targetProperty;
    private $filters;
    private $timeframe;
    private $interval;
    private $email;
    private $steps;

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;
        return $this;
    }

    public function getAnalysisType()
    {
        return $this->analysisType;
    }

    public function setAnalysisType($value)
    {
        $this->analysisType = $value;
        return $this;
    }

    public function getTargetProperty()
    {
        return $this->targetProperty;
    }

    public function setTargetProperty($value)
    {
        $this->targetProperty = $value;
        return $this;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($value)
    {
        $this->filters = $value;
        return $this;
    }

    public function getTimeframe()
    {
        return $this->timeframe;
    }

    public function setTimeframe($value)
    {
        $this->timeframe = $value;
        return $this;
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function setInterval($value)
    {
        $this->interval = $value;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
        return $this;
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function setSteps($value)
    {
        $this->steps = $value;
        return $this;
    }

    public function get()
    {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getCollection()->getProject()->getName() . '/saved_queries/' . $this->getName());
        $http->setMethod('GET');

        $query = $this->sendHttpRequest($http);

        if (isset($query->analysis_type)) $this->setAnalysisType($query->analysis_type);
        if (isset($query->target_property)) $this->setTargetProperty($query->target_property);
        if (isset($query->filters)) $this->setFilters($query->filters);
        if (isset($query->timeframe)) $this->setTimeframe($query->timeframe);
        if (isset($query->interval)) $this->setInterval($query->interval);
        if (isset($query->email)) $this->setEmail($query->email);
        if (isset($query->steps)) $this->setSteps($query->steps);

        return $query;
    }

    public function save()
    {
        $options = array();

        $options['analysis_type'] = $this->getAnalysisType();

        switch ($this->getAnalysisType()) {
            case 'count':
                $options['event_collection'] = $this->getCollection()->getName();
                if ($this->getFilters())
                    $options['filters'] = $this->getFilters();
                if ($this->getTimeframe())
                    $options['timeframe'] = $this->getTimeframe();
                if ($this->getInterval())
                    $options['interval'] = $this->getInterval();
                break;

            case 'count_unique':
            case 'select_unique':
            case 'minimum':
            case 'maximum':
            case 'average':
            case 'sum':
                $options['event_collection'] = $this->getCollection()->getName();
                if (!$this->getTargetProperty())
                    throw new \Exception('Target property is required');
                $options['target_property'] = $this->getTargetProperty();
                if ($this->getFilters())
                    $options['filters'] = $this->getFilters();
                if ($this->getTimeframe())
                    $options['timeframe'] = $this->getTimeframe();
                if ($this->getInterval())
                    $options['interval'] = $this->getInterval();
                break;

            case 'extraction':
                $options['event_collection'] = $this->getCollection()->getName();
                if (!$this->getTargetProperty())
                    throw new \Exception('Target property is required');
                $options['target_property'] = $this->getTargetProperty();
                if ($this->getFilters())
                    $options['filters'] = $this->getFilters();
                if ($this->getTimeframe())
                    $options['timeframe'] = $this->getTimeframe();
                if ($this->getEmail())
                    $options['email'] = $this->getEmail();
                break;

            case 'funnel':
                if ($this->getEmail())
                    $options['steps'] = $this->getSteps();
                break;

            default:
                throw new \Exception('Invalid analysis type');
                break;
        }


        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getCollection()->getProject()->getName() . '/saved_queries/' . $this->getName());
        $http->setMethod('PUT');
        $http->getRequest()->setContent(Json::encode($options));

        return $this->sendHttpRequest($http);
    }

    public function delete()
    {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects/' . $this->getCollection()->getProject()->getName() . '/saved_queries/' . $this->getName());
        $http->setMethod('DELETE');

        return $this->sendHttpRequest($http);
    }
}