<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217122850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ambulance (id INT AUTO_INCREMENT NOT NULL, rdv_id INT NOT NULL, local_actuel_patient VARCHAR(255) NOT NULL, besoin_infirmier TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4F20B42E4CCE3F86 (rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ambulance ADD CONSTRAINT FK_4F20B42E4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambulance DROP FOREIGN KEY FK_4F20B42E4CCE3F86');
        $this->addSql('DROP TABLE ambulance');
    }
}
