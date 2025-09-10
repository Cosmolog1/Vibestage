<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250909122451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD artiste_id INT DEFAULT NULL, DROP note
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C21D25844 ON comment (artiste_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C21D25844
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9474526C21D25844 ON comment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD note INT NOT NULL, DROP artiste_id
        SQL);
    }
}
