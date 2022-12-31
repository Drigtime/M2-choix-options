<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230193304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bloc_ue_ue (bloc_ue_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_F73889A16648E46A (bloc_ue_id), INDEX IDX_F73889A162E883B1 (ue_id), PRIMARY KEY(bloc_ue_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A16648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B6648E46A');
        $this->addSql('DROP INDEX IDX_2E490A9B6648E46A ON ue');
        $this->addSql('ALTER TABLE ue DROP bloc_ue_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A16648E46A');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A162E883B1');
        $this->addSql('DROP TABLE bloc_ue_ue');
        $this->addSql('ALTER TABLE ue ADD bloc_ue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id)');
        $this->addSql('CREATE INDEX IDX_2E490A9B6648E46A ON ue (bloc_ue_id)');
    }
}
