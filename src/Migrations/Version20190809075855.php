<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190809075855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE routine_action ADD routine_id INT NOT NULL');
        $this->addSql('ALTER TABLE routine_action ADD CONSTRAINT FK_3E0B9EDF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id)');
        $this->addSql('CREATE INDEX IDX_3E0B9EDF27A94C7 ON routine_action (routine_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE routine_action DROP FOREIGN KEY FK_3E0B9EDF27A94C7');
        $this->addSql('DROP INDEX IDX_3E0B9EDF27A94C7 ON routine_action');
        $this->addSql('ALTER TABLE routine_action DROP routine_id');
    }
}
