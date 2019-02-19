<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190213174047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_utilisateur DROP FOREIGN KEY FK_FD743B85FB88E14F');
        $this->addSql('DROP TABLE item_utilisateur');
        $this->addSql('DROP TABLE utilisateur');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_utilisateur (item_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_FD743B85FB88E14F (utilisateur_id), INDEX IDX_FD743B85126F525E (item_id), PRIMARY KEY(item_id, utilisateur_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, prenom VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, nom VARCHAR(70) DEFAULT NULL COLLATE utf8mb4_unicode_ci, pseudo VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, slug VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_utilisateur ADD CONSTRAINT FK_FD743B85126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_utilisateur ADD CONSTRAINT FK_FD743B85FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
