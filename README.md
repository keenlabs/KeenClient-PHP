Keen IO Zend PHP Library
================================
This is a library to abstract the Keen IO API to PHP objects.  


Installation
------------
  1. edit `composer.json` file with following contents:

     ```json
     "require": {
        "keen-io/keen-io-tha": "dev-master"
     }
     ```
  2. install composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  3. run `php composer.phar install`

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
$query = $collection->getSavedQuery('test1-a');
$query->setAnalysisType('count');
$query->save();
```

Show all saved queries
```php
$collection->getSavedQueries();
```
