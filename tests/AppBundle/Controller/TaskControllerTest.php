<?php


namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Task;

/**
 * Class TaskControllerTest
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends WebTestCase
{
    
    public function testListAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $crawler = $client->request('GET', '/tasks/todo');
        $tasks = $crawler->filter('.tasks')->count();
        $this->assertGreaterThan(0, $tasks);
    }
    
    private function login($username, $password)
    {
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
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    public function testEditAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/tasks/todo');
        $link = $crawler->filter('#task-1 .task-edit')->link();
        $crawler = $client->click($link);
        $this->assertContains('Title', $crawler->filter('.col-md-12 form')->text());
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]']->setValue('Task title test');
        $form['task[content]']->setValue('Task content test');
        $crawler = $client->submit($form);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    public function testToggleAction()
    {
        $client = $this->login('Admin', 'compteAdmin');
        $client->followRedirects();
        $crawler = $client->request('GET', '/tasks/todo');
        $toggleForm = $crawler->selectButton('task-toggle-btn-1')->form();
        $crawler = $client->submit($toggleForm);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    public function testDeleteForbiddenAction()
    {
        $client = $this->login('User', 'compteuser');
        $client->followRedirects();
        $client->request('POST', '/tasks/1/delete');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
    
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