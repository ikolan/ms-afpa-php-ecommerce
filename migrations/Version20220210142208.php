<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220210142208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1FCDAEAAA');
        $this->addSql('DROP INDEX IDX_9CE58EE1FCDAEAAA ON order_line');
        $this->addSql('ALTER TABLE order_line CHANGE order_id_id concerned_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE13ADDDF09 FOREIGN KEY (concerned_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE13ADDDF09 ON order_line (concerned_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE number number LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE street_name street_name LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE add_in add_in LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `order` CHANGE reference reference VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE shipper_name shipper_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE shipping_address shipping_address LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE payment_address payment_address LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE13ADDDF09');
        $this->addSql('DROP INDEX IDX_9CE58EE13ADDDF09 ON order_line');
        $this->addSql('ALTER TABLE order_line CHANGE product_name product_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE concerned_order_id order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9CE58EE1FCDAEAAA ON order_line (order_id_id)');
        $this->addSql('ALTER TABLE product CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE shipper CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE visualisation CHANGE path path VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE legend legend VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
