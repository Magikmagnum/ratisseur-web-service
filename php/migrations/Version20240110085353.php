<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110085353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences ADD description LONGTEXT DEFAULT NULL, ADD debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD fin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD en_cour TINYINT(1) DEFAULT NULL, ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD modify_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE realisations ADD experience_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE realisations ADD CONSTRAINT FK_FC5C476D46E90E27 FOREIGN KEY (experience_id) REFERENCES experiences (id)');
        $this->addSql('CREATE INDEX IDX_FC5C476D46E90E27 ON realisations (experience_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP description, DROP debut_at, DROP fin_at, DROP en_cour, DROP create_at, DROP modify_at');
        $this->addSql('ALTER TABLE realisations DROP FOREIGN KEY FK_FC5C476D46E90E27');
        $this->addSql('DROP INDEX IDX_FC5C476D46E90E27 ON realisations');
        $this->addSql('ALTER TABLE realisations DROP experience_id');
    }
}
