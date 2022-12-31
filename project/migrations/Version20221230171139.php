<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230171139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bloc_ue_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840B9865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840B6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('CREATE INDEX IDX_C4F2840B9865110C ON bloc_ue (bloc_uecategory_id)');
        $this->addSql('CREATE INDEX IDX_C4F2840B6E38C0DB ON bloc_ue (parcours_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840B9865110C');
        $this->addSql('DROP TABLE bloc_ue_category');
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840B6E38C0DB');
        $this->addSql('DROP INDEX IDX_C4F2840B9865110C ON bloc_ue');
        $this->addSql('DROP INDEX IDX_C4F2840B6E38C0DB ON bloc_ue');
    }
}
