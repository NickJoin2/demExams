-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 02 2024 г., 23:23
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `grechdem`
--

-- --------------------------------------------------------

--
-- Структура таблицы `car`
--

CREATE TABLE `car` (
  `id` int(20) NOT NULL,
  `number` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `car`
--

INSERT INTO `car` (`id`, `number`, `brand`, `model`, `color`) VALUES
(1, 'А224НУ', 'Лада', 'Prius', 'Золотистый'),
(2, 'А224НУ', 'Лада', 'Prius', 'Золотистый'),
(3, 'А224НУ', 'Лада', 'Prius', 'Золотистый'),
(4, 'adf', 'asdf', 'adf', 'adsf'),
(5, 'adsf', 'adf', 'adf', 'adf'),
(6, '1', '1', '1', '1'),
(7, '2', '2', '2', '2'),
(8, '3', '3', '3', '3'),
(9, '4', '4', '4', '4'),
(10, '7', '7', '7', '7'),
(11, '0', '0', '0', '0'),
(12, '11', '11', '11', '11'),
(13, '33', '33', '33', '33'),
(14, '23', '23', '23', '23'),
(15, '324', '234', '234', '342'),
(16, '23424', '23423', '234234', '234234'),
(17, '23424', '23423', '234234', '234234'),
(18, '23', '13', '23', '123'),
(19, '55345', '34535', '34535', '345345'),
(20, '55345', '34535', '34535', '345345'),
(21, 'хуй21', 'Членосос', 'Белый 15 см', 'Белый'),
(22, 'хуй', 'хуй', 'хуй', 'хуй'),
(23, 'asdfadf', 'adsfafd', 'adfsadfs', 'adsfafda'),
(24, '12345Y', 'BMW', 'PRO', 'Серебристый'),
(25, 'A223y', 'KIA', 'LOGAN', 'ЗЕЛЕНЫЙ'),
(26, '2fdc43', 'BMW', 'Prius', 'Белый'),
(27, '23f23', 'BMW', 'Prius', 'Черненький'),
(28, 'Ошибка тест', 'Ошибка тест', 'Ошибка тест', 'Ошибка тест'),
(29, 'Ошибка тест', 'Ошибка тест', 'Ошибка тест', 'Ошибка тест'),
(30, 'X 883 XX 33', 'BMW', 'LOGAN', 'Черный');

-- --------------------------------------------------------

--
-- Структура таблицы `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `statement_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `photos`
--

INSERT INTO `photos` (`id`, `statement_id`, `photo_path`) VALUES
(53, 26, '11_66f9b732090a9.jpg'),
(54, 26, '12_66f9b73209a1f.jpg'),
(55, 26, '13_66f9b7320a09f.jpg'),
(56, 26, '14_66f9b7320a652.jpg'),
(57, 26, '15_66f9b7320aae0.jpg'),
(58, 28, '13_66f9b84349d65.jpg'),
(59, 28, '14_66f9b8434a542.jpg'),
(60, 28, '15_66f9b8434ac09.jpg'),
(61, 28, '16_66f9b8434b29e.jpg'),
(62, 28, '17_66f9b8434b8b2.jpg'),
(63, 28, '18_66f9b8434becb.jpg'),
(64, 29, '1_66f9b8b4a4de0.jpg'),
(65, 29, '2_66f9b8b4a560d.jpg'),
(66, 29, '3_66f9b8b4a5d04.jpg'),
(67, 30, '15_66fc56b818b84.jpg'),
(68, 30, '16_66fc56b819345.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'user'),
(2, 'admin'),
(3, 'superAdmin'),
(4, 'blocked');

-- --------------------------------------------------------

--
-- Структура таблицы `statement`
--

CREATE TABLE `statement` (
  `id` int(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `violation_type` varchar(255) NOT NULL,
  `car_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `date_submission` datetime NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `statement`
--

INSERT INTO `statement` (`id`, `address`, `violation_type`, `car_id`, `user_id`, `status_id`, `date_submission`, `date_create`) VALUES
(26, 'Тот самый', 'Нарушил', 26, 18, 2, '2024-10-01 04:24:00', '2024-09-30 00:23:14'),
(27, 'Тот самый', 'Нарушил', 27, 18, 3, '2024-09-13 03:26:00', '2024-09-30 00:25:13'),
(28, 'Ошибка тест', 'Ошибка тест', 28, 18, 3, '2024-09-29 22:27:47', '2024-09-30 00:27:47'),
(29, 'Ошибка тест', 'Ошибка тест', 29, 19, 2, '2024-09-05 03:29:00', '2024-09-30 00:29:40'),
(30, 'лен ком', 'пересечение двойной сплошной', 30, 18, 1, '2024-10-10 02:09:00', '2024-10-02 00:08:24');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Новое'),
(2, 'Принятое'),
(3, 'Отклоненное');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `role_id`) VALUES
(17, 'victoria20', 'cheril@gmail.com', '$2y$10$sDhkArtCBXQJY/nDEfs2AukWgTrgSovwegCdgZMzgsqCNaADQAmom', 4),
(18, 'victoria2', 'aliment@gmail.com', '$2y$10$XnInc2O4lYv3AMtGoqxUs.bF./ESzgevj3NsGgpXhFixqf9NSXRkO', 3),
(19, 'victoria23', 'victoria@gmail.com', '$2y$10$yRvgSJf9nkmjc2WMnnM22OKZjqIav5LdLyoxJ6uMpAqsdt06WdLN6', 3),
(20, 'leps', 'leps@gmail.com', '$2y$10$AgTIKaZFJ85ZOJQEf2KI2uU79wHbsWBvbg6jWQzkGEW.N5JuKEZvu', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `user_info`
--

CREATE TABLE `user_info` (
  `id` int(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `phone_clean` varchar(255) NOT NULL,
  `phone_mask` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user_info`
--

INSERT INTO `user_info` (`id`, `first_name`, `last_name`, `middle_name`, `phone_clean`, `phone_mask`, `user_id`, `photo_path`) VALUES
(16, 'Виктория', 'Соколова', 'Вячеславовна', '73242222222', '+7 (324) - 222 - 22 - 22', 17, '_66fc226a7b30b.'),
(17, 'Виктория', 'Соколова', 'Вячеславовна', '73242222225', '+7 (324) - 222 - 22 - 25', 18, '17_66fc59796da80.jpg'),
(18, 'Виктория', 'Виктория', 'Вячеславовна', '7324222221', '+7 (324) - 222 - 22 - 1', 19, '5467813218773756333_66fdaffc7a11e.jpg'),
(19, 'Григорий', 'Лепс', 'Валерьевич', '71234564321', '+7 (123) - 456 - 43 - 21', 20, 'Снимок экрана 2024-08-28 222245_66fd4235e8633.png');

-- --------------------------------------------------------

--
-- Структура таблицы `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `statement_id` int(11) NOT NULL,
  `video_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `video`
--

INSERT INTO `video` (`id`, `statement_id`, `video_path`) VALUES
(11, 26, '3_66f9b7320b2f6.mp4'),
(12, 27, '2_66f9b7a90bc61.mp4'),
(13, 28, '2_66f9b8434c707.mp4'),
(14, 29, '6_66f9b8b4a66e4.avi'),
(15, 30, '1_66fc56b81f256.mp4'),
(16, 30, '2_66fc56b81fe54.mp4');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statement_id` (`statement_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `statement`
--
ALTER TABLE `statement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Индексы таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statement_id` (`statement_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car`
--
ALTER TABLE `car`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `statement`
--
ALTER TABLE `statement`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`statement_id`) REFERENCES `statement` (`id`);

--
-- Ограничения внешнего ключа таблицы `statement`
--
ALTER TABLE `statement`
  ADD CONSTRAINT `statement_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`),
  ADD CONSTRAINT `statement_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `statement_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`statement_id`) REFERENCES `statement` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
