Keen IO Zend Framework 2 Library
================================
This is a library to abstract the Keen IO API to PHP objects.  

Install
-------
Edit your composer.json file with the following contents
```json
"require": {
    "keen-io/keen-io-tha": "dev-master"
}
```

Use
---

Create a KeenIO service object
```php
$keenIO = new \KeenIO\Service\KeenIO('apiKey');
```

Fetch a list of all projects 
```php
$keenIO->getProjects();
```

Fetch a project
```php
$project = $keenIO->getProject('projectId');
```

Fetch a collection for a project
```php
$collection = $project->getCollection('collectionName');
```

Echo the project and collection names
```php
$project->getName();
$collection->getName();
```

Send a new event
```php
$collection->send(array(
    'test1' => 1,
    'test2' => 2,
    'test3' => 3,
    'test4' => 4,
));
```

Analyze a collection
```php
$collection->count();
$collection->countUnique('test1');
$collection->minimum('test1');
$collection->maximum('test1');
$collection->average('test1');
$collection->sum('test1');
$collection->selectUnique('test1');
$collection->extraction();
$collection->funnel(array('see docs'));
```

Fetch or create a query
```php
$query = $collection->getQuery('test1-a');
$query->setAnalysisType('count');
$query->save();
```

Show all saved queries
```php
$collection->savedQueries();
```
