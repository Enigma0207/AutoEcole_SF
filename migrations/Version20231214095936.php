<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214095936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneaux DROP FOREIGN KEY FK_77F13C6D5AB72B27');
        $this->addSql('ALTER TABLE creneaux DROP FOREIGN KEY FK_77F13C6D7716ECF9');
        $this->addSql('ALTER TABLE creneaux DROP FOREIGN KEY FK_77F13C6DBA266824');
        $this->addSql('DROP INDEX UNIQ_77F13C6D5AB72B27 ON creneaux');
        $this->addSql('DROP INDEX UNIQ_77F13C6DBA266824 ON creneaux');
        $this->addSql('DROP INDEX IDX_77F13C6D7716ECF9 ON creneaux');
        $this->addSql('ALTER TABLE creneaux ADD permis_id INT NOT NULL, ADD is_available TINYINT(1) NOT NULL, DROP id_eleve_id, DROP id_moniteur_id, DROP disponibility, CHANGE permis_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE creneaux ADD CONSTRAINT FK_77F13C6DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE creneaux ADD CONSTRAINT FK_77F13C6D3594A24E FOREIGN KEY (permis_id) REFERENCES permis (id)');
        $this->addSql('CREATE INDEX IDX_77F13C6DA76ED395 ON creneaux (user_id)');
        $this->addSql('CREATE INDEX IDX_77F13C6D3594A24E ON creneaux (permis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneaux DROP FOREIGN KEY FK_77F13C6DA76ED395');
        $this->addSql('ALTER TABLE creneaux DROP FOREIGN KEY FK_77F13C6D3594A24E');
        $this->addSql('DROP INDEX IDX_77F13C6DA76ED395 ON creneaux');
        $this->addSql('DROP INDEX IDX_77F13C6D3594A24E ON creneaux');
        $this->addSql('ALTER TABLE creneaux ADD permis_id_id INT NOT NULL, ADD id_eleve_id INT DEFAULT NULL, ADD id_moniteur_id INT DEFAULT NULL, ADD disponibility JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP user_id, DROP permis_id, DROP is_available');
        $this->addSql('ALTER TABLE creneaux ADD CONSTRAINT FK_77F13C6D5AB72B27 FOREIGN KEY (id_eleve_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE creneaux ADD CONSTRAINT FK_77F13C6D7716ECF9 FOREIGN KEY (permis_id_id) REFERENCES permis (id)');
        $this->addSql('ALTER TABLE creneaux ADD CONSTRAINT FK_77F13C6DBA266824 FOREIGN KEY (id_moniteur_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_77F13C6D5AB72B27 ON creneaux (id_eleve_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_77F13C6DBA266824 ON creneaux (id_moniteur_id)');
        $this->addSql('CREATE INDEX IDX_77F13C6D7716ECF9 ON creneaux (permis_id_id)');
    }
}
