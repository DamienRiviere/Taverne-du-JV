<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211101251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, hash VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sub_category_article DROP FOREIGN KEY FK_6E29C8317294869C');
        $this->addSql('ALTER TABLE sub_category_article DROP FOREIGN KEY FK_6E29C831F7BFE87C');
        $this->addSql('ALTER TABLE sub_category_article DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE sub_category_article ADD CONSTRAINT FK_6E29C8317294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE sub_category_article ADD CONSTRAINT FK_6E29C831F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE sub_category_article ADD PRIMARY KEY (article_id, sub_category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE sub_category_article DROP FOREIGN KEY FK_6E29C8317294869C');
        $this->addSql('ALTER TABLE sub_category_article DROP FOREIGN KEY FK_6E29C831F7BFE87C');
        $this->addSql('ALTER TABLE sub_category_article DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE sub_category_article ADD CONSTRAINT FK_6E29C8317294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sub_category_article ADD CONSTRAINT FK_6E29C831F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sub_category_article ADD PRIMARY KEY (sub_category_id, article_id)');
    }
}
