<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827094623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82331EA42A');
        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82CC0B3066');
        $this->addSql('ALTER TABLE word_used_times CHANGE word_text word_text VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82331EA42A FOREIGN KEY (word_text) REFERENCES word (text)');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82CC0B3066 FOREIGN KEY (telegram_id) REFERENCES chat (telegram_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82CC0B3066');
        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82331EA42A');
        $this->addSql('ALTER TABLE word_used_times CHANGE word_text word_text INT NOT NULL');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82CC0B3066 FOREIGN KEY (telegram_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82331EA42A FOREIGN KEY (word_text) REFERENCES word (id)');
    }
}
