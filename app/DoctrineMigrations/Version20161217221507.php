<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161217221507 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `ROLE` VALUES (26,'Role promo view','ROLE_PROMO_VIEW','2016-12-17 13:56:47','2016-12-17 13:56:47'),(27,'Role promo edit','ROLE_PROMO_EDIT','2016-12-17 13:56:47','2016-12-17 13:56:47');
");

        $this->addSql("INSERT INTO `USER_GROUP_ROLE_MAP` VALUES (1,26),(2,26),(1,27),(2,27)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
