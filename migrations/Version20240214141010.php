<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214141010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, rdv_id INT NOT NULL, description VARCHAR(255) NOT NULL, duree_maladie DATETIME NOT NULL, poids INT NOT NULL, taille INT NOT NULL, temperature INT NOT NULL, frequence_cardique INT NOT NULL, respiration INT NOT NULL, conseils VARCHAR(255) NOT NULL, medicament VARCHAR(255) NOT NULL, date_prochaine DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_964685A64CCE3F86 (rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, posted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9A9C723A12469DE2 (category_id), INDEX IDX_9A9C723AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(400) NOT NULL, date_q DATE NOT NULL, temp_q TIME NOT NULL, datetemp_q DATETIME NOT NULL, INDEX IDX_B6F7494E6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, medecin VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, description_rec VARCHAR(300) NOT NULL, avis VARCHAR(255) NOT NULL, INDEX IDX_CE6064046B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, medecin_id INT NOT NULL, expert_id INT NOT NULL, date_heure DATETIME NOT NULL, status_rdv INT NOT NULL, description VARCHAR(255) NOT NULL, reponse_refuse VARCHAR(255) NOT NULL, urgence TINYINT(1) NOT NULL, INDEX IDX_65E8AA0A6B899279 (patient_id), INDEX IDX_65E8AA0A4F31A84 (medecin_id), INDEX IDX_65E8AA0AC5568CE4 (expert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, medecin_id INT NOT NULL, description_r VARCHAR(400) NOT NULL, datetemp_r DATETIME NOT NULL, INDEX IDX_5FB6DEC71E27F6BF (question_id), INDEX IDX_5FB6DEC74F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, role INT NOT NULL, gender VARCHAR(255) NOT NULL, num_tel INT NOT NULL, date_create_compte DATE NOT NULL, last_modify_date DATE NOT NULL, last_modify_data DATE NOT NULL, date_naissance DATE NOT NULL, image VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, maladie_chronique VARCHAR(255) DEFAULT NULL, num_tel2 INT NOT NULL, specialite VARCHAR(255) DEFAULT NULL, validation INT DEFAULT NULL, rate INT DEFAULT NULL, disponibilite TIME DEFAULT NULL, date_debut TIME DEFAULT NULL, date_fin TIME DEFAULT NULL, prix_c DOUBLE PRECISION DEFAULT NULL, diplomes VARCHAR(255) DEFAULT NULL, dure_rdv TIME DEFAULT NULL, allergies VARCHAR(255) DEFAULT NULL, antecedent_maladie VARCHAR(255) DEFAULT NULL, antecedent_medicaux VARCHAR(255) DEFAULT NULL, groupe_sanguin VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A64CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id)');
        $this->addSql('ALTER TABLE medicament ADD CONSTRAINT FK_9A9C723A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE medicament ADD CONSTRAINT FK_9A9C723AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A4F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AC5568CE4 FOREIGN KEY (expert_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC74F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A64CCE3F86');
        $this->addSql('ALTER TABLE medicament DROP FOREIGN KEY FK_9A9C723A12469DE2');
        $this->addSql('ALTER TABLE medicament DROP FOREIGN KEY FK_9A9C723AA76ED395');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E6B899279');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A4F31A84');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AC5568CE4');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC74F31A84');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
