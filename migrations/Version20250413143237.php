<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413143237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avance_salaire (id INT AUTO_INCREMENT NOT NULL, rh_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, datedemande DATE NOT NULL, date_avance DATE NOT NULL, etat VARCHAR(255) DEFAULT NULL, INDEX IDX_6DA8D10522A2877C (rh_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, typeconge_id INT NOT NULL, rh_id INT NOT NULL, datedebut DATE NOT NULL, nbjour INT NOT NULL, description VARCHAR(255) DEFAULT NULL, datedemande DATE NOT NULL, etat VARCHAR(255) DEFAULT NULL, datefin DATE NOT NULL, INDEX IDX_2ED8934896C514D1 (typeconge_id), INDEX IDX_2ED8934822A2877C (rh_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modification_information (id INT AUTO_INCREMENT NOT NULL, type_modification_id INT NOT NULL, rh_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, datedemande DATE NOT NULL, etat VARCHAR(255) DEFAULT NULL, INDEX IDX_D0BC8ABBD54C553 (type_modification_id), INDEX IDX_D0BC8AB22A2877C (rh_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, conge_id INT DEFAULT NULL, destinateur_id INT DEFAULT NULL, recepteur_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, is_read INT NOT NULL, date_notification DATE NOT NULL, INDEX IDX_BF5476CACAAC9A59 (conge_id), INDEX IDX_BF5476CAC631C63F (destinateur_id), INDEX IDX_BF5476CA3B49782D (recepteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rh (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, cin INT NOT NULL, active VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, telephone INT NOT NULL, UNIQUE INDEX UNIQ_1FB9E0E1E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_modification (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typeconge (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typedocument (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avance_salaire ADD CONSTRAINT FK_6DA8D10522A2877C FOREIGN KEY (rh_id) REFERENCES rh (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED8934896C514D1 FOREIGN KEY (typeconge_id) REFERENCES typeconge (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED8934822A2877C FOREIGN KEY (rh_id) REFERENCES rh (id)');
        $this->addSql('ALTER TABLE modification_information ADD CONSTRAINT FK_D0BC8ABBD54C553 FOREIGN KEY (type_modification_id) REFERENCES type_modification (id)');
        $this->addSql('ALTER TABLE modification_information ADD CONSTRAINT FK_D0BC8AB22A2877C FOREIGN KEY (rh_id) REFERENCES rh (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACAAC9A59 FOREIGN KEY (conge_id) REFERENCES conge (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAC631C63F FOREIGN KEY (destinateur_id) REFERENCES rh (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA3B49782D FOREIGN KEY (recepteur_id) REFERENCES rh (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avance_salaire DROP FOREIGN KEY FK_6DA8D10522A2877C');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED8934896C514D1');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED8934822A2877C');
        $this->addSql('ALTER TABLE modification_information DROP FOREIGN KEY FK_D0BC8ABBD54C553');
        $this->addSql('ALTER TABLE modification_information DROP FOREIGN KEY FK_D0BC8AB22A2877C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CACAAC9A59');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAC631C63F');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA3B49782D');
        $this->addSql('DROP TABLE avance_salaire');
        $this->addSql('DROP TABLE conge');
        $this->addSql('DROP TABLE modification_information');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE rh');
        $this->addSql('DROP TABLE type_modification');
        $this->addSql('DROP TABLE typeconge');
        $this->addSql('DROP TABLE typedocument');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
