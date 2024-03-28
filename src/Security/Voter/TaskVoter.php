<?php

/**
 * TaskVoter File Doc Comment
 *
 * @category Voter
 * @package  App\Security\Voter
 * @author   Marine Sanson <marine_sanson@yahoo.fr>
 * @license  https://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * TaskVoter Class Doc Comment
 *
 * @category Voter
 * @package  App\Security\Voter
 * @author   Marine Sanson <marine_sanson@yahoo.fr>
 * @license  https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class TaskVoter extends Voter
{

    /**
     * Summary of EDIT
     *
     * const string
     */
    const EDIT = 'TASK_EDIT';

    /**
     * Summary of DELETE
     *
     * const string
     */
    const DELETE = 'TASK_DELETE';


    /**
     * Summary of function __construct
     *
     * @param Security $security Security
     */
    public function __construct(private Security $security)
    {

    }


    /**
     * Summary of supports
     *
     * @param string $attribute Attribute
     * @param mixed  $task      task
     *
     * @return bool
     */
    protected function supports(string $attribute, mixed $subject): bool
    {

        // If the attribute isn't one we support, return false.
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // Only vote on `Post` objects.
        if (!$subject instanceof Task) {
            return false;
        }

        return true;

    }


    /**
     * Summary of voteOnAttribute
     *
     * @param string         $attribute Attribute
     * @param mixed          $task      task
     * @param TokenInterface $token     Token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $user = $token->getUser();

        if (!$user instanceof User) {
            // The user must be logged in; if not, deny access.
            return false;
        }

        $task = $subject; 

        return match ($attribute) {
            self::EDIT => $this->canEdit($task, $user),
            self::DELETE => $this->canDelete($task, $user),
            default => throw new \LogicException('This code should not be reached!')
        };

    }


    /**
     * Summary of canEdit
     *
     * @param Task $task Task
     * @param User $user User
     *
     * @return bool
     */
    private function canEdit(Task $task, User $user): bool
    {
        if ($task->getUser() !== null) {
            if ($task->getUser()->getId() !== null) {
                return $user->getId() === $task->getUser()->getId();
            }
        }

        if ($task->getUser() === null) {
            return $this->security->isGranted('ROLE_ADMIN');
        }

        return false;

    }


    /**
     * Summary of canDelete
     *
     * @param Task $task Task
     * @param User $user User
     *
     * @return bool
     */
    private function canDelete(Task $task, User $user): bool
    {

        if ($task->getUser() !== null) {
            if ($task->getUser()->getId() !== null) {
                return $user->getId() === $task->getUser()->getId();
            }
        }

        if ($task->getUser() === null) {
            return $this->security->isGranted('ROLE_ADMIN');
        }

        return false;

    }


}
