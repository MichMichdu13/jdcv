<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523204300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE logement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, prix_nuit DOUBLE PRECISION NOT NULL, tags LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', nb_personne INT NOT NULL, ville VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, gps VARCHAR(255) NOT NULL, INDEX IDX_F0FD4457A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457A76ED395');
        $this->addSql('DROP TABLE logement');
    }
}
