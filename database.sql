-- =============================================
-- DATABASE ERBORISTERIA CECCATO - HOMEPAGE CMS
-- =============================================

CREATE DATABASE IF NOT EXISTS erboristeria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE erboristeria_db;

-- Tabella Categorie
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    icona VARCHAR(10) DEFAULT '🌿',
    ordine INT DEFAULT 0
);

-- Tabella Prodotti
CREATE TABLE prodotti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descrizione TEXT,
    prezzo DECIMAL(10,2) NOT NULL,
    prezzo_originale DECIMAL(10,2) DEFAULT NULL,
    icona VARCHAR(10) DEFAULT '🌿',
    categoria_id INT,
    badge VARCHAR(50) DEFAULT NULL,
    in_evidenza BOOLEAN DEFAULT FALSE,
    attivo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorie(id)
);

-- Tabella Slider
CREATE TABLE slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(200),
    sottotitolo VARCHAR(300),
    immagine VARCHAR(500),
    link VARCHAR(300),
    colore_bg VARCHAR(100) DEFAULT 'linear-gradient(135deg, #4a7c23 0%, #8fbc3b 100%)',
    ordine INT DEFAULT 0,
    attivo BOOLEAN DEFAULT TRUE
);

-- Tabella Video
CREATE TABLE video (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(200) NOT NULL,
    url VARCHAR(500) NOT NULL,
    ordine INT DEFAULT 0,
    attivo BOOLEAN DEFAULT TRUE
);

-- Tabella Fornitori (loghi)
CREATE TABLE fornitori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    icona VARCHAR(10) DEFAULT '🏭',
    logo_url VARCHAR(500) DEFAULT NULL,
    ordine INT DEFAULT 0,
    attivo BOOLEAN DEFAULT TRUE
);

-- =============================================
-- DATI INIZIALI
-- =============================================

-- Categorie
INSERT INTO categorie (nome, slug, icona, ordine) VALUES
('Tisane e Infusi', 'tisane-infusi', '🍵', 1),
('Integratori', 'integratori', '💊', 2),
('Cosmesi Naturale', 'cosmesi-naturale', '🧴', 3),
('Oli Essenziali', 'oli-essenziali', '🫒', 4),
('Cristalli e Pietre', 'cristalli-pietre', '💎', 5),
('Erbe e Spezie', 'erbe-spezie', '🌿', 6);

-- Prodotti in evidenza (quelli della homepage)
INSERT INTO prodotti (nome, descrizione, prezzo, prezzo_originale, icona, categoria_id, badge, in_evidenza) VALUES
('Camomilla Bio', 'Camomilla biologica italiana', 8.90, 12.50, '🌼', 1, 'OFFERTA', TRUE),
('Olio Lavanda', 'Olio essenziale puro 100%', 15.90, NULL, '💜', 4, 'NUOVO', TRUE),
('Ametista', 'Cristallo brasiliano', 22.00, NULL, '💎', 5, NULL, TRUE),
('Vitamina C', 'Integratore alto dosaggio', 18.50, NULL, '💊', 2, NULL, TRUE),
('Crema Calendula', 'Crema naturale lenitiva', 24.90, 29.90, '🌻', 3, 'OFFERTA', TRUE),
('Curcuma', 'Spezia biologica pura', 6.50, NULL, '🌿', 6, NULL, TRUE),
('Quarzo Rosa', 'Cristallo dell\'amore', 18.00, NULL, '💗', 5, 'NUOVO', TRUE),
('Magnesio', 'Integratore magnesio', 21.90, NULL, '⚡', 2, NULL, TRUE);

-- Slide
INSERT INTO slider (titolo, sottotitolo, colore_bg, ordine) VALUES
('Benvenuti nella Natura', 'Scopri i nostri prodotti biologici', 'linear-gradient(135deg, #4a7c23 0%, #8fbc3b 100%)', 1),
('Offerte Speciali', 'Fino al 30% di sconto', 'linear-gradient(135deg, #f4d03f 0%, #c5b358 100%)', 2),
('Cristalloterapia', 'Nuova collezione cristalli', 'linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%)', 3);

-- Video
INSERT INTO video (titolo, url, ordine) VALUES
('Introduzione Erbe Officinali', 'https://www.w3schools.com/html/mov_bbb.mp4', 1),
('Guida Oli Essenziali', 'https://www.w3schools.com/html/movie.mp4', 2),
('Cristalloterapia', 'https://www.w3schools.com/html/mov_bbb.mp4', 3),
('Integratori Naturali', 'https://www.w3schools.com/html/movie.mp4', 4),
('Cosmesi Naturale', 'https://www.w3schools.com/html/mov_bbb.mp4', 5);

-- Fornitori
INSERT INTO fornitori (nome, icona, ordine) VALUES
('Aboca', '🌱', 1),
('Specchiasol', '🌿', 2),
('Erbamea', '🍃', 3),
('Solgar', '💊', 4),
('ESI', '🧴', 5),
('Named', '⚗️', 6),
('Zuccari', '🍯', 7),
('Naturando', '🌻', 8),
('Helan', '🌸', 9),
('L\'Erbolario', '🌺', 10),
('Bios Line', '🧬', 11),
('Planta Medica', '🏥', 12);
