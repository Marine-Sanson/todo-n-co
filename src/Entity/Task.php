<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{

    /**
     * Summary of id
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Summary of createdAt
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * Summary of title
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Vous devez saisir un titre')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
        max: 50,
        maxMessage: 'Le titre ne doit pas faire plus de {{ limit }} caractères'
    )]
    private ?string $title = null;

    /**
     * Summary of content
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Vous devez saisir du contenu')]
    private ?string $content = null;

    /**
     * Summary of isDone
     *
     * @var boolean|null
     */
    #[ORM\Column]
    private ?bool $isDone = null;

    /**
     * Summary of user
     *
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'tasks', cascade:['persist'])]
    private ?User $user = null;

    /**
     * Summary of function getId
     *
     * @return int|null
     */
    public function getId(): ?int
    {

        return $this->id;

    }

    /**
     * Summary of function getCreatedAt
     *
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {

        return $this->createdAt;

    }

    /**
     * Summary of function setCreatedAt
     *
     * @param DateTimeImmutable $createdAt createdAt
     *
     * @return static
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {

        $this->createdAt = $createdAt;

        return $this;

    }

    /**
     * Summary of function getTitle
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {

        return $this->title;

    }

    /**
     * Summary of function setTitle
     *
     * @param string $title title
     *
     * @return static
     */
    public function setTitle(string $title): static
    {

        $this->title = $title;

        return $this;

    }

    /**
     * Summary of function getContent
     *
     * @return string|null
     */
    public function getContent(): ?string
    {

        return $this->content;

    }

    /**
     * Summary of function setContent
     *
     * @param string $content content
     *
     * @return static
     */
    public function setContent(string $content): static
    {

        $this->content = $content;

        return $this;

    }

    /**
     * Summary of function isDone
     *
     * @return boolean|null
     */
    public function isDone(): ?bool
    {

        return $this->isDone;

    }

    /**
     * Summary of function setIsDone
     *
     * @param bool $isDone isDone
     *
     * @return static
     */
    public function setIsDone(bool $isDone): static
    {

        $this->isDone = $isDone;

        return $this;

    }

    /**
     * Summary of function getUser
     *
     * @return User|null
     */
    public function getUser(): ?User
    {

        return $this->user;

    }

    /**
     * Summary of function setUser
     *
     * @param User $user User
     *
     * @return static
     */
    public function setUser(?User $user): static
    {

        $this->user = $user;

        return $this;

    }


}
