<?php

$app['locale'] = 'fr';

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text($app));
    $twig->addFilter('md5', new \Twig_Filter_Function('md5'));
    return $twig;
}));
