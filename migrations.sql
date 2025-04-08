-- Doctrine Migration File Generated on 2025-02-12 16:05:09

-- Version DoctrineMigrations\Version20250206153031
CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
-- Version DoctrineMigrations\Version20250206153031 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206153031', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206153324
CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, foundation_year INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
-- Version DoctrineMigrations\Version20250206153324 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206153324', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206153541
CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, biography LONGTEXT DEFAULT NULL, author_picture VARCHAR(255) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
-- Version DoctrineMigrations\Version20250206153541 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206153541', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206153941
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, registration_date DATETIME NOT NULL, birth_date DATETIME NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
-- Version DoctrineMigrations\Version20250206153941 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206153941', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206154127
CREATE TABLE default_avatar (id INT AUTO_INCREMENT NOT NULL, image_url VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
-- Version DoctrineMigrations\Version20250206154127 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206154127', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206154644
CREATE TABLE bookshelf (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, shelf_name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_E1FF60F0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE bookshelf ADD CONSTRAINT FK_E1FF60F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
-- Version DoctrineMigrations\Version20250206154644 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206154644', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206155439
CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, genre_id INT NOT NULL, publisher_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, isbn VARCHAR(255) DEFAULT NULL, publication_date DATETIME DEFAULT NULL, cover_image VARCHAR(255) DEFAULT NULL, INDEX IDX_CBE5A331F675F31B (author_id), INDEX IDX_CBE5A3314296D31F (genre_id), INDEX IDX_CBE5A33140C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id);
ALTER TABLE book ADD CONSTRAINT FK_CBE5A3314296D31F FOREIGN KEY (genre_id) REFERENCES genre (id);
ALTER TABLE book ADD CONSTRAINT FK_CBE5A33140C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id);
-- Version DoctrineMigrations\Version20250206155439 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206155439', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206170324
CREATE TABLE profil_picture (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, default_avatar_id INT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, file_size INT DEFAULT NULL, file_format VARCHAR(255) DEFAULT NULL, upload_date DATETIME DEFAULT NULL, INDEX IDX_93CF80D8A76ED395 (user_id), INDEX IDX_93CF80D8175A13B7 (default_avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D8175A13B7 FOREIGN KEY (default_avatar_id) REFERENCES default_avatar (id);
-- Version DoctrineMigrations\Version20250206170324 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206170324', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206171000
CREATE TABLE library (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, added_date DATETIME NOT NULL, book_status VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_A18098BCA76ED395 (user_id), INDEX IDX_A18098BC16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE library ADD CONSTRAINT FK_A18098BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE library ADD CONSTRAINT FK_A18098BC16A2B381 FOREIGN KEY (book_id) REFERENCES book (id);
-- Version DoctrineMigrations\Version20250206171000 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206171000', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250206171253
CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, review_text LONGTEXT NOT NULL, rating INT NOT NULL, review_date DATETIME NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C616A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE review ADD CONSTRAINT FK_794381C616A2B381 FOREIGN KEY (book_id) REFERENCES book (id);
-- Version DoctrineMigrations\Version20250206171253 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250206171253', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250210131330
ALTER TABLE user CHANGE registration_date registration_date DATE NOT NULL COMMENT '(DC2Type:date_immutable)';
-- Version DoctrineMigrations\Version20250210131330 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250210131330', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250211141416
ALTER TABLE profil_picture DROP INDEX IDX_93CF80D8A76ED395, ADD UNIQUE INDEX UNIQ_93CF80D8A76ED395 (user_id);
ALTER TABLE profil_picture CHANGE user_id user_id INT NOT NULL;
ALTER TABLE user CHANGE registration_date registration_date DATETIME NOT NULL;
-- Version DoctrineMigrations\Version20250211141416 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250211141416', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250211142416
ALTER TABLE profil_picture DROP INDEX UNIQ_93CF80D8A76ED395, ADD INDEX IDX_93CF80D8A76ED395 (user_id);
-- Version DoctrineMigrations\Version20250211142416 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250211142416', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250212130142
CREATE TABLE library_book (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_6D2A695CFE2541D7 (library_id), INDEX IDX_6D2A695C16A2B381 (book_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695CFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON DELETE CASCADE;
ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE;
ALTER TABLE library DROP FOREIGN KEY FK_A18098BC16A2B381;
DROP INDEX IDX_A18098BC16A2B381 ON library;
ALTER TABLE library DROP book_id;
ALTER TABLE user CHANGE registration_date registration_date DATETIME NOT NULL;
-- Version DoctrineMigrations\Version20250212130142 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250212130142', '2025-02-12 16:05:08', 0);

-- Version DoctrineMigrations\Version20250212131910
CREATE TABLE library_book (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_6D2A695CFE2541D7 (library_id), INDEX IDX_6D2A695C16A2B381 (book_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695CFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON DELETE CASCADE;
ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE;
ALTER TABLE bookshelf ADD library_id INT NOT NULL;
ALTER TABLE bookshelf ADD CONSTRAINT FK_E1FF60F0FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id);
CREATE INDEX IDX_E1FF60F0FE2541D7 ON bookshelf (library_id);
ALTER TABLE library DROP FOREIGN KEY FK_A18098BC16A2B381;
DROP INDEX IDX_A18098BC16A2B381 ON library;
ALTER TABLE library DROP book_id;
ALTER TABLE profil_picture DROP INDEX UNIQ_93CF80D8A76ED395, ADD INDEX IDX_93CF80D8A76ED395 (user_id);
ALTER TABLE profil_picture CHANGE user_id user_id INT DEFAULT NULL;
-- Version DoctrineMigrations\Version20250212131910 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20250212131910', '2025-02-12 16:05:08', 0);
