<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323143223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE destinataire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, expediteur_id INT NOT NULL, destinataire_id INT NOT NULL, date_val DATE NOT NULL, email VARCHAR(255) NOT NULL, client_type TINYINT(1) NOT NULL, service_type TINYINT(1) NOT NULL, societe VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, INDEX IDX_8B27C52B6A99F74A (membre_id), INDEX IDX_8B27C52B10335F61 (expediteur_id), INDEX IDX_8B27C52BA4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_marchandise (devis_id INT NOT NULL, marchandise_id INT NOT NULL, INDEX IDX_77C9BADF41DEFADA (devis_id), INDEX IDX_77C9BADFF7FBEBE (marchandise_id), PRIMARY KEY(devis_id, marchandise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, expediteur VARCHAR(255) NOT NULL, destinataire VARCHAR(255) NOT NULL, date_enlevement DATETIME NOT NULL, date_livree DATETIME NOT NULL, service_livraison VARCHAR(255) NOT NULL, INDEX IDX_A60C9F1F6A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marchandise (id INT AUTO_INCREMENT NOT NULL, conditionnement VARCHAR(255) NOT NULL, type_marchandise VARCHAR(255) NOT NULL, qte INT NOT NULL, longueur DOUBLE PRECISION NOT NULL, largeur DOUBLE PRECISION NOT NULL, hauteur DOUBLE PRECISION NOT NULL, poids DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, date_token DATETIME DEFAULT NULL, user_token VARCHAR(255) DEFAULT NULL, user_date_token DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B6A99F74A FOREIGN KEY (membre_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B10335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES destinataire (id)');
        $this->addSql('ALTER TABLE devis_marchandise ADD CONSTRAINT FK_77C9BADF41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devis_marchandise ADD CONSTRAINT FK_77C9BADFF7FBEBE FOREIGN KEY (marchandise_id) REFERENCES marchandise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F6A99F74A FOREIGN KEY (membre_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B6A99F74A');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B10335F61');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA4F84F6E');
        $this->addSql('ALTER TABLE devis_marchandise DROP FOREIGN KEY FK_77C9BADF41DEFADA');
        $this->addSql('ALTER TABLE devis_marchandise DROP FOREIGN KEY FK_77C9BADFF7FBEBE');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F6A99F74A');
        $this->addSql('DROP TABLE destinataire');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_marchandise');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE marchandise');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
