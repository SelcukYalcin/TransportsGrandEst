<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507205814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marchandise ADD livraison_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marchandise ADD CONSTRAINT FK_D9A871DE8E54FB25 FOREIGN KEY (livraison_id) REFERENCES livraison (id)');
        $this->addSql('CREATE INDEX IDX_D9A871DE8E54FB25 ON marchandise (livraison_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marchandise DROP FOREIGN KEY FK_D9A871DE8E54FB25');
        $this->addSql('DROP INDEX IDX_D9A871DE8E54FB25 ON marchandise');
        $this->addSql('ALTER TABLE marchandise DROP livraison_id');
    }
}
