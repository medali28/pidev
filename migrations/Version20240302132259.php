<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302132259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP date_q, DROP temp_q, CHANGE patient_id patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD pinned TINYINT(1) DEFAULT NULL, CHANGE medecin_id medecin_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD date_q DATE NOT NULL, ADD temp_q TIME NOT NULL, CHANGE patient_id patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP pinned, CHANGE medecin_id medecin_id INT NOT NULL');
    }
}