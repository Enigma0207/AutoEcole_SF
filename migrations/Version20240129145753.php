<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129145753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneaux ADD user_eleve_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE creneaux ADD CONSTRAINT FK_77F13C6DEAE123B2 FOREIGN KEY (user_eleve_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_77F13C6DEAE123B2 ON creneaux (user_eleve_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneaux DROP FOREIGN KEY FK_77F13C6DEAE123B2');
        $this->addSql('DROP INDEX IDX_77F13C6DEAE123B2 ON creneaux');
        $this->addSql('ALTER TABLE creneaux DROP user_eleve_id');
    }
}
