<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304234754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progress_bar ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE progress_bar ADD CONSTRAINT FK_FFD045AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FFD045AAA76ED395 ON progress_bar (user_id)');
        $this->addSql('ALTER TABLE user CHANGE disponibilite disponibilite TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progress_bar DROP FOREIGN KEY FK_FFD045AAA76ED395');
        $this->addSql('DROP INDEX IDX_FFD045AAA76ED395 ON progress_bar');
        $this->addSql('ALTER TABLE progress_bar DROP user_id');
        $this->addSql('ALTER TABLE user CHANGE disponibilite disponibilite TINYINT(1) DEFAULT NULL');
    }
}
