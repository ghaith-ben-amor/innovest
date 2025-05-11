-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 04 mai 2025 à 13:20
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
-- Base de données : `chat`
--

-- --------------------------------------------------------

--
-- Structure de la table `banned_words`
--

CREATE TABLE `banned_words` (
  `id` int(11) NOT NULL,
  `word` varchar(255) NOT NULL,
  `severity` enum('low','medium','high') DEFAULT 'medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `banned_words`
--

INSERT INTO `banned_words` (`id`, `word`, `severity`, `created_at`, `updated_at`) VALUES
(1, 'merde', 'low', '2025-04-30 09:15:29', '2025-04-30 09:15:29'),
(2, 'putain', 'medium', '2025-04-30 09:15:29', '2025-04-30 09:15:29'),
(3, 'connard', 'high', '2025-04-30 09:15:29', '2025-04-30 09:15:29');

-- --------------------------------------------------------

--
-- Structure de la table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `discussions`
--

INSERT INTO `discussions` (`id`, `user1_id`, `user2_id`, `created_at`) VALUES
(1, 25, 32, '2025-04-23 17:50:59'),
(2, 29, 32, '2025-04-23 17:50:59'),
(3, 33, 34, '2025-04-23 17:50:59'),
(4, 33, 35, '2025-04-23 17:50:59'),
(5, 33, 36, '2025-04-23 17:50:59'),
(12, 25, 33, '2025-04-23 18:49:32'),
(13, 38, 39, '2025-04-23 19:12:09'),
(14, 40, 41, '2025-04-25 23:43:56'),
(15, 29, 40, '2025-04-25 23:53:57'),
(16, 35, 40, '2025-04-29 18:38:41'),
(17, 33, 40, '2025-04-30 09:09:18'),
(18, 29, 41, '2025-04-30 10:28:38'),
(19, 40, 44, '2025-04-30 10:53:34'),
(20, 41, 44, '2025-05-01 12:58:24');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `has_offensive_content` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `discussion_id`, `sender_id`, `message`, `has_offensive_content`, `created_at`) VALUES
(1, 1, 25, 'aaa', 0, '2025-04-16 00:07:47'),
(2, 2, 29, 'salut', 0, '2025-04-20 10:39:56'),
(3, 2, 32, 'salut', 0, '2025-04-20 10:40:20'),
(4, 2, 32, 'hmdlhh ', 0, '2025-04-20 10:45:16'),
(5, 2, 32, 'cvvv', 0, '2025-04-20 10:59:36'),
(6, 2, 29, 'ouuaaayyy ', 0, '2025-04-20 11:04:09'),
(7, 2, 32, 'okkk', 0, '2025-04-20 11:07:51'),
(8, 2, 29, 'aaaa', 0, '2025-04-20 11:33:41'),
(9, 2, 32, 'aa', 0, '2025-04-20 11:48:23'),
(10, 2, 32, 'jjjjjjjjjjjjjj', 0, '2025-04-20 11:48:38'),
(11, 2, 29, 'jnjnj', 0, '2025-04-20 11:48:55'),
(12, 2, 29, 'aaa cvv', 0, '2025-04-20 21:50:43'),
(13, 2, 29, 'aaaaa', 0, '2025-04-20 21:51:32'),
(14, 2, 32, 'aaaaaaaaaaaaaaaaaa', 0, '2025-04-20 21:54:45'),
(15, 2, 29, 'haa chfamma ', 0, '2025-04-20 21:55:07'),
(16, 3, 34, 'aa yessin ', 0, '2025-04-22 17:43:26'),
(17, 3, 33, 'aa rayen  chfamma ', 0, '2025-04-22 17:43:49'),
(18, 3, 33, 'aeaazaz', 0, '2025-04-22 17:44:15'),
(19, 3, 34, 'ghghgh', 0, '2025-04-22 17:44:20'),
(20, 4, 33, 'khezbfdkj', 0, '2025-04-23 06:45:46'),
(21, 3, 33, 'aaa', 0, '2025-04-23 08:34:38'),
(22, 5, 33, 'aaa ya ', 0, '2025-04-23 09:13:05'),
(32, 1, 25, 'aaa', 0, '2025-04-16 00:07:47'),
(33, 2, 29, 'salut', 0, '2025-04-20 10:39:56'),
(34, 2, 32, 'salut', 0, '2025-04-20 10:40:20'),
(35, 2, 32, 'hmdlhh ', 0, '2025-04-20 10:45:16'),
(36, 2, 32, 'cvvv', 0, '2025-04-20 10:59:36'),
(37, 2, 29, 'ouuaaayyy ', 0, '2025-04-20 11:04:09'),
(38, 2, 32, 'okkk', 0, '2025-04-20 11:07:51'),
(39, 2, 29, 'aaaa', 0, '2025-04-20 11:33:41'),
(40, 2, 32, 'aa', 0, '2025-04-20 11:48:23'),
(41, 2, 32, 'jjjjjjjjjjjjjj', 0, '2025-04-20 11:48:38'),
(42, 2, 29, 'jnjnj', 0, '2025-04-20 11:48:55'),
(43, 2, 29, 'aaa cvv', 0, '2025-04-20 21:50:43'),
(44, 2, 29, 'aaaaa', 0, '2025-04-20 21:51:32'),
(45, 2, 32, 'aaaaaaaaaaaaaaaaaa', 0, '2025-04-20 21:54:45'),
(46, 2, 29, 'haa chfamma ', 0, '2025-04-20 21:55:07'),
(47, 3, 34, 'aa yessin ', 0, '2025-04-22 17:43:26'),
(48, 3, 33, 'aa rayen  chfamma ', 0, '2025-04-22 17:43:49'),
(49, 3, 33, 'aeaazaz', 0, '2025-04-22 17:44:15'),
(50, 3, 34, 'ghghgh', 0, '2025-04-22 17:44:20'),
(51, 4, 33, 'khezbfdkj', 0, '2025-04-23 06:45:46'),
(52, 3, 33, 'aaa', 0, '2025-04-23 08:34:38'),
(53, 5, 33, 'aaa ya ', 0, '2025-04-23 09:13:05'),
(63, 1, 25, 'aaa', 0, '2025-04-16 00:07:47'),
(64, 2, 29, 'salut', 0, '2025-04-20 10:39:56'),
(65, 2, 32, 'salut', 0, '2025-04-20 10:40:20'),
(66, 2, 32, 'hmdlhh ', 0, '2025-04-20 10:45:16'),
(67, 2, 32, 'cvvv', 0, '2025-04-20 10:59:36'),
(68, 2, 29, 'ouuaaayyy ', 0, '2025-04-20 11:04:09'),
(69, 2, 32, 'okkk', 0, '2025-04-20 11:07:51'),
(70, 2, 29, 'aaaa', 0, '2025-04-20 11:33:41'),
(71, 2, 32, 'aa', 0, '2025-04-20 11:48:23'),
(72, 2, 32, 'jjjjjjjjjjjjjj', 0, '2025-04-20 11:48:38'),
(73, 2, 29, 'jnjnj', 0, '2025-04-20 11:48:55'),
(74, 2, 29, 'aaa cvv', 0, '2025-04-20 21:50:43'),
(75, 2, 29, 'aaaaa', 0, '2025-04-20 21:51:32'),
(76, 2, 32, 'aaaaaaaaaaaaaaaaaa', 0, '2025-04-20 21:54:45'),
(77, 2, 29, 'haa chfamma ', 0, '2025-04-20 21:55:07'),
(78, 3, 34, 'aa yessin ', 0, '2025-04-22 17:43:26'),
(79, 3, 33, 'aa rayen  chfamma ', 0, '2025-04-22 17:43:49'),
(80, 3, 33, 'aeaazaz', 0, '2025-04-22 17:44:15'),
(81, 3, 34, 'ghghgh', 0, '2025-04-22 17:44:20'),
(82, 4, 33, 'khezbfdkj', 0, '2025-04-23 06:45:46'),
(83, 3, 33, 'aaa', 0, '2025-04-23 08:34:38'),
(84, 5, 33, 'aaa ya ', 0, '2025-04-23 09:13:05'),
(94, 1, 25, 'aaa', 0, '2025-04-16 00:07:47'),
(95, 2, 29, 'salut', 0, '2025-04-20 10:39:56'),
(96, 2, 32, 'salut', 0, '2025-04-20 10:40:20'),
(97, 2, 32, 'hmdlhh ', 0, '2025-04-20 10:45:16'),
(98, 2, 32, 'cvvv', 0, '2025-04-20 10:59:36'),
(99, 2, 29, 'ouuaaayyy ', 0, '2025-04-20 11:04:09'),
(100, 2, 32, 'okkk', 0, '2025-04-20 11:07:51'),
(101, 2, 29, 'aaaa', 0, '2025-04-20 11:33:41'),
(102, 2, 32, 'aa', 0, '2025-04-20 11:48:23'),
(103, 2, 32, 'jjjjjjjjjjjjjj', 0, '2025-04-20 11:48:38'),
(104, 2, 29, 'jnjnj', 0, '2025-04-20 11:48:55'),
(105, 2, 29, 'aaa cvv', 0, '2025-04-20 21:50:43'),
(106, 2, 29, 'aaaaa', 0, '2025-04-20 21:51:32'),
(107, 2, 32, 'aaaaaaaaaaaaaaaaaa', 0, '2025-04-20 21:54:45'),
(108, 2, 29, 'haa chfamma ', 0, '2025-04-20 21:55:07'),
(109, 3, 34, 'aa yessin ', 0, '2025-04-22 17:43:26'),
(110, 3, 33, 'aa rayen  chfamma ', 0, '2025-04-22 17:43:49'),
(111, 3, 33, 'aeaazaz', 0, '2025-04-22 17:44:15'),
(112, 3, 34, 'ghghgh', 0, '2025-04-22 17:44:20'),
(113, 4, 33, 'khezbfdkj', 0, '2025-04-23 06:45:46'),
(114, 3, 33, 'aaa', 0, '2025-04-23 08:34:38'),
(115, 5, 33, 'aaa ya ', 0, '2025-04-23 09:13:05'),
(125, 3, 33, 'waaaaaaaaaaaaaaaaaaaaaaaaa', 0, '2025-04-23 18:21:38'),
(126, 3, 33, 'ghghghgh', 0, '2025-04-23 18:26:37'),
(127, 12, 33, 'ella', 0, '2025-04-23 18:49:32'),
(128, 3, 33, 'aaaaaaaaaaa', 0, '2025-04-23 19:11:19'),
(129, 13, 39, 'ghghghghghgh', 0, '2025-04-23 19:12:09'),
(130, 13, 39, 'ghghghghghg', 0, '2025-04-23 19:15:05'),
(131, 14, 41, 'aaa', 0, '2025-04-25 23:43:56'),
(133, 14, 41, 'aaaa', 0, '2025-04-25 23:45:31'),
(134, 14, 40, 'cvvv', 0, '2025-04-25 23:50:42'),
(135, 15, 40, 'bonjour', 0, '2025-04-25 23:53:57'),
(136, 15, 40, '❤️', 0, '2025-04-25 23:56:06'),
(137, 15, 40, '????????????????', 0, '2025-04-25 23:56:38'),
(138, 15, 40, '????????????', 0, '2025-04-25 23:56:43'),
(139, 15, 40, '????', 0, '2025-04-25 23:56:47'),
(140, 15, 40, '????', 0, '2025-04-25 23:56:51'),
(141, 15, 40, '????', 0, '2025-04-25 23:56:54'),
(142, 15, 40, '❤️', 0, '2025-04-25 23:56:57'),
(143, 15, 40, '????', 0, '2025-04-25 23:57:01'),
(144, 15, 40, '😂', 0, '2025-04-25 23:58:40'),
(145, 15, 40, '😍', 0, '2025-04-25 23:58:45'),
(146, 15, 40, '🙀', 0, '2025-04-25 23:58:49'),
(147, 15, 40, '', 0, '2025-04-26 00:01:04'),
(148, 15, 40, '', 0, '2025-04-26 00:01:41'),
(149, 15, 40, '', 0, '2025-04-26 00:03:16'),
(150, 14, 41, 'aaaaaaaaaaaaaa', 0, '2025-04-26 00:04:01'),
(151, 14, 40, '😂😂😂😂😂', 0, '2025-04-26 00:04:16'),
(152, 14, 40, '', 0, '2025-04-26 00:04:32'),
(153, 14, 40, '', 0, '2025-04-26 00:07:15'),
(154, 14, 40, '', 0, '2025-04-26 00:10:27'),
(155, 16, 40, 'aaa', 0, '2025-04-29 18:38:41'),
(156, 16, 40, '❤️', 0, '2025-04-29 18:38:48'),
(157, 14, 41, 'aaa bnjjb', 0, '2025-04-29 18:40:29'),
(158, 14, 40, 'cvv', 0, '2025-04-29 18:40:50'),
(159, 14, 40, 'cvv', 0, '2025-04-29 18:40:50'),
(160, 14, 40, 'cvv', 0, '2025-04-29 18:40:50'),
(161, 14, 40, 'cvv', 0, '2025-04-29 18:40:51'),
(162, 14, 40, 'cvv', 0, '2025-04-29 18:40:51'),
(163, 14, 40, '', 0, '2025-04-29 20:09:57'),
(164, 14, 40, '', 0, '2025-04-29 20:12:48'),
(165, 14, 40, '', 0, '2025-04-29 20:15:19'),
(166, 14, 40, '', 0, '2025-04-29 20:22:58'),
(167, 14, 40, '', 0, '2025-04-29 20:28:03'),
(168, 14, 40, '', 0, '2025-04-29 20:32:04'),
(169, 14, 41, 'aaa', 0, '2025-04-29 21:02:59'),
(170, 14, 41, 'aaa', 0, '2025-04-29 21:07:04'),
(171, 14, 40, '', 0, '2025-04-30 08:56:56'),
(172, 14, 40, '👾', 0, '2025-04-30 09:05:15'),
(174, 17, 40, 'bonjour', 0, '2025-04-30 09:09:18'),
(175, 17, 40, 'merde', 0, '2025-04-30 09:17:55'),
(176, 17, 40, 'merde', 0, '2025-04-30 09:26:26'),
(177, 17, 40, 'merde', 0, '2025-04-30 09:28:32'),
(178, 17, 40, 'merde', 0, '2025-04-30 10:20:10'),
(179, 17, 40, 'aa', 0, '2025-04-30 10:20:17'),
(180, 17, 40, 'merde', 0, '2025-04-30 10:20:22'),
(181, 18, 41, 'aaa', 0, '2025-04-30 10:28:38'),
(182, 18, 41, 'merde', 0, '2025-04-30 10:28:43'),
(183, 18, 41, 'aaa', 0, '2025-04-30 10:33:46'),
(184, 18, 41, 'merde', 0, '2025-04-30 10:33:51'),
(185, 14, 40, 'salut', 0, '2025-04-30 10:37:15'),
(186, 14, 40, 'aaa', 0, '2025-04-30 10:42:43'),
(187, 14, 40, 'aaa', 0, '2025-04-30 10:42:49'),
(188, 14, 40, 'sss', 0, '2025-04-30 10:42:54'),
(189, 14, 40, 'salutt', 0, '2025-04-30 10:52:38'),
(190, 19, 44, 'salut❤️', 0, '2025-04-30 10:53:34'),
(191, 19, 40, 'cvv', 0, '2025-04-30 10:53:49'),
(192, 19, 44, '', 0, '2025-04-30 10:54:39'),
(193, 20, 41, 'salut', 0, '2025-05-01 12:58:24'),
(194, 14, 40, 'aaa', 0, '2025-05-01 12:58:53'),
(195, 14, 40, 'sss', 0, '2025-05-01 12:58:58'),
(196, 14, 40, 'oui', 0, '2025-05-01 13:02:37'),
(197, 14, 41, 'salut❤️', 0, '2025-05-01 13:02:46');

-- --------------------------------------------------------

--
-- Structure de la table `message_reactions`
--

CREATE TABLE `message_reactions` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reaction_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `idnot` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `is_seen` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `iduser` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`iduser`, `username`, `password`, `type`) VALUES
(25, 'koussay', '$2y$10$Y8g2/FaDfOejAydsnTqq1uAnbmOHpzQDR9GKoqYjd3sSyLtoDSYiO', 'admin'),
(29, 'ggg', '$2y$10$sa7QZel06tiKCRWy1iAsJuRMTIZIxnAAwkGnPv3lfK7iqnZQO5hUW', 'user'),
(31, 'admin', '$2y$10$FxmETIdp870VGJ.o3PyVNe/MPIQZUabfh7Fa93f.33DuME9YQBj/m', 'admin'),
(32, 'ah', '$2y$10$FblpzH1s9Ko0Ot85V6nPaObFNYBdEMKleOmmF.ac.nKWudQnAzZMS', 'user'),
(33, 'yessin', '$2y$10$a2vsGERkeVRvwAYQTm4jzuLJLJre0uFzE301vvf8GOuSQ2dtFu4n2', 'user'),
(34, 'rayen', '$2y$10$mQpRRqInKu/zXK0x9NfaBuRcMacawwUHIaz5Ud2mJe64BRp.zt3Xy', 'user'),
(35, 'becem', '$2y$10$yw8ordd4KcONtCfuGWDnQe8xRZwbTXwOFRaAMpAURoM9Sg7Ne5uga', 'admin'),
(36, 'mrhdi ', '$2y$10$/78LpNQg9amVrOsqTWFN4uu8Z2R6cyZyEx2CzJpUEtexAhIDFD5Be', 'user'),
(37, 'ghghg', '$2y$10$oIYfwUDwvY0ZHS3kE7OXruSIPariOAtPuMS4E3dEApTYZae9vafD6', 'admin'),
(38, 'ha', '$2y$10$g/SemKfLEOn5tc8tH/gFguj7gUm3H/jezbJollzBkeDthi6P5O9fi', 'user'),
(39, 'ham', '$2y$10$c0DVAx2om.CNxWJYxmJoWucLi13fc18gMmSKQCmXtrHUPq9kXyMqG', 'user'),
(40, 'ghaith', '$2y$10$agU.R4zFh9bMmnkmdFfwe.sJUKuxrb4AC0vDHKZPekk8PwAAy4D0m', 'user'),
(41, 'ahmed ', '$2y$10$VCtp0aSaL6sfTjHB6LqeJOMtE/JzjsK0Hcdh3plSeLPCQsjLDgM1m', 'user'),
(42, 'add', '$2y$10$StmP/PD7vwKAP1HAI3LstOxyfwsTLp7ZyJ3S3v4PGXixlDI1/TzfK', 'admin'),
(43, 'o', '$2y$10$5sQ6gARSGyd4NcxVyF4ENeRWH5kdT47i0JlbMkjCmWJvYvdLp0UiW', 'admin'),
(44, 'sssss', '$2y$10$z0olrY7Z9ZixKLRlbOaxSOgPABsLzVyrYzC9phu8kUMlvPgG1KxC.', 'user');

-- --------------------------------------------------------

--
-- Structure de la table `user_violations`
--

CREATE TABLE `user_violations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attempt_count` int(11) DEFAULT 1,
  `last_attempt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `banned_words`
--
ALTER TABLE `banned_words`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Index pour la table `message_reactions`
--
ALTER TABLE `message_reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reaction` (`message_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`idnot`),
  ADD KEY `message_id` (`message_id`);

--
-- Index pour la table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`);

--
-- Index pour la table `user_violations`
--
ALTER TABLE `user_violations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `banned_words`
--
ALTER TABLE `banned_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT pour la table `message_reactions`
--
ALTER TABLE `message_reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `idnot` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `user_violations`
--
ALTER TABLE `user_violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`iduser`),
  ADD CONSTRAINT `discussions_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`iduser`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`iduser`);

--
-- Contraintes pour la table `message_reactions`
--
ALTER TABLE `message_reactions`
  ADD CONSTRAINT `message_reactions_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_reactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`iduser`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
