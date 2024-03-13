<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
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


    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {

    }


    public function load(ObjectManager $manager): void
    {

        $this->faker = Factory::create('fr_FR');

        $admin = (new User())
            ->setUsername('admin')
            ->setEmail('admin@ex.com')
            ->setRoles(['ROLE_ADMIN']);

        $password = $this->userPasswordHasher->hashPassword($admin, 'mdpass');
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();

        $defaultUser = (new User())
            ->setUsername('user')
            ->setEmail('user@ex.com')
            ->setRoles(['ROLE_USER']);

        $password = $this->userPasswordHasher->hashPassword($defaultUser, 'mdpass');
        $defaultUser->setPassword($password);

        $manager->persist($defaultUser);
        $manager->flush();

        for ($i = 0; $i < 11; $i++) {
            $domain = $this->faker->domainWord();
            $tld = $this->faker->tld();
            $username = $this->faker->userName();
            $k = rand(0, 10);

            if ($k === 10) {
                $role = ['ROLE_ADMIN'];
            }

            if ($k <= 9) {
                $role = ['ROLE_USER'];
            }

            $user = (new User())
                ->setUsername($username)
                ->setEmail($username.'@'.$domain.'.'.$tld)
                ->setRoles($role);

            $password = $this->userPasswordHasher->hashPassword($user, 'mdpass');
            $user->setPassword($password);

            $this->addReference('user'.$i, $user);
            $manager->persist($user);
            $manager->flush();
        }//end for

    }


}
