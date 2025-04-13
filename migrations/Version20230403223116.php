<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403223116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avance_salaire ADD rh_id INT NOT NULL');
        $this->addSql('ALTER TABLE avance_salaire ADD CONSTRAINT FK_6DA8D10522A2877C FOREIGN KEY (rh_id) REFERENCES rh (id)');
        $this->addSql('CREATE INDEX IDX_6DA8D10522A2877C ON avance_salaire (rh_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avance_salaire DROP FOREIGN KEY FK_6DA8D10522A2877C');
        $this->addSql('DROP INDEX IDX_6DA8D10522A2877C ON avance_salaire');
        $this->addSql('ALTER TABLE avance_salaire DROP rh_id');
    }
}
