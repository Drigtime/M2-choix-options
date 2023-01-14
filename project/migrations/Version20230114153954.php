<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114153954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant_ue (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, ue_id INT DEFAULT NULL, acquis TINYINT(1) NOT NULL, INDEX IDX_4C9ADC68DDEAB1A3 (etudiant_id), INDEX IDX_4C9ADC6862E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etudiant_ue ADD CONSTRAINT FK_4C9ADC68DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE etudiant_ue ADD CONSTRAINT FK_4C9ADC6862E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant_ue DROP FOREIGN KEY FK_4C9ADC68DDEAB1A3');
        $this->addSql('ALTER TABLE etudiant_ue DROP FOREIGN KEY FK_4C9ADC6862E883B1');
        $this->addSql('DROP TABLE etudiant_ue');
    }
}
