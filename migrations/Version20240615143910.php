<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240615143910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page_menu ADD app_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page_menu ADD CONSTRAINT FK_FAC126497987212D FOREIGN KEY (app_id) REFERENCES app (id)');
        $this->addSql('CREATE INDEX IDX_FAC126497987212D ON page_menu (app_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page_menu DROP FOREIGN KEY FK_FAC126497987212D');
        $this->addSql('DROP INDEX IDX_FAC126497987212D ON page_menu');
        $this->addSql('ALTER TABLE page_menu DROP app_id');
    }
}
