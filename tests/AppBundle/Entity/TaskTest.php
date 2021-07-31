<?php

use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Task;

/**
 * Class TaskTest
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 */
class TaskTest extends TestCase
{
    public function testTaskCreate()
    {
        $task = new Task();
        $task->setTitle('Task test title');
        $task->setContent('Task test content');
        $this->assertSame(null, $task->getId());
        $this->assertSame(false, $task->isDone());
        $this->assertEquals('Task test title', $task->getTitle());
        $this->assertEquals('Task test content', $task->getContent());
        $this->assertEquals(new \DateTime(), $task->getCreatedAt());
        $this->assertEquals(null, $task->getUser());
    }
    
    public function testTaskToggle()
    {
        $task = new Task();
        $task->toggle(!$task->isDone());
        $this->assertSame(true, $task->isDone());
    }
}