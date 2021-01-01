<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210101170845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE competence_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE groupe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE groupe_competence_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE groupe_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE niveau_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profil_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profil_sortie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE promo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE referentiel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE apprenant (id INT NOT NULL, profil_sortie_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4EB462E6409EF73 ON apprenant (profil_sortie_id)');
        $this->addSql('CREATE TABLE community_manager (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE competence (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE formateur (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE groupe (id INT NOT NULL, promo_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, statut BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B98C21D0C07AFF ON groupe (promo_id)');
        $this->addSql('CREATE TABLE groupe_formateur (groupe_id INT NOT NULL, formateur_id INT NOT NULL, PRIMARY KEY(groupe_id, formateur_id))');
        $this->addSql('CREATE INDEX IDX_BDE2AD787A45358C ON groupe_formateur (groupe_id)');
        $this->addSql('CREATE INDEX IDX_BDE2AD78155D8F51 ON groupe_formateur (formateur_id)');
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, PRIMARY KEY(groupe_id, apprenant_id))');
        $this->addSql('CREATE INDEX IDX_947F95197A45358C ON groupe_apprenant (groupe_id)');
        $this->addSql('CREATE INDEX IDX_947F9519C5697D6D ON groupe_apprenant (apprenant_id)');
        $this->addSql('CREATE TABLE groupe_competence (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif TEXT NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE groupe_competence_competence (groupe_competence_id INT NOT NULL, competence_id INT NOT NULL, PRIMARY KEY(groupe_competence_id, competence_id))');
        $this->addSql('CREATE INDEX IDX_F64AE85C89034830 ON groupe_competence_competence (groupe_competence_id)');
        $this->addSql('CREATE INDEX IDX_F64AE85C15761DAB ON groupe_competence_competence (competence_id)');
        $this->addSql('CREATE TABLE groupe_tag (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE groupe_tag_tag (groupe_tag_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(groupe_tag_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_C430CACFD1EC9F2B ON groupe_tag_tag (groupe_tag_id)');
        $this->addSql('CREATE INDEX IDX_C430CACFBAD26311 ON groupe_tag_tag (tag_id)');
        $this->addSql('CREATE TABLE niveau (id INT NOT NULL, competence_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, critere_evaluation TEXT NOT NULL, groupe_action TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4BDFF36B15761DAB ON niveau (competence_id)');
        $this->addSql('CREATE TABLE profil (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE profil_sortie (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE promo (id INT NOT NULL, referentiel_id INT DEFAULT NULL, user_id INT NOT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description TEXT NOT NULL, lieu VARCHAR(255) DEFAULT NULL, date_debut DATE NOT NULL, date_provisoire DATE NOT NULL, date_fin DATE DEFAULT NULL, fabrique VARCHAR(255) NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B0139AFB805DB139 ON promo (referentiel_id)');
        $this->addSql('CREATE INDEX IDX_B0139AFBA76ED395 ON promo (user_id)');
        $this->addSql('CREATE TABLE promo_formateur (promo_id INT NOT NULL, formateur_id INT NOT NULL, PRIMARY KEY(promo_id, formateur_id))');
        $this->addSql('CREATE INDEX IDX_C5BC19F4D0C07AFF ON promo_formateur (promo_id)');
        $this->addSql('CREATE INDEX IDX_C5BC19F4155D8F51 ON promo_formateur (formateur_id)');
        $this->addSql('CREATE TABLE referentiel (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation TEXT NOT NULL, programme BYTEA DEFAULT NULL, critere_evaluation TEXT NOT NULL, critere_admission TEXT NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE referentiel_groupe_competence (referentiel_id INT NOT NULL, groupe_competence_id INT NOT NULL, PRIMARY KEY(referentiel_id, groupe_competence_id))');
        $this->addSql('CREATE INDEX IDX_EC387D5B805DB139 ON referentiel_groupe_competence (referentiel_id)');
        $this->addSql('CREATE INDEX IDX_EC387D5B89034830 ON referentiel_groupe_competence (groupe_competence_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif TEXT NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, profil_id INT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, avatar BYTEA DEFAULT NULL, statut BOOLEAN NOT NULL, is_deleted BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX IDX_1483A5E9275ED078 ON users (profil_id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E6409EF73 FOREIGN KEY (profil_sortie_id) REFERENCES profil_sortie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EBF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE community_manager ADD CONSTRAINT FK_DEE14CEABF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4FBF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD787A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD78155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_competence_competence ADD CONSTRAINT FK_F64AE85C89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_competence_competence ADD CONSTRAINT FK_F64AE85C15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFD1EC9F2B FOREIGN KEY (groupe_tag_id) REFERENCES groupe_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo_formateur ADD CONSTRAINT FK_C5BC19F4D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo_formateur ADD CONSTRAINT FK_C5BC19F4155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referentiel_groupe_competence ADD CONSTRAINT FK_EC387D5B805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referentiel_groupe_competence ADD CONSTRAINT FK_EC387D5B89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE groupe_apprenant DROP CONSTRAINT FK_947F9519C5697D6D');
        $this->addSql('ALTER TABLE groupe_competence_competence DROP CONSTRAINT FK_F64AE85C15761DAB');
        $this->addSql('ALTER TABLE niveau DROP CONSTRAINT FK_4BDFF36B15761DAB');
        $this->addSql('ALTER TABLE groupe_formateur DROP CONSTRAINT FK_BDE2AD78155D8F51');
        $this->addSql('ALTER TABLE promo_formateur DROP CONSTRAINT FK_C5BC19F4155D8F51');
        $this->addSql('ALTER TABLE groupe_formateur DROP CONSTRAINT FK_BDE2AD787A45358C');
        $this->addSql('ALTER TABLE groupe_apprenant DROP CONSTRAINT FK_947F95197A45358C');
        $this->addSql('ALTER TABLE groupe_competence_competence DROP CONSTRAINT FK_F64AE85C89034830');
        $this->addSql('ALTER TABLE referentiel_groupe_competence DROP CONSTRAINT FK_EC387D5B89034830');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP CONSTRAINT FK_C430CACFD1EC9F2B');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9275ED078');
        $this->addSql('ALTER TABLE apprenant DROP CONSTRAINT FK_C4EB462E6409EF73');
        $this->addSql('ALTER TABLE groupe DROP CONSTRAINT FK_4B98C21D0C07AFF');
        $this->addSql('ALTER TABLE promo_formateur DROP CONSTRAINT FK_C5BC19F4D0C07AFF');
        $this->addSql('ALTER TABLE promo DROP CONSTRAINT FK_B0139AFB805DB139');
        $this->addSql('ALTER TABLE referentiel_groupe_competence DROP CONSTRAINT FK_EC387D5B805DB139');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP CONSTRAINT FK_C430CACFBAD26311');
        $this->addSql('ALTER TABLE apprenant DROP CONSTRAINT FK_C4EB462EBF396750');
        $this->addSql('ALTER TABLE community_manager DROP CONSTRAINT FK_DEE14CEABF396750');
        $this->addSql('ALTER TABLE formateur DROP CONSTRAINT FK_ED767E4FBF396750');
        $this->addSql('ALTER TABLE promo DROP CONSTRAINT FK_B0139AFBA76ED395');
        $this->addSql('DROP SEQUENCE competence_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE groupe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE groupe_competence_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE groupe_tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE niveau_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profil_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profil_sortie_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE promo_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE referentiel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE apprenant');
        $this->addSql('DROP TABLE community_manager');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_formateur');
        $this->addSql('DROP TABLE groupe_apprenant');
        $this->addSql('DROP TABLE groupe_competence');
        $this->addSql('DROP TABLE groupe_competence_competence');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_tag');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_sortie');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE promo_formateur');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE referentiel_groupe_competence');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE users');
    }
}
