<?php namespace App\Infrastructure\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Common\Repository\DoctrineEventSourcedRepository;

class DoctrineEventSourcedUserRepository extends DoctrineEventSourcedRepository implements UserRepositoryInterface
{
    public function getEntityName(): string
    {
        return User::class;
    }
}