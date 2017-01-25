<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170116060214 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP INDEX radio_stream_search_idx ON RADIO_STATION_STREAM');
        $this->addSql('CREATE INDEX radio_stream_search_idx ON RADIO_STATION_STREAM (artist, title, isrc, acrid)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX radio_stream_search_idx ON RADIO_STATION_STREAM');
        $this->addSql('CREATE INDEX radio_stream_search_idx ON RADIO_STATION_STREAM (artist, title, isrc, acrid)');
    }
}
