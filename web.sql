drop database if exists web;
create database web;
use web;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 14 Φεβ 2024 στις 22:23:31
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `testing_final`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `administrator`
--

CREATE TABLE `administrator` (
  `ad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `administrator`
--

INSERT INTO `administrator` (`ad_id`) VALUES
(1001),
(1002),
(1003);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `announcements`
--

CREATE TABLE `announcements` (
  `an_id` int(11) NOT NULL,
  `an_ad_id` int(11) NOT NULL,
  `an_product_id` int(11) NOT NULL,
  `announcement_content` varchar(255) DEFAULT NULL,
  `announcement_date` date DEFAULT curdate(),
  `is_uploaded` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `announcements`
--

INSERT INTO `announcements` (`an_id`, `an_ad_id`, `an_product_id`, `announcement_content`, `announcement_date`, `is_uploaded`) VALUES
(11, 1001, 55, 'makaronia, ryzi', '2024-02-05', 1),
(12, 1001, 56, 'makaronia, ryzi', '2024-02-05', 1),
(15, 1001, 63, 'giganteskon, tost', '2024-02-07', 1),
(16, 1001, 74, 'giganteskon, tost', '2024-02-07', 1),
(17, 1001, 56, 'ryzi, makaronia', '2024-02-09', 1),
(18, 1001, 55, 'ryzi, makaronia', '2024-02-09', 1),
(19, 1001, 57, 'lazania', '2024-02-09', 1),
(21, 1001, 78, 'zampon, tost', '2024-02-14', 1),
(22, 1001, 74, 'zampon, tost', '2024-02-14', 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `announcement_product_mapping`
--

CREATE TABLE `announcement_product_mapping` (
  `mapping_id` int(11) NOT NULL,
  `an_id` int(11) NOT NULL,
  `an_product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `announcement_product_mapping`
--

INSERT INTO `announcement_product_mapping` (`mapping_id`, `an_id`, `an_product_id`) VALUES
(1, 12, 55),
(2, 12, 56),
(3, 16, 63),
(4, 16, 74),
(5, 18, 56),
(6, 18, 55),
(7, 19, 57),
(8, 22, 78),
(9, 22, 74);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `base`
--

CREATE TABLE `base` (
  `product_id` int(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `product` varchar(30) NOT NULL,
  `num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `base`
--

INSERT INTO `base` (`product_id`, `category`, `product`, `num`) VALUES
(55, 'zymarika', 'makaronia', 70),
(56, 'zymarika', 'ryzi', 41),
(57, 'zymarika', 'lazania', 81),
(58, 'zymarika', 'xylopites', 248),
(59, 'zymarika', 'kritharaki', 300),
(60, 'zymarika', 'traxanas', 449),
(61, 'konserves', 'kalampoki', 199),
(62, 'konserves', 'tonos', 250),
(63, 'konserves', 'giganteskon', 34),
(64, 'konserves', 'ntolmantakia', 150),
(65, 'konserves', 'sardeles', 450),
(66, 'konserves', 'pikles', 470),
(67, 'ospria', 'fakes', 278),
(68, 'ospria', 'revithia', 350),
(69, 'ospria', 'fasolia', 247),
(70, 'galaktokomika', 'gala', 300),
(71, 'galaktokomika', 'tyri', 349),
(72, 'galaktokomika', 'giaourti', 249),
(73, 'psomi', 'xwriatiko', 198),
(74, 'psomi', 'tost', 34),
(75, 'psomi', 'olikis', 150),
(76, 'psomi', 'friganies', 349),
(77, 'alantika', 'loukaniko', 400),
(78, 'alantika', 'zampon', 13),
(79, 'alantika', 'galopoula', 102),
(80, 'alantika', 'kotopoulo', 300),
(81, 'alantika', 'aeros', 188);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `civilian`
--

CREATE TABLE `civilian` (
  `c_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `civilian`
--

INSERT INTO `civilian` (`c_id`) VALUES
(3001),
(3002),
(3003),
(3004),
(3005),
(3006),
(3007),
(3008),
(3009),
(3010),
(3011),
(3012),
(3013),
(3014),
(3015),
(3016),
(3017),
(3018),
(3019),
(3020),
(3026),
(3027);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `contact_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `contact`
--

INSERT INTO `contact` (`contact_id`, `c_id`, `message`, `contact_date`) VALUES
(1, 3001, '', '2024-02-05 18:47:15'),
(2, 3017, 'ΕΕ', '2024-02-08 16:22:58'),
(3, 3017, 'ee', '2024-02-09 21:09:49'),
(4, 3017, 'aa', '2024-02-14 18:43:38');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `markers`
--

CREATE TABLE `markers` (
  `marker_id` int(11) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `marker_type` enum('activeTaskCar','inactiveTaskCar','activeDonation','inactiveDonation','activeRequest','inactiveRequest','base') NOT NULL,
  `or_id` int(11) DEFAULT NULL,
  `ve_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `markers`
--

INSERT INTO `markers` (`marker_id`, `latitude`, `longitude`, `marker_type`, `or_id`, `ve_id`) VALUES
(16, 38.259244, 21.742062, 'inactiveTaskCar', NULL, 621),
(17, 38.244877, 21.737574, 'inactiveTaskCar', NULL, 622),
(18, 38.257507, 21.740772, 'inactiveTaskCar', NULL, 623),
(19, 38.257574, 21.741119, 'inactiveTaskCar', NULL, 624),
(20, 38.258492, 21.742052, 'inactiveTaskCar', NULL, 625),
(21, 38.256472, 21.743883, 'inactiveTaskCar', NULL, 626),
(22, 38.261029, 21.742859, 'inactiveTaskCar', NULL, 627),
(23, 38.256853, 21.743903, 'inactiveTaskCar', NULL, 628),
(24, 38.239533, 21.737892, 'inactiveTaskCar', NULL, 629),
(25, 38.248427, 21.739540, 'base', NULL, NULL),
(27, 38.246640, 21.734574, 'inactiveTaskCar', NULL, 635),
(80, 38.255247, 21.746148, 'inactiveTaskCar', NULL, 622),
(81, 38.248223, 21.740570, 'inactiveTaskCar', NULL, 620);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `offers`
--

CREATE TABLE `offers` (
  `o_id` int(11) NOT NULL,
  `o_c_id` int(11) NOT NULL,
  `o_an_id` int(11) NOT NULL,
  `o_pr_id` int(11) NOT NULL,
  `o_number` int(11) NOT NULL,
  `o_or_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `offers`
--

INSERT INTO `offers` (`o_id`, `o_c_id`, `o_an_id`, `o_pr_id`, `o_number`, `o_or_id`) VALUES
(34, 3001, 19, 57, 1, 89),
(35, 3018, 18, 55, 1, 93),
(36, 3018, 18, 56, 1, 93),
(37, 3008, 18, 55, 1, 94),
(38, 3008, 18, 56, 1, 94);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `orders`
--

CREATE TABLE `orders` (
  `or_id` int(11) NOT NULL,
  `or_c_id` int(11) NOT NULL,
  `or_date` date NOT NULL DEFAULT curdate(),
  `or_type` enum('Αίτημα','Προσφορά') DEFAULT NULL,
  `order_state` enum('Σε επεξεργασία','Παραδόθηκε','Προς Παράδοση') NOT NULL,
  `or_task_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `orders`
--

INSERT INTO `orders` (`or_id`, `or_c_id`, `or_date`, `or_type`, `order_state`, `or_task_id`) VALUES
(87, 3017, '2024-02-14', 'Αίτημα', 'Παραδόθηκε', NULL),
(88, 3017, '2024-02-14', 'Αίτημα', 'Παραδόθηκε', NULL),
(89, 3001, '2024-02-14', 'Προσφορά', 'Παραδόθηκε', NULL),
(90, 3018, '2024-02-14', 'Αίτημα', 'Παραδόθηκε', NULL),
(91, 3017, '2024-02-14', 'Αίτημα', 'Παραδόθηκε', NULL),
(92, 3001, '2024-02-14', 'Αίτημα', 'Παραδόθηκε', NULL),
(93, 3018, '2024-02-14', 'Προσφορά', 'Παραδόθηκε', NULL),
(94, 3008, '2024-02-14', 'Προσφορά', 'Παραδόθηκε', NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `requests`
--

CREATE TABLE `requests` (
  `re_id` int(11) NOT NULL,
  `re_c_id` int(11) NOT NULL,
  `re_number` int(11) NOT NULL,
  `re_pr_id` int(11) NOT NULL,
  `re_or_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `requests`
--

INSERT INTO `requests` (`re_id`, `re_c_id`, `re_number`, `re_pr_id`, `re_or_id`) VALUES
(163, 3017, 1, 81, 87),
(164, 3017, 1, 67, 87),
(165, 3017, 1, 81, 88),
(166, 3017, 1, 67, 88),
(167, 3018, 1, 81, 90),
(168, 3018, 1, 67, 90),
(169, 3017, 1, 81, 91),
(170, 3017, 1, 67, 91),
(171, 3001, 1, 81, 92),
(172, 3001, 1, 67, 92);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `rescuer`
--

CREATE TABLE `rescuer` (
  `resc_id` int(11) NOT NULL,
  `resc_ve_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `rescuer`
--

INSERT INTO `rescuer` (`resc_id`, `resc_ve_id`) VALUES
(2011, NULL),
(2012, NULL),
(2013, NULL),
(2014, NULL),
(2015, NULL),
(2016, NULL),
(2017, NULL),
(2018, NULL),
(2019, NULL),
(2020, NULL),
(2001, 620),
(2002, 621),
(2003, 622),
(2004, 623),
(2005, 624),
(2006, 625),
(2007, 626),
(2008, 627),
(2009, 628),
(2010, 629);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `tasks`
--

CREATE TABLE `tasks` (
  `t_id` int(11) NOT NULL,
  `t_state` enum('done','inprocess') NOT NULL,
  `t_date` date NOT NULL,
  `t_vehicle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `tasks`
--

INSERT INTO `tasks` (`t_id`, `t_state`, `t_date`, `t_vehicle`) VALUES
(59, 'done', '2024-02-14', 620),
(60, 'done', '2024-02-14', 620),
(61, 'done', '2024-02-14', 620),
(62, 'done', '2024-02-14', 620),
(63, 'done', '2024-02-14', 620);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','rescuer','civilian') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`user_id`, `name`, `lastname`, `address`, `phone`, `username`, `password`, `role`) VALUES
(1001, 'Marianthi', 'Thodi', 'Sostratou 15', '6987562335', 'MThodi', 'f00586aab1d7719dee1ef36136edef3749012db514d77c549cea44d2746d8c25', 'admin'),
(1002, 'Alexandra', 'Kagiouli', 'Karaiskaki 225', '6996558657', 'AKag', '9cb20def812484abedecd78b389e23118e6ccfb258be2be96b6d9de2b696f817', 'admin'),
(1003, 'Alexis', 'Giannoutsos', 'Ellinos Stratiotou 50', '6984521424', 'AGian', 'd6a7304069f99c5a5bfe62f1bf9134fb10e109eb5042210045ac422e90b61e6e', 'admin'),
(2001, 'Evaggelia', 'Kolampa', 'Xeilonos Patreos 25', '6989563635', 'EvaKol', 'b78855e50e39a1bf52ab74330b6db5883df7527446284b1738fab0a1b208b1e0', 'rescuer'),
(2002, 'Elpida', 'Kokkali', 'Ellinos Stratiotou 65', '6989455226', 'ElpKo', '49e4a85ea9962e5f19d5278b0457eea6a6ef1bd3c8d82eded0bc12dcfe567e1a', 'rescuer'),
(2003, 'Erifili', 'Karagianni', 'Erissou 6', '6952341126', 'EriKar', '6f3589db559c9efbfc2c403539c3c51e2112e3bf751014e8b501c80c356329b5', 'rescuer'),
(2004, 'Marileni', 'Valavani', 'Agias sofias 15', '6989784515', 'MarVal', 'd5906b095d77f1bb955de2778a88d537809466a0aa4c9c6e7ba1e8073789c638', 'rescuer'),
(2005, 'Danai', 'Batsouli', 'Agias Sofias 17', '6956231224', 'DanB', '570a7407e063e6d6de629986ff791c74ebcad339ef838d668c664d595f0ec07f', 'rescuer'),
(2006, 'Alexia', 'Diamantopoulou', 'Ellinos Stratiotou 56', '6953233629', 'AlDiam', 'be8da289072a17b5a6330ce109780537a5170feef164f7ec27a4f1ee8d89d127', 'rescuer'),
(2007, 'Fotis', 'Kalioras', 'Agias sofias 32', '6984512335', 'FotKal', '551230a9c0d1c87b16155f6591b6854f51824a4313dc3f69fa0ee046d2a44490', 'rescuer'),
(2008, 'Panos', 'Kapetanidis', 'Ellinos Stratiotou 87', '6987453226', 'PanKap', '5e7b8a2e2981d313fb08707e8c71351bbc5c8401ba4b38e8e11b7ef2fbca07f7', 'rescuer'),
(2009, 'Antonis', 'Lykourinas', 'Agias sofias 51', '6987562336', 'AntLyk', 'c7b0f1a09cb66658e9f487728271aefbdd83ad0f827ffb6e21a121032cd7198f', 'rescuer'),
(2010, 'Theodora', 'Vaso', 'Xeilonos Patreos 23', '6989562341', 'TheodV', '41a33193e9fe6feb10afa3b6e0822a34a264f713e5f610508434b8329b907d06', 'rescuer'),
(2011, 'Eleni', 'Gallous', 'Xeilonos Patreos 14', '6989564578', 'EleniG', 'eed6a7d48182a22155e7df8955d55c97faafa7c434c7376bf2116b216e761c6f', 'rescuer'),
(2012, 'Aristeidis', 'Votsi', 'Karaiskaki 28', '6985231425', 'ArisV', 'c076a6d6fb34bba66d9be3b82d999f0956103dde2858b297b58e56dfe2593ea5', 'rescuer'),
(2013, 'Elton', 'Pietri', 'Karaiskaki 24', '6985755326', 'EltonP', '235447331e0dce28bf3e553c6098516d3a654cf425b2c8333dc25e2b9ecc45f3', 'rescuer'),
(2014, 'Sertzio', 'Dasi', 'Kanakri 78', '6985452632', 'SertzD', '1da4409b19abb6712896c8a9c03f0a8af461be7926baf694c16e60063e128fbf', 'rescuer'),
(2015, 'Eneas', 'Lepouri', 'Ellinos Stratiotou 65', '6956368947', 'EniL', '5297e00ce147d4b484550a957f5e4b422b73da775f77a57eac1760940e0ec1d1', 'rescuer'),
(2016, 'Jonathan', 'Chacon', 'Kanakari 336', '6989562336', 'JonCh', 'ff7a4da45cc0e677370192ce2ae19d23f2b139d74a376955e0d72f4a871eae61', 'rescuer'),
(2017, 'Maria', 'Anastasopoulou', 'Kolokotroni 15', '6989563625', 'MarAnast', '9ff18ebe7449349f358e3af0b57cf7a032c1c6b2272cb2656ff85eb112232f16', 'rescuer'),
(2018, 'Markella', 'Magouli', 'Kolokotroni 78', '6936235425', 'MarkeMag', 'adb07066de87d56295baf563f8247178103ee896ec0d250fa043d354e7f56b46', 'rescuer'),
(2019, 'Clara', 'Bencomo', 'Kolokotroni 56', '6956592631', 'ClaraBen', '8df830a849e93985f1f647c0b1089223273720b037af5ecbcf25563f99ea34e8', 'rescuer'),
(2020, 'Alice', 'Karagianni', 'Kolokotroni 25', '6986425312', 'AliceKar', '28a946260e2d2bd8ff991c554c2326a891afa8288fa0600a80eb82bbe54703f0', 'rescuer'),
(3001, 'Athina', 'Vamvaka', 'Korinthou 15', '6951525356', 'AthVam', '44c6a374366f345cec0fa7d98eddf8185a533d4f3922c9c9397c38e8a8f526e2', 'civilian'),
(3002, 'Nikol', 'Mitsoula', 'Agiou Andrea 105', '6956587859', 'NikMi', '26d31f9265361b390d50cceda9bcae84cca4d3e72b54c75ad14c13f12a662c44', 'civilian'),
(3003, 'Aggeliki', 'Sideri', 'Gounari 108', '6956632414', 'AggSid', '92ebcbef3c4a91fe886aa6909c8890d2ac42887b581cd9bdf22ee25db624f7db', 'civilian'),
(3004, 'Xristina', 'Kallidi', 'Kanakari 56', '6996332546', 'ChrisKal', '6aed0206d633918b61d3a8f40a5485a98f2629e1f05c7d600df64f7348379af2', 'civilian'),
(3005, 'Maria', 'Aggelidi', 'Gounari 227', '6963332545', 'MarAgg', '75ba7ecb3e871361559dd74bc0f5592f8b5be407599bc864f774eb54cc97c06c', 'civilian'),
(3006, 'Nikoleta', 'Zervidi', 'Karaiskaki 108', '6998741235', 'NikZer', '7cb47af660c718070799bbb05f08996adb259fccbdb27e22ba9e8a8345fa8188', 'civilian'),
(3007, 'Christina', 'Nikodimou', 'Karaiskaki 105', '6995232214', 'ChristN', '36f23da95b9c841f02af30fe2b880acb8b7704bac809e3f0623cf4e0391b6e5b', 'civilian'),
(3008, 'Dionisios', 'Xenakis', 'Patreos 50', '6989552336', 'DionXen', '2e880ecee0651fd3e663cb4cd473c2ed9d39fb207a45b19a209fb7c2ce2ece0f', 'civilian'),
(3009, 'Stefanos', 'Xenopoulos', 'Patreos 22', '6996335544', 'StefXen', '5637181cf0e95f436bf5bba34be62c5eee287ea442c37211dd516845b9d60447', 'civilian'),
(3010, 'Stefanos', 'Doutsi', 'Karaiskaki 120', '6987554885', 'StefDou', 'afb0427181fe92ddea7c437acdb980b1ece98a625856bcfbd2c96819e8e36c8c', 'civilian'),
(3011, 'Tzeni', 'Kurti', 'Korinthou 225', '6993221245', 'TzKour', 'cdce132dbddb650a9cbb6a22cbd68f7abfff685d6490ecde5ded1ac5da987819', 'civilian'),
(3012, 'Marianneta', 'Daskalopoulou', 'Sostratou 19', '6998885223', 'MarDas', 'be18d105c8f6b0aced283e90fe44bea1c166231736198c4aa696cd28c079f8c6', 'civilian'),
(3013, 'Stavros', 'Daskalopoulos', 'Amerikis 80', '6998774455', 'StavDas', 'df2ce255dafb0c486cb43a044633dbd918234d6716b7d2e101d8d0ca70bf3a5e', 'civilian'),
(3014, 'Fotini', 'Lampropoulou', 'Amerikis 100', '6925665541', 'FotLamp', 'efb175f67eba903b42092af690bae9875325ed8f5c200df5ec2372c093ec42ce', 'civilian'),
(3015, 'Marika', 'Rokka', 'Korinthou 127', '6933665245', 'MarRo', '75dcfea9d1cb0f2505a39e87cab0972773b1bdd01b515887fe257f7f4336c184', 'civilian'),
(3016, 'Eugenia', 'Katintzarou', 'Karaiskaki 103', '6936223545', 'EvgKat', 'ef34097ba51fc87ffa044e4f75ebc2e69aba8c93bf249c653fa6890312e7f0e5', 'civilian'),
(3017, 'Katerina', 'Lakkou', 'Sostratou 19', '6933662211', 'KatLak', 'b38ae16c58b4139f299861adc9b19b4201629d8434b90606729d19dfe6204f8c', 'civilian'),
(3018, 'Elpida', 'Kati', 'Gounari 15', '6965871245', 'ElpKat', 'fd5b40f6a46373f2bf5e5bcfe5702f1993a1b54e791233dba661f03055938f89', 'civilian'),
(3019, 'Fotoula', 'Zaxaropoulou', 'Maizonos 65', '6985552321', 'FotZax', '3959b595de53cb9eab6eb4b1a61d192a069dc5af3dd0a31860abefa714b36b9c', 'civilian'),
(3020, 'Stavroula', 'Liaskou', 'Maizonos 235', '6932111425', 'StavLiask', 'd7e926a03442b31e71f93a56a22fe833b7031529bfaeff3efc059e12e95841d7', 'civilian'),
(3026, 'Efi', 'Karamouzi', 'Themistokleous 34', '6945608513', 'Efoula', 'b8f7d6d3f3aede1e9496cd38778e7724b8e99fb084b9169d5ad50ebe6dcce460', 'civilian'),
(3027, 'eeee', 'eee', 'AAAA', '1234567890', 'EEEEE', 'c1989b460dcf1bd94d515a7e7cd94df5ffeda2ad9c29e4b296c4d346662c9a97', 'civilian'),
(3028, 'Antwnis', 'Likourinas', 'Korinthou 12', '1234567890', 'Antwnis1', '$2y$10$LbT32kRPk3.e3fRH/PkeLeY1jEcttSdfDWdzw6Y4Zq1wZ7Ofcb5PW', 'civilian');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `vehicle`
--

CREATE TABLE `vehicle` (
  `ve_id` int(11) NOT NULL,
  `ve_username` varchar(30) NOT NULL,
  `ve_state` enum('fortosi','ekfortosi','ontheroad','onhold') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `vehicle`
--

INSERT INTO `vehicle` (`ve_id`, `ve_username`, `ve_state`) VALUES
(620, 'alekos', 'onhold'),
(621, 'alexis', 'onhold'),
(622, 'marianthi', 'onhold'),
(623, 'loser', 'onhold'),
(624, 'duck', 'onhold'),
(625, 'tortilla', 'onhold'),
(626, 'burito', 'onhold'),
(627, 'lol', 'onhold'),
(628, 'noname', 'onhold'),
(629, 'jo', 'onhold'),
(630, 'koukla', 'onhold'),
(631, 'lisa', 'onhold'),
(632, 'tyn', 'onhold'),
(633, 'miko', 'onhold'),
(634, 'drama', 'onhold'),
(635, 'bb', 'onhold'),
(636, 'omar', 'onhold'),
(637, 'chulo', 'onhold'),
(638, 'red', 'onhold'),
(639, 'loca', 'onhold');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`ad_id`);

--
-- Ευρετήρια για πίνακα `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`an_id`),
  ADD KEY `announcementid` (`an_ad_id`),
  ADD KEY `announceprid` (`an_product_id`);

--
-- Ευρετήρια για πίνακα `announcement_product_mapping`
--
ALTER TABLE `announcement_product_mapping`
  ADD PRIMARY KEY (`mapping_id`),
  ADD KEY `mapping_announcement_fk` (`an_id`),
  ADD KEY `mapping_product_fk` (`an_product_id`);

--
-- Ευρετήρια για πίνακα `base`
--
ALTER TABLE `base`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product` (`product`);

--
-- Ευρετήρια για πίνακα `civilian`
--
ALTER TABLE `civilian`
  ADD PRIMARY KEY (`c_id`);

--
-- Ευρετήρια για πίνακα `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `contact_civilian_fk` (`c_id`);

--
-- Ευρετήρια για πίνακα `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`marker_id`),
  ADD KEY `vemarkid` (`ve_id`),
  ADD KEY `or_id` (`or_id`);

--
-- Ευρετήρια για πίνακα `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `ciid` (`o_c_id`),
  ADD KEY `anid` (`o_an_id`),
  ADD KEY `oorid` (`o_or_id`),
  ADD KEY `oprid` (`o_pr_id`);

--
-- Ευρετήρια για πίνακα `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`or_id`),
  ADD KEY `ordc` (`or_c_id`),
  ADD KEY `ortaskid` (`or_task_id`);

--
-- Ευρετήρια για πίνακα `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`re_id`),
  ADD KEY `civid` (`re_c_id`),
  ADD KEY `prid` (`re_pr_id`),
  ADD KEY `orid` (`re_or_id`);

--
-- Ευρετήρια για πίνακα `rescuer`
--
ALTER TABLE `rescuer`
  ADD PRIMARY KEY (`resc_id`),
  ADD KEY `vehid` (`resc_ve_id`);

--
-- Ευρετήρια για πίνακα `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `taskveh` (`t_vehicle`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_address` (`address`),
  ADD KEY `idx_role` (`role`);

--
-- Ευρετήρια για πίνακα `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`ve_id`),
  ADD UNIQUE KEY `ve_username` (`ve_username`),
  ADD KEY `idx_username` (`ve_username`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `announcements`
--
ALTER TABLE `announcements`
  MODIFY `an_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT για πίνακα `announcement_product_mapping`
--
ALTER TABLE `announcement_product_mapping`
  MODIFY `mapping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT για πίνακα `base`
--
ALTER TABLE `base`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT για πίνακα `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `markers`
--
ALTER TABLE `markers`
  MODIFY `marker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT για πίνακα `offers`
--
ALTER TABLE `offers`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT για πίνακα `orders`
--
ALTER TABLE `orders`
  MODIFY `or_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT για πίνακα `requests`
--
ALTER TABLE `requests`
  MODIFY `re_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT για πίνακα `tasks`
--
ALTER TABLE `tasks`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3029;

--
-- AUTO_INCREMENT για πίνακα `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `ve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=640;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `adminid` FOREIGN KEY (`ad_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcementid` FOREIGN KEY (`an_ad_id`) REFERENCES `administrator` (`ad_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announceprid` FOREIGN KEY (`an_product_id`) REFERENCES `base` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `announcement_product_mapping`
--
ALTER TABLE `announcement_product_mapping`
  ADD CONSTRAINT `mapping_announcement_fk` FOREIGN KEY (`an_id`) REFERENCES `announcements` (`an_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mapping_product_fk` FOREIGN KEY (`an_product_id`) REFERENCES `base` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `civilian`
--
ALTER TABLE `civilian`
  ADD CONSTRAINT `cid` FOREIGN KEY (`c_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_civilian_fk` FOREIGN KEY (`c_id`) REFERENCES `civilian` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `markers`
--
ALTER TABLE `markers`
  ADD CONSTRAINT `markers_ibfk_1` FOREIGN KEY (`or_id`) REFERENCES `orders` (`or_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vemarkid` FOREIGN KEY (`ve_id`) REFERENCES `vehicle` (`ve_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `anid` FOREIGN KEY (`o_an_id`) REFERENCES `announcements` (`an_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ciid` FOREIGN KEY (`o_c_id`) REFERENCES `civilian` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `oorid` FOREIGN KEY (`o_or_id`) REFERENCES `orders` (`or_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `oprid` FOREIGN KEY (`o_pr_id`) REFERENCES `base` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `ordc` FOREIGN KEY (`or_c_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ortaskid` FOREIGN KEY (`or_task_id`) REFERENCES `tasks` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `civid` FOREIGN KEY (`re_c_id`) REFERENCES `civilian` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orid` FOREIGN KEY (`re_or_id`) REFERENCES `orders` (`or_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prid` FOREIGN KEY (`re_pr_id`) REFERENCES `base` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `rescuer`
--
ALTER TABLE `rescuer`
  ADD CONSTRAINT `sid` FOREIGN KEY (`resc_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vehid` FOREIGN KEY (`resc_ve_id`) REFERENCES `vehicle` (`ve_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `taskveh` FOREIGN KEY (`t_vehicle`) REFERENCES `vehicle` (`ve_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;