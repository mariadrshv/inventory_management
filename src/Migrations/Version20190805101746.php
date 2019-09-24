<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190805101746
 * @package DoctrineMigrations
 */
final class Version20190805101746 extends AbstractMigration
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
            'CREATE TABLE property (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id_id INT NOT NULL, 
                flat VARCHAR(255) DEFAULT NULL, 
                car VARCHAR(255) DEFAULT NULL, 
                country_house VARCHAR(255) DEFAULT NULL, 
                INDEX IDX_8BF21CDE9D86650F (user_id_id), 
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql(
            'ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
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

        $this->addSql('DROP TABLE property');
    }
}
