<?php

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;


class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {

        $formData = [
            'title' => 'titre test',
            'content' => 'contenu de la tache pour test',
            'isDone' => false
        ];

        $entity = new Task();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TaskType::class, $entity);

        $expected = (new Task())
            ->setTitle('titre test')
            ->setContent('contenu de la tache pour test')
            ->setIsDone(false);
        // ...populate $expected properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $entity);

    }

    public function testCustomFormView(): void
    {

        $formData = (new Task())
        // ... prepare the data as you need
            ->setTitle('titre test')
            ->setContent('contenu de la tache pour test')
            ->setIsDone(false);
        // The initial data may be used to compute custom view variables

        $view = $this->factory->create(TaskType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('data', $view->vars);
        $this->assertSame($formData, $view->vars['data']);

    }

}
