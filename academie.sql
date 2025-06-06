-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql
-- Généré le : ven. 06 juin 2025 à 13:48
-- Version du serveur : 8.0.42
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `academie`
--

-- --------------------------------------------------------

--
-- Structure de la table `bestiaire`
--

CREATE TABLE `bestiaire` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_id` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bestiaire`
--

INSERT INTO `bestiaire` (`id`, `nom`, `type`, `created_id`, `image`, `description`) VALUES
(15, 'kappa', 'Aquatique', 6, '1749198424_2293.jpg', 'Le kappa est décrit comme dégageant une odeur forte et fétide, semblable à une odeur de poisson. La plupart des représentations font du kappa une créature avec une queue extrêmement courte, voire dépourvue de cet appendice. Il est parfois décrit comme ayant trois anus.'),
(16, 'Elementaire d\'eau', 'Aquatique', 8, '1749201615_8439.jpg', '“L\'eau, ça mouille.” Un Élémentaire d\'eau est une créature primordiale constituée entièrement d\'eau (douce ou salée). Au repos, il est impossible de déceler la présence de cet élémentaire translucide, surtout s\'il se cache dans une nappe d\'eau.'),
(17, 'kirin', 'Aquatique', 9, '1749202043_3941.jpg', 'Le qilin, k\'ilin, kiling ou kirin (chinois : 麒麟, pinyin : qílín, EFEO : k\'i-lin ; japonais : 麒麟, kirin ; coréen : 기린, kirin ; vietnamien : kì lân ou kỳ lân) est un animal composite fabuleux issu de la mythologie chinoise possédant plusieurs apparences. Il tient généralement un peu du cerf et du cheval, possède un pelage, des écailles ou les deux, et une paire de cornes semblables aux bois d\'un cerf. Créature cosmogonique, symbole d\'harmonie et roi des animaux à pelage, il ne réside que dans les endroits paisibles ou au voisinage d’un sage, et en découvrir un est toujours un bon présage. Parfois représenté avec une corne unique, son nom est souvent traduit par licorne et il est régulièrement confondu avec le xièzhì, dans les langues occidentales.'),
(18, 'cerbere', 'Démoniaque', 7, '1749202148_2403.jpg', 'Il est représenté avec une queue de dragon, et des têtes de serpents sur l\'échine. Cerbère est un monstre de la mythologie grecque et romaine, préposé à la garde des Enfers, où il ne laisse pénétrer aucun humain vivant, d\'où il ne permet à aucune ombre de s\'échapper'),
(19, 'seigneur des abimes', 'Démoniaque', 7, '1749202181_1775.jpg', 'Les Seigneurs des abîmes sont en général de grand \"sac à points de vie\". Ils possèdent une grande force physique, ce qui leur permet d\'infliger de lourds dégâts. Ils ont des sorts s\'approchant plus de la démonologie. Ils utilisent une lance à double tranchants.'),
(20, 'succube', 'Démoniaque', 7, '1749202259_3740.jpg', 'Un succube est un démon judéo-chrétien féminin qui séduit les hommes et abuse d\'eux durant leur sommeil et leurs rêves. Les succubes servent Lilith. Leur pendant masculin est l\'incube. Des légendes racontent que le succube prendrait l\'apparence d\'une femme défunte, faisant croire à sa résurrection pour abuser d\'eux.'),
(21, 'tourmenteur', 'Démoniaque', 10, '1749202324_2397.jpg', 'Consumés par leur ego et leurs désirs, les Tourmenteurs constituent des troupes de première ligne agressives et rapides. Toujours avides de nouvelles expériences extrêmes, ils se délectent des morts sanglantes qu\'ils infligent à l\'ennemi.'),
(22, 'Centaure', 'Mi-bête', 10, '1749202628_6276.jpg', 'Dans la mythologie grecque, un centaure est une créature mi-homme, mi-cheval, que l\'on disait issue soit d\'Ixion et de Néphélé, soit de Centauros et des juments de Magnésie. Le plus célèbre des centaures est Chiron, immortel et chargé de former les jeunes héros'),
(23, 'cyclope', 'Mi-bête', 10, '1749202688_1952.jpg', 'Le cyclope ressemble à un géant bestial, avec de gros bras et des jambes arquées. Il possède un œil unique sur le front. Sa peau va du clair au brun.'),
(24, 'harpie', 'Mi-bête', 11, '1749202806_5270.jpg', 'Ce sont des divinités de la dévastation et de la vengeance divine. Plus rapides que le vent, invulnérables, caquetantes, elles dévorent tout sur leur passage, ne laissant que leurs excréments. Empoisonnent les airs, et souillent la verdure. »'),
(25, 'minotaure', 'Mi-bête', 11, '1749202839_3697.png', 'le Minotaure est un monstre fabuleux au corps d\'un homme et à tête d\'un taureau ou mi-homme et mi-taureau.'),
(26, 'fantome', 'Mort-Vivant', 9, '1749202924_4160.jpg', 'Un fantôme est l\'âme ou l\'esprit d\'une personne ou d\'un animal décédés pouvant se manifester dans le monde des vivants.'),
(27, 'lamasu', 'Mort-Vivant', 9, '1749202955_6324.jpg', 'Le lamassu est un être céleste de l\'ancienne religion mésopotamienne. Il porte une tête humaine, symbole de l\'intelligence ; un corps de taureau, symbole de la force ; et des ailes d\'aigle, symbole de la liberté . Il possède parfois des cornes et des oreilles de taureau. Il apparaît fréquemment dans l\'art mésopotamien.'),
(28, 'liche', 'Mort-Vivant', 9, '1749202979_5999.jpg', 'Contrairement aux zombies, souvent décrits comme dépourvus d\'esprit, les liches sont des revenants conscients, conservant leur intelligence et leurs pouvoirs magiques antérieurs . On les décrit souvent comme détenant un pouvoir sur des soldats et serviteurs morts-vivants moins doués d\'esprit.'),
(29, 'squelette', 'Mort-Vivant', 9, '1749203001_3870.jpg', 'Un squelette ou skellington est, dans les œuvres de fiction un personnage mort-vivant qui a perdu sa chair putréfiée, son corps n\'étant plus composé que de ses os qui sont maintenus en « vie » magiquement ou grâce à tout autre phénomène surnaturel. Par défaut, le terme désigne un squelette humain.');

-- --------------------------------------------------------

--
-- Structure de la table `codex`
--

CREATE TABLE `codex` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_id` int NOT NULL,
  `element_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `codex`
--

INSERT INTO `codex` (`id`, `nom`, `image`, `created_id`, `element_id`) VALUES
(1, 'test', '1749126771_6765.png', 1, 7),
(2, 'dsdsds', '1749127626_2467.webp', 5, 6),
(3, 'Eclair', '1749129072_9548.webp', 1, 7),
(4, 'Elementair d\'air', '1749129240_8054.webp', 1, 7),
(5, 'Vent violent', '1749129250_6646.webp', 1, 7),
(6, 'Armure de glace', '1749129396_3655.webp', 1, 5),
(7, 'Blizzard', '1749129409_8607.webp', 1, 5),
(8, 'Cercle de l\'hiver', '1749129419_7318.webp', 1, 5),
(9, 'Elementaire d\'eau', '1749129429_4527.webp', 1, 5),
(10, 'mur de glace', '1749129445_8662.webp', 1, 5),
(11, 'bouclier de feu', '1749129487_3465.webp', 1, 6),
(12, 'boule de feu', '1749129500_6543.webp', 1, 6),
(13, 'Elementaire de feu', '1749129510_1754.webp', 1, 6),
(14, 'Immolation', '1749129519_5760.webp', 1, 6),
(15, 'Tempête de feu', '1749129528_9444.webp', 1, 6),
(16, 'armure celeste', '1749129563_5598.webp', 1, 8),
(17, 'Elementaire de lumière', '1749129574_6390.webp', 1, 8),
(18, 'Purification', '1749129584_5517.webp', 1, 8),
(19, 'Retribution', '1749129593_6273.webp', 1, 8),
(20, 'soin', '1749129601_2841.webp', 1, 8);

-- --------------------------------------------------------

--
-- Structure de la table `elements`
--

CREATE TABLE `elements` (
  `id` int NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `elements`
--

INSERT INTO `elements` (`id`, `type`) VALUES
(5, 'eau'),
(6, 'feu'),
(7, 'air'),
(8, 'lumiere');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `admin` varchar(255) NOT NULL,
  `eleve` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `specialisation` varchar(255) DEFAULT NULL,
  `role_id` int NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `specialisation`, `role_id`, `password`) VALUES
(1, 'Catherine', '6,8,5,7', 2, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(6, 'Anastasya', '5', 3, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(7, 'Kiril', '6,8', 3, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(8, 'Anton', '8,5', 3, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(9, 'Irina', '5,7', 3, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(10, 'Jorgen', '6', 3, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(11, 'Kalindra', '7', 3, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- --------------------------------------------------------

--
-- Structure de la table `user_elements`
--

CREATE TABLE `user_elements` (
  `user_id` int NOT NULL,
  `element_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_elements`
--

INSERT INTO `user_elements` (`user_id`, `element_id`) VALUES
(1, 5),
(6, 5),
(8, 5),
(9, 5),
(1, 6),
(7, 6),
(10, 6),
(1, 7),
(9, 7),
(11, 7),
(1, 8),
(7, 8),
(8, 8);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bestiaire`
--
ALTER TABLE `bestiaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bestiaire_user` (`created_id`);

--
-- Index pour la table `codex`
--
ALTER TABLE `codex`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `elements`
--
ALTER TABLE `elements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_elements`
--
ALTER TABLE `user_elements`
  ADD PRIMARY KEY (`user_id`,`element_id`),
  ADD KEY `element_id` (`element_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bestiaire`
--
ALTER TABLE `bestiaire`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `codex`
--
ALTER TABLE `codex`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `elements`
--
ALTER TABLE `elements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bestiaire`
--
ALTER TABLE `bestiaire`
  ADD CONSTRAINT `fk_bestiaire_user` FOREIGN KEY (`created_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `user_elements`
--
ALTER TABLE `user_elements`
  ADD CONSTRAINT `user_elements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_elements_ibfk_2` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
