<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Http\Discovery\Exception\NotFoundException;
use Nelmio\Alice\Throwable\Exception\Generator\Context\CachedValueNotFound;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param \DateTimeInterface $birthDay
     */
    public function createUser(string $id, string $firstName, string $lastName, \DateTimeInterface $birthDay)
    {
        $user = new User($id, $firstName, $lastName, $birthDay);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param \DateTimeInterface $birthDay
     */
    public function modifyUser(string $id, string $firstName, string $lastName, \DateTimeInterface $birthDay)
    {
        /** @var User $user */
        //je vais chercher mon objet user par l'id
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);

        /**
         * j'utilise ma fonction upadte qui se trouve dans ma class User
         *je lui donne des parammétre pour leurs affécter de nouvelles valeurs
         **/
        $user->update($firstName, $lastName, $birthDay);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    /**
     * @param string $id
     */
    public function deleteUser(string $id)
    {
        /**@var User $user */

        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (empty($user)) {
            throw new NotFoundHttpException("error while deleting the user, the user is empty ");
        }
        $this->entityManager->remove($user);

        $this->entityManager->flush();
    }

    /**
     * @param string $id
     * @return array
     */
    public function loadUser(string $id)
    {
        /**@var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (empty($user)) {
            throw new NotFoundHttpException('User not found');
        }

        $result = [];
        $result["firstname"] = $user->getFirstname();
        $result["lastname"] = $user->getLastname();

        $tabComments = [];
        foreach ($user->getComments() as $comment) {
            $tabComments[] = [
                "title" => $comment->getTitle(),
                "comment" => $comment->getDescription()
            ];
        }
        $result["comments"] = $tabComments;

        return $result;
    }

    public function loadAllUser()
    {
        /** @var User[] $users*/
        $users = $this->entityManager->getRepository(User::class)->findAll();

        if (empty($users)) {
            throw new NotFoundHttpException('Users not foundr');
        }

        $tabUser = [];

        foreach ($users as $key => $user) {

            $tabUser[$key] = [
                "id" => $user->getId(),
                "firstname" => $user->getFirstname(),
                "lastname" => $user->getLastname(),
                "birthday" => $user->getBirthday()->format('Y-m-d'),
            ];

            foreach ($user->getComments() as $comment) {
                $tabUser[$key]['comments'][] = [
                    "title" => $comment->getTitle(),
                    "comment" => $comment->getDescription()
                ];
            }
        }

        return $tabUser;
    }

}

























































