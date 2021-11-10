<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110134903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_reaction ADD training_id INT DEFAULT NULL, CHANGE reaction_type reaction_type INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user_reaction ADD CONSTRAINT FK_445AE3F7BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('CREATE INDEX IDX_445AE3F7BEFD98D1 ON user_reaction (training_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_reaction DROP FOREIGN KEY FK_445AE3F7BEFD98D1');
        $this->addSql('DROP INDEX IDX_445AE3F7BEFD98D1 ON user_reaction');
        $this->addSql('ALTER TABLE user_reaction DROP training_id, CHANGE reaction_type reaction_type INT NOT NULL');
    }
}
