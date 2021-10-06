<?php

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

/**
 * Class UserTest
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 */
class UserTest extends TestCase
{
    public function testUserCreate()
    {
        $user = new User();
        $user->setUsername('User_test');
        $user->setEmail('email@test.com');
        $user->setPassword('compteUser');
        $user->setTasks(new ArrayCollection());
        $this->assertEquals(null, $user->getId());
        $this->assertEquals('User_test', $user->getUsername());
        $this->assertEquals('email@test.com', $user->getEmail());
        $this->assertIsArray($user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEmpty($user->getTasks());
        $this->assertEquals('compteUser', $user->getPassword());
    }
    
    public function testUpdateUserRole()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }
}