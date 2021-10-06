<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setEmail('admin@todolist.com');
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'compteAdmin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        
        $user = new User();
        $user->setUsername('User');
        $user->setEmail('user@todolist.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'compteUser'));
        $manager->persist($user);
        
        $user = new User();
        $user->setUsername('Member');
        $user->setEmail('member@todolist.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'compteMember'));
        $manager->persist($user);
        
        $manager->flush();
    }
}
