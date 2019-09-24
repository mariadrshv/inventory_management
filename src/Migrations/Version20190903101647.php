<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903101647 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE maintenance ADD property_id INT DEFAULT NULL, ADD room_id INT DEFAULT NULL, ADD item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E954177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9549213EC ON maintenance (property_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E954177093 ON maintenance (room_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9126F525E ON maintenance (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9549213EC');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E954177093');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9126F525E');
        $this->addSql('DROP INDEX IDX_2F84F8E9549213EC ON maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E954177093 ON maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E9126F525E ON maintenance');
        $this->addSql('ALTER TABLE maintenance DROP property_id, DROP room_id, DROP item_id');
    }
}
