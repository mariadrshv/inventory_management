<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830140309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE applications');
        $this->addSql('ALTER TABLE item DROP string');
        $this->addSql('ALTER TABLE property DROP flat, DROP car, DROP country_house, DROP address');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE applications (id INT AUTO_INCREMENT NOT NULL, assignee_id INT NOT NULL, property_id INT NOT NULL, room_id INT NOT NULL, item_id INT NOT NULL, status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, property_address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, notes VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, creation_date DATE NOT NULL, update_date DATE DEFAULT NULL, INDEX IDX_F7C966F054177093 (room_id), INDEX IDX_F7C966F0549213EC (property_id), INDEX IDX_F7C966F059EC7D60 (assignee_id), INDEX IDX_F7C966F0126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F054177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F059EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item ADD string VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE property ADD flat VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD car VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD country_house VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD state2 VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
