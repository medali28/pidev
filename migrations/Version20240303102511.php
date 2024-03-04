<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303102511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ambulance (id INT AUTO_INCREMENT NOT NULL, rdv_id INT NOT NULL, local_actuel_patient VARCHAR(255) NOT NULL, besoin_infirmier TINYINT(1) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4F20B42E4CCE3F86 (rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cnam (id INT AUTO_INCREMENT NOT NULL, consultation_id INT NOT NULL, numero_carnet VARCHAR(255) NOT NULL, prix_consultation INT NOT NULL, UNIQUE INDEX UNIQ_57B26D862FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forbidden_keyword (id INT AUTO_INCREMENT NOT NULL, keyword VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progress_bar (id INT AUTO_INCREMENT NOT NULL, target INT NOT NULL, current INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ambulance ADD CONSTRAINT FK_4F20B42E4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cnam ADD CONSTRAINT FK_57B26D862FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE category ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE consultation CHANGE rdv_id rdv_id INT DEFAULT NULL, CHANGE duree_maladie duree_maladie DOUBLE PRECISION NOT NULL, CHANGE poids poids DOUBLE PRECISION NOT NULL, CHANGE taille taille DOUBLE PRECISION NOT NULL, CHANGE temperature temperature DOUBLE PRECISION NOT NULL, CHANGE frequence_cardique frequence_cardique DOUBLE PRECISION NOT NULL, CHANGE respiration respiration DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE medicament ADD date_fin DATE NOT NULL, DROP posted_at');
        $this->addSql('ALTER TABLE question DROP date_q, DROP temp_q, CHANGE patient_id patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD date DATE NOT NULL, ADD time TIME NOT NULL, ADD reminder_email TINYINT(1) NOT NULL, DROP date_heure, CHANGE expert_id expert_id INT DEFAULT NULL, CHANGE status_rdv status_rdv VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD pinned TINYINT(1) DEFAULT NULL, CHANGE medecin_id medecin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD last_modify_password DATE DEFAULT NULL, ADD log DOUBLE PRECISION DEFAULT NULL, ADD lat DOUBLE PRECISION DEFAULT NULL, ADD pays VARCHAR(255) DEFAULT NULL, ADD ville VARCHAR(255) DEFAULT NULL, ADD active TINYINT(1) NOT NULL, ADD is_verified TINYINT(1) NOT NULL, DROP role, DROP last_modify_date, DROP address, CHANGE email email VARCHAR(180) NOT NULL, CHANGE gender gender VARCHAR(255) DEFAULT NULL, CHANGE date_create_compte date_create_compte DATE DEFAULT NULL, CHANGE last_modify_data last_modify_data DATE DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE num_tel2 num_tel2 INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambulance DROP FOREIGN KEY FK_4F20B42E4CCE3F86');
        $this->addSql('ALTER TABLE cnam DROP FOREIGN KEY FK_57B26D862FF6CDF');
        $this->addSql('DROP TABLE ambulance');
        $this->addSql('DROP TABLE cnam');
        $this->addSql('DROP TABLE forbidden_keyword');
        $this->addSql('DROP TABLE progress_bar');
        $this->addSql('ALTER TABLE category DROP description');
        $this->addSql('ALTER TABLE consultation CHANGE rdv_id rdv_id INT NOT NULL, CHANGE duree_maladie duree_maladie DATETIME NOT NULL, CHANGE poids poids INT NOT NULL, CHANGE taille taille INT NOT NULL, CHANGE temperature temperature INT NOT NULL, CHANGE frequence_cardique frequence_cardique INT NOT NULL, CHANGE respiration respiration INT NOT NULL');
        $this->addSql('ALTER TABLE medicament ADD posted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP date_fin');
        $this->addSql('ALTER TABLE question ADD date_q DATE NOT NULL, ADD temp_q TIME NOT NULL, CHANGE patient_id patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD date_heure DATETIME NOT NULL, DROP date, DROP time, DROP reminder_email, CHANGE expert_id expert_id INT NOT NULL, CHANGE status_rdv status_rdv INT NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP pinned, CHANGE medecin_id medecin_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD role INT NOT NULL, ADD last_modify_date DATE NOT NULL, ADD address VARCHAR(255) NOT NULL, DROP roles, DROP last_modify_password, DROP log, DROP lat, DROP pays, DROP ville, DROP active, DROP is_verified, CHANGE email email VARCHAR(255) NOT NULL, CHANGE gender gender VARCHAR(255) NOT NULL, CHANGE date_create_compte date_create_compte DATE NOT NULL, CHANGE last_modify_data last_modify_data DATE NOT NULL, CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE num_tel2 num_tel2 INT NOT NULL');
    }
}
