<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use Faker\Generator;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskFixtures extends Fixture
{

    /**
     * Summary of faker
     *
     * @var Generator
     */
    public Generator $faker;


    /**
     * Summary of __construct
     *
     * @param UserPasswordHasherInterface $userPasswordHasher UserPasswordHasherInterface
     */
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {

    }


    /**
     * Summary of load
     *
     * @param ObjectManager $manager ObjectManager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('fr_FR');

        $testdate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s'));

        $testuser = $this->getReference('testuser');
        $testtask = (new Task())
            ->setTitle('testtask')
            ->setContent('testtask content')
            ->setCreatedAt($testdate)
            ->setIsDone(0)
            ->setUser($testuser);

        $manager->persist($testtask);
        $manager->flush();

        $testtask2 = (new Task())
            ->setTitle('testtask2')
            ->setContent('testtask2 content')
            ->setCreatedAt($testdate)
            ->setIsDone(0)
            ->setUser($testuser);

        $manager->persist($testtask2);
        $manager->flush();

        $testtask3 = (new Task())
            ->setTitle('testtask3')
            ->setContent('testtask3 content')
            ->setCreatedAt($testdate)
            ->setIsDone(0)
            ->setUser($testuser);

        $manager->persist($testtask3);
        $manager->flush();

        for ($j = 0; $j < 15; $j++) {
            $i = rand(0, 11);
            $user = null;
            if ($i < 10) {
                $user = $this->getReference('user'.$i);
            }

            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s'));

            $task = (new Task())
                ->setTitle($this->faker->sentence())
                ->setContent($this->faker->text())
                ->setCreatedAt($date)
                ->setIsDone(rand(0, 1))
                ->setUser($user);

            $manager->persist($task);
            $manager->flush();
        }

    }


}
