<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('Admin')
            ->setPassword('compteAdmin')
            ->addRole('ROLE_ADMIN');
        
        $manager->flush();
    }
}
