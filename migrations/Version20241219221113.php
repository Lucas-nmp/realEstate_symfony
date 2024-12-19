<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219221113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feature_building (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_9882CEEB549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_extra (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, name VARCHAR(150) NOT NULL, INDEX IDX_8461076C549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_property (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_F21FB3E1549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(15) NOT NULL, title VARCHAR(100) NOT NULL, area VARCHAR(150) NOT NULL, price DOUBLE PRECISION NOT NULL, short_description LONGTEXT NOT NULL, long_description LONGTEXT NOT NULL, outstanding TINYINT(1) NOT NULL, operation_type VARCHAR(30) NOT NULL, price_observation VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feature_building ADD CONSTRAINT FK_9882CEEB549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE feature_extra ADD CONSTRAINT FK_8461076C549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE feature_property ADD CONSTRAINT FK_F21FB3E1549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_building DROP FOREIGN KEY FK_9882CEEB549213EC');
        $this->addSql('ALTER TABLE feature_extra DROP FOREIGN KEY FK_8461076C549213EC');
        $this->addSql('ALTER TABLE feature_property DROP FOREIGN KEY FK_F21FB3E1549213EC');
        $this->addSql('DROP TABLE feature_building');
        $this->addSql('DROP TABLE feature_extra');
        $this->addSql('DROP TABLE feature_property');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
