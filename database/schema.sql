-- ============================================================
-- SCHEMA.SQL — Application de Supervision Bancaire
-- Base de données : MySQL 5.7+
-- Auteur : Architecte Logiciel Senior
-- Date   : 2026-03-04
-- ============================================================

CREATE DATABASE IF NOT EXISTS supervision_bancaire
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE supervision_bancaire;

-- ============================================================
-- TABLE : users
-- Gestion des utilisateurs applicatifs (admin / user)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt',
    role       ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : client
-- Référentiel des comptes clients bancaires
-- ============================================================
CREATE TABLE IF NOT EXISTS client (
    numero_compte INT PRIMARY KEY,
    nom_client    VARCHAR(100) NOT NULL,
    solde         DECIMAL(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : versement
-- Enregistrement des versements par chèque
-- ============================================================
CREATE TABLE IF NOT EXISTS versement (
    numero_versement INT AUTO_INCREMENT PRIMARY KEY,
    numero_cheque    VARCHAR(50) NOT NULL,
    numero_compte    INT         NOT NULL,
    montant          DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (numero_compte) REFERENCES client(numero_compte)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : audit_versement
-- Journal d'audit alimenté automatiquement par les triggers
-- ============================================================
CREATE TABLE IF NOT EXISTS audit_versement (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    type_action      ENUM('INSERT','UPDATE','DELETE') NOT NULL,
    date_operation   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    numero_versement INT,
    numero_compte    INT,
    nom_client       VARCHAR(100),
    montant_ancien   DECIMAL(15,2),
    montant_nouveau  DECIMAL(15,2),
    utilisateur      VARCHAR(100),
    machine_hote     VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- TRIGGERS
-- ============================================================

DELIMITER $$

-- ------------------------------------------------------------
-- TRIGGER : after_versement_insert
-- Déclenché après chaque INSERT dans versement
-- Met à jour le solde du client et trace l'opération dans audit
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS after_versement_insert$$

CREATE TRIGGER after_versement_insert
AFTER INSERT ON versement
FOR EACH ROW
BEGIN
    -- Mise à jour du solde : ancien solde + montant inséré
    UPDATE client
    SET    solde = solde + NEW.montant
    WHERE  numero_compte = NEW.numero_compte;

    -- Insertion dans le journal d'audit
    INSERT INTO audit_versement (
        type_action,
        date_operation,
        numero_versement,
        numero_compte,
        nom_client,
        montant_ancien,
        montant_nouveau,
        utilisateur,
        machine_hote
    )
    SELECT
        'INSERT',
        NOW(),
        NEW.numero_versement,
        NEW.numero_compte,
        c.nom_client,
        0.00,
        NEW.montant,
        COALESCE(@app_user, USER()),
        @@hostname
    FROM client c
    WHERE c.numero_compte = NEW.numero_compte;
END$$


-- ------------------------------------------------------------
-- TRIGGER : after_versement_update
-- Déclenché après chaque UPDATE dans versement
-- Recalcule le delta de montant et trace la modification
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS after_versement_update$$

CREATE TRIGGER after_versement_update
AFTER UPDATE ON versement
FOR EACH ROW
BEGIN
    -- Ajustement du solde : soustraire l'ancien montant, ajouter le nouveau
    UPDATE client
    SET    solde = solde - OLD.montant + NEW.montant
    WHERE  numero_compte = NEW.numero_compte;

    -- Si le compte rattaché a changé, corriger le solde de l'ancien compte
    IF OLD.numero_compte <> NEW.numero_compte THEN
        UPDATE client
        SET    solde = solde - NEW.montant
        WHERE  numero_compte = NEW.numero_compte;

        UPDATE client
        SET    solde = solde - OLD.montant
        WHERE  numero_compte = OLD.numero_compte;
    END IF;

    -- Insertion dans le journal d'audit
    INSERT INTO audit_versement (
        type_action,
        date_operation,
        numero_versement,
        numero_compte,
        nom_client,
        montant_ancien,
        montant_nouveau,
        utilisateur,
        machine_hote
    )
    SELECT
        'UPDATE',
        NOW(),
        NEW.numero_versement,
        NEW.numero_compte,
        c.nom_client,
        OLD.montant,
        NEW.montant,
        COALESCE(@app_user, USER()),
        @@hostname
    FROM client c
    WHERE c.numero_compte = NEW.numero_compte;
END$$


-- ------------------------------------------------------------
-- TRIGGER : after_versement_delete
-- Déclenché après chaque DELETE dans versement
-- Déduit le montant supprimé du solde et trace la suppression
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS after_versement_delete$$

CREATE TRIGGER after_versement_delete
AFTER DELETE ON versement
FOR EACH ROW
BEGIN
    -- Déduction du montant supprimé du solde client
    UPDATE client
    SET    solde = solde - OLD.montant
    WHERE  numero_compte = OLD.numero_compte;

    -- Insertion dans le journal d'audit
    INSERT INTO audit_versement (
        type_action,
        date_operation,
        numero_versement,
        numero_compte,
        nom_client,
        montant_ancien,
        montant_nouveau,
        utilisateur,
        machine_hote
    )
    SELECT
        'DELETE',
        NOW(),
        OLD.numero_versement,
        OLD.numero_compte,
        c.nom_client,
        OLD.montant,
        0.00,
        COALESCE(@app_user, USER()),
        @@hostname
    FROM client c
    WHERE c.numero_compte = OLD.numero_compte;
END$$

DELIMITER ;


-- ============================================================
-- DONNEES DE DEMONSTRATION
-- ============================================================

-- Utilisateurs applicatifs
-- admin : Admin123!   |   user1 : User123!
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$RO3cwwFspqC6L7dpTvOrlO/x7HkONMZ6d5CdxNcQqBavYL6x.t7T.', 'admin'),
('user1', '$2y$10$LLSAQxmYiGgrdrnZVpdqOeqPMTAhlyK3wa7L32hkooO3aJbUo4fj6', 'user');

-- Comptes clients
INSERT INTO client (numero_compte, nom_client, solde) VALUES
(1001, 'Dupont Martin',    0.00),
(1002, 'Bernard Sophie',   0.00),
(1003, 'Leroy Thomas',     0.00),
(1004, 'Moreau Isabelle',  0.00),
(1005, 'Laurent Pierre',   0.00);
