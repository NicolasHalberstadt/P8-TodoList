<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Task;

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
        $now = new \DateTime();
        $this->assertEquals($now->format('Y-m-d H:i:s'), $task->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertSame(null, $task->getId());
        $this->assertSame(false, $task->isDone());
        $this->assertEquals('Task test title', $task->getTitle());
        $this->assertEquals('Task test content', $task->getContent());
        $this->assertEquals(null, $task->getUser());
    }
    
    public function testTaskToggle()
    {
        $task = new Task();
        $task->toggle(!$task->isDone());
        $this->assertSame(true, $task->isDone());
    }
}