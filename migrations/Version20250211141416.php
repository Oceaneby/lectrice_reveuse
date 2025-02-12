<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211141416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_picture DROP INDEX IDX_93CF80D8A76ED395, ADD UNIQUE INDEX UNIQ_93CF80D8A76ED395 (user_id)');
        $this->addSql('ALTER TABLE profil_picture CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE registration_date registration_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_picture DROP INDEX UNIQ_93CF80D8A76ED395, ADD INDEX IDX_93CF80D8A76ED395 (user_id)');
        $this->addSql('ALTER TABLE profil_picture CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE registration_date registration_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
