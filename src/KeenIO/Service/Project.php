<?php

namespace KeenIO\Service;

use KeenIO\Service\AbstractService;
use KeenIO\Service\Collection;
use KeenIO\Service\KeenIO;

final class Project extends AbstractService {

    // Reference to creating KeenIO
    private $service;

    public function getService() {
        return $this->service;
    }

    public function setService(KeenIO $service) {
        $this->service = $service;
    }

    public function getCollection($collectionName) {
        $this->verifyCollectionName($collectionName);

        $collection = new Collection($this->getApiKey());
        $collection->setName($collectionName);
        $collection->setProject($this);

        return $collection;
    }
}