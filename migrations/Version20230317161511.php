<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317161511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis ADD client_type BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE devis ADD service_type BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE devis ADD societe VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE devis ADD prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD telephone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE devis DROP client_type');
        $this->addSql('ALTER TABLE devis DROP service_type');
        $this->addSql('ALTER TABLE devis DROP societe');
        $this->addSql('ALTER TABLE devis DROP prenom');
        $this->addSql('ALTER TABLE devis DROP nom');
        $this->addSql('ALTER TABLE devis DROP telephone');
    }
}
