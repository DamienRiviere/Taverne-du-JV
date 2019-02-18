<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190218094423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_article ADD moderation_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_article ADD CONSTRAINT FK_F1496C7667FDB807 FOREIGN KEY (moderation_id) REFERENCES moderation (id)');
        $this->addSql('CREATE INDEX IDX_F1496C7667FDB807 ON comment_article (moderation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_article DROP FOREIGN KEY FK_F1496C7667FDB807');
        $this->addSql('DROP INDEX IDX_F1496C7667FDB807 ON comment_article');
        $this->addSql('ALTER TABLE comment_article DROP moderation_id');
    }
}
