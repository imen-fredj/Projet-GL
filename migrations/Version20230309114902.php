<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309114902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conge ADD rh_id INT NOT NULL');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED8934822A2877C FOREIGN KEY (rh_id) REFERENCES rh (id)');
        $this->addSql('CREATE INDEX IDX_2ED8934822A2877C ON conge (rh_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED8934822A2877C');
        $this->addSql('DROP INDEX IDX_2ED8934822A2877C ON conge');
        $this->addSql('ALTER TABLE conge DROP rh_id');
    }
}
