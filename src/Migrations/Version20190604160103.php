<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190604160103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, acheteur_id INT NOT NULL, qte INT NOT NULL, date DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_26A984564CC8505A (offre_id), INDEX IDX_26A9845696A7BB5F (acheteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE achat_gros (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, acheteur_id INT NOT NULL, qte INT NOT NULL, date DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_669375A14CC8505A (offre_id), INDEX IDX_669375A196A7BB5F (acheteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collecteur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, addresse VARCHAR(255) NOT NULL, pays VARCHAR(255) DEFAULT NULL, solde INT DEFAULT NULL, points_fidelite INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, date_abonnement DATETIME NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_517B3AC2E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enchere (id INT AUTO_INCREMENT NOT NULL, offre_enchere_id INT NOT NULL, grossiste_acheteur_id INT NOT NULL, prix INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_38D1870FC55509DE (offre_enchere_id), INDEX IDX_38D1870FD2DE7F30 (grossiste_acheteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grossiste_acheteur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, addresse VARCHAR(255) NOT NULL, pays VARCHAR(255) DEFAULT NULL, solde INT DEFAULT NULL, points_fidelite INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, date_abonnement DATETIME NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_BCF65C9FE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grossiste_revendeur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, addresse VARCHAR(255) NOT NULL, pays VARCHAR(255) DEFAULT NULL, solde INT DEFAULT NULL, points_fidelite INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, date_abonnement DATETIME NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A94DCA45E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT DEFAULT NULL, date_envoi DATETIME NOT NULL, vu TINYINT(1) DEFAULT NULL, destinataire_id INT NOT NULL, expediteur_id INT NOT NULL, INDEX IDX_B6BD307FA4F84F6E (destinataire_id), INDEX IDX_B6BD307F10335F61 (expediteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_achat (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, produit_id INT NOT NULL, prix INT NOT NULL, avec_transport TINYINT(1) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, qte INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_718363CD76C50E4A (proprietaire_id), INDEX IDX_718363CDF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_achat_gros (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, produit_id INT NOT NULL, prix INT NOT NULL, avec_transport TINYINT(1) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, qte INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_4BCD128476C50E4A (proprietaire_id), INDEX IDX_4BCD1284F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_enchere (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, produit_id INT NOT NULL, prix_fin INT DEFAULT NULL, prix INT NOT NULL, avec_transport TINYINT(1) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, qte INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_940AF42F76C50E4A (proprietaire_id), INDEX IDX_940AF42FF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_vente (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, acheteur_id INT NOT NULL, produit_id INT NOT NULL, prix INT NOT NULL, avec_transport TINYINT(1) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, qte INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_DFA0CDD776C50E4A (proprietaire_id), INDEX IDX_DFA0CDD796A7BB5F (acheteur_id), INDEX IDX_DFA0CDD7F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, photos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reaction (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, destinataire_id INT NOT NULL, expediteur_id INT NOT NULL, INDEX IDX_A4D707F7A4F84F6E (destinataire_id), INDEX IDX_A4D707F710335F61 (expediteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, status TINYINT(1) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, acheteur_id INT NOT NULL, vendeur_id INT NOT NULL, INDEX IDX_723705D196A7BB5F (acheteur_id), INDEX IDX_723705D1858C065E (vendeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A984564CC8505A FOREIGN KEY (offre_id) REFERENCES offre_achat (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845696A7BB5F FOREIGN KEY (acheteur_id) REFERENCES collecteur (id)');
        $this->addSql('ALTER TABLE achat_gros ADD CONSTRAINT FK_669375A14CC8505A FOREIGN KEY (offre_id) REFERENCES offre_achat_gros (id)');
        $this->addSql('ALTER TABLE achat_gros ADD CONSTRAINT FK_669375A196A7BB5F FOREIGN KEY (acheteur_id) REFERENCES grossiste_revendeur (id)');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_38D1870FC55509DE FOREIGN KEY (offre_enchere_id) REFERENCES offre_enchere (id)');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_38D1870FD2DE7F30 FOREIGN KEY (grossiste_acheteur_id) REFERENCES grossiste_acheteur (id)');
        $this->addSql('ALTER TABLE offre_achat ADD CONSTRAINT FK_718363CD76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES grossiste_revendeur (id)');
        $this->addSql('ALTER TABLE offre_achat ADD CONSTRAINT FK_718363CDF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE offre_achat_gros ADD CONSTRAINT FK_4BCD128476C50E4A FOREIGN KEY (proprietaire_id) REFERENCES grossiste_acheteur (id)');
        $this->addSql('ALTER TABLE offre_achat_gros ADD CONSTRAINT FK_4BCD1284F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE offre_enchere ADD CONSTRAINT FK_940AF42F76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES grossiste_acheteur (id)');
        $this->addSql('ALTER TABLE offre_enchere ADD CONSTRAINT FK_940AF42FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE offre_vente ADD CONSTRAINT FK_DFA0CDD776C50E4A FOREIGN KEY (proprietaire_id) REFERENCES collecteur (id)');
        $this->addSql('ALTER TABLE offre_vente ADD CONSTRAINT FK_DFA0CDD796A7BB5F FOREIGN KEY (acheteur_id) REFERENCES grossiste_revendeur (id)');
        $this->addSql('ALTER TABLE offre_vente ADD CONSTRAINT FK_DFA0CDD7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845696A7BB5F');
        $this->addSql('ALTER TABLE offre_vente DROP FOREIGN KEY FK_DFA0CDD776C50E4A');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_38D1870FD2DE7F30');
        $this->addSql('ALTER TABLE offre_achat_gros DROP FOREIGN KEY FK_4BCD128476C50E4A');
        $this->addSql('ALTER TABLE offre_enchere DROP FOREIGN KEY FK_940AF42F76C50E4A');
        $this->addSql('ALTER TABLE achat_gros DROP FOREIGN KEY FK_669375A196A7BB5F');
        $this->addSql('ALTER TABLE offre_achat DROP FOREIGN KEY FK_718363CD76C50E4A');
        $this->addSql('ALTER TABLE offre_vente DROP FOREIGN KEY FK_DFA0CDD796A7BB5F');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A984564CC8505A');
        $this->addSql('ALTER TABLE achat_gros DROP FOREIGN KEY FK_669375A14CC8505A');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_38D1870FC55509DE');
        $this->addSql('ALTER TABLE offre_achat DROP FOREIGN KEY FK_718363CDF347EFB');
        $this->addSql('ALTER TABLE offre_achat_gros DROP FOREIGN KEY FK_4BCD1284F347EFB');
        $this->addSql('ALTER TABLE offre_enchere DROP FOREIGN KEY FK_940AF42FF347EFB');
        $this->addSql('ALTER TABLE offre_vente DROP FOREIGN KEY FK_DFA0CDD7F347EFB');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE achat_gros');
        $this->addSql('DROP TABLE collecteur');
        $this->addSql('DROP TABLE enchere');
        $this->addSql('DROP TABLE grossiste_acheteur');
        $this->addSql('DROP TABLE grossiste_revendeur');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE offre_achat');
        $this->addSql('DROP TABLE offre_achat_gros');
        $this->addSql('DROP TABLE offre_enchere');
        $this->addSql('DROP TABLE offre_vente');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('DROP TABLE transaction');
    }
}
