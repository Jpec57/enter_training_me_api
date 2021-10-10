<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010082714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training ADD reference_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_D5128A8F1645DEA9 FOREIGN KEY (reference_id) REFERENCES training (id)');
        $this->addSql('CREATE INDEX IDX_D5128A8F1645DEA9 ON training (reference_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training DROP FOREIGN KEY FK_D5128A8F1645DEA9');
        $this->addSql('DROP INDEX IDX_D5128A8F1645DEA9 ON training');
        $this->addSql('ALTER TABLE training DROP reference_id');
    }
}
