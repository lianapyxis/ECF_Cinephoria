<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108153957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE22A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('CREATE INDEX IDX_8244BE22A32EFC6 ON film (rating_id)');
        $this->addSql('ALTER TABLE room ADD number_rows INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE22A32EFC6');
        $this->addSql('DROP INDEX IDX_8244BE22A32EFC6 ON film');
        $this->addSql('ALTER TABLE room DROP number_rows');
    }
}
