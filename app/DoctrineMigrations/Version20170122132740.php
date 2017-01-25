<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170122132740 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RADIO_SHOW_TIME_SLOT (id INT AUTO_INCREMENT NOT NULL, show_id INT DEFAULT NULL, radio_station_id INT DEFAULT NULL, week_of INT DEFAULT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F8AFE70ED0C1FC64 (show_id), INDEX IDX_F8AFE70E20189378 (radio_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RADIO_SHOW_TIME_SLOT ADD CONSTRAINT FK_F8AFE70ED0C1FC64 FOREIGN KEY (show_id) REFERENCES RADIO_SHOW (id)');
        $this->addSql('ALTER TABLE RADIO_SHOW_TIME_SLOT ADD CONSTRAINT FK_F8AFE70E20189378 FOREIGN KEY (radio_station_id) REFERENCES RADIO_STATION (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE RADIO_SHOW_TIME_SLOT');
    }
}
