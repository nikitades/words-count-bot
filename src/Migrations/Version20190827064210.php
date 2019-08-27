<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827064210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_659DF2AACC0B3066 ON chat (telegram_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3F175113B8BA7C7 ON word (text)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_659DF2AACC0B3066 ON chat');
        $this->addSql('ALTER TABLE chat_word ADD used_times INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_C3F175113B8BA7C7 ON word');
    }
}
