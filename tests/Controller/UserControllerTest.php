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
        $form['user[username]'] = 'UserTest';
        $form['user[email]'] = 'usertest@contact.com';
        $form['user[password][first]'] = 'user_password';
        $form['user[password][second]'] = 'user_password';
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
        $updateLink = $crawler->filter('#user_UserTest .user_links a:first-of-type')->link();
        $crawler = $client->click($updateLink);
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'UserTest';
        $form['user[email]'] = 'usertest@contact.com';
        $form['user[password][first]'] = 'password_user';
        $form['user[password][second]'] = 'password_user';
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
        $deleteLink = $crawler->filter('#user_UserTest .user_links a:last-of-type')->link();
        $crawler = $client->click($deleteLink);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}