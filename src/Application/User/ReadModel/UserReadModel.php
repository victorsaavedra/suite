<?php namespace App\Application\User\ReadModel;

use StraTDeS\SharedKernel\Application\CQRS\ReadModel;

class UserReadModel extends ReadModel
{
    /** @var string */
    private $userId;

    /** @var string */
    private $name;

    /** @var string */
    private $phoneNumber;

    public function __construct(
        string $id,
        string $userId,
        string $name,
        string $phoneNumber
    )
    {
        parent::__construct($id);

        $this->userId = $userId;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
    }
}