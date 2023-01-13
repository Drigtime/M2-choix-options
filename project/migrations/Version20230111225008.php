<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111225008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ue_bloc_uecategory (ue_id INT NOT NULL, bloc_uecategory_id INT NOT NULL, INDEX IDX_3F1210A662E883B1 (ue_id), INDEX IDX_3F1210A69865110C (bloc_uecategory_id), PRIMARY KEY(ue_id, bloc_uecategory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ue_bloc_uecategory ADD CONSTRAINT FK_3F1210A662E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ue_bloc_uecategory ADD CONSTRAINT FK_3F1210A69865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_uecategory_ue DROP FOREIGN KEY FK_9157CC762E883B1');
        $this->addSql('ALTER TABLE bloc_uecategory_ue DROP FOREIGN KEY FK_9157CC79865110C');
        $this->addSql('DROP TABLE bloc_uecategory_ue');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bloc_uecategory_ue (bloc_uecategory_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_9157CC79865110C (bloc_uecategory_id), INDEX IDX_9157CC762E883B1 (ue_id), PRIMARY KEY(bloc_uecategory_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bloc_uecategory_ue ADD CONSTRAINT FK_9157CC762E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_uecategory_ue ADD CONSTRAINT FK_9157CC79865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ue_bloc_uecategory DROP FOREIGN KEY FK_3F1210A662E883B1');
        $this->addSql('ALTER TABLE ue_bloc_uecategory DROP FOREIGN KEY FK_3F1210A69865110C');
        $this->addSql('DROP TABLE ue_bloc_uecategory');
    }
}
