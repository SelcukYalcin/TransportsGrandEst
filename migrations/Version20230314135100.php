<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314135100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE destinataire_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE expediteur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE marchandise_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE destinataire (id INT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE expediteur (id INT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE marchandise (id INT NOT NULL, expediteur_id INT NOT NULL, destinataire_id INT NOT NULL, conditionnement VARCHAR(255) NOT NULL, type_marchandise VARCHAR(255) NOT NULL, qte INT NOT NULL, longueur DOUBLE PRECISION NOT NULL, largeur INT NOT NULL, hauteur DOUBLE PRECISION NOT NULL, poids DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D9A871DE10335F61 ON marchandise (expediteur_id)');
        $this->addSql('CREATE INDEX IDX_D9A871DEA4F84F6E ON marchandise (destinataire_id)');
        $this->addSql('ALTER TABLE marchandise ADD CONSTRAINT FK_D9A871DE10335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marchandise ADD CONSTRAINT FK_D9A871DEA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES destinataire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis DROP qte');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE destinataire_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE expediteur_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE marchandise_id_seq CASCADE');
        $this->addSql('ALTER TABLE marchandise DROP CONSTRAINT FK_D9A871DE10335F61');
        $this->addSql('ALTER TABLE marchandise DROP CONSTRAINT FK_D9A871DEA4F84F6E');
        $this->addSql('DROP TABLE destinataire');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('DROP TABLE marchandise');
        $this->addSql('ALTER TABLE devis ADD qte INT DEFAULT NULL');
    }
}
