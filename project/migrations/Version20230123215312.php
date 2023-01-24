<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123215312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F48809181F88642');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091DDEAB1A3');
        $this->addSql('DROP INDEX IDX_4F48809181F88642 ON choix');
        $this->addSql('DROP INDEX IDX_4F488091DDEAB1A3 ON choix');
        $this->addSql('ALTER TABLE choix DROP etudiant_id, DROP campagne_choix_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix ADD etudiant_id INT DEFAULT NULL, ADD campagne_choix_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F48809181F88642 FOREIGN KEY (campagne_choix_id) REFERENCES campagne_choix (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_4F48809181F88642 ON choix (campagne_choix_id)');
        $this->addSql('CREATE INDEX IDX_4F488091DDEAB1A3 ON choix (etudiant_id)');
    }
}
