-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: db_service_complains:3306
-- Время создания: Ноя 05 2023 г., 14:50
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
  `obj_id_fk` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `user_login` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_when_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

INSERT INTO `object` (`id`, `inner_number`, `title`, `full_text`, `date_when_created`, `is_published`, `closed`, `agreement`, `service`, `user_login`) VALUES
(1, 123123, '123123', '<br>Внешний номер магазина = 123123<br>Тип магазина = 123123<br>Субъект РФ  = 123123<br>Адрес  = 123123<br>ФИО ДМ  = 123123<br>Телефон ДМ  = 123123<br>Суть разговора с ДМ  = 123123<br>ФИО СПВ  = 123123<br>Телефон СПВ  = 123123<br>Суть разговора с СПВ = 123123 <br><b> Основной текст:</b><br>123123', '2023-11-05 14:49:30', 0, 0, 'com1', 'com1', 'snappsi');

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
(8, 'snappsi', 'admin', 'super_user', 'Леонид Николаевич'),
(9, 'admin', 'admin', 'super_user', 'Админ'),
(10, 'DM', 'DM', 'DM', 'DM'),
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
  ADD KEY `object_id` (`obj_id_fk`),
  ADD KEY `obj_id_fk` (`obj_id_fk`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `img`
--
ALTER TABLE `img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `msg`
--
ALTER TABLE `msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `object`
--
ALTER TABLE `object`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment_img`
--
ALTER TABLE `comment_img`
  ADD CONSTRAINT `comment_img_ibfk_1` FOREIGN KEY (`comment_object_id`) REFERENCES `comment_object` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `img`
--
ALTER TABLE `img`
  ADD CONSTRAINT `img_ibfk_1` FOREIGN KEY (`obj_id`) REFERENCES `object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
