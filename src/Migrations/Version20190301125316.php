<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190301125316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE destinations ADD display_type_id INT NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE destinations ADD CONSTRAINT FK_2D3343C3F2A36E08 FOREIGN KEY (display_type_id) REFERENCES display_type (id)');
        $this->addSql('CREATE INDEX IDX_2D3343C3F2A36E08 ON destinations (display_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE destinations DROP FOREIGN KEY FK_2D3343C3F2A36E08');
        $this->addSql('DROP INDEX IDX_2D3343C3F2A36E08 ON destinations');
        $this->addSql('ALTER TABLE destinations DROP display_type_id');
    }
}
