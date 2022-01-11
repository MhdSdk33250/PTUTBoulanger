<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213194447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE declinaison');
        $this->addSql('ALTER TABLE article DROP declinaison_id');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E667F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE declinaison (id INT AUTO_INCREMENT NOT NULL, poids INT NOT NULL, prix INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E667F2DEE08');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F347EFB');
        $this->addSql('ALTER TABLE article ADD declinaison_id INT NOT NULL');
    }
}
