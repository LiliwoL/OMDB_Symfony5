<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617180732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_39DA19FB8F93B6FC');
        $this->addSql('DROP INDEX IDX_39DA19FB10DAF24A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__actor_movie AS SELECT actor_id, movie_id FROM actor_movie');
        $this->addSql('DROP TABLE actor_movie');
        $this->addSql('CREATE TABLE actor_movie (actor_id INTEGER NOT NULL, movie_id INTEGER NOT NULL, PRIMARY KEY(actor_id, movie_id), CONSTRAINT FK_39DA19FB10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_39DA19FB8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO actor_movie (actor_id, movie_id) SELECT actor_id, movie_id FROM __temp__actor_movie');
        $this->addSql('DROP TABLE __temp__actor_movie');
        $this->addSql('CREATE INDEX IDX_39DA19FB8F93B6FC ON actor_movie (movie_id)');
        $this->addSql('CREATE INDEX IDX_39DA19FB10DAF24A ON actor_movie (actor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_39DA19FB10DAF24A');
        $this->addSql('DROP INDEX IDX_39DA19FB8F93B6FC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__actor_movie AS SELECT actor_id, movie_id FROM actor_movie');
        $this->addSql('DROP TABLE actor_movie');
        $this->addSql('CREATE TABLE actor_movie (actor_id INTEGER NOT NULL, movie_id INTEGER NOT NULL, PRIMARY KEY(actor_id, movie_id))');
        $this->addSql('INSERT INTO actor_movie (actor_id, movie_id) SELECT actor_id, movie_id FROM __temp__actor_movie');
        $this->addSql('DROP TABLE __temp__actor_movie');
        $this->addSql('CREATE INDEX IDX_39DA19FB10DAF24A ON actor_movie (actor_id)');
        $this->addSql('CREATE INDEX IDX_39DA19FB8F93B6FC ON actor_movie (movie_id)');
    }
}
