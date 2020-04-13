-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Апр 13 2020 г., 17:28
-- Версия сервера: 5.7.29-0ubuntu0.18.04.1
-- Версия PHP: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `times`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `login` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `tasks` varchar(1000) DEFAULT NULL,
  `goals_ids` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `login`, `name`, `description`, `tasks`, `goals_ids`) VALUES
(1, 'first', 'first\'s category №1', '.......', '37|37|37|37', NULL),
(2, 'first', 'first\'s category №2', '.......', '37|37', NULL),
(3, 'first', 'This is name of category...', 'Description...', '37|37|37|37', NULL),
(6, 'first', 'Еще одна категория=)', 'описание...', '37', NULL),
(7, 'first', 'Еще одна категория...', 'описание...', '37|37|37', NULL),
(8, 'My priofil', 'Образование', 'Книги, курсы, школа...', NULL, NULL),
(9, 'My priofil', 'Досуг', 'Прогулки с друзьями, просмотр фильмов...', NULL, NULL),
(10, 'My priofil', 'Красота и здоровье', 'Спорт, маникюр, уход за лицом...', NULL, NULL),
(11, 'first', 'Категория...', '', '37|37|37|37', NULL),
(12, 'first', 'Английский', '', NULL, NULL),
(13, 'test', 'Первая категория', '....', '38', NULL),
(14, 'test', 'Вторая...', '', NULL, NULL),
(15, 'zzz', 'Первая категория', 'цяучкесншигштоооооо\r\n', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `login` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` date NOT NULL,
  `created_time` time NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `login`, `created_date`, `created_time`, `value`) VALUES
(33, 'first', '2020-04-03', '11:10:00', 'Комментарий...'),
(34, 'first', '2020-04-03', '11:23:00', 'Это многострочный  комментарий\r\n*\r\n*\r\n*\r\n*'),
(35, 'first', '2020-04-03', '11:24:00', 'Это многострочный  комментарий\r\n*\r\n*\r\n*\r\n*'),
(36, 'first', '2020-04-03', '11:24:00', 'Это многострочный  комментарий\r\n*\r\n*\r\n*\r\n*'),
(37, 'first', '2020-04-03', '11:25:00', 'Еще один комментарий...'),
(38, 'first', '2020-04-03', '11:27:00', 'Еще один комментарий...'),
(39, 'first', '2020-04-03', '11:28:00', 'Еще один комментарий...'),
(40, 'first', '2020-04-03', '12:58:00', 'Первый комментарий'),
(41, 'test', '2020-04-03', '17:37:00', 'Первый комментарий к первой задаче...'),
(42, 'test', '2020-04-03', '17:37:00', 'Второй...'),
(43, 'first', '2020-04-05', '20:11:00', 'First\'s first comment');

-- --------------------------------------------------------

--
-- Структура таблицы `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `login` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_date` date DEFAULT NULL,
  `finish_date` date DEFAULT NULL,
  `ready` varchar(50) NOT NULL,
  `priority` varchar(40) DEFAULT NULL,
  `tasks_ids` varchar(255) DEFAULT NULL,
  `categories_ids` varchar(255) DEFAULT NULL,
  `categories` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `goals`
--

INSERT INTO `goals` (`id`, `login`, `name`, `description`, `created_date`, `finish_date`, `ready`, `priority`, `tasks_ids`, `categories_ids`, `categories`) VALUES
(1, 'first', 'Goal name', 'Description...\r\n', NULL, NULL, 'done', NULL, NULL, NULL, NULL),
(2, 'first', 'Цель', '', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(3, 'first', 'Еще одна цель', '...', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(4, 'first', 'One more goal...', '', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(5, 'first', 'One more goal...', '', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(6, 'first', 'One more goal...', '', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(7, 'first', 'One more goal...', '', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(8, 'first', '...', '...', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(9, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(10, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(11, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(12, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(13, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(14, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(15, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(16, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(17, 'first', 'qwert', 'asdf', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(18, 'first', 'Asd', 'asd\r\n', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(19, 'first', 'qwerty1', 'qwertyv', NULL, NULL, 'inProgress', NULL, NULL, NULL, NULL),
(20, 'first', 'Новая цель', 'описание...', '2020-03-31', '2020-03-27', 'inProgress', 'urgent|notImportant', NULL, NULL, '1|3'),
(21, 'first', 'Hello!', 'zxcvbnm,,', '2020-04-01', '2020-04-30', 'inProgress', 'urgent|notImportant', NULL, NULL, '1|2|3'),
(22, 'first', 'Новая цель', 'Описание..............................................................................................................................................................', '2020-04-03', '2020-04-11', 'inProgress', 'notUrgent|notImportant', NULL, NULL, '2|7'),
(23, 'first', 'Goal', 'qwerty...', '2020-04-04', '2020-04-09', 'inProgress', 'notUrgent|important', NULL, NULL, '1|6|11');

-- --------------------------------------------------------

--
-- Структура таблицы `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `login` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `notes`
--

INSERT INTO `notes` (`id`, `login`, `name`, `value`, `date`, `time`, `description`) VALUES
(1, 'first', 'My first note...', 'Hm...', '2003-10-20', '11:26:00', NULL),
(2, 'first', 'zxcvbnm', 'One more note...', '2003-10-20', '12:09:00', NULL),
(4, 'first', 'Asd', 'asd', '2020-03-27', '11:20:00', NULL),
(5, 'first', 'My note', 'zxcvbn', '2020-03-27', '11:31:00', NULL),
(6, 'My priofil', 'Список покупок', 'Гречка, яблоки, макароны, хлеб', '2020-03-27', '12:09:00', NULL),
(7, 'test', 'Новая заметка', '....\r\nапгттэцуа\r\nцпдцммжд', '2020-04-03', '17:40:00', NULL),
(8, 'test', 'Еще одна заметка', '', '2020-04-03', '17:41:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `strangers`
--

CREATE TABLE `strangers` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `strangers`
--

INSERT INTO `strangers` (`id`, `login`, `email`, `password`) VALUES
(13, 'first', 'first@first.first', '$2y$10$W3gXHN1mIK/6zsIPSUd2Ieo.GJDQh8QEJEsqFxX/x4.5tJnZ8YgYO'),
(20, 'second', 'second@second.second', '$2y$10$tOxs.DnQOzsvV4VTR0eQJOfTPiZnKe1InHNEaZhMAnd2ZCGSFelOK'),
(21, 'someone', 'someone@someone.someone', '$2y$10$XeANcqy.guhPprP49Vwsh.9BiOEslIQ5hZW6q3jFuvO68SBKxam2u'),
(22, 'zzzzz', 'zzzzz@zzzzz.zzzzz', '$2y$10$yTqDPoKxHosPJdId1IoWDuruWUUO.hw6XydtAU6O1s3X9MROzvfTq'),
(23, 'My priofil', 'myprofil@myprofil.myprofil', '$2y$10$NaacG7b1GZCvVsTD39dwAOy0Mf/s1DmBK9kMXXGixgh0dbckKEwBe'),
(24, 'test', 'test@test.test', '$2y$10$FZgKja3KP/Iw5hlMZpxTrOVX9rNy2MvLbRo/FVmUJbj8H9lM1kZ7G'),
(25, 'zzz', 'zzz@zzz.zzz', '$2y$10$h8thAAFenqFZPMhOPgVFDesWQySq3bpuCft7IdQ79U8mkDF61B/de');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `login` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `goal_id` int(11) DEFAULT NULL,
  `created` varchar(10) NOT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `finish_date` date DEFAULT NULL,
  `finish_time` time DEFAULT NULL,
  `priority` varchar(30) DEFAULT NULL,
  `categories` varchar(100) DEFAULT NULL,
  `comments_ids` varchar(100) DEFAULT NULL,
  `ready` varchar(10) DEFAULT NULL,
  `repeat` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `login`, `name`, `description`, `goal_id`, `created`, `start_date`, `start_time`, `finish_date`, `finish_time`, `priority`, `categories`, `comments_ids`, `ready`, `repeat`) VALUES
(2, 'first', 'One more task', '...', NULL, '2020-03-25', NULL, NULL, NULL, NULL, 'notUrgent|notImportant', '1|2|3', NULL, 'inProgress', NULL),
(5, 'first', '5yhrmrm', 'geelfblfbdfjvns.vdns;ovinsovihnsv;oblifvnskjvnlx  ljkv n ln l. vn.l nln lo zid vnzids vnov;nzdivndv\' ibicoznsh;voinz vol;\r\n b;od vbdx vnzo;dvnsdvo;vbnxfdfd', NULL, '2020-03-25', NULL, NULL, NULL, NULL, 'urgent|important', '2|3|5', NULL, 'inProgress', NULL),
(6, 'first', '=))))', 'egtjnej5egerggdflvjbskbilubweiufwieufbwfubsiubsdvujbsdvlij sks welfuhwelifuhwelifuhwelrgluew wlifl luwieh lwe', 1, '2020-03-25', '2020-03-27', '05:53:00', '2020-04-05', '23:23:00', 'urgent|important', '2|6', NULL, 'done', NULL),
(8, 'first', 'qwerty2', 'Описание ............................................................................................. ............. ...............................................................................................', NULL, '2020-03-25', '2020-03-27', '18:36:29', '2020-04-23', '12:52:00', 'notUrgent|notImportant', '2|3', '43', 'inProgress', NULL),
(9, 'first', 'qwerty3', '', NULL, '2020-03-25', '2020-03-27', NULL, NULL, NULL, 'notUrgent|notImportant', '', NULL, 'inProgress', NULL),
(10, 'first', 'qwerty4', '', NULL, '2020-03-25', '2020-03-27', NULL, NULL, NULL, 'notUrgent|notImportant', '', NULL, 'inProgress', NULL),
(11, 'first', 'qwerty5', '', NULL, '2020-03-25', '2020-03-27', NULL, NULL, NULL, 'urgent|notImportant', '3|6', NULL, 'inProgress', NULL),
(13, 'first', 'qwerty5', '', NULL, '2020-03-25', '2020-03-27', '22:22:00', NULL, '15:23:00', 'urgent|important', '1', NULL, 'freezen', NULL),
(14, 'first', 'qwerty6', '', NULL, '2020-03-25', '2020-03-27', NULL, NULL, NULL, 'urgent|notImportant', '3|6', '33|34|35|36|37|38|39', 'freezen', NULL),
(15, 'first', 'Hello!', NULL, NULL, '2020-03-24', NULL, NULL, NULL, NULL, 'urgent|notImportant', '1|3', NULL, 'inProgress', NULL),
(16, 'first', 'Hello!', NULL, NULL, '2020-03-24', NULL, NULL, NULL, NULL, 'urgent|notImportant', '1|3', NULL, 'inProgress', NULL),
(17, 'first', 'Hello! =)', 'qwerty12345', NULL, '2020-03-24', '2020-03-26', '23:02:00', '2020-03-26', NULL, 'urgent|important', '1|3', NULL, 'inProgress', NULL),
(18, 'first', '!@#$', 'qwerty121', NULL, '2020-03-24', NULL, NULL, NULL, NULL, 'urgent|important', '1|3', NULL, 'inProgress', NULL),
(19, 'first', 'Reading....', NULL, NULL, '2020-03-24', NULL, NULL, NULL, NULL, 'urgent|important', '1|2', NULL, 'inProgress', NULL),
(20, 'first', 'Hello! =)', 'qwerty12345', NULL, '2020-03-24', '2020-03-26', '23:02:00', '2020-03-26', NULL, 'urgent|important', '1|3', NULL, 'inProgress', NULL),
(21, 'first', '123', 'zxcvbnm', NULL, '2020-03-24', '2020-03-27', NULL, NULL, NULL, 'urgent|important', '2|5', NULL, 'done', NULL),
(22, 'first', '1234', NULL, NULL, '2020-03-24', '2020-03-27', NULL, NULL, NULL, 'urgent|important', NULL, NULL, 'done', NULL),
(24, 'first', 'asd', NULL, NULL, '2020-03-26', NULL, NULL, NULL, NULL, NULL, '1|6', NULL, 'inProgress', NULL),
(25, 'first', 'Qwerty123', NULL, NULL, '2020-03-26', NULL, '23:05:00', '2020-03-29', NULL, 'urgent|notImportant', '1|3|6', NULL, 'inProgress', NULL),
(26, 'My priofil', 'Посмотреть фильм', '\"Матрица\"', NULL, '2020-03-27', '2020-03-27', NULL, NULL, NULL, NULL, '9', NULL, 'inProgress', NULL),
(27, 'My priofil', 'Посмотреть фильм', '\"Матрица\"', NULL, '2020-03-27', '2020-03-27', NULL, NULL, NULL, 'urgent|notImportant', '9', NULL, 'inProgress', NULL),
(28, 'My priofil', 'Вебинар по физике', NULL, NULL, '2020-03-27', '2020-03-27', '15:00:00', NULL, NULL, 'urgent|notImportant', '8', NULL, 'inProgress', NULL),
(29, 'My priofil', 'Английский в дискорде', NULL, NULL, '2020-03-27', '2020-03-27', '19:00:00', NULL, NULL, 'urgent|notImportant', '8', NULL, 'inProgress', NULL),
(30, 'My priofil', 'Помочь другу с прямой трансляцией', NULL, NULL, '2020-03-27', '2020-03-27', '18:00:00', NULL, NULL, 'urgent|notImportant', NULL, NULL, 'inProgress', NULL),
(31, 'My priofil', 'Помыть полы', NULL, NULL, '2020-03-27', '2020-03-27', NULL, '2020-03-27', NULL, 'urgent|important', NULL, NULL, 'freezen', NULL),
(32, 'My priofil', 'Прочитать второй том ', NULL, NULL, '2020-03-27', '2020-03-27', '12:00:00', '2020-03-29', NULL, 'urgent|important', '8', NULL, 'inProgress', NULL),
(33, 'My priofil', 'Бег', NULL, NULL, '2020-03-27', NULL, NULL, NULL, NULL, 'urgent|notImportant', '10', NULL, 'inProgress', NULL),
(34, 'My priofil', 'Новая задача', '...', NULL, '2020-03-28', '2020-03-28', '15:00:00', NULL, NULL, 'urgent|notImportant', '8', NULL, 'inProgress', NULL),
(35, 'first', 'Новая задача', 'Какое-то описание...', NULL, '2020-04-03', '2020-04-03', '20:52:00', '2020-04-25', NULL, 'urgent|important', '1|3|7', '40', 'inProgress', NULL),
(36, 'first', 'zxcvbnm', 'qwerebbebfd', NULL, '2020-04-03', NULL, NULL, NULL, NULL, NULL, '1|3|6|12', NULL, 'inProgress', NULL),
(37, 'first', 'zxcvbnm,', NULL, NULL, '2020-04-03', NULL, NULL, NULL, NULL, 'notUrgent|notImportant', '1|2|3|6|7|11', NULL, 'inProgress', NULL),
(38, 'test', 'Первая задача', '..............', NULL, '2020-04-03', '2020-04-10', '15:02:00', '2020-04-17', NULL, 'urgent|notImportant', '13', '41|42', 'inProgress', NULL),
(39, 'first', 'Задача...', 'фывап', NULL, '2020-04-06', NULL, NULL, NULL, NULL, 'urgent|important', '3|7', NULL, 'inProgress', 'd'),
(40, 'first', 'zxcvb', NULL, NULL, '2020-04-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inProgress', 'm'),
(41, 'first', 'zxcvbn', NULL, NULL, '2020-04-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inProgress', 'w');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `strangers`
--
ALTER TABLE `strangers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT для таблицы `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `strangers`
--
ALTER TABLE `strangers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
