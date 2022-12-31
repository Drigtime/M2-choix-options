<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230194728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ue ADD bloc_uecategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B9865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('CREATE INDEX IDX_2E490A9B9865110C ON ue (bloc_uecategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B9865110C');
        $this->addSql('DROP INDEX IDX_2E490A9B9865110C ON ue');
        $this->addSql('ALTER TABLE ue DROP bloc_uecategory_id');
    }
}
