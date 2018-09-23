<?php namespace App\Application\Common;

use StraTDeS\SharedKernel\Application\CQRS\Command;

interface CommandBusInterface
{
    /**
     * @param Command $command
     * @return mixed
     */
    public function handle(Command $command): void;
}