<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230113202623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_etudiant (groupe_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_E0DC29937A45358C (groupe_id), INDEX IDX_E0DC2993DDEAB1A3 (etudiant_id), PRIMARY KEY(groupe_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_etudiant ADD CONSTRAINT FK_E0DC29937A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_etudiant ADD CONSTRAINT FK_E0DC2993DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_etudiant DROP FOREIGN KEY FK_E0DC29937A45358C');
        $this->addSql('ALTER TABLE groupe_etudiant DROP FOREIGN KEY FK_E0DC2993DDEAB1A3');
        $this->addSql('DROP TABLE groupe_etudiant');
    }
}
