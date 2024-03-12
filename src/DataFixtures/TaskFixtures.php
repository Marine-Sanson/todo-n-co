<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
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

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher){}

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('fr_FR');

        for ($j=0; $j<15; $j++){

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
