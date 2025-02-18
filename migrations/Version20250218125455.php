<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218125455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE library_books (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_79BEF8BDFE2541D7 (library_id), INDEX IDX_79BEF8BD16A2B381 (book_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE library_books ADD CONSTRAINT FK_79BEF8BDFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_books ADD CONSTRAINT FK_79BEF8BD16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_book DROP FOREIGN KEY FK_6D2A695C16A2B381');
        $this->addSql('ALTER TABLE library_book DROP FOREIGN KEY FK_6D2A695CFE2541D7');
        $this->addSql('DROP TABLE library_book');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE library_book (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_6D2A695C16A2B381 (book_id), INDEX IDX_6D2A695CFE2541D7 (library_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695CFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_books DROP FOREIGN KEY FK_79BEF8BDFE2541D7');
        $this->addSql('ALTER TABLE library_books DROP FOREIGN KEY FK_79BEF8BD16A2B381');
        $this->addSql('DROP TABLE library_books');
    }
}
