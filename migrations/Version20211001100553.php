<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001100553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE muscle_activation (id INT AUTO_INCREMENT NOT NULL, exercise_reference_id INT DEFAULT NULL, muscle VARCHAR(255) NOT NULL, activation_ratio DOUBLE PRECISION NOT NULL, INDEX IDX_DA0A28D7FCDD6B80 (exercise_reference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE muscle_activation ADD CONSTRAINT FK_DA0A28D7FCDD6B80 FOREIGN KEY (exercise_reference_id) REFERENCES exercise_reference (id)');
        $this->addSql('ALTER TABLE exercise_reference ADD material LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD strainess_factor DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE muscle_activation');
        $this->addSql('ALTER TABLE exercise_reference DROP material, DROP strainess_factor');
    }
}
