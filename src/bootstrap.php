<?php

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => __DIR__.'/../views',
    'twig.options' => array(
        'cache' => __DIR__.'/../cache/twig',
        'debug' => true,
    ),
));
$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new FormServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'my_database',
        'user'      => 'my_username',
        'password'  => 'my_password',
    ),
));

$app['debug'] = true;
$app['locale'] = 'fr';
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text($app));
    $twig->addFilter('md5', new \Twig_Filter_Function('md5'));
    return $twig;
}));
