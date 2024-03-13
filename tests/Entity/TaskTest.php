<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{

    public function testGetCreatedAt(): void
    {

        // Given - prépare les éléments
        $expectedCreatedAt = new DateTimeImmutable();
        $task = (new Task())
            ->setCreatedAt($expectedCreatedAt);

        // When - execute la commande testée
        $date = $task->getCreatedAt();

        // Then - je m'assure que c'est bon
        $this->assertEquals($expectedCreatedAt, $date);

    }

    public function testGetTitle(): void
    {

        // Given
        $expectedTitle = "Expected title";
        $task = (new Task())
            ->setTitle($expectedTitle);

        // When
        $title = $task->getTitle();

        // Then
        $this->assertEquals($expectedTitle, $title);

    }

    public function testGetContent(): void
    {

        // Given
        $expectedContent = "Expected content";
        $task = (new Task())
            ->setContent($expectedContent);

        // When
        $content = $task->getContent();

        // Then
        $this->assertEquals($expectedContent, $content);

    }

    public function testIsDone(): void
    {

        // Given
        $expectedIsDone = true;
        $task = (new Task())
            ->setIsDone($expectedIsDone);

        // When
        $isDone = $task->isDone();

        // Then
        $this->assertEquals($expectedIsDone, $isDone);

    }

    public function testIsNotDone(): void
    {

        // Given
        $expectedIsNotDone = false;
        $task = (new Task())
            ->setIsDone($expectedIsNotDone);

        // When
        $isNotDone = $task->isDone();

        // Then
        $this->assertEquals($expectedIsNotDone, $isNotDone);

    }

}
