-- Création de la base de données (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS endless_db;
--  Cette ligne crée une nouvelle base de données appelée "endless_db" si elle n'existe pas déjà.
--  C'est comme créer un nouveau classeur vide qui contiendra toutes nos tables.

USE endless_db; 
-- ! Je reprends endless_db pour le PDO
-- Cette ligne indique à MySQL d'utiliser la base de données "endless_db" pour toutes les commandes suivantes.
--  C'est comme ouvrir ce classeur pour y travailler.

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    -- Nous commençons à créer une nouvelle table nommée "users"
    -- si elle n'existe pas déjà. Cette table va stocker les informations de tous les utilisateurs du site.
    id INT AUTO_INCREMENT PRIMARY KEY, 
    -- La colonne "id" est un nombre entier qui s'incrémente automatiquement (commence à 1, puis 2, 3, etc.)
    -- pour chaque nouvel utilisateur. PRIMARY KEY indique que c'est l'identifiant unique de chaque utilisateur.
    email VARCHAR(100) NOT NULL UNIQUE,
    -- Explication : La colonne "email" stocke l'adresse email de l'utilisateur.
    -- VARCHAR(100) : peut contenir jusqu'à 100 caractères
    -- NOT NULL : ce champ doit obligatoirement être rempli
    -- UNIQUE : deux utilisateurs ne peuvent pas avoir le même email
    password VARCHAR(255) NOT NULL,
    -- Stocke le mot de passe crypté de l'utilisateur. On utilise VARCHAR(255) car les mots de passe cryptés sont généralement longs.
    -- Supposons que votre mot de passe soit motdepasse123
    -- Mot de passe original : motdepasse123
    -- Mot de passe crypté : $2y$10$h.KxQEIH7VH5j6HM1x3NLOov0BYA7VT.NXxmNL1YkTk.YPYPjQlA6
    -- Comme vous pouvez le voir, le mot de passe crypté est complètement différent et illisible !
    -- Imaginez que votre base de données soit piratée. Si les mots de passe sont stockés en clair (non cryptés), le pirate peut :
    -- - Voir les mots de passe
    -- - Se connecter à tous les comptes
    -- - Utilisr ces mots de passe sur d'autres sites
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    -- Stocke le prénom et le nom de l'utilisateur. Chaque champ peut contenir jusqu'à 50 caractères et ne peut pas être vide.
    phone VARCHAR(20) NOT NULL,
    phone_prefix VARCHAR(10) NOT NULL,
    -- Stocke le numéro de téléphone et son préfixe international (comme +33 pour la France).
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    -- Enregistre automatiquement la date et l'heure auxquelles l'utilisateur s'est inscrit.
    -- DEFAULT CURRENT_TIMESTAMP signifie que si aucune valeur n'est spécifiée, le système utilisera la date et l'heure actuelles
    last_login TIMESTAMP NULL,
    --  Stocke la date et l'heure de la dernière connexion de l'utilisateur.
    --  NULL signifie que ce champ peut être vide (par exemple, si l'utilisateur ne s'est jamais connecté).
    role ENUM('user', 'admin') DEFAULT 'user',
    -- Définit le rôle de l'utilisateur. ENUM signifie que seules deux valeurs sont autorisées :
    -- 'utilisateur' ou 'administrateur'. Par défaut, chaque nouveau compte est un 'utilisateur'.
    email_verified BOOLEAN DEFAULT FALSE,
    login_attempts INT NOT NULL DEFAULT 0,
    last_login_attempts TIMESTAMP DEFAULT NULL
    -- Le mail n'est pas vérifié.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Termine la définition de la table.
-- ENGINE=InnoDB : utilise le moteur InnoDB qui supporte les clés étrangères
-- CHARSET=utf8mb4 : permet de stocker des caractères spéciaux, accents, émojis, etc.


-- Table des formations
CREATE TABLE `formations` (
  `id` int NOT NULL COMMENT 'Comme pour la table utilisateurs, chaque formation aura un identifiant unique qui s incrémente automatiquement.',
  `slug` varchar(50) NOT NULL COMMENT 'Il s agit d un identifiant unique pour chaque formation, utilisé dans les URL. Par exemple, pour la formation "Manucure Russe", le slug pourrait être "manucure_russe".',
  `title` varchar(100) NOT NULL COMMENT 'Le nom de la formation (max 100 caractères)',
  `description` text NOT NULL COMMENT 'Un court résumé de la formation',
  `content` text NOT NULL COMMENT 'Description détaillée de la formation, TEXT permet de stocker des textes plus longs que VARCHAR',
  `duration` varchar(50) NOT NULL COMMENT 'La durée de la formation (ex: "1H30 à 2H")',
  `price` decimal(10,2) NOT NULL COMMENT 'Le prix normal de la formation',
  `discount_price` decimal(10,2) DEFAULT NULL COMMENT 'Le prix en promotion (peut être NULL si pas de promotion) DECIMAL(10, 2) signifie un nombre avec jusqu à 10 chiffres au total, dont 2 après la virgule (ex: 1234567.89)',
  `image_path` varchar(255) NOT NULL COMMENT 'Le chemin vers l image de la formation (ex: "/Endless-/Images/...")',
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Enregistre automatiquement quand la formation a été ajoutée au système.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `formations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);


-- Table des achats de formations
CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    formation_id INT NOT NULL,
-- id : Identifiant unique pour chaque achat
-- id_utilisateur : Identifie quel utilisateur a fait l'achat
-- id_formation : Identifie quelle formation a été achetée
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
-- Enregistre automatiquement quand l'achat a été effectué.
    price_paid DECIMAL(10, 2) NOT NULL,
-- Le montant payé par l'utilisateur (peut être différent du prix normal si promotion).
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
-- L'état du paiement. Seules trois valeurs sont possibles :
-- en_attente : Paiement en cours de traitement
-- complete : Paiement réussi
-- echoue : Paiement échoué
    payment_method VARCHAR(50) NOT NULL,
--  Comment l'utilisateur a payé (ex: "carte bancaire", "PayPal").
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE NO ACTION,
-- Cette ligne crée un lien (relation) entre cette table et la table utilisateurs.

-- FOREIGN KEY : Indique que id_utilisateur est une clé étrangère
-- REFERENCES utilisateurs(id) : Elle fait référence à la colonne id de la table utilisateurs
-- ON DELETE CASCADE : Si un utilisateur est supprimé, tous ses achats seront également supprimés
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE RESTRICT
-- Crée un lien avec la table formations
-- ON DELETE RESTRICT : Empêche la suppression d'une formation si elle a déjà été achetée par quelqu'un
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Table des modules de formation
CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
--     id = Le nom de la colonne
-- INT = Le type de données (Integer = nombre entier)
-- AUTO_INCREMENT = Se incrémente automatiquement
-- PRIMARY KEY = Clé primaire (identifiant unique)
    -- INT signifie "Integer" (nombre entier)
    formation_id INT NOT NULL,
    -- Chaque module appartient à une formation spécifique.
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    order_number INT NOT NULL,
    -- L'ordre dans lequel les modules doivent être présentés (1, 2, 3, etc.).
    video_path VARCHAR(255) NULL,
    -- Le chemin vers la vidéo du module. Peut être NULL si la vidéo n'est pas encore disponible.
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE
    --  Si une formation est supprimée, tous ses modules seront également supprimés.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table de progression des utilisateurs
CREATE TABLE IF NOT EXISTS user_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    last_viewed TIMESTAMP NULL,
    viewing_time INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE KEY user_module (user_id, module_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des paniers
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des éléments du panier
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    formation_id INT NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE,
    UNIQUE KEY cart_formation (cart_id, formation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les réinitalisations de mot de passe
CREATE TABLE IF NOT EXISTS password_reset (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_token VARCHAR(255) NOT NULL,
    expiry_date TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour confirmer les emails (comme password_reset mais pour les emails)
CREATE TABLE IF NOT EXISTS email_confirmations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,  -- : quel utilisateur doit confirmer son email
    confirmation_token VARCHAR(255) NOT NULL,--    : le fameux token secret
    expiry_date TIMESTAMP NOT NULL,-- :quand le token expire
    used BOOLEAN DEFAULT FALSE, -- :est-ce que le token a déjà été utilisé ?
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données initiales pour les formations (basées sur votre page d'accueil)
INSERT INTO `formations` (`id`, `slug`, `title`, `description`, `content`, `duration`, `price`, `discount_price`, `image_path`, `creation_date`) VALUES
(1, 'manucure_russe', 'Manucure Russe', 'Maîtrisez la technique révolutionnaire de la manucure russe. Apprenez les gestes précis et les outils spécialisés pour des résultats parfaits.', '<h2>Tout ce qu\'il faut savoir</h2>\n    <p>\n        Tu veux devenir une pro de la manucure russe ou perfectionner ta technique ?<br>\n        La formation Manucure russe est faite pour toi ! Que tu sois débutante passionnée ou déjà en activité, cette formation complète t’apprend toutes les étapes clés pour réaliser des manucures impeccables, durables et professionnelles.\n    </p>\n    <h3>Ce que tu vas apprendre :</h3>\n    <ul>\n        <li>Les bases essentielles de l’hygiène et de la préparation de l’ongle</li>\n        <li>Le matériel indispensable et comment bien l’utiliser</li>\n        <li>Les techniques de manucure russe et combinée</li>\n        <li>Le gainage sur l’ongle naturel : renforcement pour une meilleure tenue sans extension</li>\n        <li>Le limage parfait pour une forme harmonieuse</li>\n        <li>Application de la couleur sous cuticules : effet fondu et propre pour un rendu ultra net</li>\n        <li>Les erreurs courantes à éviter et les astuces de pro pour un rendu net et durable</li>\n        <li>La dépose complète, expliquée étape par étape pour respecter l’ongle naturel</li>\n    </ul>\n    <h3>À qui s’adresse cette formation ?</h3>\n    <ul>\n        <li>Aux débutantes qui souhaitent se lancer dans l’univers de la manucure</li>\n        <li>Aux pros en activité qui veulent revoir leurs bases ou apprendre de nouvelles techniques</li>\n        <li>Aux passionnées qui souhaitent gagner en confiance et qualité dans leurs prestations</li>\n    </ul>\n    <h3>Informations pratiques :</h3>\n    <ul>\n        <li>100 % en ligne – Accès illimité, à ton rythme</li>\n        <li>Durée : environ XXXX de vidéo découpée en modules clairs</li>\n        <li>Visionnage illimité à vie</li>\n        <li>Support PDF téléchargeable : livret de formation</li>\n        <li>Bonus : Protocole complet de stérilisation du matériel pour garantir des prestations sûres et conformes aux normes d’hygiène</li>\n    </ul>', '1H30 à 2H', 150.00, NULL, '/Images/Photos\\ Formations/IMG_4015.jpeg', '2025-06-12 11:36:42'),
(2, 'extension_gel_x', 'Extension Gel X', 'Devenez experte en extensions Gel X. Technique moderne et durable pour des ongles parfaits et une tenue exceptionnelle.', 'Maîtrisez les extensions Gel X pour des ongles parfaits', '2H à 2H30', 200.00, NULL, '/Images/Photos\\ Formations/GelX.jpg', '2025-06-12 11:36:42'),
(3, 'beaute_des_pieds', 'Beauté des pieds', 'Perfectionnez vos techniques de pédicure professionnelle. Soins complets pour la beauté et la santé des pieds.', 'Découvrez les secrets d\'une pédicure professionnelle', '1H30 à 2H', 180.00, NULL, '/Images/Photos\\ Formations/Soin-pied.jpg', '2025-06-12 11:36:42'),
(4, 'soin_anti-callosite_des_pieds', 'Soin anti-callosité des pieds', 'Spécialisez-vous dans le traitement des callosités. Techniques avancées pour des pieds doux et en parfaite santé.', 'Techniques efficaces pour éliminer les callosités', '1H à 1H30', 120.00, NULL, '/Images/Photos\\ Formations/Anti-callosite.jpg', '2025-06-12 11:36:42'),
(5, 'pack_complet', 'PACK COMPLET', 'Formation complète incluant toutes nos spécialités. Devenez une professionnelle polyvalente avec notre pack tout-en-un.', 'Accédez à toutes nos formations à prix réduit', 'Environ 8H', 500.00, 450.00, '/Images/Photos\\ Formations/Pack-complet.png', '2025-06-12 11:36:42');

-- Création d'un compte administrateur pour gérer le site (mot de passe: admin123)
-- Note: En production, utilisez un mot de passe fort et sécurisé!


--  + COMPREHENSION DE NULL et NOT_NULL :

-- Qu'est-ce que NULL ?
-- NULL signifie "vide", "rien", "aucune valeur", "inconnu" ou "non renseigné". C'est différent de zéro (0) ou d'une chaîne vide ("").

-- Imaginez un formulaire papier :

-- NULL = case laissée complètement vide
-- Chaîne vide ("") = case où vous avez écrit mais effacé tout
-- Zéro (0) = case où vous avez écrit "0"

-- Exemple concret : 
-- Formations avec prix réduit

-- CREATE TABLE formations (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     titre VARCHAR(100) NOT NULL,      -- Obligatoire
--     prix DECIMAL(10, 2) NOT NULL,     -- Obligatoire
--     prix_reduit DECIMAL(10, 2) NULL   -- Optionnel
-- );

-- Formation sans promotion : 
-- INSERT INTO formations (titre, prix) 
-- VALUES ('Manucure Russe', 150.00);

-- id      titre                   prix            prix reduit
-- 1       Manucure Russe          150€            NULL

-- Cette formation n'a pas de prix réduit, donc prix_reduit est NULL


-- Maintenant voyons avec une formation avec promotion :

-- INSERT INTO formations (titre, prix, prix_reduit) 
-- VALUES ('Pack Complet', 500.00, 450.00);

-- id              titre                   prix                    prix_reduit
-- 1               Manucure Russe          150.00                  NULL
-- 2               Pack Complet            500.00                  450.00


-- Le Pack Complet a une promotion, donc prix_reduit a une vraie valeur.
-- Quand vous mettez NOT NULL, cela signifie que ce champ DOIT toujours avoir une valeur.

-- Base de données pour Endless Beauty
-- À créer dans votre phpMyAdmin ou interface MySQL


-- Table des services
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    duree INT NOT NULL, -- en minutes
    prix DECIMAL(10,2) NOT NULL,
    categorie VARCHAR(50),
    description TEXT,
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des services complets d'Endless Beauty
INSERT INTO services (nom, duree, prix, categorie, description) VALUES
-- BEAUTÉ DES MAINS
('Manucure Russe seule', 30, 35.00, 'BEAUTÉ DES MAINS', ''),
('Gainage nude (sur ongle nu)', 60, 50.00, 'BEAUTÉ DES MAINS', 'Sur ongle nu'),
('Gainage avec couleur (sur ongle nu)', 70, 55.00, 'BEAUTÉ DES MAINS', 'Sur ongle nu'),
('Remplissage gainage nude', 70, 55.00, 'BEAUTÉ DES MAINS', '⚠️ Toute pose extérieure entraînera un supplément de 10€'),
('Remplissage gainage + couleur', 80, 60.00, 'BEAUTÉ DES MAINS', '⚠️ Toute pose extérieure entraînera un supplément de 10€'),
('Dépose complète', 30, 25.00, 'BEAUTÉ DES MAINS', ''),

-- RALLONGEMENT ONGLES
('Rallongement capsules Gel X', 95, 65.00, 'RALLONGEMENT ONGLES', ''),
('Dépose + repose capsules Gel X', 100, 70.00, 'RALLONGEMENT ONGLES', ''),

-- BEAUTÉ DES PIEDS
('Beauté des pieds Russe seule', 30, 35.00, 'BEAUTÉ DES PIEDS', ''),
('Renfort base nude (sur ongle nu)', 60, 45.00, 'BEAUTÉ DES PIEDS', 'Sur ongle nu'),
('Renfort avec couleur (sur ongle nu)', 70, 50.00, 'BEAUTÉ DES PIEDS', 'Sur ongle nu'),
('Dépose + renfort base nude', 70, 50.00, 'BEAUTÉ DES PIEDS', ''),
('Dépose + renfort + couleur', 75, 55.00, 'BEAUTÉ DES PIEDS', ''),
('Traitement anti-callosités + renfort', 95, 90.00, 'BEAUTÉ DES PIEDS', ''),
('Traitement anti-callosités + beauté des pieds russe seule', 70, 80.00, 'BEAUTÉ DES PIEDS', ''),
('Dépose complète pieds', 30, 25.00, 'BEAUTÉ DES PIEDS', ''),

-- BEAUTÉ DU REGARD
('Teinture cils ou sourcils', 15, 10.00, 'BEAUTÉ DU REGARD', ''),
('Réhaussement des cils', 40, 40.00, 'BEAUTÉ DU REGARD', ''),

-- ÉPILATIONS
('Sourcils', 12, 10.00, 'ÉPILATIONS', ''),
('Lèvres & Sourcils', 15, 15.00, 'ÉPILATIONS', ''),
('Lèvre ou Menton', 10, 7.00, 'ÉPILATIONS', ''),
('Aisselles', 10, 10.00, 'ÉPILATIONS', ''),
('1/2 Jambes', 20, 20.00, 'ÉPILATIONS', ''),
('Jambes entières', 30, 30.00, 'ÉPILATIONS', ''),
('Maillot intégral + inter fessier', 30, 25.00, 'ÉPILATIONS', ''),
('Maillot américain', 30, 20.00, 'ÉPILATIONS', ''),
('Maillot échancré', 15, 15.00, 'ÉPILATIONS', ''),
('Sillon inter fessier (SIF)', 5, 5.00, 'ÉPILATIONS', ''),
('1/2 Bras', 20, 15.00, 'ÉPILATIONS', ''),
('Bras entiers', 30, 20.00, 'ÉPILATIONS', ''),
('Bas du dos', 10, 15.00, 'ÉPILATIONS', '');

-- Table des suppléments
CREATE TABLE supplements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    duree INT, -- en minutes
    prix DECIMAL(10,2) NOT NULL,
    description TEXT
);

INSERT INTO supplements (nom, duree, prix, description) VALUES
('French / Baby Boomer', 10, 10.00, ''),
('Effet chrome', 5, 10.00, ''),
('Reconstruction d\'ongle en acrygel', 10, 3.00, 'Par ongle'),
('Incrustation sur un ongle', 3, 3.00, 'Par ongle'),
('Motif', 10, 0.00, 'Sur devis'),
('Supplément ongles longs (+5mm)', 0, 5.00, 'Automatique si ongles > 5mm'),
('Supplément pose extérieure', 0, 10.00, 'Pour remplissage après pose extérieure');

-- Table des rendez-vous
CREATE TABLE rendez_vous (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_client VARCHAR(100) NOT NULL,
    prothesiste VARCHAR(100) NOT NULL, -- Nom du prothésiste
    service_id INT,
    service_nom VARCHAR(100), -- Dupliqué pour historique
    service_prix DECIMAL(10,2),
    service_duree INT,
    supplements JSON, -- Pour stocker les suppléments choisis
    date_rdv DATE NOT NULL,
    heure_rdv TIME NOT NULL,
    notes TEXT,
    statut ENUM('en_attente', 'confirmé', 'annulé', 'terminé') DEFAULT 'confirmé',
    accompte_requis BOOLEAN DEFAULT FALSE,
    accompte_montant DECIMAL(10,2),
    accompte_paye BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id)
);



-- Table des paramètres du salon
CREATE TABLE parametres_salon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(50) UNIQUE,
    valeur TEXT,
    description TEXT
);

INSERT INTO parametres_salon (cle, valeur, description) VALUES
('nom_salon', 'Endless Beauty', 'Nom du salon'),
('adresse', '37 Rue de la Cousinerie, 59650 Villeneuve-d\'Ascq', 'Adresse complète'),
('telephone', '', 'Téléphone du salon'),
('email', '', 'Email du salon'),
('accompte_pourcentage', '50', 'Pourcentage d\'accompte pour nouvelles clientes'),
('accompte_obligatoire_nouvelle_cliente', 'true', 'Accompte obligatoire pour nouvelles clientes'),
('duree_creneau', '15', 'Durée minimale d\'un créneau en minutes'),
('anticipation_max_jours', '60', 'Nombre de jours maximum pour réserver à l\'avance');

-- Index pour optimiser les performances
CREATE INDEX idx_rdv_date_heure ON rendez_vous(date_rdv, heure_rdv);
CREATE INDEX idx_rdv_statut ON rendez_vous(statut);
CREATE INDEX idx_services_categorie ON services(categorie);

-- Vue pour les créneaux disponibles
CREATE VIEW vue_creneaux_disponibles AS
SELECT 
    DATE(date_rdv) as date_libre,
    TIME(heure_rdv) as heure_libre,
    COUNT(*) as nb_rdv
FROM rendez_vous 
WHERE statut IN ('confirmé', 'en_attente') 
GROUP BY date_rdv, heure_rdv;


