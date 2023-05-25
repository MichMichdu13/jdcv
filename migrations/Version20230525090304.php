<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525090304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_to_logement (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, logement_id INT NOT NULL, INDEX IDX_EACBB10BBAD26311 (tag_id), INDEX IDX_EACBB10B58ABF955 (logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tags_to_logement ADD CONSTRAINT FK_EACBB10BBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE tags_to_logement ADD CONSTRAINT FK_EACBB10B58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE logement DROP tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags_to_logement DROP FOREIGN KEY FK_EACBB10BBAD26311');
        $this->addSql('ALTER TABLE tags_to_logement DROP FOREIGN KEY FK_EACBB10B58ABF955');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_to_logement');
        $this->addSql('ALTER TABLE logement ADD tags LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
