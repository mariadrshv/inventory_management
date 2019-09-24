<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190912073951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE maintenance_photos (maintenance_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_228BD6D0F6C202BC (maintenance_id), INDEX IDX_228BD6D0301EC62 (photos_id), PRIMARY KEY(maintenance_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE maintenance_photos ADD CONSTRAINT FK_228BD6D0F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maintenance_photos ADD CONSTRAINT FK_228BD6D0301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD make VARCHAR(255) DEFAULT NULL, ADD year INT DEFAULT NULL, ADD vin VARCHAR(255) DEFAULT NULL, ADD exterior_color VARCHAR(255) DEFAULT NULL, ADD interior_color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requests DROP FOREIGN KEY FK_7B85D65159EC7D60');
        $this->addSql('DROP INDEX IDX_7B85D65159EC7D60 ON requests');
        $this->addSql('ALTER TABLE requests ADD visit_date_and_time DATETIME DEFAULT NULL, DROP assignee_id, DROP status, DROP creation_date, DROP update_date, CHANGE property_address type_of_request enum(\'Replace\',\'Service\')');
        $this->addSql('ALTER TABLE technician CHANGE address email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE warranties ADD line1 VARCHAR(255) DEFAULT NULL, ADD line2 VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, ADD zip VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE maintenance_photos');
        $this->addSql('ALTER TABLE item DROP make, DROP year, DROP vin, DROP exterior_color, DROP interior_color');
        $this->addSql('ALTER TABLE requests ADD assignee_id INT NOT NULL, ADD status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD creation_date DATE NOT NULL, ADD update_date DATE DEFAULT NULL, DROP visit_date_and_time, CHANGE type_of_request property_address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D65159EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7B85D65159EC7D60 ON requests (assignee_id)');
        $this->addSql('ALTER TABLE technician CHANGE email address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE warranties DROP line1, DROP line2, DROP city, DROP state, DROP zip');
    }
}
