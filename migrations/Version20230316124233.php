<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316124233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis_marchandise (devis_id INT NOT NULL, marchandise_id INT NOT NULL, PRIMARY KEY(devis_id, marchandise_id))');
        $this->addSql('CREATE INDEX IDX_77C9BADF41DEFADA ON devis_marchandise (devis_id)');
        $this->addSql('CREATE INDEX IDX_77C9BADFF7FBEBE ON devis_marchandise (marchandise_id)');
        $this->addSql('ALTER TABLE devis_marchandise ADD CONSTRAINT FK_77C9BADF41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis_marchandise ADD CONSTRAINT FK_77C9BADFF7FBEBE FOREIGN KEY (marchandise_id) REFERENCES marchandise (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD expediteur_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD destinataire_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B10335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES destinataire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8B27C52B10335F61 ON devis (expediteur_id)');
        $this->addSql('CREATE INDEX IDX_8B27C52BA4F84F6E ON devis (destinataire_id)');
        $this->addSql('ALTER TABLE expediteur DROP CONSTRAINT fk_aba4cf8e41defada');
        $this->addSql('DROP INDEX idx_aba4cf8e41defada');
        $this->addSql('ALTER TABLE expediteur DROP devis_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE devis_marchandise DROP CONSTRAINT FK_77C9BADF41DEFADA');
        $this->addSql('ALTER TABLE devis_marchandise DROP CONSTRAINT FK_77C9BADFF7FBEBE');
        $this->addSql('DROP TABLE devis_marchandise');
        $this->addSql('ALTER TABLE expediteur ADD devis_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expediteur ADD CONSTRAINT fk_aba4cf8e41defada FOREIGN KEY (devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_aba4cf8e41defada ON expediteur (devis_id)');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B10335F61');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BA4F84F6E');
        $this->addSql('DROP INDEX IDX_8B27C52B10335F61');
        $this->addSql('DROP INDEX IDX_8B27C52BA4F84F6E');
        $this->addSql('ALTER TABLE devis DROP expediteur_id');
        $this->addSql('ALTER TABLE devis DROP destinataire_id');
    }
}
