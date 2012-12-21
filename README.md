Keen IO Zend Framework 2 Library
================================
This is a library to abstract the Keen IO API to PHP objects.  


Create a KeenIO service object
```
$keenIO = new \KeenIO\Service\KeenIO('apiKey');
```

Fetch a list of all projects 
```
$keenIO->getProjects();
```

Fetch a project
```
$project = $keenIO->getProject('projectId');
```

Fetch a collection for a project
```
$collection = $project->getCollection('collectionName');
```

Echo the project and collection names
```
$project->getName();
$collection->getName();
```

Send a new event
```
$collection->send(array(
    'test1' => 1,
    'test2' => 2,
    'test3' => 3,
    'test4' => 4,
));
```

Analyze a collection
```
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
```
$query = $collection->getQuery('test1-a');
$query->setAnalysisType('count');
$query->save();
```

Show all saved queries
```
$collection->savedQueries();
```
