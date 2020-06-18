<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getApiToken(User $user): string
    {
        return $user->getApiToken() ?: $this->refreshApiToken($user);
    }

    public function refreshApiToken(User $user): string
    {
        $user->setApiToken(bin2hex(random_bytes(32)));

        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();

        return $user->getApiToken();
    }

    public function createUser(string $email, int $googleId): User
    {
        $user = new User();

        $user->setEmail($email);
            // ->setGoogleId

        return $user;
    }
}
