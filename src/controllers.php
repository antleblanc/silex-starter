<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

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

// MATCH
$app->match('/form', function(Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('name', 'text', array(
            'label' => 'Votre nom',
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Veuillez renseigner votre nom.',
                )),
            ),
            'attr'  => array(
                'placeholder' => 'Votre nom',
            ),
        ))
        ->add('email', 'email', array(
            'label' => 'Votre email',
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Veuillez renseigner votre email.',
                )),
            ),
            'attr'  => array(
                'placeholder' => 'Votre email',
            ),
        ))
        ->getForm()
    ;

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            ($app['debug']) ? $app['monolog']->addInfo('Form submitted.') : '';
            $app['session']->getFlashBag()->add('notice', 'Vos informations ont été sauvegardées!');

            // do something with the data

            return $app->redirect($app['url_generator']->generate('homepage'));
        }
    }

    return $app['twig']->render('form/index.html.twig', array(
        'form' => $form->createView(),
    ));
})
->bind('form');

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
