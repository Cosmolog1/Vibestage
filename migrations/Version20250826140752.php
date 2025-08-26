<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250826140752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees ADD CONSTRAINT FK_FE8960BC21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees ADD CONSTRAINT FK_FE8960BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime ADD CONSTRAINT FK_8533FE871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime ADD CONSTRAINT FK_8533FE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste CHANGE country country VARCHAR(255) NOT NULL, CHANGE nb_abonne nb_abonne VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD CONSTRAINT FK_9C07354F25E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste ADD CONSTRAINT FK_9C07354FEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees DROP FOREIGN KEY FK_FE8960BC21D25844
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnees DROP FOREIGN KEY FK_FE8960BCA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime DROP FOREIGN KEY FK_8533FE871F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aime DROP FOREIGN KEY FK_8533FE8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP FOREIGN KEY FK_9C07354F25E254A1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste DROP FOREIGN KEY FK_9C07354FEA9FDD75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artiste CHANGE country country DATE NOT NULL, CHANGE nb_abonne nb_abonne INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL
        SQL);
    }
}
