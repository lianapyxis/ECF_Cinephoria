<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226114345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film_film_genre DROP FOREIGN KEY FK_A5D860E0567F5183');
        $this->addSql('ALTER TABLE film_film_genre DROP FOREIGN KEY FK_A5D860E0E0111C76');
        $this->addSql('DROP TABLE film_film_genre');
        $this->addSql('ALTER TABLE film ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE224296D31F FOREIGN KEY (genre_id) REFERENCES film_genre (id)');
        $this->addSql('CREATE INDEX IDX_8244BE224296D31F ON film (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film_film_genre (film_id INT NOT NULL, film_genre_id INT NOT NULL, INDEX IDX_A5D860E0567F5183 (film_id), INDEX IDX_A5D860E0E0111C76 (film_genre_id), PRIMARY KEY(film_id, film_genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE film_film_genre ADD CONSTRAINT FK_A5D860E0567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_film_genre ADD CONSTRAINT FK_A5D860E0E0111C76 FOREIGN KEY (film_genre_id) REFERENCES film_genre (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE224296D31F');
        $this->addSql('DROP INDEX IDX_8244BE224296D31F ON film');
        $this->addSql('ALTER TABLE film DROP genre_id');
    }
}
