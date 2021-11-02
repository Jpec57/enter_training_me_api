<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102124622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `set` ADD user_id INT DEFAULT NULL, ADD realised_date DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE `set` ADD CONSTRAINT FK_E61425DCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_E61425DCA76ED395 ON `set` (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `set` DROP FOREIGN KEY FK_E61425DCA76ED395');
        $this->addSql('DROP INDEX IDX_E61425DCA76ED395 ON `set`');
        $this->addSql('ALTER TABLE `set` DROP user_id, DROP realised_date');
    }
}
