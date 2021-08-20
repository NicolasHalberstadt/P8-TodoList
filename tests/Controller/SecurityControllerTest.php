<?php


namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityController
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package Tests\Controller
 */
class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username']->setValue('Admin');
        $form['_password']->setValue('compteAdmin');
        $crawler = $client->submit($form);
        $this->assertStringContainsString('Bienvenue sur Todo List', $crawler->filter('.col-md-12 h1')->text());
        print("login OK ");
    }
    
    public function testWrongLoginAction()
    {
        self::ensureKernelShutdown();
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
        print("wrong login OK ");
    }
}