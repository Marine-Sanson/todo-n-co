<?php

namespace App\Tests;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Forms;
use App\Controller\TaskController;
use App\Controller\UserController;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class TaskControllerTest extends WebTestCase
{

    private KernelBrowser $client;

    private UserRepository $userRepository;

    private TaskController $taskController;

    private TaskRepository $taskRepository;

    private EntityManager $entityManager;

    private Container $container;

    private Router $urlGenerator;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        $this->userRepository = $this->container->get(UserRepository::class);

        $this->taskController = $this->container->get(TaskController::class);

        $this->taskRepository = $this->container->get(TaskRepository::class);
        
        $this->urlGenerator = $this->client->getContainer()->get('router.default');

    }

    public function testList(): void
    {
        // Given

        // retrieve the test user
        $testUser = $this->userRepository->findOneByUsername('admin');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // When
        $this->client->request('GET', '/tasks');

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des tâches');

    }

    public function testCreateTask()
    {
        $user = $this->userRepository->findOneByUsername('admin');
        $this->client->loginUser($user);

        $oldCount = count($this->taskRepository->findAll());

        $urlGenerator = $this->client->getContainer()->get('router.default');

        $crawler = $this->client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));
        
        $form = $crawler->selectButton("Ajouter")->form([
            "task[title]" => 'new task for test',
            "task[content]" => 'new task for test content',
            "task[isDone]" => false
        ]);

        // $form = $this->client->getContainer()->get('form.factory')->create(TaskType::class, $entity);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();

        $allTasks = $this->taskRepository->findAll();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount($oldCount + 1, $allTasks);

    }

    public function testCreateTaskWithNoOneConnected(): void
    {
        $urlGenerator = $this->client->getContainer()->get('router.default');

        $oldCount = count($this->taskRepository->findAll());

        $crawler = $this->client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));

        $this->assertResponseRedirects('/login');
        $crawler = $this->client->followRedirect();

        $allTasks = $this->taskRepository->findAll();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount($oldCount, $allTasks);

    }

    public function testEditTask()
    {

        $task = $this->taskRepository->findOneByTitle("new task for test");

        $user = $task->getUser();

        $this->client->loginUser($user);

        $urlGenerator = $this->client->getContainer()->get('router.default');

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $task->getId()]));

        $expectedContent = 'task for test new content';




        // $formData = [
        //     'title' => $task->getTitle(),
        //     'content' => $expectedContent,
        //     'isDone' => false
        // ];
        $form = $crawler->selectButton("Modifier")->form([
            "task[title]" => $task->getTitle(),
            "task[content]" => $expectedContent
        ]);

        // $form = $this->client->getContainer()->get('form.factory')->create(TaskType::class, $entity);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertTrue($crawler->filter('#taskId-'. $task->getId())->count() == 1);
    }

    public function testtaskIsDone(): void
    {
        $task = $this->taskRepository->findOneByTitle("new task for test");

        $oldIsDone = $task->isDone();

        $user = $task->getUser();

        $this->client->loginUser($user);

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $task->getId()]));
        
        $this->assertResponseRedirects('/tasks');

        $crawler = $this->client->followRedirect();

        $this->assertEquals(!$oldIsDone, $task->isDone());

    }

    public function testDeleteTask(): void
    {
        $task = $this->taskRepository->findOneByTitle("new task for test");

        $user = $task->getUser();

        $this->client->loginUser($user);

        $oldCount = count($this->taskRepository->findAll());

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $task->getId()]));
        
        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();

        $allTasks = $this->taskRepository->findAll();

        $this->assertCount($oldCount - 1, $allTasks);
        $this->assertNotContains($task, $allTasks);

    }


}
