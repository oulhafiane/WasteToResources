<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190808120934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1EBBCD5E0');
        $this->addSql('CREATE TABLE gain (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, user_id INT NOT NULL, paid TINYINT(1) NOT NULL, refunded TINYINT(1) NOT NULL, type SMALLINT NOT NULL, date DATETIME NOT NULL, fees DOUBLE PRECISION NOT NULL, INDEX IDX_D0952D0053C674EE (offer_id), INDEX IDX_D0952D00A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gain ADD CONSTRAINT FK_D0952D0053C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE gain ADD CONSTRAINT FK_D0952D00A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE on_hold');
        $this->addSql('DROP INDEX IDX_723705D1EBBCD5E0 ON transaction');
        $this->addSql('ALTER TABLE transaction CHANGE on_hold_id gain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1C60EF8C4 FOREIGN KEY (gain_id) REFERENCES gain (id)');
        $this->addSql('CREATE INDEX IDX_723705D1C60EF8C4 ON transaction (gain_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1C60EF8C4');
        $this->addSql('CREATE TABLE on_hold (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, user_id INT NOT NULL, paid TINYINT(1) NOT NULL, refunded TINYINT(1) NOT NULL, date DATETIME NOT NULL, fees DOUBLE PRECISION NOT NULL, type SMALLINT NOT NULL, INDEX IDX_56A10759A76ED395 (user_id), INDEX IDX_56A1075953C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE on_hold ADD CONSTRAINT FK_56A1075953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE on_hold ADD CONSTRAINT FK_56A10759A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE gain');
        $this->addSql('DROP INDEX IDX_723705D1C60EF8C4 ON transaction');
        $this->addSql('ALTER TABLE transaction CHANGE gain_id on_hold_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EBBCD5E0 FOREIGN KEY (on_hold_id) REFERENCES on_hold (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_723705D1EBBCD5E0 ON transaction (on_hold_id)');
    }
}
