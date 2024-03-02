<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301211608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cnam (id INT AUTO_INCREMENT NOT NULL, consultation_id INT NOT NULL, numero_carnet VARCHAR(255) NOT NULL, prix_consultation INT NOT NULL, UNIQUE INDEX UNIQ_57B26D862FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cnam ADD CONSTRAINT FK_57B26D862FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE ambulance ADD latitude VARCHAR(255) NOT NULL, ADD longitude VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE consultation CHANGE rdv_id rdv_id INT DEFAULT NULL, CHANGE duree_maladie duree_maladie DOUBLE PRECISION NOT NULL, CHANGE poids poids DOUBLE PRECISION NOT NULL, CHANGE taille taille DOUBLE PRECISION NOT NULL, CHANGE temperature temperature DOUBLE PRECISION NOT NULL, CHANGE frequence_cardique frequence_cardique DOUBLE PRECISION NOT NULL, CHANGE respiration respiration DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cnam DROP FOREIGN KEY FK_57B26D862FF6CDF');
        $this->addSql('DROP TABLE cnam');
        $this->addSql('ALTER TABLE ambulance DROP latitude, DROP longitude');
        $this->addSql('ALTER TABLE consultation CHANGE rdv_id rdv_id INT NOT NULL, CHANGE duree_maladie duree_maladie DATETIME NOT NULL, CHANGE poids poids INT NOT NULL, CHANGE taille taille INT NOT NULL, CHANGE temperature temperature INT NOT NULL, CHANGE frequence_cardique frequence_cardique INT NOT NULL, CHANGE respiration respiration INT NOT NULL');
    }
}
