<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118165730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app (id INT AUTO_INCREMENT NOT NULL, public_id VARCHAR(6) NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE environment (id INT AUTO_INCREMENT NOT NULL, app_id INT NOT NULL, public_id VARCHAR(6) NOT NULL, name VARCHAR(20) NOT NULL, php_version ENUM(\'8.1\', \'8.2\', \'8.3\', \'8.4\') NOT NULL COMMENT \'(DC2Type:PhpVersionType)\', INDEX IDX_4626DE227987212D (app_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE environment ADD CONSTRAINT FK_4626DE227987212D FOREIGN KEY (app_id) REFERENCES app (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE environment DROP FOREIGN KEY FK_4626DE227987212D');
        $this->addSql('DROP TABLE app');
        $this->addSql('DROP TABLE environment');
    }
}
