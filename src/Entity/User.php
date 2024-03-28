<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
     * Summary of username
     *
     * @var string|null
     */
    #[ORM\Column(length: 25, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir un nom d\'utilisateur')]
    #[Assert\Length(
        min: 4,
        minMessage: 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères',
        max: 25,
        maxMessage: 'Le nom d\'utilisateur ne doit pas faire plus de {{ limit }} caractères'
    )]
    private ?string $username = null;

    /**
     * Summary of email
     *
     * @var string|null
     */
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir une adresse email')]
    #[Assert\Length(
        min: 5,
        minMessage: 'L\'adresse email doit contenir au moins {{ limit }} caractères',
        max: 180,
        maxMessage: 'L\'adresse email ne doit pas faire plus de {{ limit }} caractères'
    )]
    #[Assert\Email(message: 'Le format de l\'adresse n\'est pas correct')]
    private ?string $email = null;

    /**
     * Summary of roles
     *
     * @var array
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Summary of tasks
     *
     * @var Collection<Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'user')]
    private Collection $tasks;


    /**
     * Summary of function __construct
     */
    public function __construct()
    {

        $this->tasks = new ArrayCollection();

    }


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
     * Summary of function setId
     *
     * @param int $id Id
     *
     * @return static
     */
    public function setId(string $id): static
    {

        $this->id = $id;

        return $this;

    }


    /**
     * Summary of function getUsername
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {

        return $this->username;

    }


    /**
     * Summary of function setUsername
     *
     * @param string $username username
     *
     * @return static
     */
    public function setUsername(string $username): static
    {

        $this->username = $username;

        return $this;

    }


    /**
     * Summary of function getEmail
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {

        return $this->email;

    }


    /**
     * Summary of function setEmail
     *
     * @param string $email email
     *
     * @return static
     */
    public function setEmail(string $email): static
    {

        $this->email = $email;

        return $this;

    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {

        return (string) $this->email;

    }


    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {

        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);

    }


    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {

        $this->roles = $roles;

        return $this;

    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {

        return $this->password;

    }


    /**
     * Summary of function setPassword
     *
     * @param string $password password
     *
     * @return static
     */
    public function setPassword(string $password): static
    {

        $this->password = $password;

        return $this;

    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {

        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;

    }


    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {

        return $this->tasks;

    }


    /**
     * Summary of function addTask
     *
     * @param Task $task Task
     *
     * @return static
     */
    public function addTask(Task $task): static
    {

        if ($this->tasks->contains($task) === false) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;

    }


    /**
     * Summary of function removeTask
     *
     * @param Task $task Task
     *
     * @return static
     */
    public function removeTask(Task $task): static
    {

        if ($this->tasks->removeElement($task) === true) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;

    }


}
