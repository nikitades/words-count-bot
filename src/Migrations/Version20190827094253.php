<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827094253 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E821A9A7125');
        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82E357438D');
        $this->addSql('DROP INDEX IDX_92EC8E821A9A7125 ON word_used_times');
        $this->addSql('DROP INDEX IDX_92EC8E82E357438D ON word_used_times');
        $this->addSql('DROP INDEX unique_used_times_counter ON word_used_times');
        $this->addSql('ALTER TABLE word_used_times ADD telegram_id INT NOT NULL, ADD word_text INT NOT NULL, DROP chat_id, DROP word_id');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82CC0B3066 FOREIGN KEY (telegram_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82331EA42A FOREIGN KEY (word_text) REFERENCES word (id)');
        $this->addSql('CREATE INDEX IDX_92EC8E82CC0B3066 ON word_used_times (telegram_id)');
        $this->addSql('CREATE INDEX IDX_92EC8E82331EA42A ON word_used_times (word_text)');
        $this->addSql('CREATE UNIQUE INDEX unique_used_times_counter ON word_used_times (word_text, telegram_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82CC0B3066');
        $this->addSql('ALTER TABLE word_used_times DROP FOREIGN KEY FK_92EC8E82331EA42A');
        $this->addSql('DROP INDEX IDX_92EC8E82CC0B3066 ON word_used_times');
        $this->addSql('DROP INDEX IDX_92EC8E82331EA42A ON word_used_times');
        $this->addSql('DROP INDEX unique_used_times_counter ON word_used_times');
        $this->addSql('ALTER TABLE word_used_times ADD chat_id INT NOT NULL, ADD word_id INT NOT NULL, DROP telegram_id, DROP word_text');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E821A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE word_used_times ADD CONSTRAINT FK_92EC8E82E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('CREATE INDEX IDX_92EC8E821A9A7125 ON word_used_times (chat_id)');
        $this->addSql('CREATE INDEX IDX_92EC8E82E357438D ON word_used_times (word_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_used_times_counter ON word_used_times (word_id, chat_id)');
    }
}
