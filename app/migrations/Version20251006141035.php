<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251006141035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id SERIAL NOT NULL, passport_number VARCHAR(64) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, citizenship VARCHAR(8) NOT NULL, passport_expiration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN application.passport_expiration IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN application.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN application.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE black_list_application (id SERIAL NOT NULL, passport_number VARCHAR(64) NOT NULL, reason VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN black_list_application.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql("
            INSERT INTO black_list_application (passport_number, reason)
            VALUES
                ('X1234567', 'Fraudulent visa application detected'),
                ('Y9876543', 'Visa overstay in previous country'),
                ('Z5551111', 'Forgery of passport document'),
                ('A2024056', 'Suspicious travel pattern flagged'),
                ('B7654321', 'Deported due to immigration violation'),
                ('C1112223', 'Passport reported lost or stolen'),
                ('D9998887', 'Attempted entry with invalid visa'),
                ('E5557779', 'Linked to multiple failed applications'),
                ('F3334445', 'Blacklist due to national security concern'),
                ('G1010101', 'Inconsistent identity information')
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE black_list_application');
    }
}
