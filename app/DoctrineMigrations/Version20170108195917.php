<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170108195917 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ARCHIVE (id INT AUTO_INCREMENT NOT NULL, radio_station_id INT DEFAULT NULL, radio_show_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, duration VARCHAR(255) DEFAULT NULL, bitrate VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, local_file VARCHAR(255) NOT NULL, real_file_path VARCHAR(255) DEFAULT NULL, s3file VARCHAR(255) DEFAULT NULL, error LONGTEXT DEFAULT NULL, is_uploaded_to_s3 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uploaded_to_s3at DATETIME DEFAULT NULL, expire_at DATETIME DEFAULT NULL, INDEX IDX_EAB9A38620189378 (radio_station_id), INDEX IDX_EAB9A386E9A5E92F (radio_show_id), INDEX IDX_EAB9A3866BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ARCHIVE ADD CONSTRAINT FK_EAB9A38620189378 FOREIGN KEY (radio_station_id) REFERENCES RADIO_STATION (id)');
        $this->addSql('ALTER TABLE ARCHIVE ADD CONSTRAINT FK_EAB9A386E9A5E92F FOREIGN KEY (radio_show_id) REFERENCES RADIO_SHOW (id)');
        $this->addSql('ALTER TABLE ARCHIVE ADD CONSTRAINT FK_EAB9A3866BF700BD FOREIGN KEY (status_id) REFERENCES STATUS (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ARCHIVE');
        $this->addSql('DROP INDEX radio_stream_search_idx ON RADIO_STATION_STREAM');
    }
}
