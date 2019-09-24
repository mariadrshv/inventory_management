<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190812142143
 * @package DoctrineMigrations
 */
final class Version20190812142143 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'CREATE TABLE applications (
                id INT AUTO_INCREMENT NOT NULL, 
                assignee_id INT NOT NULL, 
                property_id INT NOT NULL, 
                room_id INT NOT NULL, 
                item_id INT NOT NULL, 
                status VARCHAR(255) NOT NULL, 
                property_address VARCHAR(255) NOT NULL, 
                notes VARCHAR(255) DEFAULT NULL, 
                creation_date DATE NOT NULL, 
                update_date DATE DEFAULT NULL, 
                INDEX IDX_F7C966F059EC7D60 (assignee_id), 
                INDEX IDX_F7C966F0549213EC (property_id), 
                INDEX IDX_F7C966F054177093 (room_id), 
                INDEX IDX_F7C966F0126F525E (item_id), 
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F059EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F054177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE applications');
    }
}
