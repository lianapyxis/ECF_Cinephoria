<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250104175401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city_film (city_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_610A834A8BAC62AF (city_id), INDEX IDX_610A834A567F5183 (film_id), PRIMARY KEY(city_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE damaged_place (id INT AUTO_INCREMENT NOT NULL, id_room_id INT NOT NULL, place VARCHAR(255) NOT NULL, status INT NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_47BB56EA8A8AD9E3 (id_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE format (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_seance_id INT NOT NULL, cost_total DOUBLE PRECISION NOT NULL, status INT NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_42C8495579F37AE5 (id_user_id), INDEX IDX_42C84955634CC6B3 (id_seance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_details (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, place VARCHAR(255) NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_15B3B00F85542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, id_city_id INT NOT NULL, format_id INT NOT NULL, type_seats_id INT NOT NULL, title VARCHAR(255) NOT NULL, number_seats INT NOT NULL, number_rows INT NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_729F519B5531CBDF (id_city_id), INDEX IDX_729F519BD629F605 (format_id), INDEX IDX_729F519B7CFC8404 (type_seats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, id_film_id INT NOT NULL, id_room_id INT NOT NULL, time_start DATETIME NOT NULL, time_end DATETIME NOT NULL, price_ttc DOUBLE PRECISION NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_DF7DFD0E88E2F8F3 (id_film_id), INDEX IDX_DF7DFD0E8A8AD9E3 (id_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE special_place (id INT AUTO_INCREMENT NOT NULL, id_room_id INT NOT NULL, place VARCHAR(255) NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_D823FD8C8A8AD9E3 (id_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_seats (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date_add DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city_film ADD CONSTRAINT FK_610A834A8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_film ADD CONSTRAINT FK_610A834A567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE damaged_place ADD CONSTRAINT FK_47BB56EA8A8AD9E3 FOREIGN KEY (id_room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955634CC6B3 FOREIGN KEY (id_seance_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE reservation_details ADD CONSTRAINT FK_15B3B00F85542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B5531CBDF FOREIGN KEY (id_city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BD629F605 FOREIGN KEY (format_id) REFERENCES format (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B7CFC8404 FOREIGN KEY (type_seats_id) REFERENCES type_seats (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E88E2F8F3 FOREIGN KEY (id_film_id) REFERENCES film (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E8A8AD9E3 FOREIGN KEY (id_room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE special_place ADD CONSTRAINT FK_D823FD8C8A8AD9E3 FOREIGN KEY (id_room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE film ADD rating_id INT NOT NULL');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE22A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('CREATE INDEX IDX_8244BE22A32EFC6 ON film (rating_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE22A32EFC6');
        $this->addSql('ALTER TABLE city_film DROP FOREIGN KEY FK_610A834A8BAC62AF');
        $this->addSql('ALTER TABLE city_film DROP FOREIGN KEY FK_610A834A567F5183');
        $this->addSql('ALTER TABLE damaged_place DROP FOREIGN KEY FK_47BB56EA8A8AD9E3');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955634CC6B3');
        $this->addSql('ALTER TABLE reservation_details DROP FOREIGN KEY FK_15B3B00F85542AE1');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B5531CBDF');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519BD629F605');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B7CFC8404');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E88E2F8F3');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E8A8AD9E3');
        $this->addSql('ALTER TABLE special_place DROP FOREIGN KEY FK_D823FD8C8A8AD9E3');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_film');
        $this->addSql('DROP TABLE damaged_place');
        $this->addSql('DROP TABLE format');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_details');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE special_place');
        $this->addSql('DROP TABLE type_seats');
        $this->addSql('DROP INDEX IDX_8244BE22A32EFC6 ON film');
        $this->addSql('ALTER TABLE film DROP rating_id');
    }
}
