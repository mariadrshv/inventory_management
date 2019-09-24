<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190821133209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE warranties ADD property_id INT DEFAULT NULL, ADD room_id INT DEFAULT NULL, ADD item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE warranties ADD CONSTRAINT FK_BEF2594C549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE warranties ADD CONSTRAINT FK_BEF2594C54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE warranties ADD CONSTRAINT FK_BEF2594C126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_BEF2594C549213EC ON warranties (property_id)');
        $this->addSql('CREATE INDEX IDX_BEF2594C54177093 ON warranties (room_id)');
        $this->addSql('CREATE INDEX IDX_BEF2594C126F525E ON warranties (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE warranties DROP FOREIGN KEY FK_BEF2594C549213EC');
        $this->addSql('ALTER TABLE warranties DROP FOREIGN KEY FK_BEF2594C54177093');
        $this->addSql('ALTER TABLE warranties DROP FOREIGN KEY FK_BEF2594C126F525E');
        $this->addSql('DROP INDEX IDX_BEF2594C549213EC ON warranties');
        $this->addSql('DROP INDEX IDX_BEF2594C54177093 ON warranties');
        $this->addSql('DROP INDEX IDX_BEF2594C126F525E ON warranties');
        $this->addSql('ALTER TABLE warranties DROP property_id, DROP room_id, DROP item_id');
    }
}
