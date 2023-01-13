<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111223913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bloc_uecategory_ue (bloc_uecategory_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_9157CC79865110C (bloc_uecategory_id), INDEX IDX_9157CC762E883B1 (ue_id), PRIMARY KEY(bloc_uecategory_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc_uecategory_ue ADD CONSTRAINT FK_9157CC79865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_uecategory_ue ADD CONSTRAINT FK_9157CC762E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours_bloc_ue DROP FOREIGN KEY FK_4B99BA5D6648E46A');
        $this->addSql('ALTER TABLE parcours_bloc_ue DROP FOREIGN KEY FK_4B99BA5D6E38C0DB');
        $this->addSql('DROP TABLE parcours_bloc_ue');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B9865110C');
        $this->addSql('DROP INDEX IDX_2E490A9B9865110C ON ue');
        $this->addSql('ALTER TABLE ue DROP bloc_uecategory_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parcours_bloc_ue (parcours_id INT NOT NULL, bloc_ue_id INT NOT NULL, INDEX IDX_4B99BA5D6E38C0DB (parcours_id), INDEX IDX_4B99BA5D6648E46A (bloc_ue_id), PRIMARY KEY(parcours_id, bloc_ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE parcours_bloc_ue ADD CONSTRAINT FK_4B99BA5D6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours_bloc_ue ADD CONSTRAINT FK_4B99BA5D6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_uecategory_ue DROP FOREIGN KEY FK_9157CC79865110C');
        $this->addSql('ALTER TABLE bloc_uecategory_ue DROP FOREIGN KEY FK_9157CC762E883B1');
        $this->addSql('DROP TABLE bloc_uecategory_ue');
        $this->addSql('ALTER TABLE ue ADD bloc_uecategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B9865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('CREATE INDEX IDX_2E490A9B9865110C ON ue (bloc_uecategory_id)');
    }
}
