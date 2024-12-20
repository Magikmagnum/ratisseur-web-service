<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240114165304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences ADD yes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E702CB716C7 FOREIGN KEY (yes_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_82020E702CB716C7 ON experiences (yes_id)');
        $this->addSql('ALTER TABLE formations ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formations ADD CONSTRAINT FK_40902137A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_40902137A4AEAFEA ON formations (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E702CB716C7');
        $this->addSql('DROP INDEX IDX_82020E702CB716C7 ON experiences');
        $this->addSql('ALTER TABLE experiences DROP yes_id');
        $this->addSql('ALTER TABLE formations DROP FOREIGN KEY FK_40902137A4AEAFEA');
        $this->addSql('DROP INDEX IDX_40902137A4AEAFEA ON formations');
        $this->addSql('ALTER TABLE formations DROP entreprise_id');
    }
}
