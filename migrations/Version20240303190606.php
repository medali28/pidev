<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303190606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP INDEX UNIQ_8F91ABF02D6BA2D9, ADD INDEX IDX_8F91ABF02D6BA2D9 (reclamation_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404197E709F');
        $this->addSql('DROP INDEX UNIQ_CE606404197E709F ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP avis_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP INDEX IDX_8F91ABF02D6BA2D9, ADD UNIQUE INDEX UNIQ_8F91ABF02D6BA2D9 (reclamation_id)');
        $this->addSql('ALTER TABLE reclamation ADD avis_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404197E709F FOREIGN KEY (avis_id) REFERENCES avis (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE606404197E709F ON reclamation (avis_id)');
    }
}
