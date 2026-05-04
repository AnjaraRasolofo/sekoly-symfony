<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430090435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD payment_date DATE NOT NULL, ADD note VARCHAR(255) DEFAULT NULL, ADD enrollment_id INT NOT NULL, DROP date, CHANGE amount amount NUMERIC(10, 2) NOT NULL, CHANGE method method VARCHAR(50) NOT NULL, CHANGE status status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8F7DB25B FOREIGN KEY (enrollment_id) REFERENCES enrollment (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D8F7DB25B ON payment (enrollment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D8F7DB25B');
        $this->addSql('DROP INDEX IDX_6D28840D8F7DB25B ON payment');
        $this->addSql('ALTER TABLE payment ADD date DATE DEFAULT NULL, DROP payment_date, DROP note, DROP enrollment_id, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL, CHANGE method method VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
