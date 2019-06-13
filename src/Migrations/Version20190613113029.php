<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190613113029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auction_bid (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type_offer_id INT NOT NULL, end_price BIGINT NOT NULL, price INT NOT NULL, with_transport TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, weight BIGINT NOT NULL, creation_date DATETIME NOT NULL, pictures LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', locations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_401A9C437E3C61F9 (owner_id), INDEX IDX_401A9C439E395312 (type_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, bidder_id INT NOT NULL, price BIGINT NOT NULL, date DATETIME NOT NULL, INDEX IDX_4AF2B3F353C674EE (offer_id), INDEX IDX_4AF2B3F3BE40AFAE (bidder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bulk_purchase (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, seller_id INT NOT NULL, weight BIGINT NOT NULL, date DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_725A4FE53C674EE (offer_id), INDEX IDX_725A4FE8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bulk_purchase_offer (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type_offer_id INT NOT NULL, price INT NOT NULL, with_transport TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, weight BIGINT NOT NULL, creation_date DATETIME NOT NULL, pictures LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', locations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_9B7263147E3C61F9 (owner_id), INDEX IDX_9B7263149E395312 (type_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, city VARCHAR(50) NOT NULL, address LONGTEXT NOT NULL, country VARCHAR(50) NOT NULL, phone VARCHAR(20) NOT NULL, balance BIGINT NOT NULL, loyalty_points INT NOT NULL, picture VARCHAR(255) DEFAULT NULL, subscription_date DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, sender_id INT NOT NULL, text LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_D2294458CD53EDB6 (receiver_id), INDEX IDX_D2294458F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, sender_id INT NOT NULL, text LONGTEXT NOT NULL, date DATETIME NOT NULL, seen TINYINT(1) NOT NULL, INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, seller_id INT NOT NULL, weight BIGINT NOT NULL, date DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_6117D13B53C674EE (offer_id), INDEX IDX_6117D13B8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_offer (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type_offer_id INT NOT NULL, price INT NOT NULL, with_transport TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, weight BIGINT NOT NULL, creation_date DATETIME NOT NULL, pictures LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', locations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_FD1D04147E3C61F9 (owner_id), INDEX IDX_FD1D04149E395312 (type_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale_offer (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, buyer_id INT NOT NULL, type_offer_id INT NOT NULL, price INT NOT NULL, with_transport TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, weight BIGINT NOT NULL, creation_date DATETIME NOT NULL, pictures LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', locations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_F90ADFBD7E3C61F9 (owner_id), INDEX IDX_F90ADFBD6C755722 (buyer_id), INDEX IDX_F90ADFBD9E395312 (type_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, buyer_id INT NOT NULL, seller_id INT NOT NULL, status TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, total BIGINT NOT NULL, seller_key CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', buyer_key CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', offer_id INT NOT NULL, UNIQUE INDEX UNIQ_723705D11EC6416A (seller_key), UNIQUE INDEX UNIQ_723705D13448F789 (buyer_key), INDEX IDX_723705D16C755722 (buyer_id), INDEX IDX_723705D18DE820D9 (seller_id), INDEX IDX_723705D153C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_offer (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auction_bid ADD CONSTRAINT FK_401A9C437E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE auction_bid ADD CONSTRAINT FK_401A9C439E395312 FOREIGN KEY (type_offer_id) REFERENCES type_offer (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F353C674EE FOREIGN KEY (offer_id) REFERENCES auction_bid (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3BE40AFAE FOREIGN KEY (bidder_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bulk_purchase ADD CONSTRAINT FK_725A4FE53C674EE FOREIGN KEY (offer_id) REFERENCES bulk_purchase_offer (id)');
        $this->addSql('ALTER TABLE bulk_purchase ADD CONSTRAINT FK_725A4FE8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bulk_purchase_offer ADD CONSTRAINT FK_9B7263147E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bulk_purchase_offer ADD CONSTRAINT FK_9B7263149E395312 FOREIGN KEY (type_offer_id) REFERENCES type_offer (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B53C674EE FOREIGN KEY (offer_id) REFERENCES purchase_offer (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchase_offer ADD CONSTRAINT FK_FD1D04147E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchase_offer ADD CONSTRAINT FK_FD1D04149E395312 FOREIGN KEY (type_offer_id) REFERENCES type_offer (id)');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBD7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBD6C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBD9E395312 FOREIGN KEY (type_offer_id) REFERENCES type_offer (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D18DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F353C674EE');
        $this->addSql('ALTER TABLE bulk_purchase DROP FOREIGN KEY FK_725A4FE53C674EE');
        $this->addSql('ALTER TABLE auction_bid DROP FOREIGN KEY FK_401A9C437E3C61F9');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3BE40AFAE');
        $this->addSql('ALTER TABLE bulk_purchase DROP FOREIGN KEY FK_725A4FE8DE820D9');
        $this->addSql('ALTER TABLE bulk_purchase_offer DROP FOREIGN KEY FK_9B7263147E3C61F9');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458CD53EDB6');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458F624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B8DE820D9');
        $this->addSql('ALTER TABLE purchase_offer DROP FOREIGN KEY FK_FD1D04147E3C61F9');
        $this->addSql('ALTER TABLE sale_offer DROP FOREIGN KEY FK_F90ADFBD7E3C61F9');
        $this->addSql('ALTER TABLE sale_offer DROP FOREIGN KEY FK_F90ADFBD6C755722');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D16C755722');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D18DE820D9');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B53C674EE');
        $this->addSql('ALTER TABLE auction_bid DROP FOREIGN KEY FK_401A9C439E395312');
        $this->addSql('ALTER TABLE bulk_purchase_offer DROP FOREIGN KEY FK_9B7263149E395312');
        $this->addSql('ALTER TABLE purchase_offer DROP FOREIGN KEY FK_FD1D04149E395312');
        $this->addSql('ALTER TABLE sale_offer DROP FOREIGN KEY FK_F90ADFBD9E395312');
        $this->addSql('DROP TABLE auction_bid');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE bulk_purchase');
        $this->addSql('DROP TABLE bulk_purchase_offer');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE purchase_offer');
        $this->addSql('DROP TABLE sale_offer');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE type_offer');
    }
}
