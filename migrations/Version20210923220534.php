<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923220534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE execution_style (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, strain_factor DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_cycle (id INT AUTO_INCREMENT NOT NULL, training_id INT DEFAULT NULL, rest_between_loop INT NOT NULL, number_of_loops INT NOT NULL, INDEX IDX_E90B177ABEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_format (id INT AUTO_INCREMENT NOT NULL, execution_style_id INT DEFAULT NULL, predefined_rest INT NOT NULL, INDEX IDX_ABF5E7B429A96238 (execution_style_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realised_exercise (id INT AUTO_INCREMENT NOT NULL, exercise_reference_id INT NOT NULL, execution_style_id INT DEFAULT NULL, exercice_cycle_id INT DEFAULT NULL, rest_between_set INT NOT NULL, INDEX IDX_22BCE687FCDD6B80 (exercise_reference_id), INDEX IDX_22BCE68729A96238 (execution_style_id), INDEX IDX_22BCE687AD767541 (exercice_cycle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_training (id INT AUTO_INCREMENT NOT NULL, training_reference_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BBF38DA7E56F2A4 (training_reference_id), INDEX IDX_BBF38DA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `set` (id INT AUTO_INCREMENT NOT NULL, exercise_format_id INT DEFAULT NULL, realised_exercise_id INT NOT NULL, reps INT NOT NULL, weight_percent DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E61425DC78FEF0EF (exercise_format_id), INDEX IDX_E61425DC48C863DA (realised_exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, rest_between_cycles INT NOT NULL, INDEX IDX_D5128A8FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercice_cycle ADD CONSTRAINT FK_E90B177ABEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE exercise_format ADD CONSTRAINT FK_ABF5E7B429A96238 FOREIGN KEY (execution_style_id) REFERENCES execution_style (id)');
        $this->addSql('ALTER TABLE realised_exercise ADD CONSTRAINT FK_22BCE687FCDD6B80 FOREIGN KEY (exercise_reference_id) REFERENCES exercise_reference (id)');
        $this->addSql('ALTER TABLE realised_exercise ADD CONSTRAINT FK_22BCE68729A96238 FOREIGN KEY (execution_style_id) REFERENCES execution_style (id)');
        $this->addSql('ALTER TABLE realised_exercise ADD CONSTRAINT FK_22BCE687AD767541 FOREIGN KEY (exercice_cycle_id) REFERENCES exercice_cycle (id)');
        $this->addSql('ALTER TABLE saved_training ADD CONSTRAINT FK_BBF38DA7E56F2A4 FOREIGN KEY (training_reference_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE saved_training ADD CONSTRAINT FK_BBF38DA7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `set` ADD CONSTRAINT FK_E61425DC78FEF0EF FOREIGN KEY (exercise_format_id) REFERENCES exercise_format (id)');
        $this->addSql('ALTER TABLE `set` ADD CONSTRAINT FK_E61425DC48C863DA FOREIGN KEY (realised_exercise_id) REFERENCES realised_exercise (id)');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_D5128A8FF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise_format DROP FOREIGN KEY FK_ABF5E7B429A96238');
        $this->addSql('ALTER TABLE realised_exercise DROP FOREIGN KEY FK_22BCE68729A96238');
        $this->addSql('ALTER TABLE realised_exercise DROP FOREIGN KEY FK_22BCE687AD767541');
        $this->addSql('ALTER TABLE `set` DROP FOREIGN KEY FK_E61425DC78FEF0EF');
        $this->addSql('ALTER TABLE `set` DROP FOREIGN KEY FK_E61425DC48C863DA');
        $this->addSql('ALTER TABLE exercice_cycle DROP FOREIGN KEY FK_E90B177ABEFD98D1');
        $this->addSql('ALTER TABLE saved_training DROP FOREIGN KEY FK_BBF38DA7E56F2A4');
        $this->addSql('DROP TABLE execution_style');
        $this->addSql('DROP TABLE exercice_cycle');
        $this->addSql('DROP TABLE exercise_format');
        $this->addSql('DROP TABLE realised_exercise');
        $this->addSql('DROP TABLE saved_training');
        $this->addSql('DROP TABLE `set`');
        $this->addSql('DROP TABLE training');
    }
}
