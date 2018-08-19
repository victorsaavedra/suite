<?php namespace App\Infrastructure\User\Repository;

use App\Application\User\ReadModel\UserReadModel;
use App\Application\User\Repository\UserReadModelRepositoryInterface;
use StraTDeS\SharedKernel\Infrastructure\DoctrinePersistentReadModelRepository;

class DoctrineUserReadModelRepository extends DoctrinePersistentReadModelRepository implements UserReadModelRepositoryInterface
{
    public function getReadModelName(): string
    {
        return UserReadModel::class;
    }
}