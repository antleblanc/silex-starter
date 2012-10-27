<?php

use Silex\WebTestCase;

class FunctionalTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';

        require __DIR__.'/../config/dev.php';
        require __DIR__.'/../src/controllers.php';

        $app['session.test'] = true;

        return $app;
    }

    public function testInitialPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertTrue($client->getResponse()->isOk());
    }

    public function testHelloPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/hello/Foo');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Foo")'));
    }

    public function testFormPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/form');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testFormSubmit()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/form');

        $form = $crawler->selectButton('submit')->form();

        $form['form[name]'] = 'John';
        $form['form[email]'] = 'john@doe.com';

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }
}
