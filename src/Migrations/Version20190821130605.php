<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190821130605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE warranties (id INT AUTO_INCREMENT NOT NULL, exp_date DATE DEFAULT NULL, provider_name VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, notes VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE requests');
        $this->addSql('ALTER TABLE property DROP photo_filename');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE requests (id INT AUTO_INCREMENT NOT NULL, assignee_id INT NOT NULL, property_id INT NOT NULL, room_id INT NOT NULL, item_id INT NOT NULL, status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, property_address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, notes VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, creation_date DATE NOT NULL, update_date DATE DEFAULT NULL, INDEX IDX_7B85D65154177093 (room_id), INDEX IDX_7B85D651549213EC (property_id), INDEX IDX_7B85D65159EC7D60 (assignee_id), INDEX IDX_7B85D651126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D651126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D65154177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D651549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE requests ADD CONSTRAINT FK_7B85D65159EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE warranties');
        $this->addSql('ALTER TABLE property ADD photo_filename VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
