<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116040237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E702CB716C7');
        $this->addSql('DROP INDEX IDX_82020E702CB716C7 ON experiences');
        $this->addSql('ALTER TABLE experiences ADD user_id INT NOT NULL, CHANGE yes_id entrepise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E7098630F92 FOREIGN KEY (entrepise_id) REFERENCES entreprises (id)');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_82020E7098630F92 ON experiences (entrepise_id)');
        $this->addSql('CREATE INDEX IDX_82020E70A76ED395 ON experiences (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E7098630F92');
        $this->addSql('ALTER TABLE experiences DROP FOREIGN KEY FK_82020E70A76ED395');
        $this->addSql('DROP INDEX IDX_82020E7098630F92 ON experiences');
        $this->addSql('DROP INDEX IDX_82020E70A76ED395 ON experiences');
        $this->addSql('ALTER TABLE experiences DROP user_id, CHANGE entrepise_id yes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experiences ADD CONSTRAINT FK_82020E702CB716C7 FOREIGN KEY (yes_id) REFERENCES entreprises (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_82020E702CB716C7 ON experiences (yes_id)');
    }
}
