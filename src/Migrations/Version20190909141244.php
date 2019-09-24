<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909141244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE warranties_photos (warranties_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_96367BFC270BC407 (warranties_id), INDEX IDX_96367BFC301EC62 (photos_id), PRIMARY KEY(warranties_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE warranties_photos ADD CONSTRAINT FK_96367BFC270BC407 FOREIGN KEY (warranties_id) REFERENCES warranties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE warranties_photos ADD CONSTRAINT FK_96367BFC301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE warranties_photos');
    }
}
