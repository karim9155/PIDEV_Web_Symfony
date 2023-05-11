<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503140342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_annonce VARCHAR(255) NOT NULL, INDEX IDX_CB988C6F79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('ALTER TABLE moyen_transport DROP note');
        $this->addSql('ALTER TABLE reclamation MODIFY id_reclamation INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('DROP INDEX idUser ON reclamation');
        $this->addSql('DROP INDEX `primary` ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP idUser, CHANGE id_reclamation id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE station ADD commune_id INT DEFAULT NULL, CHANGE long_alt long_alt VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B1131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('CREATE INDEX IDX_9F39F8B1131A4F72 ON station (commune_id)');
        $this->addSql('ALTER TABLE ticket ADD nom_ticket VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495503D054 FOREIGN KEY (id_state_id) REFERENCES user_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponse (id_reponse INT AUTO_INCREMENT NOT NULL, id_reclamation INT NOT NULL, text_rep VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX id_reclamation (id_reclamation), PRIMARY KEY(id_reponse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F79F37AE5');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('ALTER TABLE moyen_transport ADD note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD idUser INT NOT NULL, CHANGE id id_reclamation INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (idUser) REFERENCES user (id)');
        $this->addSql('CREATE INDEX idUser ON reclamation (idUser)');
        $this->addSql('ALTER TABLE reclamation ADD PRIMARY KEY (id_reclamation)');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B1131A4F72');
        $this->addSql('DROP INDEX IDX_9F39F8B1131A4F72 ON station');
        $this->addSql('ALTER TABLE station DROP commune_id, CHANGE long_alt long_alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP nom_ticket');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989E8BDC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495503D054');
    }
}
