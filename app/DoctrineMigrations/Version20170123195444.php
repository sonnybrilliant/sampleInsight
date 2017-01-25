<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170123195444 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_SHOW_TIME_SLOT ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE RADIO_SHOW_TIME_SLOT ADD CONSTRAINT FK_F8AFE70E6BF700BD FOREIGN KEY (status_id) REFERENCES STATUS (id)');
        $this->addSql('CREATE INDEX IDX_F8AFE70E6BF700BD ON RADIO_SHOW_TIME_SLOT (status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_SHOW_TIME_SLOT DROP FOREIGN KEY FK_F8AFE70E6BF700BD');
        $this->addSql('DROP INDEX IDX_F8AFE70E6BF700BD ON RADIO_SHOW_TIME_SLOT');
        $this->addSql('ALTER TABLE RADIO_SHOW_TIME_SLOT DROP status_id');
    }
}
