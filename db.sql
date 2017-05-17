-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 17 2017 г., 21:19
-- Версия сервера: 5.5.53
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `blog1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `material` int(11) NOT NULL,
  `module` int(11) NOT NULL,
  `added` varchar(20) NOT NULL,
  `text` tinytext NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `cat` int(11) NOT NULL,
  `added` varchar(20) NOT NULL,
  `text` longtext NOT NULL,
  `date` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `rate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `name`, `cat`, `added`, `text`, `date`, `active`, `rate`) VALUES
(126, 'Lorem ipsum', 1, 'admin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In fringilla ipsum sed nisl tristique faucibus. Curabitur turpis magna, hendrerit quis mollis vel, tincidunt non nulla. Sed vel mattis nunc. Vestibulum ut neque quis dui congue tincidunt sit amet ne', '2017-05-16 20:48:23', 1, 0),
(125, 'lorem ipsum 4', 1, 'admin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In fringilla ipsum sed nisl tristique faucibus. Curabitur turpis magna, hendrerit quis mollis vel, tincidunt non nulla. Sed vel mattis nunc. Vestibulum ut neque quis dui congue tincidunt sit amet ne', '2017-05-16 20:48:08', 1, 0),
(124, 'Lorem ipsum 3', 1, 'admin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In fringilla ipsum sed nisl tristique faucibus. Curabitur turpis magna, hendrerit quis mollis vel, tincidunt non nulla. Sed vel mattis nunc. Vestibulum ut neque quis dui congue tincidunt sit amet ne', '2017-05-16 20:47:57', 1, 0),
(123, 'Lorem ipsum 2', 1, 'admin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In fringilla ipsum sed nisl tristique faucibus. Curabitur turpis magna, hendrerit quis mollis vel, tincidunt non nulla. Sed vel mattis nunc. Vestibulum ut neque quis dui congue tincidunt sit amet ne', '2017-05-16 20:47:42', 1, 0),
(122, 'Lorem Ipsum', 1, 'admin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In fringilla ipsum sed nisl tristique faucibus. Curabitur turpis magna, hendrerit quis mollis vel, tincidunt non nulla. Sed vel mattis nunc. Vestibulum ut neque quis dui congue tincidunt sit amet ne', '2017-05-16 20:47:22', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `regdate` datetime NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gender` int(11) NOT NULL,
  `avatar` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `group` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `regdate`, `email`, `gender`, `avatar`, `active`, `group`) VALUES
(21, 'admin', '17293bc3f4df46375e00e388c600cff4', 'admin', '2017-05-17 20:07:01', 'admin@gmail.com', 1, 0, 0, 2),
(22, 'user', '78233335e92bb540d6c154fd12758367', 'user', '2017-05-17 20:36:08', 'user@gmail.com', 1, 0, 0, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;
--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
