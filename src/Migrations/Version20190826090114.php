<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826090114 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item ADD type ENUM("Appliance", "Electronic ", "Devices", "Household", "Auto") DEFAULT NULL, ADD width VARCHAR(255) DEFAULT NULL, ADD height VARCHAR(255) DEFAULT NULL, ADD depth VARCHAR(255) DEFAULT NULL, ADD serial_number VARCHAR(255) DEFAULT NULL, ADD model VARCHAR(255) NOT NULL, ADD string VARCHAR(255) DEFAULT NULL, ADD manufacturer VARCHAR(255) DEFAULT NULL, ADD purchase_date DATE DEFAULT NULL, ADD purchase_price NUMERIC(15, 2) DEFAULT NULL, ADD note VARCHAR(255) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP type, DROP width, DROP height, DROP depth, DROP serial_number, DROP model, DROP string, DROP manufacturer, DROP purchase_date, DROP purchase_price, DROP note, DROP photo');
    }
}
