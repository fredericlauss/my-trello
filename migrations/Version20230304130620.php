<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304130620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boards (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F3EE4D137E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE columns (id INT AUTO_INCREMENT NOT NULL, board_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_ACCEC0B7E7EC5785 (board_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, columnid_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_54469DF4BD43D929 (columnid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets_user (tickets_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_88E800948FDC0E9A (tickets_id), INDEX IDX_88E80094A76ED395 (user_id), PRIMARY KEY(tickets_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_boards (user_id INT NOT NULL, boards_id INT NOT NULL, INDEX IDX_96BC562EA76ED395 (user_id), INDEX IDX_96BC562E7A4426DD (boards_id), PRIMARY KEY(user_id, boards_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boards ADD CONSTRAINT FK_F3EE4D137E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE columns ADD CONSTRAINT FK_ACCEC0B7E7EC5785 FOREIGN KEY (board_id) REFERENCES boards (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4BD43D929 FOREIGN KEY (columnid_id) REFERENCES columns (id)');
        $this->addSql('ALTER TABLE tickets_user ADD CONSTRAINT FK_88E800948FDC0E9A FOREIGN KEY (tickets_id) REFERENCES tickets (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tickets_user ADD CONSTRAINT FK_88E80094A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_boards ADD CONSTRAINT FK_96BC562EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_boards ADD CONSTRAINT FK_96BC562E7A4426DD FOREIGN KEY (boards_id) REFERENCES boards (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boards DROP FOREIGN KEY FK_F3EE4D137E3C61F9');
        $this->addSql('ALTER TABLE columns DROP FOREIGN KEY FK_ACCEC0B7E7EC5785');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4BD43D929');
        $this->addSql('ALTER TABLE tickets_user DROP FOREIGN KEY FK_88E800948FDC0E9A');
        $this->addSql('ALTER TABLE tickets_user DROP FOREIGN KEY FK_88E80094A76ED395');
        $this->addSql('ALTER TABLE user_boards DROP FOREIGN KEY FK_96BC562EA76ED395');
        $this->addSql('ALTER TABLE user_boards DROP FOREIGN KEY FK_96BC562E7A4426DD');
        $this->addSql('DROP TABLE boards');
        $this->addSql('DROP TABLE columns');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE tickets_user');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_boards');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
