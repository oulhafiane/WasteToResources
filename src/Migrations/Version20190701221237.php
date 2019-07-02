<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701221237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gain ADD offer_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP user, DROP offer');
        $this->addSql('ALTER TABLE gain ADD CONSTRAINT FK_D0952D0053C674EEA76ED395 FOREIGN KEY (offer_id, user_id) REFERENCES on_hold (offer_id, user_id)');
        $this->addSql('CREATE UNIQUE INDEX fromOnHold_uniq ON gain (offer_id, user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gain DROP FOREIGN KEY FK_D0952D0053C674EEA76ED395');
        $this->addSql('DROP INDEX fromOnHold_uniq ON gain');
        $this->addSql('ALTER TABLE gain ADD user INT DEFAULT NULL, ADD offer INT DEFAULT NULL, DROP offer_id, DROP user_id');
    }
}
