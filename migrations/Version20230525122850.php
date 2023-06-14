<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525122850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE logement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, prix_nuit DOUBLE PRECISION NOT NULL, nb_personne INT NOT NULL, ville VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, gps VARCHAR(255) NOT NULL, INDEX IDX_F0FD4457A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_E6D6B297E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_to_logement (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, logement_id INT NOT NULL, INDEX IDX_EACBB10BBAD26311 (tag_id), INDEX IDX_EACBB10B58ABF955 (logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profile_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tags_to_logement ADD CONSTRAINT FK_EACBB10BBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE tags_to_logement ADD CONSTRAINT FK_EACBB10B58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profil (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457A76ED395');
        $this->addSql('ALTER TABLE tags_to_logement DROP FOREIGN KEY FK_EACBB10BBAD26311');
        $this->addSql('ALTER TABLE tags_to_logement DROP FOREIGN KEY FK_EACBB10B58ABF955');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('DROP TABLE logement');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_to_logement');
        $this->addSql('DROP TABLE user');
    }
}
