<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213083549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book RENAME INDEX fk_book_author TO IDX_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book RENAME INDEX fk_book_genre TO IDX_CBE5A3314296D31F');
        $this->addSql('ALTER TABLE book RENAME INDEX fk_book_publisher TO IDX_CBE5A33140C86FCE');
        $this->addSql('ALTER TABLE bookshelf ADD CONSTRAINT FK_E1FF60F0FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('CREATE INDEX IDX_E1FF60F0FE2541D7 ON bookshelf (library_id)');
        $this->addSql('ALTER TABLE bookshelf RENAME INDEX fk_bookshelf_user TO IDX_E1FF60F0A76ED395');
        $this->addSql('ALTER TABLE library RENAME INDEX fk_library_user TO IDX_A18098BCA76ED395');
        $this->addSql('ALTER TABLE library_book RENAME INDEX fk_library_book_book TO IDX_6D2A695C16A2B381');
        $this->addSql('ALTER TABLE profil_picture RENAME INDEX fk_profil_picture_user TO IDX_93CF80D8A76ED395');
        $this->addSql('ALTER TABLE profil_picture RENAME INDEX fk_profil_picture_avatar TO IDX_93CF80D8175A13B7');
        $this->addSql('ALTER TABLE review RENAME INDEX fk_review_user TO IDX_794381C6A76ED395');
        $this->addSql('ALTER TABLE review RENAME INDEX fk_review_book TO IDX_794381C616A2B381');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE book RENAME INDEX idx_cbe5a3314296d31f TO FK_BOOK_GENRE');
        $this->addSql('ALTER TABLE book RENAME INDEX idx_cbe5a33140c86fce TO FK_BOOK_PUBLISHER');
        $this->addSql('ALTER TABLE book RENAME INDEX idx_cbe5a331f675f31b TO FK_BOOK_AUTHOR');
        $this->addSql('ALTER TABLE bookshelf DROP FOREIGN KEY FK_E1FF60F0FE2541D7');
        $this->addSql('DROP INDEX IDX_E1FF60F0FE2541D7 ON bookshelf');
        $this->addSql('ALTER TABLE bookshelf RENAME INDEX idx_e1ff60f0a76ed395 TO FK_BOOKSHELF_USER');
        $this->addSql('ALTER TABLE library RENAME INDEX idx_a18098bca76ed395 TO FK_LIBRARY_USER');
        $this->addSql('ALTER TABLE library_book RENAME INDEX idx_6d2a695c16a2b381 TO FK_LIBRARY_BOOK_BOOK');
        $this->addSql('ALTER TABLE profil_picture RENAME INDEX idx_93cf80d8a76ed395 TO FK_PROFIL_PICTURE_USER');
        $this->addSql('ALTER TABLE profil_picture RENAME INDEX idx_93cf80d8175a13b7 TO FK_PROFIL_PICTURE_AVATAR');
        $this->addSql('ALTER TABLE review RENAME INDEX idx_794381c6a76ed395 TO FK_REVIEW_USER');
        $this->addSql('ALTER TABLE review RENAME INDEX idx_794381c616a2b381 TO FK_REVIEW_BOOK');
    }
}
