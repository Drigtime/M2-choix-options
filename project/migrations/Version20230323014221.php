<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323014221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option ADD parcours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8B6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('CREATE INDEX IDX_4B83AB8B6E38C0DB ON bloc_option (parcours_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8B6E38C0DB');
        $this->addSql('DROP INDEX IDX_4B83AB8B6E38C0DB ON bloc_option');
        $this->addSql('ALTER TABLE bloc_option DROP parcours_id');
    }
}
