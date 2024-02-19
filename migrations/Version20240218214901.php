<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218214901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD last_modify_password DATE DEFAULT NULL, DROP last_modify_date, CHANGE role role VARCHAR(255) DEFAULT NULL, CHANGE gender gender VARCHAR(255) DEFAULT NULL, CHANGE date_create_compte date_create_compte DATE DEFAULT NULL, CHANGE last_modify_data last_modify_data DATE DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE num_tel2 num_tel2 INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` ADD last_modify_date DATE NOT NULL, DROP last_modify_password, CHANGE role role INT NOT NULL, CHANGE gender gender VARCHAR(255) NOT NULL, CHANGE date_create_compte date_create_compte DATE NOT NULL, CHANGE last_modify_data last_modify_data DATE NOT NULL, CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE num_tel2 num_tel2 INT NOT NULL');
    }
}
