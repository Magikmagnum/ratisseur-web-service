<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116051719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences ADD label_id INT NOT NULL');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E7033B92F39 FOREIGN KEY (label_id) REFERENCES experiences_liste (id)');
        $this->addSql('CREATE INDEX IDX_82020E7033B92F39 ON experiences (label_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E7033B92F39');
        $this->addSql('DROP INDEX IDX_82020E7033B92F39 ON experiences');
        $this->addSql('ALTER TABLE experiences DROP label_id');
    }
}
