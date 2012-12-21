<?php

namespace KeenIO\Service;

use KeenIO\Service\AbstractService;
use KeenIO\Service\Project;

final class KeenIO extends AbstractService {

    public function projects() {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects');
        $http->getMethod('GET');

        return $this->sendHttpRequest($http);
    }

    public function getProject($name) {
        $project = new Project($this->getApiKey());
        $project->setName($name);
        $project->setService($this);

        return $project;
    }
}