<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317160159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marchandise DROP CONSTRAINT fk_d9a871de10335f61');
        $this->addSql('ALTER TABLE marchandise DROP CONSTRAINT fk_d9a871dea4f84f6e');
        $this->addSql('DROP INDEX idx_d9a871dea4f84f6e');
        $this->addSql('DROP INDEX idx_d9a871de10335f61');
        $this->addSql('ALTER TABLE marchandise DROP expediteur_id');
        $this->addSql('ALTER TABLE marchandise DROP destinataire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE marchandise ADD expediteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marchandise ADD destinataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marchandise ADD CONSTRAINT fk_d9a871de10335f61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marchandise ADD CONSTRAINT fk_d9a871dea4f84f6e FOREIGN KEY (destinataire_id) REFERENCES destinataire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d9a871dea4f84f6e ON marchandise (destinataire_id)');
        $this->addSql('CREATE INDEX idx_d9a871de10335f61 ON marchandise (expediteur_id)');
    }
}
