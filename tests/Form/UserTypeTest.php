<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Forms;

class UserTypeTest extends KernelTestCase
{


    public function testSubmitValidData(): void
    {

        $formFactory = Forms::createFormFactory();

        $formData = [
            'username' => 'username test',
            'email' => 'email@test.com',
            'roles' => ['ROLE_USER']
        ];

        $entity = new User();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $formFactory->create(UserType::class, $entity);

        $expected = (new User())
            ->setUsername('username test')
            ->setEmail('email@test.com')
            ->setRoles(['ROLE_USER']);
        // ...populate $expected properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());
        $this->assertInstanceOf(User::class, $expected);
        $this->assertInstanceOf(User::class, $entity);

    }


}
