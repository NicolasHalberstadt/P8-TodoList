<?php


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Faker\Factory;

/**
 * Class TaskFixtures
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\UserFixtures
 */
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i = 1; $i <= 5; $i++) {
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
        
        for ($i = 1; $i <= 5; $i++) {
            $task = new Task();
            $task->setTitle("Do ".$faker->sentence(3, true));
            $task->setContent("We have to ".$faker->sentence(10, true));
            $task->setUser(
                $manager->getRepository(User::class)->findOneBy(
                    [
                        'username' => 'Member',
                    ]
                )
            );
            $manager->persist($task);
        }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}