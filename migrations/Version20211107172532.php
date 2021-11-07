<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107172532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realised_exercise DROP FOREIGN KEY FK_22BCE687380B18A');
        $this->addSql('DROP TABLE exercise_cycle');
        $this->addSql('DROP INDEX IDX_22BCE687380B18A ON realised_exercise');
        $this->addSql('ALTER TABLE realised_exercise DROP exercise_cycle_id');
        $this->addSql('ALTER TABLE training ADD number_of_loops INT DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercise_cycle (id INT AUTO_INCREMENT NOT NULL, training_id INT DEFAULT NULL, rest_between_loop INT NOT NULL, number_of_loops INT NOT NULL, INDEX IDX_95B02651BEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE exercise_cycle ADD CONSTRAINT FK_95B02651BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE realised_exercise ADD exercise_cycle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE realised_exercise ADD CONSTRAINT FK_22BCE687380B18A FOREIGN KEY (exercise_cycle_id) REFERENCES exercise_cycle (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_22BCE687380B18A ON realised_exercise (exercise_cycle_id)');
        $this->addSql('ALTER TABLE training DROP number_of_loops');
    }
}
