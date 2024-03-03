<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303191458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP INDEX IDX_8F91ABF02D6BA2D9, ADD UNIQUE INDEX UNIQ_8F91ABF02D6BA2D9 (reclamation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP INDEX UNIQ_8F91ABF02D6BA2D9, ADD INDEX IDX_8F91ABF02D6BA2D9 (reclamation_id)');
    }
}
