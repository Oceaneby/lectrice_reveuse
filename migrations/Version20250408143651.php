<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408143651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, biography LONGTEXT DEFAULT NULL, author_picture VARCHAR(255) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, publisher_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, isbn VARCHAR(255) DEFAULT NULL, publication_date DATETIME DEFAULT NULL, cover_image VARCHAR(255) DEFAULT NULL, amazon_url VARCHAR(255) DEFAULT NULL, fnac_url VARCHAR(255) DEFAULT NULL, INDEX IDX_CBE5A33140C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author_books (book_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_5C930A5F16A2B381 (book_id), INDEX IDX_5C930A5FF675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_genres (book_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_813CEE9B16A2B381 (book_id), INDEX IDX_813CEE9B4296D31F (genre_id), PRIMARY KEY(book_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookshelf (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, library_id INT NOT NULL, shelf_name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_E1FF60F0A76ED395 (user_id), INDEX IDX_E1FF60F0FE2541D7 (library_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_bookshelf (bookshelf_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_F35176B69B7A322B (bookshelf_id), INDEX IDX_F35176B616A2B381 (book_id), PRIMARY KEY(bookshelf_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE default_avatar (id INT AUTO_INCREMENT NOT NULL, image_url VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_picture (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, default_avatar_id INT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, file_size INT DEFAULT NULL, file_format VARCHAR(255) DEFAULT NULL, upload_date DATETIME DEFAULT NULL, INDEX IDX_93CF80D8A76ED395 (user_id), INDEX IDX_93CF80D8175A13B7 (default_avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, foundation_year INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, review_text LONGTEXT NOT NULL, rating INT NOT NULL, review_date DATETIME NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C616A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, registration_date DATETIME NOT NULL, birth_date DATETIME NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_library (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, added_date DATETIME NOT NULL, book_status VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_F98D86B6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library_books (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_79BEF8BDFE2541D7 (library_id), INDEX IDX_79BEF8BD16A2B381 (book_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33140C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('ALTER TABLE author_books ADD CONSTRAINT FK_5C930A5F16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE author_books ADD CONSTRAINT FK_5C930A5FF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_genres ADD CONSTRAINT FK_813CEE9B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_genres ADD CONSTRAINT FK_813CEE9B4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookshelf ADD CONSTRAINT FK_E1FF60F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bookshelf ADD CONSTRAINT FK_E1FF60F0FE2541D7 FOREIGN KEY (library_id) REFERENCES user_library (id)');
        $this->addSql('ALTER TABLE book_bookshelf ADD CONSTRAINT FK_F35176B69B7A322B FOREIGN KEY (bookshelf_id) REFERENCES bookshelf (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_bookshelf ADD CONSTRAINT FK_F35176B616A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D8175A13B7 FOREIGN KEY (default_avatar_id) REFERENCES default_avatar (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C616A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE user_library ADD CONSTRAINT FK_F98D86B6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE library_books ADD CONSTRAINT FK_79BEF8BDFE2541D7 FOREIGN KEY (library_id) REFERENCES user_library (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_books ADD CONSTRAINT FK_79BEF8BD16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33140C86FCE');
        $this->addSql('ALTER TABLE author_books DROP FOREIGN KEY FK_5C930A5F16A2B381');
        $this->addSql('ALTER TABLE author_books DROP FOREIGN KEY FK_5C930A5FF675F31B');
        $this->addSql('ALTER TABLE book_genres DROP FOREIGN KEY FK_813CEE9B16A2B381');
        $this->addSql('ALTER TABLE book_genres DROP FOREIGN KEY FK_813CEE9B4296D31F');
        $this->addSql('ALTER TABLE bookshelf DROP FOREIGN KEY FK_E1FF60F0A76ED395');
        $this->addSql('ALTER TABLE bookshelf DROP FOREIGN KEY FK_E1FF60F0FE2541D7');
        $this->addSql('ALTER TABLE book_bookshelf DROP FOREIGN KEY FK_F35176B69B7A322B');
        $this->addSql('ALTER TABLE book_bookshelf DROP FOREIGN KEY FK_F35176B616A2B381');
        $this->addSql('ALTER TABLE profil_picture DROP FOREIGN KEY FK_93CF80D8A76ED395');
        $this->addSql('ALTER TABLE profil_picture DROP FOREIGN KEY FK_93CF80D8175A13B7');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C616A2B381');
        $this->addSql('ALTER TABLE user_library DROP FOREIGN KEY FK_F98D86B6A76ED395');
        $this->addSql('ALTER TABLE library_books DROP FOREIGN KEY FK_79BEF8BDFE2541D7');
        $this->addSql('ALTER TABLE library_books DROP FOREIGN KEY FK_79BEF8BD16A2B381');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE author_books');
        $this->addSql('DROP TABLE book_genres');
        $this->addSql('DROP TABLE bookshelf');
        $this->addSql('DROP TABLE book_bookshelf');
        $this->addSql('DROP TABLE default_avatar');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE profil_picture');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_library');
        $this->addSql('DROP TABLE library_books');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
