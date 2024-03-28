<?php

namespace App\Tests\Form;

use App\Entity\User;
use Symfony\Component\Form\Forms;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegistrationFormTypeTest extends KernelTestCase
{


    /**
     * Function testSubmitValidData
     */
    public function testSubmitValidData(): void
    {

        $formFactory = Forms::createFormFactory();

        $formData = [
            'username' => 'username test',
            'email' => 'email@test.com',
            'password' => 'password',
            'roles' => ['ROLE_USER']
        ];

        $entity = new User();
        $form = $formFactory->create(RegistrationFormType::class, $entity);

        $expected = (new User())
            ->setUsername('username test')
            ->setEmail('email@test.com')
            ->setPassword('password')
            ->setRoles(['ROLE_USER']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertInstanceOf(User::class, $expected);
        $this->assertInstanceOf(User::class, $entity);

    }


}
