<?php


namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package Tests\Controller
 */
class UserControllerTest extends WebTestCase
{
    public function testForbiddenAccess()
    {
        $client = $this->login('User', 'compteUser');
        $client->followRedirects();
        $client->request('GET', '/users');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    private function login(string $username, string $password)
    {
        self::ensureKernelShutdown();
        
        return static::createClient(
            [],
            [
                'PHP_AUTH_USER' => $username,
                'PHP_AUTH_PW' => $password,
            ]
        );
    }
    
    public function testListAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $crawler = $client->request('GET', '/users');
        $tasks = $crawler->filter('.users')->count();
        $this->assertGreaterThan(0, $tasks);
    }
    
    public function testCreateAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $crawler = $client->request('GET', '/users/create');
        $client->followRedirects();
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Test';
        $form['user[email]'] = 'test@todolist.com';
        $form['user[password][first]'] = 'compteTest';
        $form['user[password][second]'] = 'compteTest';
        $crawler = $client->submit($form);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    /**
     * @depends testCreateAction
     */
    public function testUpdateAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/users');
        $updateLink = $crawler->filter('#user_Test .user_links a:first-of-type')->link();
        $crawler = $client->click($updateLink);
        $form = $crawler->selectButton('Modifier')->form();
        $form['user_edit[username]'] = 'Test';
        $form['user_edit[email]'] = 'usertest@contact.com';
        $crawler = $client->submit($form);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    /**
     * @depends testUpdateAction
     */
    public function testDeleteAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/users');
        $deleteLink = $crawler->filter('#user_Test .user_links a:last-of-type')->link();
        $crawler = $client->click($deleteLink);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}