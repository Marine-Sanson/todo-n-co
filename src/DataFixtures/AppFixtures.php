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

class AppFixtures extends Fixture
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

        $admin = (new User())
            ->setUsername('admin')
            ->setEmail('admin@ex.com');

        $password = $this->userPasswordHasher->hashPassword($admin, 'mdpass');
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();

        $defaultUser = (new User())
            ->setUsername('user')
            ->setEmail('user@ex.com');

        $password = $this->userPasswordHasher->hashPassword($defaultUser, 'mdpass');
        $defaultUser->setPassword($password);

        $manager->persist($defaultUser);
        $manager->flush();

        for ($i=0; $i<11; $i++){
            $domain = $this->faker->domainWord();
            $tld = $this->faker->tld();
            $username = $this->faker->userName();

            $user = (new User())
                ->setUsername($username)
                ->setEmail($username.'@'.$domain.'.'.$tld);

            $password = $this->userPasswordHasher->hashPassword($user, $this->faker->password());
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
        }

        for ($j=0; $j<15; $j++){
        
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s'));

            $task = (new Task())
                ->setTitle($this->faker->sentence())
                ->setContent($this->faker->text())
                ->setCreatedAt($date)
                ->setIsDone(rand(0, 1));

            $manager->persist($task);
            $manager->flush();
        }

    }
}
