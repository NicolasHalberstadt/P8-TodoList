<?php


namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;

/**
 * Class TaskControllerTest
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package Tests\Controller
 */
class TaskControllerTest extends WebTestCase
{
    
    public function testListAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $crawler = $client->request('GET', '/tasks/todo');
        $tasks = $crawler->filter('.tasks')->count();
        static::assertGreaterThan(0, $tasks);
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
    
    public function testCreate()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $crawler = $client->request('GET', '/tasks/create');
        $client->followRedirects();
        $form = $crawler->selectButton('task_create_button')->form();
        
        $form['task[title]']->setValue('Task title for test');
        $form['task[content]']->setValue('Task content for test');
        $crawler = $client->submit($form);
        static::assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    /**
     * @depends testCreate
     */
    public function testEditAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/tasks/todo');
        $link = $crawler->filter('#task-1 .task-edit')->link();
        $crawler = $client->click($link);
        static::assertStringContainsString('Title', $crawler->filter('.col-md-12 form')->text());
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]']->setValue('Task title test');
        $form['task[content]']->setValue('Task content test');
        $crawler = $client->submit($form);
        static::assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    /**
     * @depends testEditAction
     */
    public function testToggleAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/tasks/todo');
        $toggleForm = $crawler->selectButton('task-toggle-btn-1')->form();
        $crawler = $client->submit($toggleForm);
        static::assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    /**
     * @depends testToggleAction
     */
    public function testDeleteForbiddenAction()
    {
        $client = $this->login('User', 'compteUser');
        $client->followRedirects();
        $client->request('POST', '/tasks/1/delete');
        static::assertEquals(401, $client->getResponse()->getStatusCode());
    }
    
    /**
     * @depends testToggleAction
     */
    public function testDeleteAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/tasks/done');
        $deleteForm = $crawler->selectButton('Supprimer')->form();
        $crawler = $client->submit($deleteForm);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}