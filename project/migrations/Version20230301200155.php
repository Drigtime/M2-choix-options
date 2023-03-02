<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301200155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A162E883B1');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A16648E46A');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD id INT AUTO_INCREMENT NOT NULL, ADD is_optional TINYINT(1) DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A16648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_ue_ue MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A16648E46A');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A162E883B1');
        $this->addSql('DROP INDEX `PRIMARY` ON bloc_ue_ue');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP id, DROP is_optional');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A16648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD PRIMARY KEY (bloc_ue_id, ue_id)');
    }
}
