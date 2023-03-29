<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329205156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bloc_option_ue (id INT AUTO_INCREMENT NOT NULL, ue_id INT NOT NULL, INDEX IDX_2313EB6962E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_option_ue_bloc_option (bloc_option_ue_id INT NOT NULL, bloc_option_id INT NOT NULL, INDEX IDX_D334EF8218735E45 (bloc_option_ue_id), INDEX IDX_D334EF82B26386A2 (bloc_option_id), PRIMARY KEY(bloc_option_ue_id, bloc_option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc_option_ue ADD CONSTRAINT FK_2313EB6962E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE bloc_option_ue_bloc_option ADD CONSTRAINT FK_D334EF8218735E45 FOREIGN KEY (bloc_option_ue_id) REFERENCES bloc_option_ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_option_ue_bloc_option ADD CONSTRAINT FK_D334EF82B26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_token DROP FOREIGN KEY FK_452C9EC5A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE reset_password_token');
        $this->addSql('DROP TABLE passage_annee');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reset_password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, expired_at DATETIME NOT NULL, used TINYINT(1) NOT NULL, INDEX IDX_452C9EC5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE passage_annee (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reset_password_token ADD CONSTRAINT FK_452C9EC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bloc_option_ue DROP FOREIGN KEY FK_2313EB6962E883B1');
        $this->addSql('ALTER TABLE bloc_option_ue_bloc_option DROP FOREIGN KEY FK_D334EF8218735E45');
        $this->addSql('ALTER TABLE bloc_option_ue_bloc_option DROP FOREIGN KEY FK_D334EF82B26386A2');
        $this->addSql('DROP TABLE bloc_option_ue');
        $this->addSql('DROP TABLE bloc_option_ue_bloc_option');
    }
}
