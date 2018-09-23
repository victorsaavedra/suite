<?php namespace App\Infrastructure\Common;

use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController
{
    /** @var CommandBus */
    protected $commandBus;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(
        CommandBus $commandBus,
        EntityManager $entityManager
    )
    {
        $this->commandBus = $commandBus;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @throws ConnectionException
     */
    public function execute(Request $request)
    {
        try {
            $response = $this->executeTransaction($request);
        } catch(\Throwable $e) {
            $this->rollbackTransaction();
            $responseMessage = $e->getMessage();
            $respondeCode = $e->getCode();
        }
    }

    abstract public function doExecute(Request $request);

    /**
     * @param Request $request
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function executeTransaction(Request $request)
    {
        $this->entityManager->beginTransaction();
        $response = $this->doExecute($request);
        $this->entityManager->flush();
        $this->entityManager->commit();

        return $response;
    }

    /**
     * @throws ConnectionException
     */
    private function rollbackTransaction(): void
    {
        if (!$this->entityManager->isOpen()) {
            return;
        }
        $this->entityManager->rollback();
        $connection = $this->entityManager->getConnection();
        if (!$connection->isTransactionActive() || $connection->isRollbackOnly()) {
            $this->entityManager->close();
        }
    }
}