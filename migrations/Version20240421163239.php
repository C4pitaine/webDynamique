<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421163239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exos_cardio (id INT AUTO_INCREMENT NOT NULL, seances_id INT NOT NULL, name VARCHAR(255) NOT NULL, time DOUBLE PRECISION NOT NULL, INDEX IDX_16EA8B6710F09302 (seances_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exos_cardio ADD CONSTRAINT FK_16EA8B6710F09302 FOREIGN KEY (seances_id) REFERENCES seance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exos_cardio DROP FOREIGN KEY FK_16EA8B6710F09302');
        $this->addSql('DROP TABLE exos_cardio');
    }
}
