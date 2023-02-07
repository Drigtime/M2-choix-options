<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123103019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C216E38C0DB');
        $this->addSql('DROP INDEX IDX_4B98C216E38C0DB ON groupe');
        $this->addSql('ALTER TABLE groupe DROP parcours_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe ADD parcours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C216E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('CREATE INDEX IDX_4B98C216E38C0DB ON groupe (parcours_id)');
    }
}
