<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626140327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, musique_id INT DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, url VARCHAR(255) NOT NULL, prog VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, price INT NOT NULL, INDEX IDX_3BAE0AA725E254A1 (musique_id), UNIQUE INDEX UNIQ_3BAE0AA7EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_location (event_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_1872601B71F7E88B (event_id), INDEX IDX_1872601B64D218E (location_id), PRIMARY KEY(event_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, departement VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, ext VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE musique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA725E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_location ADD CONSTRAINT FK_1872601B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_location ADD CONSTRAINT FK_1872601B64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime ADD event_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime ADD CONSTRAINT FK_8533FE871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8533FE871F7E88B ON aime (event_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD musique_id INT DEFAULT NULL, ADD media_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD CONSTRAINT FK_9C07354F25E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD CONSTRAINT FK_9C07354FEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9C07354F25E254A1 ON artiste (musique_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9C07354FEA9FDD75 ON artiste (media_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD event_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C71F7E88B ON comment (event_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE aime DROP FOREIGN KEY FK_8533FE871F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP FOREIGN KEY FK_9C07354FEA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP FOREIGN KEY FK_9C07354F25E254A1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA725E254A1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_location DROP FOREIGN KEY FK_1872601B71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_location DROP FOREIGN KEY FK_1872601B64D218E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE musique
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8533FE871F7E88B ON aime
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime DROP event_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9C07354F25E254A1 ON artiste
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_9C07354FEA9FDD75 ON artiste
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP musique_id, DROP media_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9474526C71F7E88B ON comment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP event_id
        SQL);
    }
}
