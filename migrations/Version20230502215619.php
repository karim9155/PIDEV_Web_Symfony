<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502215619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_annonce VARCHAR(255) NOT NULL, INDEX IDX_CB988C6F79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moyen_transport (id INT AUTO_INCREMENT NOT NULL, id_ligne_id INT NOT NULL, station_id INT DEFAULT NULL, matricule INT NOT NULL, num INT NOT NULL, capacite INT NOT NULL, type_vehicule VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F42537D8A9862E3 (id_ligne_id), INDEX IDX_F42537D821BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id_reclamation INT AUTO_INCREMENT NOT NULL, message_rec VARCHAR(250) NOT NULL, objet VARCHAR(250) NOT NULL, statut VARCHAR(250) NOT NULL, date_rec DATE NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_CE606404FE6E88D7 (idUser), PRIMARY KEY(id_reclamation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id_reponse INT AUTO_INCREMENT NOT NULL, id_reclamation INT DEFAULT NULL, text_rep VARCHAR(250) NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC7D672A9F3 (id_reclamation), PRIMARY KEY(id_reponse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_client_id INT NOT NULL, id_moy_id INT NOT NULL, id_it_id INT NOT NULL, date_reservation DATE NOT NULL, heure_depart TIME NOT NULL, heure_arrive TIME NOT NULL, status VARCHAR(255) NOT NULL, type_ticket VARCHAR(255) NOT NULL, INDEX IDX_42C8495599DED506 (id_client_id), INDEX IDX_42C84955B7CA1B62 (id_moy_id), INDEX IDX_42C84955C1D888F8 (id_it_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, prix VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, nom_ticket VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_97A0ADA385542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_role_id INT NOT NULL, id_state_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, num_tel INT NOT NULL, cin INT NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649ABE530DA (cin), INDEX IDX_8D93D64989E8BDC (id_role_id), INDEX IDX_8D93D6495503D054 (id_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_state (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE moyen_transport ADD CONSTRAINT FK_F42537D8A9862E3 FOREIGN KEY (id_ligne_id) REFERENCES ligne (id)');
        $this->addSql('ALTER TABLE moyen_transport ADD CONSTRAINT FK_F42537D821BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495599DED506 FOREIGN KEY (id_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B7CA1B62 FOREIGN KEY (id_moy_id) REFERENCES moyen_transport (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C1D888F8 FOREIGN KEY (id_it_id) REFERENCES iteneraire (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA385542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495503D054 FOREIGN KEY (id_state_id) REFERENCES user_state (id)');
        $this->addSql('ALTER TABLE station CHANGE long_alt long_alt VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE trajet CHANGE temps_parcours temps_parcours INT UNSIGNED NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F79F37AE5');
        $this->addSql('ALTER TABLE moyen_transport DROP FOREIGN KEY FK_F42537D8A9862E3');
        $this->addSql('ALTER TABLE moyen_transport DROP FOREIGN KEY FK_F42537D821BDB235');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FE6E88D7');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D672A9F3');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495599DED506');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B7CA1B62');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C1D888F8');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA385542AE1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989E8BDC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495503D054');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE moyen_transport');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_state');
        $this->addSql('ALTER TABLE station CHANGE long_alt long_alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trajet CHANGE temps_parcours temps_parcours VARCHAR(255) NOT NULL');
    }
}
