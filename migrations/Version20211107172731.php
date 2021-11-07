<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107172731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realised_exercise ADD training_id INT NOT NULL');
        $this->addSql('ALTER TABLE realised_exercise ADD CONSTRAINT FK_22BCE687BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('CREATE INDEX IDX_22BCE687BEFD98D1 ON realised_exercise (training_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realised_exercise DROP FOREIGN KEY FK_22BCE687BEFD98D1');
        $this->addSql('DROP INDEX IDX_22BCE687BEFD98D1 ON realised_exercise');
        $this->addSql('ALTER TABLE realised_exercise DROP training_id');
    }
}
