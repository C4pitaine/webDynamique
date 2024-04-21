<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421165003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entrainement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entrainement_muscle (entrainement_id INT NOT NULL, muscle_id INT NOT NULL, INDEX IDX_2443A827A15E8FD (entrainement_id), INDEX IDX_2443A827354FDBB4 (muscle_id), PRIMARY KEY(entrainement_id, muscle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE muscle (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entrainement_muscle ADD CONSTRAINT FK_2443A827A15E8FD FOREIGN KEY (entrainement_id) REFERENCES entrainement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entrainement_muscle ADD CONSTRAINT FK_2443A827354FDBB4 FOREIGN KEY (muscle_id) REFERENCES muscle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entrainement_muscle DROP FOREIGN KEY FK_2443A827A15E8FD');
        $this->addSql('ALTER TABLE entrainement_muscle DROP FOREIGN KEY FK_2443A827354FDBB4');
        $this->addSql('DROP TABLE entrainement');
        $this->addSql('DROP TABLE entrainement_muscle');
        $this->addSql('DROP TABLE muscle');
    }
}
