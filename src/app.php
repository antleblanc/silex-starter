<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;

$app = new Application();

require __DIR__.'/bootstrap.php';
require __DIR__.'/controller.php';

// BEFORE
$app->before(function () use ($app) {
    
});

// GET 
$app->get('/', function () use ($app) {
    return $app['twig']->render('home/index.html.twig', array());
})
->bind('homepage');

$app->get('/hello/{name}', function ($name) use ($app) {
    return $app['twig']->render('hello/index.html.twig', array(
        'name' => $name,
    ));
})
->bind('hello')
->value('name', 'World');

// ERROR
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $error = 404 == $code ? $e->getMessage() : null;

    return new Response($app['twig']->render('error/index.html.twig', array(
        'error' => $error
    )), $code);
});

return $app;
