<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307105505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_profil ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE image_profil ADD CONSTRAINT FK_49CBEC5FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_49CBEC5FA76ED395 ON image_profil (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_profil DROP FOREIGN KEY FK_49CBEC5FA76ED395');
        $this->addSql('DROP INDEX IDX_49CBEC5FA76ED395 ON image_profil');
        $this->addSql('ALTER TABLE image_profil DROP user_id');
    }
}
