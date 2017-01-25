<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170121150844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_STATION_STREAM ADD show_id INT DEFAULT NULL, ADD archive_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE RADIO_STATION_STREAM ADD CONSTRAINT FK_B7BAEDFED0C1FC64 FOREIGN KEY (show_id) REFERENCES RADIO_SHOW (id)');
        $this->addSql('ALTER TABLE RADIO_STATION_STREAM ADD CONSTRAINT FK_B7BAEDFE2956195F FOREIGN KEY (archive_id) REFERENCES ARCHIVE (id)');
        $this->addSql('CREATE INDEX IDX_B7BAEDFED0C1FC64 ON RADIO_STATION_STREAM (show_id)');
        $this->addSql('CREATE INDEX IDX_B7BAEDFE2956195F ON RADIO_STATION_STREAM (archive_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_STATION_STREAM DROP FOREIGN KEY FK_B7BAEDFED0C1FC64');
        $this->addSql('ALTER TABLE RADIO_STATION_STREAM DROP FOREIGN KEY FK_B7BAEDFE2956195F');
        $this->addSql('DROP INDEX IDX_B7BAEDFED0C1FC64 ON RADIO_STATION_STREAM');
        $this->addSql('DROP INDEX IDX_B7BAEDFE2956195F ON RADIO_STATION_STREAM');
        $this->addSql('ALTER TABLE RADIO_STATION_STREAM DROP show_id, DROP archive_id');
    }
}
