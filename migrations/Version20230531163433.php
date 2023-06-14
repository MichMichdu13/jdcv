<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531163433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_to_logement (id INT AUTO_INCREMENT NOT NULL, logement_id INT DEFAULT NULL, event_id INT DEFAULT NULL, INDEX IDX_962D097958ABF955 (logement_id), INDEX IDX_962D097971F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style_to_logement (id INT AUTO_INCREMENT NOT NULL, logement_id INT DEFAULT NULL, style_id INT DEFAULT NULL, INDEX IDX_28808F3658ABF955 (logement_id), INDEX IDX_28808F36BACD6074 (style_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_to_logement ADD CONSTRAINT FK_962D097958ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE event_to_logement ADD CONSTRAINT FK_962D097971F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE style_to_logement ADD CONSTRAINT FK_28808F3658ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE style_to_logement ADD CONSTRAINT FK_28808F36BACD6074 FOREIGN KEY (style_id) REFERENCES style (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_to_logement DROP FOREIGN KEY FK_962D097958ABF955');
        $this->addSql('ALTER TABLE event_to_logement DROP FOREIGN KEY FK_962D097971F7E88B');
        $this->addSql('ALTER TABLE style_to_logement DROP FOREIGN KEY FK_28808F3658ABF955');
        $this->addSql('ALTER TABLE style_to_logement DROP FOREIGN KEY FK_28808F36BACD6074');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_to_logement');
        $this->addSql('DROP TABLE style');
        $this->addSql('DROP TABLE style_to_logement');
    }
}
