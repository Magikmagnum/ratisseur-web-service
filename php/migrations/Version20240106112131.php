<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240106112131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realisations ADD competence_id INT NOT NULL');
        $this->addSql('ALTER TABLE realisations ADD CONSTRAINT FK_FC5C476D15761DAB FOREIGN KEY (competence_id) REFERENCES competences (id)');
        $this->addSql('CREATE INDEX IDX_FC5C476D15761DAB ON realisations (competence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realisations DROP FOREIGN KEY FK_FC5C476D15761DAB');
        $this->addSql('DROP INDEX IDX_FC5C476D15761DAB ON realisations');
        $this->addSql('ALTER TABLE realisations DROP competence_id');
    }
}
