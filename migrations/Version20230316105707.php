<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316105707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP type_client');
        $this->addSql('ALTER TABLE devis DROP service');
        $this->addSql('ALTER TABLE devis DROP marchandise');
        $this->addSql('ALTER TABLE devis DROP expediteur');
        $this->addSql('ALTER TABLE devis DROP destinataire');
        $this->addSql('ALTER TABLE devis DROP quantite');
        $this->addSql('ALTER TABLE marchandise ALTER largeur TYPE DOUBLE PRECISION');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE marchandise ALTER largeur TYPE INT');
        $this->addSql('ALTER TABLE devis ADD type_client VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD service VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD marchandise VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD expediteur VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD destinataire VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE devis ADD quantite INT DEFAULT NULL');
    }
}
