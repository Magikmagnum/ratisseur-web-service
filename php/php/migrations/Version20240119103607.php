<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119103607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E7098630F92');
        $this->addSql('DROP INDEX IDX_82020E7098630F92 ON experiences');
        $this->addSql('ALTER TABLE experiences CHANGE entrepise_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E70A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_82020E70A4AEAFEA ON experiences (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E70A4AEAFEA');
        $this->addSql('DROP INDEX IDX_82020E70A4AEAFEA ON experiences');
        $this->addSql('ALTER TABLE experiences CHANGE entreprise_id entrepise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E7098630F92 FOREIGN KEY (entrepise_id) REFERENCES entreprises (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_82020E7098630F92 ON experiences (entrepise_id)');
    }
}
