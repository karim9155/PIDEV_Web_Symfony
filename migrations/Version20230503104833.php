<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503104833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_client_id INT NOT NULL, id_moy_id INT NOT NULL, id_it_id INT NOT NULL, date_reservation DATE NOT NULL, heure_depart TIME NOT NULL, heure_arrive TIME NOT NULL, status VARCHAR(255) NOT NULL, type_ticket VARCHAR(255) NOT NULL, INDEX IDX_42C8495599DED506 (id_client_id), INDEX IDX_42C84955B7CA1B62 (id_moy_id), INDEX IDX_42C84955C1D888F8 (id_it_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, prix VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, nom_ticket VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_97A0ADA385542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495599DED506 FOREIGN KEY (id_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B7CA1B62 FOREIGN KEY (id_moy_id) REFERENCES moyen_transport (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C1D888F8 FOREIGN KEY (id_it_id) REFERENCES iteneraire (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA385542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649ABE530DA ON user (cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495599DED506');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B7CA1B62');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C1D888F8');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA385542AE1');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649ABE530DA ON user');
    }
}
