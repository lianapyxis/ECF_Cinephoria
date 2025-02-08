<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250104174939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film_genre_film (film_genre_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_864DCA9AE0111C76 (film_genre_id), INDEX IDX_864DCA9A567F5183 (film_id), PRIMARY KEY(film_genre_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE film_genre_film ADD CONSTRAINT FK_864DCA9AE0111C76 FOREIGN KEY (film_genre_id) REFERENCES film_genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_genre_film ADD CONSTRAINT FK_864DCA9A567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE224296D31F');
        $this->addSql('DROP INDEX IDX_8244BE224296D31F ON film');
        $this->addSql('ALTER TABLE film DROP genre_id');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE22A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('CREATE INDEX IDX_8244BE22A32EFC6 ON film (rating_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film_genre_film DROP FOREIGN KEY FK_864DCA9AE0111C76');
        $this->addSql('ALTER TABLE film_genre_film DROP FOREIGN KEY FK_864DCA9A567F5183');
        $this->addSql('DROP TABLE film_genre_film');
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE22A32EFC6');
        $this->addSql('DROP INDEX IDX_8244BE22A32EFC6 ON film');
        $this->addSql('ALTER TABLE film ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE224296D31F FOREIGN KEY (genre_id) REFERENCES film_genre (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8244BE224296D31F ON film (genre_id)');
    }
}
