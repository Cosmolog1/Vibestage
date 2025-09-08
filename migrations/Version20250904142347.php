<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904142347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, nb_abonne VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE artiste_category (artiste_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_4B8925D821D25844 (artiste_id), INDEX IDX_4B8925D812469DE2 (category_id), PRIMARY KEY(artiste_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATE NOT NULL, update_at DATE NOT NULL, content LONGTEXT NOT NULL, note INT NOT NULL, INDEX IDX_9474526C71F7E88B (event_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, url VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, date_end DATE NOT NULL, UNIQUE INDEX UNIQ_3BAE0AA7EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_location (event_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_1872601B71F7E88B (event_id), INDEX IDX_1872601B64D218E (location_id), PRIMARY KEY(event_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_artiste (event_id INT NOT NULL, artiste_id INT NOT NULL, INDEX IDX_19509CE071F7E88B (event_id), INDEX IDX_19509CE021D25844 (artiste_id), PRIMARY KEY(event_id, artiste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, artiste_id INT DEFAULT NULL, event_id INT DEFAULT NULL, created_at DATE DEFAULT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B321D25844 (artiste_id), INDEX IDX_AC6340B371F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, artiste_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C21D25844 (artiste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste_category ADD CONSTRAINT FK_4B8925D821D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste_category ADD CONSTRAINT FK_4B8925D812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
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
            ALTER TABLE event_artiste ADD CONSTRAINT FK_19509CE071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_artiste ADD CONSTRAINT FK_19509CE021D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B321D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste_category DROP FOREIGN KEY FK_4B8925D821D25844
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste_category DROP FOREIGN KEY FK_4B8925D812469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395
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
            ALTER TABLE event_artiste DROP FOREIGN KEY FK_19509CE071F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_artiste DROP FOREIGN KEY FK_19509CE021D25844
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B321D25844
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B371F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C21D25844
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE artiste
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE artiste_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_artiste
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `like`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE media
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
