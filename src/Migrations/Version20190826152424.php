<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826152424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, telegram_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_word (chat_id INT NOT NULL, word_id INT NOT NULL, used_times INT, INDEX IDX_656DE8501A9A7125 (chat_id), INDEX IDX_656DE850E357438D (word_id), PRIMARY KEY(chat_id, word_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_word ADD CONSTRAINT FK_656DE8501A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_word ADD CONSTRAINT FK_656DE850E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_word DROP FOREIGN KEY FK_656DE8501A9A7125');
        $this->addSql('ALTER TABLE chat_word DROP FOREIGN KEY FK_656DE850E357438D');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_word');
        $this->addSql('DROP TABLE word');
    }
}
