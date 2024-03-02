<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217122224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cnam ADD consultation_id INT NOT NULL');
        $this->addSql('ALTER TABLE cnam ADD CONSTRAINT FK_57B26D862FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57B26D862FF6CDF ON cnam (consultation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cnam DROP FOREIGN KEY FK_57B26D862FF6CDF');
        $this->addSql('DROP INDEX UNIQ_57B26D862FF6CDF ON cnam');
        $this->addSql('ALTER TABLE cnam DROP consultation_id');
    }
}
