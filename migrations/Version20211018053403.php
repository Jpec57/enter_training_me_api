<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018053403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fitness_badge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fitness_profile (id INT AUTO_INCREMENT NOT NULL, experience INT DEFAULT 0 NOT NULL, weight DOUBLE PRECISION NOT NULL, age INT DEFAULT NULL, goals LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fitness_team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, experience INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE muscle_profile (id INT AUTO_INCREMENT NOT NULL, back_experience INT DEFAULT 0 NOT NULL, chest_experience INT DEFAULT 0 NOT NULL, triceps_experience INT DEFAULT 0 NOT NULL, biceps_experience INT DEFAULT 0 NOT NULL, abs_experience INT DEFAULT 0 NOT NULL, quadriceps_experience INT DEFAULT 0 NOT NULL, hamstring_experience INT DEFAULT 0 NOT NULL, calf_experience INT DEFAULT 0 NOT NULL, forearm_experience INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD fitness_team_id INT DEFAULT NULL, ADD fitness_profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E36756D0 FOREIGN KEY (fitness_team_id) REFERENCES fitness_team (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A0473B65 FOREIGN KEY (fitness_profile_id) REFERENCES fitness_profile (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E36756D0 ON user (fitness_team_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0473B65 ON user (fitness_profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A0473B65');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649E36756D0');
        $this->addSql('DROP TABLE fitness_badge');
        $this->addSql('DROP TABLE fitness_profile');
        $this->addSql('DROP TABLE fitness_team');
        $this->addSql('DROP TABLE muscle_profile');
        $this->addSql('DROP INDEX IDX_8D93D649E36756D0 ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D649A0473B65 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP fitness_team_id, DROP fitness_profile_id');
    }
}
