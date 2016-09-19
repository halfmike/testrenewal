-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 18 2016 г., 20:19
-- Версия сервера: 5.5.48
-- Версия PHP: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `testrenewal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `dealer`
--

CREATE TABLE IF NOT EXISTS `dealer` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `pattern` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dealer`
--

INSERT INTO `dealer` (`id`, `name`, `pattern`) VALUES
(1, 'Дистрибьютор 1', 'pharmacy|product|count'),
(2, 'Дистрибьютор 2', 'product|pharmacy|count');

-- --------------------------------------------------------

--
-- Структура таблицы `pharmacy`
--

CREATE TABLE IF NOT EXISTS `pharmacy` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pharmacy_alias`
--

CREATE TABLE IF NOT EXISTS `pharmacy_alias` (
  `id` int(10) unsigned NOT NULL,
  `pharmacy_id` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `dealer_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `product_alias`
--

CREATE TABLE IF NOT EXISTS `product_alias` (
  `id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `dealer_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `supply`
--

CREATE TABLE IF NOT EXISTS `supply` (
  `id` int(10) unsigned NOT NULL,
  `dealer_id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `file` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `supply_list`
--

CREATE TABLE IF NOT EXISTS `supply_list` (
  `id` int(10) unsigned NOT NULL,
  `supply_id` int(10) unsigned NOT NULL,
  `product_alias_id` int(10) unsigned NOT NULL,
  `pharmacy_alias_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `dealer`
--
ALTER TABLE `dealer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `pharmacy_alias`
--
ALTER TABLE `pharmacy_alias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_id` (`pharmacy_id`),
  ADD KEY `dealer_id` (`dealer_id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `product_alias`
--
ALTER TABLE `product_alias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `dealer_id` (`dealer_id`);

--
-- Индексы таблицы `supply`
--
ALTER TABLE `supply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dealer_id` (`dealer_id`);

--
-- Индексы таблицы `supply_list`
--
ALTER TABLE `supply_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_alias_id`),
  ADD KEY `pharmacy_id` (`pharmacy_alias_id`),
  ADD KEY `supply_id` (`supply_id`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `dealer`
--
ALTER TABLE `dealer`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `pharmacy_alias`
--
ALTER TABLE `pharmacy_alias`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `product_alias`
--
ALTER TABLE `product_alias`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT для таблицы `supply`
--
ALTER TABLE `supply`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `supply_list`
--
ALTER TABLE `supply_list`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pharmacy_alias`
--
ALTER TABLE `pharmacy_alias`
  ADD CONSTRAINT `pharmacy_alias_ibfk_2` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmacy_alias_ibfk_1` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_alias`
--
ALTER TABLE `product_alias`
  ADD CONSTRAINT `product_alias_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_alias_ibfk_2` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `supply`
--
ALTER TABLE `supply`
  ADD CONSTRAINT `supply_ibfk_1` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `supply_list`
--
ALTER TABLE `supply_list`
  ADD CONSTRAINT `supply_list_ibfk_3` FOREIGN KEY (`pharmacy_alias_id`) REFERENCES `pharmacy_alias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supply_list_ibfk_1` FOREIGN KEY (`supply_id`) REFERENCES `supply` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supply_list_ibfk_2` FOREIGN KEY (`product_alias_id`) REFERENCES `product_alias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
