<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820143633 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE routine_log (id INT AUTO_INCREMENT NOT NULL, routine_id INT NOT NULL, action_id INT NOT NULL, handling INT NOT NULL, status INT NOT NULL, scheduled_at DATETIME NOT NULL, started_at DATETIME DEFAULT NULL, stopped_at DATETIME DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, INDEX IDX_E1192DD1F27A94C7 (routine_id), INDEX IDX_E1192DD19D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE routine_log ADD CONSTRAINT FK_E1192DD1F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id)');
        $this->addSql('ALTER TABLE routine_log ADD CONSTRAINT FK_E1192DD19D32F035 FOREIGN KEY (action_id) REFERENCES routine_action (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE routine_log');
    }
}
