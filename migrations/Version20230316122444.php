<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316122444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expediteur ADD devis_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expediteur ADD CONSTRAINT FK_ABA4CF8E41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_ABA4CF8E41DEFADA ON expediteur (devis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE expediteur DROP CONSTRAINT FK_ABA4CF8E41DEFADA');
        $this->addSql('DROP INDEX IDX_ABA4CF8E41DEFADA');
        $this->addSql('ALTER TABLE expediteur DROP devis_id');
    }
}
