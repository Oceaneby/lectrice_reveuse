<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206170324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_picture (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, default_avatar_id INT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, file_size INT DEFAULT NULL, file_format VARCHAR(255) DEFAULT NULL, upload_date DATETIME DEFAULT NULL, INDEX IDX_93CF80D8A76ED395 (user_id), INDEX IDX_93CF80D8175A13B7 (default_avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D8175A13B7 FOREIGN KEY (default_avatar_id) REFERENCES default_avatar (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_picture DROP FOREIGN KEY FK_93CF80D8A76ED395');
        $this->addSql('ALTER TABLE profil_picture DROP FOREIGN KEY FK_93CF80D8175A13B7');
        $this->addSql('DROP TABLE profil_picture');
    }
}
