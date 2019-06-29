<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190629003419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3EBBCD5E0');
        $this->addSql('DROP INDEX FK_4AF2B3F3EBBCD5E0 ON bid');
        $this->addSql('ALTER TABLE bid DROP on_hold_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid ADD on_hold_id INT NOT NULL');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3EBBCD5E0 FOREIGN KEY (on_hold_id) REFERENCES on_hold (id)');
        $this->addSql('CREATE INDEX FK_4AF2B3F3EBBCD5E0 ON bid (on_hold_id)');
    }
}
