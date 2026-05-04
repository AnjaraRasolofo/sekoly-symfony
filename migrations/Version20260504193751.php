<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504193751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY `FK_FBCE3E7A578D5E91`');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY `FK_FBCE3E7A591CC992`');
        $this->addSql('DROP INDEX IDX_FBCE3E7A591CC992 ON subject');
        $this->addSql('DROP INDEX IDX_FBCE3E7A578D5E91 ON subject');
        $this->addSql('ALTER TABLE subject DROP course_id, DROP exam_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject ADD course_id INT DEFAULT NULL, ADD exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT `FK_FBCE3E7A578D5E91` FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT `FK_FBCE3E7A591CC992` FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A591CC992 ON subject (course_id)');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A578D5E91 ON subject (exam_id)');
    }
}
