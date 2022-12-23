<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221173516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee_formation (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_option (id INT AUTO_INCREMENT NOT NULL, campagne_choix_id INT DEFAULT NULL, bloc_ue_id INT DEFAULT NULL, nb_uechoix INT NOT NULL, INDEX IDX_4B83AB8B81F88642 (campagne_choix_id), INDEX IDX_4B83AB8B6648E46A (bloc_ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_option_ue (bloc_option_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_2313EB69B26386A2 (bloc_option_id), INDEX IDX_2313EB6962E883B1 (ue_id), PRIMARY KEY(bloc_option_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc_ue (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campagne_choix (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_D4C770BD6E38C0DB (parcours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, ue_id INT DEFAULT NULL, campagne_choix_id INT DEFAULT NULL, ordre INT NOT NULL, INDEX IDX_4F488091DDEAB1A3 (etudiant_id), INDEX IDX_4F48809162E883B1 (ue_id), INDEX IDX_4F48809181F88642 (campagne_choix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, INDEX IDX_717E22E36E38C0DB (parcours_id), INDEX IDX_717E22E37A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, label VARCHAR(45) NOT NULL, INDEX IDX_4B98C216E38C0DB (parcours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcours (id INT AUTO_INCREMENT NOT NULL, annee_formation_id INT DEFAULT NULL, rythme_id INT DEFAULT NULL, specialisation_id INT DEFAULT NULL, INDEX IDX_99B1DEE33A687361 (annee_formation_id), INDEX IDX_99B1DEE38399A4A6 (rythme_id), INDEX IDX_99B1DEE35627D44C (specialisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcours_bloc_ue (parcours_id INT NOT NULL, bloc_ue_id INT NOT NULL, INDEX IDX_4B99BA5D6E38C0DB (parcours_id), INDEX IDX_4B99BA5D6648E46A (bloc_ue_id), PRIMARY KEY(parcours_id, bloc_ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rythme (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialisation (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(45) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue (id INT AUTO_INCREMENT NOT NULL, bloc_ue_id INT DEFAULT NULL, label VARCHAR(45) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_2E490A9B6648E46A (bloc_ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8B81F88642 FOREIGN KEY (campagne_choix_id) REFERENCES campagne_choix (id)');
        $this->addSql('ALTER TABLE bloc_option ADD CONSTRAINT FK_4B83AB8B6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id)');
        $this->addSql('ALTER TABLE bloc_option_ue ADD CONSTRAINT FK_2313EB69B26386A2 FOREIGN KEY (bloc_option_id) REFERENCES bloc_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bloc_option_ue ADD CONSTRAINT FK_2313EB6962E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE campagne_choix ADD CONSTRAINT FK_D4C770BD6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F48809162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F48809181F88642 FOREIGN KEY (campagne_choix_id) REFERENCES campagne_choix (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E36E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E37A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C216E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE33A687361 FOREIGN KEY (annee_formation_id) REFERENCES annee_formation (id)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE38399A4A6 FOREIGN KEY (rythme_id) REFERENCES rythme (id)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE35627D44C FOREIGN KEY (specialisation_id) REFERENCES specialisation (id)');
        $this->addSql('ALTER TABLE parcours_bloc_ue ADD CONSTRAINT FK_4B99BA5D6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours_bloc_ue ADD CONSTRAINT FK_4B99BA5D6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B6648E46A FOREIGN KEY (bloc_ue_id) REFERENCES bloc_ue (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8B81F88642');
        $this->addSql('ALTER TABLE bloc_option DROP FOREIGN KEY FK_4B83AB8B6648E46A');
        $this->addSql('ALTER TABLE bloc_option_ue DROP FOREIGN KEY FK_2313EB69B26386A2');
        $this->addSql('ALTER TABLE bloc_option_ue DROP FOREIGN KEY FK_2313EB6962E883B1');
        $this->addSql('ALTER TABLE campagne_choix DROP FOREIGN KEY FK_D4C770BD6E38C0DB');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091DDEAB1A3');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F48809162E883B1');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F48809181F88642');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E36E38C0DB');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E37A45358C');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C216E38C0DB');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE33A687361');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE38399A4A6');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE35627D44C');
        $this->addSql('ALTER TABLE parcours_bloc_ue DROP FOREIGN KEY FK_4B99BA5D6E38C0DB');
        $this->addSql('ALTER TABLE parcours_bloc_ue DROP FOREIGN KEY FK_4B99BA5D6648E46A');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B6648E46A');
        $this->addSql('DROP TABLE annee_formation');
        $this->addSql('DROP TABLE bloc_option');
        $this->addSql('DROP TABLE bloc_option_ue');
        $this->addSql('DROP TABLE bloc_ue');
        $this->addSql('DROP TABLE campagne_choix');
        $this->addSql('DROP TABLE choix');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE parcours');
        $this->addSql('DROP TABLE parcours_bloc_ue');
        $this->addSql('DROP TABLE rythme');
        $this->addSql('DROP TABLE specialisation');
        $this->addSql('DROP TABLE ue');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
