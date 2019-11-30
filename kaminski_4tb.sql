-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 30 Lis 2019, 02:30
-- Wersja serwera: 10.3.15-MariaDB
-- Wersja PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `kaminski_4tb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `albumy`
--

CREATE TABLE `albumy` (
  `id` int(11) NOT NULL,
  `tytul` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `data` datetime NOT NULL,
  `id_uzytkownika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `albumy`
--

INSERT INTO `albumy` (`id`, `tytul`, `data`, `id_uzytkownika`) VALUES
(6, 'album 1', '2019-11-28 17:16:30', 18),
(7, 'przykładowy album 2', '2019-11-28 17:16:40', 18),
(8, 'album 3', '2019-11-29 00:49:14', 18),
(9, 'kolejny album nr 4', '2019-11-29 00:49:26', 18),
(10, 'album innego usera 1', '2019-11-29 00:51:02', 19),
(11, 'album ASDFasdf1234 nr 2', '2019-11-29 00:51:27', 19),
(12, 'album 7', '2019-11-29 01:12:29', 19),
(13, 'album 8', '2019-11-29 01:12:34', 19),
(14, 'album 9', '2019-11-29 01:12:39', 19),
(15, 'album 10', '2019-11-29 01:12:49', 19),
(16, 'album 11', '2019-11-29 01:13:00', 19),
(17, 'album 12', '2019-11-29 01:13:03', 19),
(18, 'album 13', '2019-11-29 01:13:08', 19),
(19, 'album 14', '2019-11-29 01:13:13', 19),
(20, 'album 15', '2019-11-29 01:13:17', 19),
(21, 'album 16', '2019-11-29 01:13:21', 19),
(22, 'album 17', '2019-11-29 01:13:27', 19),
(23, 'album 18', '2019-11-29 01:13:32', 19),
(24, 'album 19', '2019-11-29 01:13:35', 19),
(25, 'album 20', '2019-11-29 01:22:15', 18),
(26, 'album 21', '2019-11-29 01:22:21', 18),
(27, 'album 22', '2019-11-29 01:22:26', 18);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `login` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `zarejestrowany` date NOT NULL,
  `uprawnienia` enum('użytkownik','moderator','administrator') COLLATE utf8_polish_ci NOT NULL,
  `aktywny` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `login`, `haslo`, `email`, `zarejestrowany`, `uprawnienia`, `aktywny`) VALUES
(18, 'QWERqwer1234', '9692507f124f9aa63d869aa72ce219ec', 'QWERqwer1234@gmail.com', '2019-10-21', 'użytkownik', 1),
(19, 'ASDFasdf1234', '86ce4a3d83ff1debe4d94021dd8a3e78', 'ASDFasdf1234@gmail.com', '2019-10-21', 'administrator', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE `zdjecia` (
  `id` int(11) NOT NULL,
  `opis` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `id_albumu` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `zaakceptowane` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia`
--

INSERT INTO `zdjecia` (`id`, `opis`, `id_albumu`, `data`, `zaakceptowane`) VALUES
(49, 'zdjęcie 1 w albumie 1', 6, '2019-11-28 17:17:42', 1),
(50, 'pionowe zdjęcie 2 w albumie 1', 6, '2019-11-28 17:18:03', 0),
(51, 'zdjęcie 1 w albumie 2', 7, '2019-11-28 17:18:27', 1),
(52, 'zdjęcie 2 w albumie 2', 7, '2019-11-28 17:18:39', 1),
(53, '', 8, '2019-11-29 00:49:52', 0),
(54, '', 8, '2019-11-29 00:49:59', 1),
(55, '', 9, '2019-11-29 00:50:08', 0),
(56, '', 9, '2019-11-29 00:50:16', 1),
(57, '', 9, '2019-11-29 00:50:28', 1),
(58, '', 11, '2019-11-29 00:51:45', 1),
(59, '', 11, '2019-11-29 00:51:50', 1),
(60, '', 10, '2019-11-29 00:52:01', 1),
(61, '', 25, '2019-11-29 01:22:39', 1),
(62, '', 26, '2019-11-29 01:22:44', 1),
(63, '', 27, '2019-11-29 01:22:49', 1),
(64, '', 12, '2019-11-29 01:23:10', 1),
(65, '', 13, '2019-11-29 01:23:15', 1),
(66, '', 14, '2019-11-29 01:23:20', 1),
(67, '', 15, '2019-11-29 01:23:24', 1),
(68, '', 16, '2019-11-29 01:23:29', 1),
(69, '', 17, '2019-11-29 01:23:33', 1),
(70, '', 18, '2019-11-29 01:23:37', 1),
(71, '', 19, '2019-11-29 01:23:42', 1),
(72, '', 20, '2019-11-29 01:23:46', 1),
(73, '', 21, '2019-11-29 01:24:43', 1),
(74, '', 22, '2019-11-29 01:24:48', 1),
(75, '', 23, '2019-11-29 01:24:54', 1),
(76, '', 24, '2019-11-29 01:24:59', 1),
(77, '', 10, '2019-11-30 02:26:05', 1),
(78, '', 10, '2019-11-30 02:26:10', 1),
(79, '', 10, '2019-11-30 02:26:14', 1),
(80, '', 10, '2019-11-30 02:26:28', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_komentarze`
--

CREATE TABLE `zdjecia_komentarze` (
  `id_zdjecia` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `komentarz` text COLLATE utf8_polish_ci NOT NULL,
  `zaakceptowany` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia_komentarze`
--

INSERT INTO `zdjecia_komentarze` (`id_zdjecia`, `id_uzytkownika`, `data`, `komentarz`, `zaakceptowany`) VALUES
(67, 19, '2019-11-29 23:29:36', 'wegrdht', 1),
(69, 19, '2019-11-29 23:31:32', 'fdjhsdo', 1),
(69, 19, '2019-11-29 23:31:40', 'wereagth', 1),
(69, 19, '2019-11-30 00:04:20', 'fdgn', 1),
(49, 19, '2019-11-30 00:15:51', '6y7uijk', 0),
(49, 19, '2019-11-30 00:15:54', 'uikl', 0),
(49, 19, '2019-11-30 00:17:46', 'rghtgh', 1),
(75, 19, '2019-11-30 00:18:38', 'thrymj', 1),
(54, 19, '2019-11-30 00:21:32', 'ut5rik6yuloi', 1),
(54, 19, '2019-11-30 00:21:34', 'yut75ri6ykgulh', 1),
(54, 19, '2019-11-30 00:21:37', 'ut5rlh', 1),
(54, 19, '2019-11-30 00:21:40', 'yut5ikl', 1),
(49, 19, '2019-11-30 00:33:51', 'hthyj', 1),
(49, 19, '2019-11-30 00:33:53', 'gfrhdngh', 1),
(61, 19, '2019-11-30 01:06:06', 'eredhyt', 1),
(68, 19, '2019-11-30 01:13:41', 'sxdcfvgb', 1),
(68, 19, '2019-11-30 01:13:44', 'dbfng', 1),
(68, 19, '2019-11-30 01:13:46', 'efdsgvfhbngf', 1),
(68, 19, '2019-11-30 01:13:49', 'gfbdng bm', 1),
(68, 19, '2019-11-30 01:13:51', 'fdngfhnbmn', 1),
(68, 19, '2019-11-30 01:13:53', 'segdrfhjgtfmyh', 1),
(68, 19, '2019-11-30 01:14:13', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1),
(68, 19, '2019-11-30 01:14:26', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#039;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#039;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_oceny`
--

CREATE TABLE `zdjecia_oceny` (
  `id_zdjecia` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `ocena` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia_oceny`
--

INSERT INTO `zdjecia_oceny` (`id_zdjecia`, `id_uzytkownika`, `ocena`) VALUES
(50, 18, 6),
(49, 18, 7),
(50, 19, 10),
(49, 19, 1),
(67, 19, 4),
(69, 19, 6),
(75, 19, 7),
(61, 19, 4),
(68, 19, 5);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `albumy`
--
ALTER TABLE `albumy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_albumu` (`id_albumu`);

--
-- Indeksy dla tabeli `zdjecia_komentarze`
--
ALTER TABLE `zdjecia_komentarze`
  ADD KEY `id_zdjecia` (`id_zdjecia`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `zdjecia_oceny`
--
ALTER TABLE `zdjecia_oceny`
  ADD KEY `id_zdjecia` (`id_zdjecia`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `albumy`
--
ALTER TABLE `albumy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `albumy`
--
ALTER TABLE `albumy`
  ADD CONSTRAINT `albumy_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD CONSTRAINT `zdjecia_ibfk_1` FOREIGN KEY (`id_albumu`) REFERENCES `albumy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `zdjecia_komentarze`
--
ALTER TABLE `zdjecia_komentarze`
  ADD CONSTRAINT `zdjecia_komentarze_ibfk_1` FOREIGN KEY (`id_zdjecia`) REFERENCES `zdjecia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zdjecia_komentarze_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `zdjecia_oceny`
--
ALTER TABLE `zdjecia_oceny`
  ADD CONSTRAINT `zdjecia_oceny_ibfk_1` FOREIGN KEY (`id_zdjecia`) REFERENCES `zdjecia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zdjecia_oceny_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
