-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 17 dec 2014 om 16:07
-- Serverversie: 5.5.40-0ubuntu0.14.04.1
-- PHP-versie: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databank: `cloudwalkers`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_access_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth2_access_tokens` (
  `access_token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_app_authorizations`
--

CREATE TABLE IF NOT EXISTS `oauth2_app_authorizations` (
  `client_id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `u_id` int(11) NOT NULL,
  `authorization_date` datetime NOT NULL,
  PRIMARY KEY (`client_id`,`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_authorization_codes`
--

CREATE TABLE IF NOT EXISTS `oauth2_authorization_codes` (
  `authorization_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `redirect_uri` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_clients`
--

CREATE TABLE IF NOT EXISTS `oauth2_clients` (
  `client_id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `client_secret` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_uri` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `grant_types` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scope` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_layout` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `skip_authorization` tinyint(1) NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_jwt`
--

CREATE TABLE IF NOT EXISTS `oauth2_jwt` (
  `client_id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_key` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_refresh_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth2_refresh_tokens` (
  `refresh_token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth2_scopes` (
  `scope` text COLLATE utf8_unicode_ci,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oauth2_users`
--

CREATE TABLE IF NOT EXISTS `oauth2_users` (
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
