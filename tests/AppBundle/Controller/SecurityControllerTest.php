<?php


namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityController
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package Tests\AppBundle\Controller
 */
class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username']->setValue('Admin');
        $form['_password']->setValue('compteAdmin');
        $crawler = $client->submit($form);
        $this->assertContains('Bienvenue sur Todo List', $crawler->filter('h1')->text());
    }
    
    public function testWrongLoginAction()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username']->setValue('wrong_account');
        $form['_password']->setValue('wrong_pwd');
        $crawler = $client->submit($form);
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }
}