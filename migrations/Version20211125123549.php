<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125123549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise_reference ADD author_id INT DEFAULT NULL, ADD is_validated TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE exercise_reference ADD CONSTRAINT FK_744FF9F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_744FF9F675F31B ON exercise_reference (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise_reference DROP FOREIGN KEY FK_744FF9F675F31B');
        $this->addSql('DROP INDEX IDX_744FF9F675F31B ON exercise_reference');
        $this->addSql('ALTER TABLE exercise_reference DROP author_id, DROP is_validated');
    }
}
