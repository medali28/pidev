<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221203250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambulance DROP FOREIGN KEY FK_4F20B42E4CCE3F86');
        $this->addSql('ALTER TABLE ambulance ADD CONSTRAINT FK_4F20B42E4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambulance DROP FOREIGN KEY FK_4F20B42E4CCE3F86');
        $this->addSql('ALTER TABLE ambulance ADD CONSTRAINT FK_4F20B42E4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id)');
    }
}
