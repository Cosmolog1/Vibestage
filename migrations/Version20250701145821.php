<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701145821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees ADD CONSTRAINT FK_FE8960BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FE8960BCA76ED395 ON abonnees (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime ADD CONSTRAINT FK_8533FE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8533FE8A76ED395 ON aime (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees DROP FOREIGN KEY FK_FE8960BCA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime DROP FOREIGN KEY FK_8533FE8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FE8960BCA76ED395 ON abonnees
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8533FE8A76ED395 ON aime
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9474526CA76ED395 ON comment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP user_id
        SQL);
    }
}
