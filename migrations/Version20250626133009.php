<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626133009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees ADD artiste_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees ADD CONSTRAINT FK_FE8960BC21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FE8960BC21D25844 ON abonnees (artiste_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees DROP FOREIGN KEY FK_FE8960BC21D25844
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FE8960BC21D25844 ON abonnees
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees DROP artiste_id
        SQL);
    }
}
