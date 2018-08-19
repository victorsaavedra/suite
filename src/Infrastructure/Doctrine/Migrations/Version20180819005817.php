<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180819005817 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on mysql');
        $this->addSql("
            CREATE TABLE `event_store`(
              `id` varbinary(16) NOT NULL,
              `entity_id` varbinary(16) NOT NULL,
              `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
              `version` int(11) NOT NULL,
              `data` longtext COLLATE utf8_unicode_ci NOT NULL,
              `creator` varbinary(16) DEFAULT NULL,
              `created_at` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on mysql');
        $this->addSql('DROP TABLE `event_store`');
    }
}
