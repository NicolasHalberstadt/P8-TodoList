<?php


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Entity\User;
use Faker\Factory;

/**
 * Class TaskFixtures
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\UserFixtures
 */
class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setTitle("Do ".$faker->sentence(3, true));
            $task->setContent("We have to ".$faker->sentence(10, true));
            $task->setUser(
                $manager->getRepository(User::class)->findOneBy(
                    [
                        'username' => 'User',
                    ]
                )
            );
            $manager->persist($task);
        }
        $manager->flush();
    }
}