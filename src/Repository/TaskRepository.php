<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Task::class);

    }

    /**
     * Summary of saveTask
     *
     * @param Task $task Task
     *
     * @return Task
     */
    public function saveTask(Task $task): Task
    {

        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();

        return $task;

    }


    /**
     * Summary of deleteTask
     *
     * @param Task $task Task
     *
     * @return void
     */
    public function deleteTask(Task $task): void
    {

        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();

    }


}
