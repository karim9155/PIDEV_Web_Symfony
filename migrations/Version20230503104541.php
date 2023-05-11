<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503104541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moyen_transport CHANGE station_id station_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE heure_depart heure_depart TIME NOT NULL, CHANGE heure_arrive heure_arrive TIME NOT NULL');
        $this->addSql('ALTER TABLE station ADD commune_id INT DEFAULT NULL, CHANGE long_alt long_alt VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B1131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('CREATE INDEX IDX_9F39F8B1131A4F72 ON station (commune_id)');
        $this->addSql('ALTER TABLE ticket ADD nom_ticket VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649ABE530DA ON user (cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moyen_transport CHANGE station_id station_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE heure_depart heure_depart VARCHAR(255) NOT NULL, CHANGE heure_arrive heure_arrive VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B1131A4F72');
        $this->addSql('DROP INDEX IDX_9F39F8B1131A4F72 ON station');
        $this->addSql('ALTER TABLE station DROP commune_id, CHANGE long_alt long_alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP nom_ticket');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649ABE530DA ON user');
    }
}
