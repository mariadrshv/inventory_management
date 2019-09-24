<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909142538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE technician ADD photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE technician ADD CONSTRAINT FK_F244E9487E9E4C8C FOREIGN KEY (photo_id) REFERENCES photos (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F244E9487E9E4C8C ON technician (photo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE technician DROP FOREIGN KEY FK_F244E9487E9E4C8C');
        $this->addSql('DROP INDEX UNIQ_F244E9487E9E4C8C ON technician');
        $this->addSql('ALTER TABLE technician DROP photo_id');
    }
}
