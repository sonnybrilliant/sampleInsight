<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161222181510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ARTIST DROP FOREIGN KEY FK_F1A151011DBAFDB8');
        $this->addSql('DROP INDEX UNIQ_F1A151012B36786B ON ARTIST');
        $this->addSql('DROP INDEX IDX_F1A151011DBAFDB8 ON ARTIST');
        $this->addSql('ALTER TABLE ARTIST ADD is_african TINYINT(1) NOT NULL, ADD api_deezer_id VARCHAR(255) DEFAULT NULL, ADD api_deezer_image56x56 VARCHAR(255) DEFAULT NULL, ADD api_deezer_image250x250 VARCHAR(255) DEFAULT NULL, ADD api_deezer_image500x500 VARCHAR(255) DEFAULT NULL, ADD api_deezer_image1000x1000 VARCHAR(255) DEFAULT NULL, DROP record_label_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ARTIST ADD record_label_id INT DEFAULT NULL, DROP is_african, DROP api_deezer_id, DROP api_deezer_image56x56, DROP api_deezer_image250x250, DROP api_deezer_image500x500, DROP api_deezer_image1000x1000');
        $this->addSql('ALTER TABLE ARTIST ADD CONSTRAINT FK_F1A151011DBAFDB8 FOREIGN KEY (record_label_id) REFERENCES RECORD_LABEL (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F1A151012B36786B ON ARTIST (title)');
        $this->addSql('CREATE INDEX IDX_F1A151011DBAFDB8 ON ARTIST (record_label_id)');
    }
}
