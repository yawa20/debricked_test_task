<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531222632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upload_entity ALTER triggers TYPE JSON');
        $this->addSql('ALTER TABLE upload_entity ALTER triggers DROP DEFAULT');
        $this->addSql('ALTER TABLE upload_result DROP CONSTRAINT fk_9e4b91ab6d59a370');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE upload_entity ALTER triggers TYPE JSON');
        $this->addSql('ALTER TABLE upload_entity ALTER triggers DROP DEFAULT');
        $this->addSql('ALTER TABLE upload_result ADD CONSTRAINT fk_9e4b91ab6d59a370 FOREIGN KEY (ci_upload_id) REFERENCES upload_entity (ci_upload_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
