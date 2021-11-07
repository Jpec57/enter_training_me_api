<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107174029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE muscle_profile');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE muscle_profile (id INT AUTO_INCREMENT NOT NULL, back_experience INT DEFAULT 0 NOT NULL, chest_experience INT DEFAULT 0 NOT NULL, triceps_experience INT DEFAULT 0 NOT NULL, biceps_experience INT DEFAULT 0 NOT NULL, abs_experience INT DEFAULT 0 NOT NULL, quadriceps_experience INT DEFAULT 0 NOT NULL, hamstring_experience INT DEFAULT 0 NOT NULL, calf_experience INT DEFAULT 0 NOT NULL, forearm_experience INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
