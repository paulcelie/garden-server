<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190808145853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE routine_condition (id INT AUTO_INCREMENT NOT NULL, routine_id INT NOT NULL, type VARCHAR(2) NOT NULL, milimeter INT NOT NULL, INDEX IDX_7292093FF27A94C7 (routine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, days VARCHAR(20) NOT NULL, start_time TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine_action (id INT AUTO_INCREMENT NOT NULL, group_id_id INT NOT NULL, duration INT NOT NULL, position INT NOT NULL, INDEX IDX_3E0B9ED2F68B530 (group_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE routine_condition ADD CONSTRAINT FK_7292093FF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id)');
        $this->addSql('ALTER TABLE routine_action ADD CONSTRAINT FK_3E0B9ED2F68B530 FOREIGN KEY (group_id_id) REFERENCES garden_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE routine_condition DROP FOREIGN KEY FK_7292093FF27A94C7');
        $this->addSql('DROP TABLE routine_condition');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE routine_action');
    }
}
