<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111235211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE35627D44C');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE38399A4A6');
        $this->addSql('DROP TABLE specialisation');
        $this->addSql('DROP TABLE rythme');
        $this->addSql('DROP INDEX IDX_99B1DEE38399A4A6 ON parcours');
        $this->addSql('DROP INDEX IDX_99B1DEE35627D44C ON parcours');
        $this->addSql('ALTER TABLE parcours ADD label VARCHAR(255) NOT NULL, DROP rythme_id, DROP specialisation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specialisation (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, active TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rythme (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE parcours ADD rythme_id INT DEFAULT NULL, ADD specialisation_id INT DEFAULT NULL, DROP label');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE38399A4A6 FOREIGN KEY (rythme_id) REFERENCES rythme (id)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE35627D44C FOREIGN KEY (specialisation_id) REFERENCES specialisation (id)');
        $this->addSql('CREATE INDEX IDX_99B1DEE38399A4A6 ON parcours (rythme_id)');
        $this->addSql('CREATE INDEX IDX_99B1DEE35627D44C ON parcours (specialisation_id)');
    }
}
