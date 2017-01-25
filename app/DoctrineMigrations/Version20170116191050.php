<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170116191050 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RADIO_SHOW_SCHEDULE_TYPE (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RADIO_SHOW ADD schedule_type_id INT DEFAULT NULL, CHANGE plays_monday plays_monday TINYINT(1) NOT NULL, CHANGE plays_tuesday plays_tuesday TINYINT(1) NOT NULL, CHANGE plays_wednesday plays_wednesday TINYINT(1) NOT NULL, CHANGE plays_thursday plays_thursday TINYINT(1) NOT NULL, CHANGE plays_friday plays_friday TINYINT(1) NOT NULL, CHANGE plays_saturday plays_saturday TINYINT(1) NOT NULL, CHANGE plays_sunday plays_sunday TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE RADIO_SHOW ADD CONSTRAINT FK_8A54DEF04826A022 FOREIGN KEY (schedule_type_id) REFERENCES RADIO_SHOW_SCHEDULE_TYPE (id)');
        $this->addSql('CREATE INDEX IDX_8A54DEF04826A022 ON RADIO_SHOW (schedule_type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RADIO_SHOW DROP FOREIGN KEY FK_8A54DEF04826A022');
        $this->addSql('DROP TABLE RADIO_SHOW_SCHEDULE_TYPE');
        $this->addSql('DROP INDEX IDX_8A54DEF04826A022 ON RADIO_SHOW');
        $this->addSql('ALTER TABLE RADIO_SHOW DROP schedule_type_id, CHANGE plays_monday plays_monday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE plays_tuesday plays_tuesday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE plays_wednesday plays_wednesday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE plays_thursday plays_thursday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE plays_friday plays_friday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE plays_saturday plays_saturday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE plays_sunday plays_sunday VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
