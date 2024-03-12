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

        for ($i=0; $i<11; $i++){
            $domain = $this->faker->domainWord();
            $tld = $this->faker->tld();
            $username = $this->faker->userName();

            $user = (new User())
                ->setUsername($username)
                ->setEmail($username.'@'.$domain.'.'.$tld);

            $password = $this->userPasswordHasher->hashPassword($user, 'mdpass');
            $user->setPassword($password);

            $this->addReference('user' . $i, $user);

            $manager->persist($user);
            $manager->flush();
        }

    }

}
