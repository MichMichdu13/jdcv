<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613192752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logement ADD event_id INT DEFAULT NULL, ADD style_id INT DEFAULT NULL, ADD date_start DATE NOT NULL, ADD date_end DATE NOT NULL');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD445771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457BACD6074 FOREIGN KEY (style_id) REFERENCES style (id)');
        $this->addSql('CREATE INDEX IDX_F0FD445771F7E88B ON logement (event_id)');
        $this->addSql('CREATE INDEX IDX_F0FD4457BACD6074 ON logement (style_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD445771F7E88B');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457BACD6074');
        $this->addSql('DROP INDEX IDX_F0FD445771F7E88B ON logement');
        $this->addSql('DROP INDEX IDX_F0FD4457BACD6074 ON logement');
        $this->addSql('ALTER TABLE logement DROP event_id, DROP style_id, DROP date_start, DROP date_end');
    }
}
