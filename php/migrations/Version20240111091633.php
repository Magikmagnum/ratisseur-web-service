<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111091633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experiences_liste (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, valide TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competences ADD label_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CE33B92F39 FOREIGN KEY (label_id) REFERENCES competences_liste (id)');
        $this->addSql('CREATE INDEX IDX_DB2077CE33B92F39 ON competences (label_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE experiences_liste');
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CE33B92F39');
        $this->addSql('DROP INDEX IDX_DB2077CE33B92F39 ON competences');
        $this->addSql('ALTER TABLE competences DROP label_id');
    }
}
