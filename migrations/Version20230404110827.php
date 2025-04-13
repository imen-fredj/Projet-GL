<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404110827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modification_information (id INT AUTO_INCREMENT NOT NULL, type_modification_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, datedemande DATE NOT NULL, etat VARCHAR(255) DEFAULT NULL, INDEX IDX_D0BC8ABBD54C553 (type_modification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modification_information ADD CONSTRAINT FK_D0BC8ABBD54C553 FOREIGN KEY (type_modification_id) REFERENCES type_modification (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_information DROP FOREIGN KEY FK_D0BC8ABBD54C553');
        $this->addSql('DROP TABLE modification_information');
    }
}
