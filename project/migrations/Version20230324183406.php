<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324183406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840B9865110C');
        $this->addSql('DROP INDEX IDX_C4F2840B9865110C ON bloc_ue');
        $this->addSql('ALTER TABLE bloc_ue CHANGE bloc_uecategory_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840B12469DE2 FOREIGN KEY (category_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('CREATE INDEX IDX_C4F2840B12469DE2 ON bloc_ue (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840B12469DE2');
        $this->addSql('DROP INDEX IDX_C4F2840B12469DE2 ON bloc_ue');
        $this->addSql('ALTER TABLE bloc_ue CHANGE category_id bloc_uecategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840B9865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('CREATE INDEX IDX_C4F2840B9865110C ON bloc_ue (bloc_uecategory_id)');
    }
}
