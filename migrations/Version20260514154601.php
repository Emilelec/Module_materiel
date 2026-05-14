<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260514154601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__materiel AS SELECT id, nom, pix_ht, prix_ttc, quantite, date_creation, tva_id FROM materiel');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('CREATE TABLE materiel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix_ht DOUBLE PRECISION NOT NULL, prix_ttc DOUBLE PRECISION NOT NULL, quantite INTEGER NOT NULL, date_creation DATETIME NOT NULL, tva_id INTEGER NOT NULL, CONSTRAINT FK_18D2B0914D79775F FOREIGN KEY (tva_id) REFERENCES tva (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO materiel (id, nom, prix_ht, prix_ttc, quantite, date_creation, tva_id) SELECT id, nom, pix_ht, prix_ttc, quantite, date_creation, tva_id FROM __temp__materiel');
        $this->addSql('DROP TABLE __temp__materiel');
        $this->addSql('CREATE INDEX IDX_18D2B0914D79775F ON materiel (tva_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__materiel AS SELECT id, nom, prix_ht, prix_ttc, quantite, date_creation, tva_id FROM materiel');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('CREATE TABLE materiel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, pix_ht DOUBLE PRECISION NOT NULL, prix_ttc DOUBLE PRECISION NOT NULL, quantite INTEGER NOT NULL, date_creation DATETIME NOT NULL, tva_id INTEGER NOT NULL, CONSTRAINT FK_18D2B0914D79775F FOREIGN KEY (tva_id) REFERENCES tva (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO materiel (id, nom, pix_ht, prix_ttc, quantite, date_creation, tva_id) SELECT id, nom, prix_ht, prix_ttc, quantite, date_creation, tva_id FROM __temp__materiel');
        $this->addSql('DROP TABLE __temp__materiel');
        $this->addSql('CREATE INDEX IDX_18D2B0914D79775F ON materiel (tva_id)');
    }
}
