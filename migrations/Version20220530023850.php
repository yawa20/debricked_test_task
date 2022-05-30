<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530023850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE upload_entity (id INT NOT NULL, filename VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, repository_name VARCHAR(255) NOT NULL, commit_name VARCHAR(255) NOT NULL, ci_upload_id INT DEFAULT NULL, triggers JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_830914D06D59A370 ON upload_entity (ci_upload_id)');
        $this->addSql('COMMENT ON COLUMN upload_entity.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN upload_entity.uploaded_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE upload_result (ci_upload_id INT NOT NULL, status INT NOT NULL, progress INT NOT NULL, vulnerabilities_found INT NOT NULL, unaffected_vulnerabilities_found INT NOT NULL, PRIMARY KEY(ci_upload_id))');
        $this->addSql('ALTER TABLE upload_result ADD CONSTRAINT FK_9E4B91AB6D59A370 FOREIGN KEY (ci_upload_id) REFERENCES upload_entity (ci_upload_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE upload_result DROP CONSTRAINT FK_9E4B91AB6D59A370');
        $this->addSql('DROP TABLE upload_entity');
        $this->addSql('DROP TABLE upload_result');
    }
}
