<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404114842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_information ADD rh_id INT NOT NULL');
        $this->addSql('ALTER TABLE modification_information ADD CONSTRAINT FK_D0BC8AB22A2877C FOREIGN KEY (rh_id) REFERENCES rh (id)');
        $this->addSql('CREATE INDEX IDX_D0BC8AB22A2877C ON modification_information (rh_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_information DROP FOREIGN KEY FK_D0BC8AB22A2877C');
        $this->addSql('DROP INDEX IDX_D0BC8AB22A2877C ON modification_information');
        $this->addSql('ALTER TABLE modification_information DROP rh_id');
    }
}
