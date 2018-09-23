<?php namespace App\Domain\Common\ValueObject;

use StraTDeS\SharedKernel\Domain\GenericDomainException;

class PhoneNumber
{
    private const COUNTRY_CODES = [
        34
    ];

    /** @var int */
    private $countryCode;

    /** @var int */
    private $phoneNumber;

    /**
     * PhoneNumber constructor.
     * @param int $countryCode
     * @param int $phoneNumber
     * @throws GenericDomainException
     */
    public function __construct(int $countryCode, int $phoneNumber)
    {
        $this->checkCountryCodeIsValid($countryCode);

        $this->countryCode = $countryCode;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @param int $countryCode
     * @param int $phoneNumber
     * @return PhoneNumber
     * @throws GenericDomainException
     */
    public static function create(int $countryCode, int $phoneNumber): PhoneNumber
    {
        return new self($countryCode, $phoneNumber);
    }

    /**
     * @return int
     */
    public function getCountryCode(): int
    {
        return $this->countryCode;
    }

    /**
     * @return int
     */
    public function getPhoneNumber(): int
    {
        return $this->phoneNumber;
    }

    public function toString(): string
    {
        return str_pad($this->getCountryCode(), 4, '0', STR_PAD_LEFT). ' '.$this->getPhoneNumber();
    }

    /**
     * @param int $prefix
     * @throws GenericDomainException
     */
    private function checkCountryCodeIsValid(int $prefix): void
    {
        if (!in_array($prefix, self::COUNTRY_CODES)) {
            throw new GenericDomainException();
        }
    }
}