<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213074853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_880E0D76A76ED395 ON admin (user_id)');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A66AF98A6B');
        $this->addSql('DROP INDEX UNIQ_964685A66AF98A6B ON consultation');
        $this->addSql('ALTER TABLE consultation CHANGE id_rdv_id rdv_id INT NOT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A64CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_964685A64CCE3F86 ON consultation (rdv_id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECE0312AE');
        $this->addSql('DROP INDEX IDX_B6F7494ECE0312AE ON question');
        $this->addSql('ALTER TABLE question CHANGE id_patient_id patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E6B899279 ON question (patient_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064045F098C81');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404CE0312AE');
        $this->addSql('DROP INDEX IDX_CE606404CE0312AE ON reclamation');
        $this->addSql('DROP INDEX IDX_CE6064045F098C81 ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD patient_id INT NOT NULL, DROP id_patient_id, DROP id_med_id, CHANGE id_medecin medecin VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_CE6064046B899279 ON reclamation (patient_id)');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AA1799A53');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ACE0312AE');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A456330C3');
        $this->addSql('DROP INDEX IDX_65E8AA0ACE0312AE ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0AA1799A53 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A456330C3 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD patient_id INT NOT NULL, ADD medecin_id INT NOT NULL, ADD expert_id INT NOT NULL, DROP id_patient_id, DROP id_medecin_id, DROP id_expert_id');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A4F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AC5568CE4 FOREIGN KEY (expert_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A6B899279 ON rendez_vous (patient_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A4F31A84 ON rendez_vous (medecin_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AC5568CE4 ON rendez_vous (expert_id)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC75F098C81');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7E0E718C5');
        $this->addSql('DROP INDEX IDX_5FB6DEC7E0E718C5 ON reponse');
        $this->addSql('DROP INDEX IDX_5FB6DEC75F098C81 ON reponse');
        $this->addSql('ALTER TABLE reponse ADD question_id INT NOT NULL, ADD medecin_id INT NOT NULL, DROP id_q_id, DROP id_med_id');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC74F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC71E27F6BF ON reponse (question_id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC74F31A84 ON reponse (medecin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76A76ED395');
        $this->addSql('DROP INDEX IDX_880E0D76A76ED395 ON admin');
        $this->addSql('ALTER TABLE admin DROP user_id');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A64CCE3F86');
        $this->addSql('DROP INDEX UNIQ_964685A64CCE3F86 ON consultation');
        $this->addSql('ALTER TABLE consultation CHANGE rdv_id id_rdv_id INT NOT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A66AF98A6B FOREIGN KEY (id_rdv_id) REFERENCES rendez_vous (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_964685A66AF98A6B ON consultation (id_rdv_id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E6B899279');
        $this->addSql('DROP INDEX IDX_B6F7494E6B899279 ON question');
        $this->addSql('ALTER TABLE question CHANGE patient_id id_patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECE0312AE FOREIGN KEY (id_patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494ECE0312AE ON question (id_patient_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B899279');
        $this->addSql('DROP INDEX IDX_CE6064046B899279 ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD id_med_id INT NOT NULL, CHANGE patient_id id_patient_id INT NOT NULL, CHANGE medecin id_medecin VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064045F098C81 FOREIGN KEY (id_med_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404CE0312AE FOREIGN KEY (id_patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE606404CE0312AE ON reclamation (id_patient_id)');
        $this->addSql('CREATE INDEX IDX_CE6064045F098C81 ON reclamation (id_med_id)');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A4F31A84');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AC5568CE4');
        $this->addSql('DROP INDEX IDX_65E8AA0A6B899279 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A4F31A84 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0AC5568CE4 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD id_patient_id INT NOT NULL, ADD id_medecin_id INT NOT NULL, ADD id_expert_id INT NOT NULL, DROP patient_id, DROP medecin_id, DROP expert_id');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AA1799A53 FOREIGN KEY (id_medecin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ACE0312AE FOREIGN KEY (id_patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A456330C3 FOREIGN KEY (id_expert_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0ACE0312AE ON rendez_vous (id_patient_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AA1799A53 ON rendez_vous (id_medecin_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A456330C3 ON rendez_vous (id_expert_id)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC74F31A84');
        $this->addSql('DROP INDEX IDX_5FB6DEC71E27F6BF ON reponse');
        $this->addSql('DROP INDEX IDX_5FB6DEC74F31A84 ON reponse');
        $this->addSql('ALTER TABLE reponse ADD id_q_id INT NOT NULL, ADD id_med_id INT NOT NULL, DROP question_id, DROP medecin_id');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC75F098C81 FOREIGN KEY (id_med_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7E0E718C5 FOREIGN KEY (id_q_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7E0E718C5 ON reponse (id_q_id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC75F098C81 ON reponse (id_med_id)');
    }
}
