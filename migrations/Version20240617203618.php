<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617203618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD app_id INT NOT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6207987212D FOREIGN KEY (app_id) REFERENCES app (id)');
        $this->addSql('CREATE INDEX IDX_140AB6207987212D ON page (app_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6207987212D');
        $this->addSql('DROP INDEX IDX_140AB6207987212D ON page');
        $this->addSql('ALTER TABLE page DROP app_id');
    }
}
