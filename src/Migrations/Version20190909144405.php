<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909144405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('CREATE TABLE maintenance_photos (maintenance_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_228BD6D0F6C202BC (maintenance_id), INDEX IDX_228BD6D0301EC62 (photos_id), PRIMARY KEY(maintenance_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE maintenance_photos ADD CONSTRAINT FK_228BD6D0F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE maintenance_photos ADD CONSTRAINT FK_228BD6D0301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
//        $this->addSql('DROP TABLE files');
        $this->addSql('ALTER TABLE maintenance DROP photo');
        $this->addSql('ALTER TABLE room DROP photo');
        $this->addSql('ALTER TABLE property DROP photo_file_name');
        $this->addSql('ALTER TABLE item DROP photo');
        $this->addSql('ALTER TABLE warranties DROP photo');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_6354059549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
//        $this->addSql('DROP TABLE maintenance_photos');
        $this->addSql('ALTER TABLE item ADD photo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE maintenance ADD photo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE property ADD photo_file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE room ADD photo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE warranties ADD photo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
