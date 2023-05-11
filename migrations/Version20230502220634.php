<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502220634 extends AbstractMigration
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
        $this->addSql('ALTER TABLE moyen_transport ADD note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('ALTER TABLE reclamation CHANGE objet objet VARCHAR(250) NOT NULL, CHANGE message_rec message_rec VARCHAR(250) NOT NULL, CHANGE statut statut VARCHAR(250) NOT NULL, CHANGE idUser idUser INT DEFAULT NULL');
        $this->addSql('DROP INDEX iduser ON reclamation');
        $this->addSql('CREATE INDEX IDX_CE606404FE6E88D7 ON reclamation (idUser)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (idUser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse DROP INDEX id_reclamation, ADD UNIQUE INDEX UNIQ_5FB6DEC7D672A9F3 (id_reclamation)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1');
        $this->addSql('ALTER TABLE reponse CHANGE id_reclamation id_reclamation INT DEFAULT NULL, CHANGE text_rep text_rep VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation)');
        $this->addSql('ALTER TABLE station CHANGE long_alt long_alt VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE trajet CHANGE temps_parcours temps_parcours INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495503D054 FOREIGN KEY (id_state_id) REFERENCES user_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F79F37AE5');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('ALTER TABLE moyen_transport DROP note');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FE6E88D7');
        $this->addSql('ALTER TABLE reclamation CHANGE message_rec message_rec VARCHAR(255) NOT NULL, CHANGE objet objet VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE idUser idUser INT NOT NULL');
        $this->addSql('DROP INDEX idx_ce606404fe6e88d7 ON reclamation');
        $this->addSql('CREATE INDEX idUser ON reclamation (idUser)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse DROP INDEX UNIQ_5FB6DEC7D672A9F3, ADD INDEX id_reclamation (id_reclamation)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D672A9F3');
        $this->addSql('ALTER TABLE reponse CHANGE id_reclamation id_reclamation INT NOT NULL, CHANGE text_rep text_rep VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE station CHANGE long_alt long_alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trajet CHANGE temps_parcours temps_parcours VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989E8BDC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495503D054');
    }
}
