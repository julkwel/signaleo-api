<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200228124719 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting ADD actualite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55A2843073 FOREIGN KEY (actualite_id) REFERENCES actualite (id)');
        $this->addSql('CREATE INDEX IDX_FC28DA55A2843073 ON voting (actualite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55A2843073');
        $this->addSql('DROP INDEX IDX_FC28DA55A2843073 ON voting');
        $this->addSql('ALTER TABLE voting DROP actualite_id');
    }
}
