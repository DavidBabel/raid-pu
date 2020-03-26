SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `raids_roles` to add column 'order'
--

ALTER TABLE  `{prefix}raids_roles` ADD `order` int(2) DEFAULT 0 AFTER  `title`;

-- --------------------------------------------------------

--
-- Alter table `classes` to add column 'icon'
--

ALTER TABLE  `{prefix}classes` ADD `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER  `slug`;

-- --------------------------------------------------------

--
-- Alter table `dungeons` to add column 'icon'
--

ALTER TABLE  `{prefix}dungeons` ADD `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER  `slug`;

-- --------------------------------------------------------

--
-- Alter table `games` to change column 'logo' type to varchar(255)
--

ALTER TABLE  `{prefix}games` MODIFY `logo` varchar(255), ADD `import_slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL AFTER  `logo`, ADD `import_modified` int(10) DEFAULT 0 AFTER  `import_slug`;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `{prefix}role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission_roles`
--

CREATE TABLE IF NOT EXISTS `{prefix}role_permission_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `role_permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

CREATE TABLE IF NOT EXISTS `{prefix}widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(25) COLLATE utf8_unicode_ci NOT NULL,  
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;