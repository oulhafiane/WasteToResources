<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190622194551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, owner_id INT NOT NULL, buyer_id INT DEFAULT NULL, title VARCHAR(25) NOT NULL, description LONGTEXT NOT NULL, price INT NOT NULL, with_transport TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, weight BIGINT NOT NULL, locations LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', keywords LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', is_active TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, end_price BIGINT DEFAULT NULL, INDEX IDX_29D6873E12469DE2 (category_id), INDEX IDX_29D6873E7E3C61F9 (owner_id), INDEX IDX_29D6873E6C755722 (buyer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, bidder_id INT NOT NULL, on_hold_id INT NOT NULL, price BIGINT NOT NULL, date DATETIME NOT NULL, INDEX IDX_4AF2B3F353C674EE (offer_id), INDEX IDX_4AF2B3F3BE40AFAE (bidder_id), UNIQUE INDEX UNIQ_4AF2B3F3EBBCD5E0 (on_hold_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bulk_purchase (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, seller_id INT NOT NULL, weight BIGINT NOT NULL, date DATETIME NOT NULL, accepted TINYINT(1) NOT NULL, INDEX IDX_725A4FE53C674EE (offer_id), INDEX IDX_725A4FE8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, city VARCHAR(50) NOT NULL, address LONGTEXT NOT NULL, country VARCHAR(50) NOT NULL, phone VARCHAR(20) NOT NULL, balance BIGINT NOT NULL, loyalty_points INT NOT NULL, picture VARCHAR(255) DEFAULT NULL, subscription_date DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, sender_id INT NOT NULL, text LONGTEXT NOT NULL, date DATETIME NOT NULL, rate INT DEFAULT NULL, INDEX IDX_D2294458CD53EDB6 (receiver_id), INDEX IDX_D2294458F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gain (id INT AUTO_INCREMENT NOT NULL, gain_from_id INT NOT NULL, date DATETIME NOT NULL, total BIGINT NOT NULL, UNIQUE INDEX UNIQ_D0952D0066B29883 (gain_from_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, sender_id INT NOT NULL, text LONGTEXT NOT NULL, date DATETIME NOT NULL, seen TINYINT(1) NOT NULL, INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE on_hold (id INT AUTO_INCREMENT NOT NULL, offer_id INT DEFAULT NULL, bid_id INT DEFAULT NULL, paid TINYINT(1) DEFAULT NULL, refunded TINYINT(1) DEFAULT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_56A1075953C674EE (offer_id), UNIQUE INDEX UNIQ_56A107594D9866B8 (bid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, offer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, size INT NOT NULL, link VARCHAR(255) NOT NULL, upload_at DATETIME DEFAULT NULL, INDEX IDX_14B7841853C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, seller_id INT NOT NULL, weight BIGINT NOT NULL, date DATETIME NOT NULL, accepted TINYINT(1) NOT NULL, INDEX IDX_6117D13B53C674EE (offer_id), INDEX IDX_6117D13B8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, buyer_id INT NOT NULL, seller_id INT NOT NULL, offer_id INT NOT NULL, completed TINYINT(1) DEFAULT NULL, canceled TINYINT(1) DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, total BIGINT NOT NULL, seller_key CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', buyer_key CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_723705D11EC6416A (seller_key), UNIQUE INDEX UNIQ_723705D13448F789 (buyer_key), INDEX IDX_723705D16C755722 (buyer_id), INDEX IDX_723705D18DE820D9 (seller_id), INDEX IDX_723705D153C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E6C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3BE40AFAE FOREIGN KEY (bidder_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3EBBCD5E0 FOREIGN KEY (on_hold_id) REFERENCES on_hold (id)');
        $this->addSql('ALTER TABLE bulk_purchase ADD CONSTRAINT FK_725A4FE53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE bulk_purchase ADD CONSTRAINT FK_725A4FE8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gain ADD CONSTRAINT FK_D0952D0066B29883 FOREIGN KEY (gain_from_id) REFERENCES on_hold (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE on_hold ADD CONSTRAINT FK_56A1075953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE on_hold ADD CONSTRAINT FK_56A107594D9866B8 FOREIGN KEY (bid_id) REFERENCES bid (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D18DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D153C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F353C674EE');
        $this->addSql('ALTER TABLE bulk_purchase DROP FOREIGN KEY FK_725A4FE53C674EE');
        $this->addSql('ALTER TABLE on_hold DROP FOREIGN KEY FK_56A1075953C674EE');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841853C674EE');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B53C674EE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D153C674EE');
        $this->addSql('ALTER TABLE on_hold DROP FOREIGN KEY FK_56A107594D9866B8');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E7E3C61F9');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E6C755722');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3BE40AFAE');
        $this->addSql('ALTER TABLE bulk_purchase DROP FOREIGN KEY FK_725A4FE8DE820D9');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458CD53EDB6');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458F624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B8DE820D9');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D16C755722');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D18DE820D9');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E12469DE2');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3EBBCD5E0');
        $this->addSql('ALTER TABLE gain DROP FOREIGN KEY FK_D0952D0066B29883');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE bulk_purchase');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE gain');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE on_hold');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE transaction');
    }
}
