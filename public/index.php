<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Name\Repository;
use Name\Finder;
use Name\Correct_interval;

require __DIR__ . '/../vendor/autoload.php';


$repo = new Repository();

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

$container->get('renderer')->setLayout("layout.php");



AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);



$app->get('/', function ($request, $response) use ($repo) {
    $repo->destroy();
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/experts/result', function ($request, $response) use ($repo) {
    $params = [
        'experts' => $repo->all()
    ];
    return $this->get('renderer')->render($response, 'experts.phtml', $params);
});


$app->get('/experts/new', function ($request, $response) {
    return $this->get('renderer')->render($response, 'form.phtml');
});



$app->post('/experts', function ($request, $response) use ($repo) {
    $finder = new Finder();
    $district = $request->getParsedBodyParam('district');
    $experts = $finder->find($district);
    $repo->save($experts);
    return $response->withHeader('Location', '/experts/result')
    	->withStatus(302);
});


$app->get('/intervals/new', function ($request, $response) {
    return $this->get('renderer')->render($response, '/time_intervals/form_intervals.phtml');
});


$app->post('/intervals', function ($request, $response) use ($repo) {
    $correct = new Correct_interval();
    $intervals = $request->getParsedBodyParam('intervals');
    $error = $correct->correct($intervals['content']);
    if ($error == '') {
        $repo->save($intervals);
        return $response->withHeader('Location', '/intervals/result')
          ->withStatus(302);
    }
    $params = [
        'intervals' => $intervals,
        'error' => $error
    ];
    return $this->get('renderer')->render($response, '/time_intervals/form_intervals.phtml', $params)
        ->withStatus(422);
});



$app->get('/intervals/result', function ($request, $response) use ($repo) {
    $params = [
        'intervals' => $repo->all()
    ];
    return $this->get('renderer')->render($response, '/time_intervals/intervals_result.phtml', $params);
});





$app->run();
