<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504213620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY `FK_169E6FB9163DDA15`');
        $this->addSql('DROP INDEX IDX_169E6FB9163DDA15 ON course');
        $this->addSql('ALTER TABLE course ADD coefficient INT NOT NULL, ADD hours_per_week INT NOT NULL, ADD subject_id INT NOT NULL, ADD teacher_id INT NOT NULL, ADD classroom_id INT NOT NULL, ADD school_year_id INT NOT NULL, DROP start, DROP end, DROP attendance_id');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB923EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB96278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9D2EECC3F FOREIGN KEY (school_year_id) REFERENCES school_year (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB923EDC87 ON course (subject_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB941807E1D ON course (teacher_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB96278D5A8 ON course (classroom_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9D2EECC3F ON course (school_year_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB923EDC87');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB96278D5A8');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9D2EECC3F');
        $this->addSql('DROP INDEX IDX_169E6FB923EDC87 ON course');
        $this->addSql('DROP INDEX IDX_169E6FB941807E1D ON course');
        $this->addSql('DROP INDEX IDX_169E6FB96278D5A8 ON course');
        $this->addSql('DROP INDEX IDX_169E6FB9D2EECC3F ON course');
        $this->addSql('ALTER TABLE course ADD start DATETIME NOT NULL, ADD end DATETIME NOT NULL, ADD attendance_id INT DEFAULT NULL, DROP coefficient, DROP hours_per_week, DROP subject_id, DROP teacher_id, DROP classroom_id, DROP school_year_id');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT `FK_169E6FB9163DDA15` FOREIGN KEY (attendance_id) REFERENCES attendance (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9163DDA15 ON course (attendance_id)');
    }
}
