-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: db_service_complains:3306
-- Время создания: Окт 09 2023 г., 11:36
-- Версия сервера: 5.7.43
-- Версия PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Complains`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comment_img`
--

CREATE TABLE `comment_img` (
  `id` int(11) NOT NULL,
  `comment_object_id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date_when_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comment_object`
--

CREATE TABLE `comment_object` (
  `id` int(11) NOT NULL,
  `inner_number` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `user_login` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_when_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `comment_object`
--

INSERT INTO `comment_object` (`id`, `inner_number`, `text`, `user_login`, `date_when_created`) VALUES
(7, 123, 'admin', 'snappsi', '2023-10-09 12:27:19'),
(17, 123, 'admin', 'admin', '2023-10-09 13:46:56'),
(25, 3321, 'sdfsdfdf', 'manager', '2023-10-09 14:01:06'),
(26, 3321, 'sdfsdfdf', 'manager', '2023-10-09 14:01:07');

-- --------------------------------------------------------

--
-- Структура таблицы `img`
--

CREATE TABLE `img` (
  `obj_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `inner_number` int(11) NOT NULL,
  `img` text COLLATE utf8_unicode_ci NOT NULL,
  `date_when_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `img`
--


-- --------------------------------------------------------

--
-- Структура таблицы `msg`
--

CREATE TABLE `msg` (
  `id` int(11) NOT NULL,
  `from_user` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `to_user` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_when_sent` datetime NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `viewed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `msg`
--


-- --------------------------------------------------------

--
-- Структура таблицы `object`
--

CREATE TABLE `object` (
  `id` int(11) NOT NULL,
  `inner_number` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `full_text` text COLLATE utf8_unicode_ci NOT NULL,
  `date_when_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `agreement` text COLLATE utf8_unicode_ci,
  `service` text COLLATE utf8_unicode_ci,
  `user_login` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `object`
--



-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `privilege` text COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `privilege`, `full_name`) VALUES
(8, 'snappsi', 'admin', 'super_user', 'https://t.me/SnappesiSnappes'),
(9, 'admin', 'admin', 'super_user', 'Админ'),
(11, 'manager', 'manager', 'manager', 'Менеджер');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comment_img`
--
ALTER TABLE `comment_img`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_object_id` (`comment_object_id`);

--
-- Индексы таблицы `comment_object`
--
ALTER TABLE `comment_object`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object_id` (`inner_number`);

--
-- Индексы таблицы `img`
--
ALTER TABLE `img`
  ADD PRIMARY KEY (`id`),
  ADD KEY `obj_id` (`obj_id`),
  ADD KEY `inner_number` (`inner_number`);

--
-- Индексы таблицы `msg`
--
ALTER TABLE `msg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user` (`from_user`),
  ADD KEY `to_user` (`to_user`);

--
-- Индексы таблицы `object`
--
ALTER TABLE `object`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inner_number_2` (`inner_number`),
  ADD KEY `user_login` (`user_login`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comment_img`
--
ALTER TABLE `comment_img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comment_object`
--
ALTER TABLE `comment_object`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `img`
--
ALTER TABLE `img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `msg`
--
ALTER TABLE `msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `object`
--
ALTER TABLE `object`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment_img`
--
ALTER TABLE `comment_img`
  ADD CONSTRAINT `comment_img_ibfk_1` FOREIGN KEY (`comment_object_id`) REFERENCES `comment_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `img`
--
ALTER TABLE `img`
  ADD CONSTRAINT `img_ibfk_1` FOREIGN KEY (`obj_id`) REFERENCES `object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
