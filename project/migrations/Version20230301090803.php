<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301090803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee_formation (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_option (id INT AUTO_INCREMENT NOT NULL, campagne_choix_id INT DEFAULT NULL, nb_uechoix INT NOT NULL, INDEX IDX_4B83AB8B81F88642 (campagne_choix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_option_ue (bloc_option_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_2313EB69B26386A2 (bloc_option_id), INDEX IDX_2313EB6962E883B1 (ue_id), PRIMARY KEY(bloc_option_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_ue (id INT AUTO_INCREMENT NOT NULL, bloc_uecategory_id INT NOT NULL, parcours_id INT NOT NULL, bloc_option_id INT DEFAULT NULL, INDEX IDX_C4F2840B9865110C (bloc_uecategory_id), INDEX IDX_C4F2840B6E38C0DB (parcours_id), UNIQUE INDEX UNIQ_C4F2840BB26386A2 (bloc_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_ue_ue (bloc_ue_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_F73889A16648E46A (bloc_ue_id), INDEX IDX_F73889A162E883B1 (ue_id), PRIMARY KEY(bloc_ue_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_ue_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campagne_choix (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_D4C770BD6E38C0DB (parcours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix (id INT AUTO_INCREMENT NOT NULL, ue_id INT DEFAULT NULL, response_campagne_id INT DEFAULT NULL, bloc_option_id INT DEFAULT NULL, ordre INT NOT NULL, INDEX IDX_4F48809162E883B1 (ue_id), INDEX IDX_4F488091B6FB69FB (response_campagne_id), INDEX IDX_4F488091B26386A2 (bloc_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, INDEX IDX_717E22E36E38C0DB (parcours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant_ue (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, ue_id INT DEFAULT NULL, acquis TINYINT(1) NOT NULL, INDEX IDX_4C9ADC68DDEAB1A3 (etudiant_id), INDEX IDX_4C9ADC6862E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, ue_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_4B98C2162E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_etudiant (groupe_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_E0DC29937A45358C (groupe_id), INDEX IDX_E0DC2993DDEAB1A3 (etudiant_id), PRIMARY KEY(groupe_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcours (id INT AUTO_INCREMENT NOT NULL, annee_formation_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_99B1DEE33A687361 (annee_formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passage_annee (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, expired_at DATETIME NOT NULL, used TINYINT(1) NOT NULL, INDEX IDX_452C9EC5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response_campagne (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT NOT NULL, campagne_id INT NOT NULL, INDEX IDX_E48DBB6BDDEAB1A3 (etudiant_id), INDEX IDX_E48DBB6B16227374 (campagne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue_bloc_uecategory (ue_id INT NOT NULL, bloc_uecategory_id INT NOT NULL, INDEX IDX_3F1210A662E883B1 (ue_id), INDEX IDX_3F1210A69865110C (bloc_uecategory_id), PRIMARY KEY(ue_id, bloc_uecategory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_gestion (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8B81F88642 FOREIGN KEY (campagne_choix_id) REFERENCES campagne_choix (id)');
        $this->addSql('ALTER TABLE bloc_option_ue ADD CONSTRAINT FK_2313EB69B26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_option_ue ADD CONSTRAINT FK_2313EB6962E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840B9865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id)');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840B6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE bloc_ue ADD CONSTRAINT FK_C4F2840BB26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id)');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A16648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_ue_ue ADD CONSTRAINT FK_F73889A162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campagne_choix ADD CONSTRAINT FK_D4C770BD6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F48809162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091B6FB69FB FOREIGN KEY (response_campagne_id) REFERENCES response_campagne (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091B26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E36E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE etudiant_ue ADD CONSTRAINT FK_4C9ADC68DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE etudiant_ue ADD CONSTRAINT FK_4C9ADC6862E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C2162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE groupe_etudiant ADD CONSTRAINT FK_E0DC29937A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_etudiant ADD CONSTRAINT FK_E0DC2993DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE33A687361 FOREIGN KEY (annee_formation_id) REFERENCES annee_formation (id)');
        $this->addSql('ALTER TABLE reset_password_token ADD CONSTRAINT FK_452C9EC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response_campagne ADD CONSTRAINT FK_E48DBB6BDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE response_campagne ADD CONSTRAINT FK_E48DBB6B16227374 FOREIGN KEY (campagne_id) REFERENCES campagne_choix (id)');
        $this->addSql('ALTER TABLE ue_bloc_uecategory ADD CONSTRAINT FK_3F1210A662E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ue_bloc_uecategory ADD CONSTRAINT FK_3F1210A69865110C FOREIGN KEY (bloc_uecategory_id) REFERENCES bloc_ue_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8B81F88642');
        $this->addSql('ALTER TABLE bloc_option_ue DROP FOREIGN KEY FK_2313EB69B26386A2');
        $this->addSql('ALTER TABLE bloc_option_ue DROP FOREIGN KEY FK_2313EB6962E883B1');
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840B9865110C');
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840B6E38C0DB');
        $this->addSql('ALTER TABLE bloc_ue DROP FOREIGN KEY FK_C4F2840BB26386A2');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A16648E46A');
        $this->addSql('ALTER TABLE bloc_ue_ue DROP FOREIGN KEY FK_F73889A162E883B1');
        $this->addSql('ALTER TABLE campagne_choix DROP FOREIGN KEY FK_D4C770BD6E38C0DB');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F48809162E883B1');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091B6FB69FB');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091B26386A2');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E36E38C0DB');
        $this->addSql('ALTER TABLE etudiant_ue DROP FOREIGN KEY FK_4C9ADC68DDEAB1A3');
        $this->addSql('ALTER TABLE etudiant_ue DROP FOREIGN KEY FK_4C9ADC6862E883B1');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C2162E883B1');
        $this->addSql('ALTER TABLE groupe_etudiant DROP FOREIGN KEY FK_E0DC29937A45358C');
        $this->addSql('ALTER TABLE groupe_etudiant DROP FOREIGN KEY FK_E0DC2993DDEAB1A3');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE33A687361');
        $this->addSql('ALTER TABLE reset_password_token DROP FOREIGN KEY FK_452C9EC5A76ED395');
        $this->addSql('ALTER TABLE response_campagne DROP FOREIGN KEY FK_E48DBB6BDDEAB1A3');
        $this->addSql('ALTER TABLE response_campagne DROP FOREIGN KEY FK_E48DBB6B16227374');
        $this->addSql('ALTER TABLE ue_bloc_uecategory DROP FOREIGN KEY FK_3F1210A662E883B1');
        $this->addSql('ALTER TABLE ue_bloc_uecategory DROP FOREIGN KEY FK_3F1210A69865110C');
        $this->addSql('DROP TABLE annee_formation');
        $this->addSql('DROP TABLE bloc_option');
        $this->addSql('DROP TABLE bloc_option_ue');
        $this->addSql('DROP TABLE bloc_ue');
        $this->addSql('DROP TABLE bloc_ue_ue');
        $this->addSql('DROP TABLE bloc_ue_category');
        $this->addSql('DROP TABLE campagne_choix');
        $this->addSql('DROP TABLE choix');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE etudiant_ue');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_etudiant');
        $this->addSql('DROP TABLE parcours');
        $this->addSql('DROP TABLE passage_annee');
        $this->addSql('DROP TABLE reset_password_token');
        $this->addSql('DROP TABLE response_campagne');
        $this->addSql('DROP TABLE ue');
        $this->addSql('DROP TABLE ue_bloc_uecategory');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_gestion');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
