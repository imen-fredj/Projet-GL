<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503103941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD destinateur_id INT DEFAULT NULL, ADD recepteur_id INT DEFAULT NULL, ADD is_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAC631C63F FOREIGN KEY (destinateur_id) REFERENCES rh (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA3B49782D FOREIGN KEY (recepteur_id) REFERENCES rh (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAC631C63F ON notification (destinateur_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA3B49782D ON notification (recepteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAC631C63F');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA3B49782D');
        $this->addSql('DROP INDEX IDX_BF5476CAC631C63F ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CA3B49782D ON notification');
        $this->addSql('ALTER TABLE notification DROP destinateur_id, DROP recepteur_id, DROP is_read');
    }
}
