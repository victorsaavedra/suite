<?php namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\PhoneNumber;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\TestCase;
use StraTDeS\SharedKernel\Domain\GenericDomainException;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class UserTest extends TestCase
{
    /**
     * @throws GenericDomainException
     */
    public function testUserEntity(): void
    {
        $expectedUser = new User(
            UUIDV4::generate(),
            UUIDV4::generate(),
            Name::create('John','Doe'),
            PhoneNumber::create(34, 666000000)
        );

        $this->assertObjectHasAttribute('id', $expectedUser);
        $this->assertObjectHasAttribute('userId', $expectedUser);
        $this->assertObjectHasAttribute('name', $expectedUser);
        $this->assertObjectHasAttribute('phoneNumber', $expectedUser);
    }
}
