<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503142251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation ADD id INT AUTO_INCREMENT NOT NULL, DROP id_reclamation, DROP idUser, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495503D054 FOREIGN KEY (id_state_id) REFERENCES user_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD id_reclamation INT NOT NULL, ADD idUser INT NOT NULL, DROP id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989E8BDC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495503D054');
    }
}
