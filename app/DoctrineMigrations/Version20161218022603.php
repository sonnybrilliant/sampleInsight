<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161218022603 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_STATION_STREAM ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE RADIO_STATION_STREAM ADD CONSTRAINT FK_B7BAEDFED0C07AFF FOREIGN KEY (promo_id) REFERENCES PROMO (id)');
        $this->addSql('CREATE INDEX IDX_B7BAEDFED0C07AFF ON RADIO_STATION_STREAM (promo_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_STATION_STREAM DROP FOREIGN KEY FK_B7BAEDFED0C07AFF');
        $this->addSql('DROP INDEX IDX_B7BAEDFED0C07AFF ON RADIO_STATION_STREAM');
        $this->addSql('ALTER TABLE RADIO_STATION_STREAM DROP promo_id');
    }
}
