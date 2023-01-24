<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124220258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix ADD bloc_option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091B26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id)');
        $this->addSql('CREATE INDEX IDX_4F488091B26386A2 ON choix (bloc_option_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091B26386A2');
        $this->addSql('DROP INDEX IDX_4F488091B26386A2 ON choix');
        $this->addSql('ALTER TABLE choix DROP bloc_option_id');
    }
}
