<?php namespace App\Infrastructure\Common\Service;

use App\Application\Common\CommandBusInterface;
use League\Tactician\CommandBus;
use StraTDeS\SharedKernel\Application\CQRS\Command;

class TacticianCommandBus implements CommandBusInterface
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Command $command
     * @return mixed
     */
    public function handle(Command $command): void
    {
        $this->commandBus->handle($command);
    }
}