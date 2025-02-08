<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226104732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film_film_genre (film_id INT NOT NULL, film_genre_id INT NOT NULL, INDEX IDX_A5D860E0567F5183 (film_id), INDEX IDX_A5D860E0E0111C76 (film_genre_id), PRIMARY KEY(film_id, film_genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE film_film_genre ADD CONSTRAINT FK_A5D860E0567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_film_genre ADD CONSTRAINT FK_A5D860E0E0111C76 FOREIGN KEY (film_genre_id) REFERENCES film_genre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film_film_genre DROP FOREIGN KEY FK_A5D860E0567F5183');
        $this->addSql('ALTER TABLE film_film_genre DROP FOREIGN KEY FK_A5D860E0E0111C76');
        $this->addSql('DROP TABLE film_film_genre');
    }
}
