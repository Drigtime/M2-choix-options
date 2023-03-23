<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322234848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campagne_choix_parcours (campagne_choix_id INT NOT NULL, parcours_id INT NOT NULL, INDEX IDX_221FD2E581F88642 (campagne_choix_id), INDEX IDX_221FD2E56E38C0DB (parcours_id), PRIMARY KEY(campagne_choix_id, parcours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campagne_choix_parcours ADD CONSTRAINT FK_221FD2E581F88642 FOREIGN KEY (campagne_choix_id) REFERENCES campagne_choix (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campagne_choix_parcours ADD CONSTRAINT FK_221FD2E56E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campagne_choix DROP FOREIGN KEY FK_D4C770BD6E38C0DB');
        $this->addSql('DROP INDEX IDX_D4C770BD6E38C0DB ON campagne_choix');
        $this->addSql('ALTER TABLE campagne_choix DROP parcours_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campagne_choix_parcours DROP FOREIGN KEY FK_221FD2E581F88642');
        $this->addSql('ALTER TABLE campagne_choix_parcours DROP FOREIGN KEY FK_221FD2E56E38C0DB');
        $this->addSql('DROP TABLE campagne_choix_parcours');
        $this->addSql('ALTER TABLE campagne_choix ADD parcours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campagne_choix ADD CONSTRAINT FK_D4C770BD6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('CREATE INDEX IDX_D4C770BD6E38C0DB ON campagne_choix (parcours_id)');
    }
}
