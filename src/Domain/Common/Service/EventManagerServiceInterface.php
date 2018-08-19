<?php namespace App\Domain\Common\Service;

use StraTDeS\SharedKernel\Domain\EventStream;

interface EventManagerServiceInterface
{
    public function handle(EventStream $eventStream): void;
}