-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 22 mars 2025 à 00:46
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `plateforme_examens`
--

-- --------------------------------------------------------

--
-- Structure de la table `copies_etudiants`
--

CREATE TABLE `copies_etudiants` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `sujet_examen_id` int(11) DEFAULT NULL,
  `fichier_chemin` varchar(255) DEFAULT NULL,
  `date_soumission` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `corriges`
--

CREATE TABLE `corriges` (
  `id` int(11) NOT NULL,
  `sujet_examen_id` int(11) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `fichier_chemin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `corriges`
--

INSERT INTO `corriges` (`id`, `sujet_examen_id`, `contenu`, `fichier_chemin`) VALUES
(5, 6, NULL, 'uploads/examen-fr_corrige.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `copie_etudiant_id` int(11) DEFAULT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `enseignant_id` int(11) DEFAULT NULL,
  `date_attribution` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rapports_plagiat`
--

CREATE TABLE `rapports_plagiat` (
  `id` int(11) NOT NULL,
  `copie_etudiant_id` int(11) DEFAULT NULL,
  `pourcentage_similarite` decimal(5,2) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `date_detection` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sujets_examen`
--

CREATE TABLE `sujets_examen` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `enseignant_id` int(11) DEFAULT NULL,
  `fichier_chemin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sujets_examen`
--

INSERT INTO `sujets_examen` (`id`, `titre`, `contenu`, `date_creation`, `enseignant_id`, `fichier_chemin`) VALUES
(6, 'Examen français', 'répondez aux questions', '2025-03-21 23:33:25', 9, 'uploads/examen-fr.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('enseignant','etudiant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'BARCOLA', 'BRADLEY', 'bradley@psg.sn', '$2y$10$9mPE4YC1NaN35/vhkYnD/u9mnd5uRSUTwQXFh2TegdhGwW5jrEYf6', 'enseignant'),
(2, 'momo', 'momo', 'momo@esp.sn', '$2y$10$vXwz/cEYDn6trelGajfRUeQ60uvz7LWPb.GSs8R2ZGyPZmnw/0ueq', 'enseignant'),
(7, 'diassy', 'ibn', 'diassy@esp.sn', '$2y$10$bmze7gasE6nGxC6B6iB31ulz1XUlAh8vKMFeDptVowhX6ApDzAruS', ''),
(9, 'leye', 'loa', 'loa@esp.sn', '$2y$10$OYs6ZcTxFt15B1sefIZyr.b4j.NrE6MU1TK.K.t5gqFcD4hoJfAxK', 'enseignant'),
(10, 'leye', 'idy', 'idy@esp.sn', '$2y$10$Z3WdNE5fnXv5MsK4iFtW8.s67MUx4kYuug2mFJ9H5tQRDwf5jW6bu', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `copies_etudiants`
--
ALTER TABLE `copies_etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `sujet_examen_id` (`sujet_examen_id`);

--
-- Index pour la table `corriges`
--
ALTER TABLE `corriges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sujet_examen_id` (`sujet_examen_id`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `copie_etudiant_id` (`copie_etudiant_id`),
  ADD KEY `enseignant_id` (`enseignant_id`);

--
-- Index pour la table `rapports_plagiat`
--
ALTER TABLE `rapports_plagiat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `copie_etudiant_id` (`copie_etudiant_id`);

--
-- Index pour la table `sujets_examen`
--
ALTER TABLE `sujets_examen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enseignant_id` (`enseignant_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `copies_etudiants`
--
ALTER TABLE `copies_etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `corriges`
--
ALTER TABLE `corriges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rapports_plagiat`
--
ALTER TABLE `rapports_plagiat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sujets_examen`
--
ALTER TABLE `sujets_examen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `copies_etudiants`
--
ALTER TABLE `copies_etudiants`
  ADD CONSTRAINT `copies_etudiants_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `copies_etudiants_ibfk_2` FOREIGN KEY (`sujet_examen_id`) REFERENCES `sujets_examen` (`id`);

--
-- Contraintes pour la table `corriges`
--
ALTER TABLE `corriges`
  ADD CONSTRAINT `corriges_ibfk_1` FOREIGN KEY (`sujet_examen_id`) REFERENCES `sujets_examen` (`id`);

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`copie_etudiant_id`) REFERENCES `copies_etudiants` (`id`),
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`enseignant_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `rapports_plagiat`
--
ALTER TABLE `rapports_plagiat`
  ADD CONSTRAINT `rapports_plagiat_ibfk_1` FOREIGN KEY (`copie_etudiant_id`) REFERENCES `copies_etudiants` (`id`);

--
-- Contraintes pour la table `sujets_examen`
--
ALTER TABLE `sujets_examen`
  ADD CONSTRAINT `sujets_examen_ibfk_1` FOREIGN KEY (`enseignant_id`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
