<?php namespace App\Domain\Common\ValueObject;

class Name
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $middleName;

    /** @var string */
    private $secondLastName;

    /**
     * Name constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $middleName
     * @param string $secondLastName
     */
    public function __construct(
        string $firstName,
        string $lastName,
        string $secondLastName = null,
        string $middleName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->secondLastName = $secondLastName;
    }

    public static function create(
        string $firstName,
        string $lastName,
        string $secondLastName = null,
        string $middleName = null)
    : Name
    {
        return new self($firstName, $lastName, $secondLastName, $middleName);
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @return string
     */
    public function getSecondLastName(): ?string
    {
        return $this->secondLastName;
    }

    public function toString(): string
    {
        $middleName = '';
        $secondLastName = '';

        if ($this->getMiddleName() !== null) {
            $middleName = $this->getMiddleName(). ' ';
        }

        if ($this->getSecondLastName() !== null) {
            $secondLastName = ' '.$this->getSecondLastName(). ' ';
        }

        return $this->getFirstName().' '.$middleName.$this->getLastName().$secondLastName;
    }
}