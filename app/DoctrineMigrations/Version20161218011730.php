<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161218011730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE PROMO (id INT AUTO_INCREMENT NOT NULL, radio_station_id INT DEFAULT NULL, radio_show_id INT DEFAULT NULL, status_id INT DEFAULT NULL, content_type_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, duration VARCHAR(255) DEFAULT NULL, bitrate VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, play_count INT DEFAULT NULL, local_file VARCHAR(255) NOT NULL, real_file_path VARCHAR(255) DEFAULT NULL, s3file VARCHAR(255) DEFAULT NULL, s3signature_file VARCHAR(255) DEFAULT NULL, is_uploaded_to_bucket TINYINT(1) NOT NULL, is_uploaded_to_s3 TINYINT(1) DEFAULT NULL, is_radio_promo TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uploaded_to_bucket_at DATETIME DEFAULT NULL, uploaded_to_s3at DATETIME DEFAULT NULL, expire_at DATETIME DEFAULT NULL, last_played_at DATETIME DEFAULT NULL, INDEX IDX_4747584B20189378 (radio_station_id), INDEX IDX_4747584BE9A5E92F (radio_show_id), INDEX IDX_4747584B6BF700BD (status_id), INDEX IDX_4747584B1A445520 (content_type_id), INDEX IDX_4747584BB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE PROMO ADD CONSTRAINT FK_4747584B20189378 FOREIGN KEY (radio_station_id) REFERENCES RADIO_STATION (id)');
        $this->addSql('ALTER TABLE PROMO ADD CONSTRAINT FK_4747584BE9A5E92F FOREIGN KEY (radio_show_id) REFERENCES RADIO_SHOW (id)');
        $this->addSql('ALTER TABLE PROMO ADD CONSTRAINT FK_4747584B6BF700BD FOREIGN KEY (status_id) REFERENCES STATUS (id)');
        $this->addSql('ALTER TABLE PROMO ADD CONSTRAINT FK_4747584B1A445520 FOREIGN KEY (content_type_id) REFERENCES CONTENT_TYPE (id)');
        $this->addSql('ALTER TABLE PROMO ADD CONSTRAINT FK_4747584BB03A8386 FOREIGN KEY (created_by_id) REFERENCES USER (id)');
        $this->addSql('ALTER TABLE SLOGAN CHANGE is_uploaded_to_bucket is_uploaded_to_bucket TINYINT(1) NOT NULL, CHANGE is_uploaded_to_s3 is_uploaded_to_s3 TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE PROMO');
        $this->addSql('ALTER TABLE SLOGAN CHANGE is_uploaded_to_bucket is_uploaded_to_bucket VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE is_uploaded_to_s3 is_uploaded_to_s3 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
