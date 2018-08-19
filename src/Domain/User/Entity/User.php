<?php namespace App\Domain\User\Entity;

use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\PhoneNumber;
use StraTDeS\SharedKernel\Domain\EventSourcedEntity;
use StraTDeS\SharedKernel\Domain\Id;

class User extends EventSourcedEntity
{
    /** @var Id */
    private $userId;

    /** @var Name */
    private $name;

    /** @var PhoneNumber */
    private $phoneNumber;

    /**
     * User constructor.
     * @param Id $id
     * @param Id $userId
     * @param Name $name
     * @param PhoneNumber $phoneNumber
     */
    public function __construct(
        Id $id,
        Id $userId,
        Name $name,
        PhoneNumber $phoneNumber
    )
    {
        parent::__construct($id);

        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }
}