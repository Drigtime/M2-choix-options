<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301151053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option ADD bloc_ue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8B6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id)');
        $this->addSql('CREATE INDEX IDX_4B83AB8B6648E46A ON bloc_option (bloc_ue_id)');
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840BB26386A2');
        $this->addSql('DROP INDEX UNIQ_C4F2840BB26386A2 ON bloc_ue');
        $this->addSql('ALTER TABLE bloc_ue DROP bloc_option_id');
        $this->addSql('ALTER TABLE ue ADD is_optional TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue ADD bloc_option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840BB26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4F2840BB26386A2 ON bloc_ue (bloc_option_id)');
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8B6648E46A');
        $this->addSql('DROP INDEX IDX_4B83AB8B6648E46A ON bloc_option');
        $this->addSql('ALTER TABLE bloc_option DROP bloc_ue_id');
        $this->addSql('ALTER TABLE ue DROP is_optional');
    }
}
