<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107163326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fitness_badge ADD fitness_profile_id INT NOT NULL, ADD image_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fitness_badge ADD CONSTRAINT FK_B9ED9D26A0473B65 FOREIGN KEY (fitness_profile_id) REFERENCES fitness_profile (id)');
        $this->addSql('CREATE INDEX IDX_B9ED9D26A0473B65 ON fitness_badge (fitness_profile_id)');
        $this->addSql('ALTER TABLE fitness_profile ADD hamstring_experience INT DEFAULT 0 NOT NULL, ADD quadriceps_experience INT DEFAULT 0 NOT NULL, ADD calf_experience INT DEFAULT 0 NOT NULL, ADD abs_experience INT DEFAULT 0 NOT NULL, ADD forearm_experience INT DEFAULT 0 NOT NULL, ADD biceps_experience INT DEFAULT 0 NOT NULL, ADD triceps_experience INT DEFAULT 0 NOT NULL, ADD shoulder_experience INT DEFAULT 0 NOT NULL, ADD chest_experience INT DEFAULT 0 NOT NULL, ADD back_experience INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fitness_badge DROP FOREIGN KEY FK_B9ED9D26A0473B65');
        $this->addSql('DROP INDEX IDX_B9ED9D26A0473B65 ON fitness_badge');
        $this->addSql('ALTER TABLE fitness_badge DROP fitness_profile_id, DROP image_path');
        $this->addSql('ALTER TABLE fitness_profile DROP hamstring_experience, DROP quadriceps_experience, DROP calf_experience, DROP abs_experience, DROP forearm_experience, DROP biceps_experience, DROP triceps_experience, DROP shoulder_experience, DROP chest_experience, DROP back_experience');
    }
}
