<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321150759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, user_agent VARCHAR(255) DEFAULT NULL, device_type VARCHAR(255) DEFAULT NULL, device_identifier VARCHAR(255) DEFAULT NULL, device_manufacturer VARCHAR(255) DEFAULT NULL, device_model VARCHAR(255) DEFAULT NULL, os_name VARCHAR(255) DEFAULT NULL, os_version VARCHAR(255) DEFAULT NULL, browser_name VARCHAR(255) DEFAULT NULL, browser_version VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, accept TINYINT(1) NOT NULL, acceptedat DATETIME NOT NULL, createdat DATETIME NOT NULL, INDEX IDX_DADD4A25B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_proposition (answer_id INT NOT NULL, proposition_id INT NOT NULL, INDEX IDX_6C35370AA334807 (answer_id), INDEX IDX_6C35370DB96F9E (proposition_id), PRIMARY KEY(answer_id, proposition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_assistive_technology (answer_id INT NOT NULL, assistive_technology_id INT NOT NULL, INDEX IDX_BFAE32BEAA334807 (answer_id), INDEX IDX_BFAE32BE725213EF (assistive_technology_id), PRIMARY KEY(answer_id, assistive_technology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assistive_technology (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A1D98E6512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, wording VARCHAR(255) NOT NULL, INDEX IDX_C7CDC353B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(300) NOT NULL, question VARCHAR(500) NOT NULL, multiple TINYINT(1) NOT NULL, status VARCHAR(255) NOT NULL, closing_message LONGTEXT DEFAULT NULL, createdat DATETIME NOT NULL, updatedat DATETIME NOT NULL, closedat DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_389B7838DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technical_component (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, title VARCHAR(255) NOT NULL, choice TINYINT(1) NOT NULL, code LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_129C0585B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, createdat DATETIME NOT NULL, updatedat DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE answer_proposition ADD CONSTRAINT FK_6C35370AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer_proposition ADD CONSTRAINT FK_6C35370DB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer_assistive_technology ADD CONSTRAINT FK_BFAE32BEAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer_assistive_technology ADD CONSTRAINT FK_BFAE32BE725213EF FOREIGN KEY (assistive_technology_id) REFERENCES assistive_technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assistive_technology ADD CONSTRAINT FK_A1D98E6512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC353B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7838DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE technical_component ADD CONSTRAINT FK_129C0585B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer_proposition DROP FOREIGN KEY FK_6C35370AA334807');
        $this->addSql('ALTER TABLE answer_assistive_technology DROP FOREIGN KEY FK_BFAE32BEAA334807');
        $this->addSql('ALTER TABLE answer_assistive_technology DROP FOREIGN KEY FK_BFAE32BE725213EF');
        $this->addSql('ALTER TABLE assistive_technology DROP FOREIGN KEY FK_A1D98E6512469DE2');
        $this->addSql('ALTER TABLE answer_proposition DROP FOREIGN KEY FK_6C35370DB96F9E');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25B3FE509D');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC353B3FE509D');
        $this->addSql('ALTER TABLE technical_component DROP FOREIGN KEY FK_129C0585B3FE509D');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7838DB60186');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE answer_proposition');
        $this->addSql('DROP TABLE answer_assistive_technology');
        $this->addSql('DROP TABLE assistive_technology');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE technical_component');
        $this->addSql('DROP TABLE user');
    }
}
