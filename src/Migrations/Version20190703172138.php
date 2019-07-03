<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190703172138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bulk_purchase DROP FOREIGN KEY FK_725A4FE53C674EE');
        $this->addSql('ALTER TABLE bulk_purchase DROP FOREIGN KEY FK_725A4FE8DE820D9');
        $this->addSql('DROP INDEX IDX_725A4FE8DE820D9 ON bulk_purchase');
        $this->addSql('DROP INDEX IDX_725A4FE53C674EE ON bulk_purchase');
        $this->addSql('ALTER TABLE bulk_purchase DROP offer_id, DROP seller_id, DROP weight, DROP date, DROP accepted, DROP price');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bulk_purchase ADD offer_id INT NOT NULL, ADD seller_id INT NOT NULL, ADD weight BIGINT NOT NULL, ADD date DATETIME NOT NULL, ADD accepted TINYINT(1) NOT NULL, ADD price BIGINT NOT NULL');
        $this->addSql('ALTER TABLE bulk_purchase ADD CONSTRAINT FK_725A4FE53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bulk_purchase ADD CONSTRAINT FK_725A4FE8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_725A4FE8DE820D9 ON bulk_purchase (seller_id)');
        $this->addSql('CREATE INDEX IDX_725A4FE53C674EE ON bulk_purchase (offer_id)');
    }
}
