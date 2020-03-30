<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200228123137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting ADD user_id INT NOT NULL, ADD date_add DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC28DA55A76ED395 ON voting (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55A76ED395');
        $this->addSql('DROP INDEX UNIQ_FC28DA55A76ED395 ON voting');
        $this->addSql('ALTER TABLE voting DROP user_id, DROP date_add');
    }
}
