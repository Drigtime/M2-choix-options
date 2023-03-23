<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323000114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8B6648E46A');
        $this->addSql('DROP INDEX IDX_4B83AB8B6648E46A ON bloc_option');
        $this->addSql('ALTER TABLE bloc_option CHANGE bloc_ue_id bloc_uecategorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8BFBCD0BDE FOREIGN KEY (bloc_uecategorie_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('CREATE INDEX IDX_4B83AB8BFBCD0BDE ON bloc_option (bloc_uecategorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8BFBCD0BDE');
        $this->addSql('DROP INDEX IDX_4B83AB8BFBCD0BDE ON bloc_option');
        $this->addSql('ALTER TABLE bloc_option CHANGE bloc_uecategorie_id bloc_ue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8B6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id)');
        $this->addSql('CREATE INDEX IDX_4B83AB8B6648E46A ON bloc_option (bloc_ue_id)');
    }
}
