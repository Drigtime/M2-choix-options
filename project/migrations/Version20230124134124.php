<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124134124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091B6FB69FB');
        $this->addSql('ALTER TABLE response_campagne DROP FOREIGN KEY FK_E48DBB6B16227374');
        $this->addSql('ALTER TABLE response_campagne DROP FOREIGN KEY FK_E48DBB6BDDEAB1A3');
        $this->addSql('DROP TABLE response_campagne');
        $this->addSql('DROP INDEX IDX_4F488091B6FB69FB ON choix');
        $this->addSql('ALTER TABLE choix ADD campagne_choix_id INT DEFAULT NULL, CHANGE response_campagne_id etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F48809181F88642 FOREIGN KEY (campagne_choix_id) REFERENCES campagne_choix (id)');
        $this->addSql('CREATE INDEX IDX_4F488091DDEAB1A3 ON choix (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_4F48809181F88642 ON choix (campagne_choix_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE response_campagne (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT NOT NULL, campagne_id INT NOT NULL, INDEX IDX_E48DBB6BDDEAB1A3 (etudiant_id), INDEX IDX_E48DBB6B16227374 (campagne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE response_campagne ADD CONSTRAINT FK_E48DBB6B16227374 FOREIGN KEY (campagne_id) REFERENCES campagne_choix (id)');
        $this->addSql('ALTER TABLE response_campagne ADD CONSTRAINT FK_E48DBB6BDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091DDEAB1A3');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F48809181F88642');
        $this->addSql('DROP INDEX IDX_4F488091DDEAB1A3 ON choix');
        $this->addSql('DROP INDEX IDX_4F48809181F88642 ON choix');
        $this->addSql('ALTER TABLE choix ADD response_campagne_id INT DEFAULT NULL, DROP etudiant_id, DROP campagne_choix_id');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091B6FB69FB FOREIGN KEY (response_campagne_id) REFERENCES response_campagne (id)');
        $this->addSql('CREATE INDEX IDX_4F488091B6FB69FB ON choix (response_campagne_id)');
    }
}
