<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902071510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE event_artiste (event_id INT NOT NULL, artiste_id INT NOT NULL, INDEX IDX_19509CE071F7E88B (event_id), INDEX IDX_19509CE021D25844 (artiste_id), PRIMARY KEY(event_id, artiste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_artiste ADD CONSTRAINT FK_19509CE071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_artiste ADD CONSTRAINT FK_19509CE021D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP FOREIGN KEY FK_9C07354F71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9C07354F71F7E88B ON artiste
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP event_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event_artiste DROP FOREIGN KEY FK_19509CE071F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_artiste DROP FOREIGN KEY FK_19509CE021D25844
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_artiste
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD event_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD CONSTRAINT FK_9C07354F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9C07354F71F7E88B ON artiste (event_id)
        SQL);
    }
}
