-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 04 2022 г., 20:34
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `moneysharing.test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin_group`
--

CREATE TABLE `admin_group` (
  `user_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admin_group`
--

INSERT INTO `admin_group` (`user_id`, `group_id`) VALUES
(1, 5),
(5, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `attachmentable`
--

CREATE TABLE `attachmentable` (
  `id` int UNSIGNED NOT NULL,
  `attachmentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachmentable_id` int UNSIGNED NOT NULL,
  `attachment_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `attachments`
--

CREATE TABLE `attachments` (
  `id` int UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint NOT NULL DEFAULT '0',
  `sort` int NOT NULL DEFAULT '0',
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `alt` text COLLATE utf8mb4_unicode_ci,
  `hash` text COLLATE utf8mb4_unicode_ci,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_expired` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `budget` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `owner`, `token`, `token_expired`, `created_at`, `updated_at`, `budget`) VALUES
(5, 'Test', 1, '638a34216a632', '2022-12-03 14:21:37', '2022-12-02 14:21:37', '2022-12-04 09:41:43', -1.00);

-- --------------------------------------------------------

--
-- Структура таблицы `group_user`
--

CREATE TABLE `group_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `group_user`
--

INSERT INTO `group_user` (`user_id`, `group_id`) VALUES
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(35, '2014_10_12_000000_create_users_table', 1),
(36, '2015_04_12_000000_create_orchid_users_table', 1),
(37, '2015_10_19_214424_create_orchid_roles_table', 1),
(38, '2015_10_19_214425_create_orchid_role_users_table', 1),
(39, '2016_08_07_125128_create_orchid_attachmentstable_table', 1),
(40, '2017_09_17_125801_create_notifications_table', 1),
(41, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(42, '2022_12_01_190121_create_groups_table', 1),
(43, '2022_12_01_190449_create_group_user_table', 1),
(44, '2022_12_01_190729_create_budgets_table', 1),
(47, '2022_12_03_075622_add_budget_to_groups', 2),
(49, '2022_12_03_185228_create_purchases_table', 4),
(50, '2022_12_03_200858_create_admin_group_table', 5),
(52, '2022_12_01_194700_create_operations_table', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `operations`
--

CREATE TABLE `operations` (
  `id` bigint UNSIGNED NOT NULL,
  `budget_before` double(8,2) NOT NULL,
  `budget_after` double(8,2) NOT NULL,
  `group_id` int NOT NULL,
  `operation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `user_id` int NOT NULL,
  `operator_id` int NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `budget_before` double(8,2) NOT NULL,
  `budget_after` double(8,2) NOT NULL,
  `group_id` int NOT NULL,
  `amount` double(8,2) NOT NULL,
  `operator_id` int NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `purchases`
--

INSERT INTO `purchases` (`id`, `budget_before`, `budget_after`, `group_id`, `amount`, `operator_id`, `comment`, `created_at`, `updated_at`) VALUES
(1, 34328.00, 44328.00, 5, 10000.00, 1, 'common comm', '2022-12-03 19:59:31', '2022-12-03 19:59:31'),
(2, 34328.00, 44328.00, 5, 10000.00, 1, 'common comm', '2022-12-03 20:00:15', '2022-12-03 20:00:15'),
(3, 34328.00, 44328.00, 5, 10000.00, 1, 'common comm', '2022-12-03 20:06:48', '2022-12-03 20:06:48'),
(4, 34328.00, 44328.00, 5, 10000.00, 1, 'common comm', '2022-12-03 20:07:17', '2022-12-03 20:07:17'),
(5, 34328.00, 44328.00, 5, 10000.00, 1, 'common comm', '2022-12-03 20:08:24', '2022-12-03 20:08:24'),
(6, 34328.00, 35328.00, 5, 1000.00, 1, NULL, '2022-12-03 20:09:23', '2022-12-03 20:09:23'),
(7, 34328.00, 35328.00, 5, 1000.00, 1, 'test', '2022-12-03 20:12:51', '2022-12-03 20:12:51'),
(8, 33328.00, 43328.00, 5, 10000.00, 1, 'селедка', '2022-12-03 20:14:30', '2022-12-03 20:14:30'),
(9, 23328.00, 24328.00, 5, 1000.00, 1, 'такси', '2022-12-03 20:17:44', '2022-12-03 20:17:44'),
(10, 20828.00, 21828.00, 5, 1000.00, 1, 'хз', '2022-12-03 20:19:26', '2022-12-03 20:19:26'),
(11, 18328.00, 19328.00, 5, 1000.00, 1, 'test', '2022-12-03 20:20:46', '2022-12-03 20:20:46'),
(12, 17328.00, 21328.00, 5, 4000.00, 1, 'test4', '2022-12-03 20:21:33', '2022-12-03 20:21:33'),
(13, 13328.00, 17772.00, 5, 4444.00, 1, '4444', '2022-12-03 20:22:38', '2022-12-03 20:22:38'),
(14, 8884.00, 14439.00, 5, 5555.00, 1, '5555', '2022-12-03 20:23:15', '2022-12-03 20:23:15'),
(15, 230000.00, 215000.00, 5, 15000.00, 1, 'рынок', '2022-12-04 03:47:25', '2022-12-04 03:47:25'),
(16, 0.00, -1.00, 5, 1.00, 1, NULL, '2022-12-04 09:41:43', '2022-12-04 09:41:43');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `role_users`
--

CREATE TABLE `role_users` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permissions` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `permissions`) VALUES
(1, NULL, '2580744@gmail.com', 'sshadowww', NULL, '$2y$10$m218zmfxnNZ8X/Q25r19TelV1PR6Bslz3gvjg5BADtEhLdP8dKTbG', NULL, '2022-12-02 13:32:01', '2022-12-02 13:32:01', NULL),
(2, NULL, '1@mail.ru', 'Nikita', NULL, '$2y$10$98ttirdV2tkqchvm22jiy.0D00o58r.At0C1bVxnv.ecNLZjClwrG', NULL, '2022-12-02 15:36:02', '2022-12-02 15:36:02', NULL),
(3, NULL, '25802744@gmail.com', 'Dima', NULL, '$2y$10$1RfwpCc8IRVtKgO.DdRvOeMbUsN2qf8jZpM2jMQSu.9xyWxgl9DVu', NULL, '2022-12-02 16:38:00', '2022-12-02 16:38:00', NULL),
(4, NULL, 'thguthgtu@mail.ru', 'dem', NULL, '$2y$10$.BEvWLcJ2wGCQG9.iRty7eI9h47mgIvamzt/1JTzjRhhofGdrUNv.', NULL, '2022-12-02 16:40:09', '2022-12-02 16:40:09', NULL),
(5, NULL, 'Spiridonowa.leo@gmail.com', 'Stasy', NULL, '$2y$10$TtzYgsVrZDfrgDXqCaBSLO0CFdUUpP32cNyefW7.mxp3n5AAYLHFC', NULL, '2022-12-03 14:03:33', '2022-12-03 14:03:33', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_group`
--
ALTER TABLE `admin_group`
  ADD KEY `admin_group_user_id_foreign` (`user_id`),
  ADD KEY `admin_group_group_id_foreign` (`group_id`);

--
-- Индексы таблицы `attachmentable`
--
ALTER TABLE `attachmentable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachmentable_attachmentable_type_attachmentable_id_index` (`attachmentable_type`,`attachmentable_id`),
  ADD KEY `attachmentable_attachment_id_foreign` (`attachment_id`);

--
-- Индексы таблицы `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD KEY `group_user_user_id_foreign` (`user_id`),
  ADD KEY `group_user_group_id_foreign` (`group_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Индексы таблицы `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Индексы таблицы `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_users_role_id_foreign` (`role_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attachmentable`
--
ALTER TABLE `attachmentable`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT для таблицы `operations`
--
ALTER TABLE `operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin_group`
--
ALTER TABLE `admin_group`
  ADD CONSTRAINT `admin_group_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_group_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `attachmentable`
--
ALTER TABLE `attachmentable`
  ADD CONSTRAINT `attachmentable_attachment_id_foreign` FOREIGN KEY (`attachment_id`) REFERENCES `attachments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `role_users`
--
ALTER TABLE `role_users`
  ADD CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
