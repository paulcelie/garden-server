<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190802083434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE garden_group (id INT AUTO_INCREMENT NOT NULL, external_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garden_group_sprinkler (id INT AUTO_INCREMENT NOT NULL, group_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, x INT NOT NULL, y INT NOT NULL, INDEX IDX_6E0B78282F68B530 (group_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE garden_group_sprinkler ADD CONSTRAINT FK_6E0B78282F68B530 FOREIGN KEY (group_id_id) REFERENCES garden_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE garden_group_sprinkler DROP FOREIGN KEY FK_6E0B78282F68B530');
        $this->addSql('DROP TABLE garden_group');
        $this->addSql('DROP TABLE garden_group_sprinkler');
    }
}
