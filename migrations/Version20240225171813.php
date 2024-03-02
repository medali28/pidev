<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225171813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD gender VARCHAR(255) DEFAULT NULL, ADD num_tel INT NOT NULL, ADD date_create_compte DATE DEFAULT NULL, ADD last_modify_password DATE DEFAULT NULL, ADD last_modify_data DATE DEFAULT NULL, ADD date_naissance DATE DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD maladie_chronique VARCHAR(255) DEFAULT NULL, ADD num_tel2 INT DEFAULT NULL, ADD specialite VARCHAR(255) DEFAULT NULL, ADD validation INT DEFAULT NULL, ADD rate INT DEFAULT NULL, ADD disponibilite TIME DEFAULT NULL, ADD date_debut TIME DEFAULT NULL, ADD date_fin TIME DEFAULT NULL, ADD prix_c DOUBLE PRECISION DEFAULT NULL, ADD diplomes VARCHAR(255) DEFAULT NULL, ADD dure_rdv TIME DEFAULT NULL, ADD allergies VARCHAR(255) DEFAULT NULL, ADD antecedent_maladie VARCHAR(255) DEFAULT NULL, ADD antecedent_medicaux VARCHAR(255) DEFAULT NULL, ADD groupe_sanguin VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name, DROP gender, DROP num_tel, DROP date_create_compte, DROP last_modify_password, DROP last_modify_data, DROP date_naissance, DROP image, DROP address, DROP maladie_chronique, DROP num_tel2, DROP specialite, DROP validation, DROP rate, DROP disponibilite, DROP date_debut, DROP date_fin, DROP prix_c, DROP diplomes, DROP dure_rdv, DROP allergies, DROP antecedent_maladie, DROP antecedent_medicaux, DROP groupe_sanguin');
    }
}
