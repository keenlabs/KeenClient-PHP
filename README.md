$keenIO = new \KeenIO\Service\KeenIO('apiKey');
$project = $keenIO->getProject('projectId');
$collection = $project->getCollection('collectionName');
$query = $project->getQuery('queryName');

$keenIO->getProjects();

$project->getName();

$collection->getName();

// Send an event
$collection->send(array('test1' => 1));

$collection->count();
$collection->countUnique('test1');
$collection->minimum('test1');
$collection->maximum('test1');
$collection->average('test1');
$collection->sum('test1');
$collection->selectUnique('test1');
$collection->extraction();
$collection->funnel(array('see docs'));

$query = $collection->getQuery('test1-a');
$query->setAnalysisType('count');
$query->save();

$collection->savedQueries();
