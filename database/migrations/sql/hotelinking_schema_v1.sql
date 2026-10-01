/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `action_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_points` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `action` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `points` int(5) NOT NULL,
  `nombre_es` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api`
--

DROP TABLE IF EXISTS `api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(7) NOT NULL,
  `public` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `private` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `archive_user_vouchers`
--

DROP TABLE IF EXISTS `archive_user_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive_user_vouchers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `voucher` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `automatic_reports`
--

DROP TABLE IF EXISTS `automatic_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automatic_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chain_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `emails` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `report_type` enum('accumulated','segment') COLLATE utf8_unicode_ci NOT NULL,
  `frequency` int(11) NOT NULL,
  `last_send` date NOT NULL,
  `brand_type` enum('hotel','chain') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chain_report_unique` (`chain_id`,`emails`,`report_type`,`brand_type`,`frequency`),
  UNIQUE KEY `hotel_report_unique` (`hotel_id`,`emails`,`report_type`,`brand_type`,`frequency`),
  CONSTRAINT `automatic_reports_chain_id_foreign` FOREIGN KEY (`chain_id`) REFERENCES `cadena` (`id`),
  CONSTRAINT `automatic_reports_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `benef_fideliz`
--

DROP TABLE IF EXISTS `benef_fideliz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `benef_fideliz` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `beneficio_es` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `beneficio_en` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking_engines`
--

DROP TABLE IF EXISTS `booking_engines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_engines` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `getParam` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `gtm_container_id` int(11) DEFAULT NULL,
  `gtm_workspace_id` int(11) DEFAULT NULL,
  `gtm_variable_map` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking_value`
--

DROP TABLE IF EXISTS `booking_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cupon` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `dollars` float NOT NULL,
  `amount` float NOT NULL,
  `coin` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `conversion_current_coin` float NOT NULL,
  `current_coin` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_checkout` int(11) NOT NULL,
  `id_used_promocode` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `booking_value_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` varchar(100) DEFAULT '',
  `chain_id` varchar(100) DEFAULT '',
  `user_id` varchar(100) DEFAULT '',
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(11) DEFAULT NULL,
  `transaction_code` varchar(45) NOT NULL,
  `booking_engine_code` varchar(45) NOT NULL,
  `promocode` varchar(45) DEFAULT NULL,
  `source` varchar(20) DEFAULT NULL,
  `source_action` varchar(20) DEFAULT NULL,
  `token` varchar(45) DEFAULT NULL,
  `pushtech_user_id` varchar(45) DEFAULT NULL,
  `pushtech_account_id` varchar(45) DEFAULT NULL,
  `pushtech_campaign_id` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_amount_transaction_code` (`amount`,`transaction_code`),
  KEY `bookings_hotel` (`hotel_id`),
  KEY `bookings_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_custom_content`
--

DROP TABLE IF EXISTS `brand_custom_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_custom_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `custom_content_id` int(10) unsigned NOT NULL,
  `active` int(11) NOT NULL,
  `custom_content_state_id` int(10) unsigned NOT NULL,
  `configuration` enum('default','own_vars','custom_content') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_brand_custom_content` (`hotel_id`,`custom_content_id`),
  UNIQUE KEY `uniq_chain_custom_content` (`chain_id`,`custom_content_id`),
  KEY `brand_custom_content_custom_content_id_foreign` (`custom_content_id`),
  KEY `brand_custom_content_custom_content_state_id_foreign` (`custom_content_state_id`),
  CONSTRAINT `brand_custom_content_chain_id_foreign` FOREIGN KEY (`chain_id`) REFERENCES `cadena` (`id`),
  CONSTRAINT `brand_custom_content_custom_content_id_foreign` FOREIGN KEY (`custom_content_id`) REFERENCES `custom_content` (`id`),
  CONSTRAINT `brand_custom_content_custom_content_state_id_foreign` FOREIGN KEY (`custom_content_state_id`) REFERENCES `custom_content_state` (`id`),
  CONSTRAINT `brand_custom_content_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_custom_module_content`
--

DROP TABLE IF EXISTS `brand_custom_module_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_custom_module_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_custom_content_id` int(10) unsigned DEFAULT NULL,
  `custom_module_id` int(10) unsigned NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_brand_custom_module_content` (`brand_custom_content_id`,`custom_module_id`,`language_id`),
  KEY `brand_custom_module_content_custom_module_id_foreign` (`custom_module_id`),
  CONSTRAINT `brand_custom_module_content_brand_custom_content_id_foreign` FOREIGN KEY (`brand_custom_content_id`) REFERENCES `brand_custom_content` (`id`),
  CONSTRAINT `brand_custom_module_content_custom_module_id_foreign` FOREIGN KEY (`custom_module_id`) REFERENCES `custom_module` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_custom_texts`
--

DROP TABLE IF EXISTS `brand_custom_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_custom_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `value` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `custom_text_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_custom_texts_custom_text_id_hotel_id_language_id_unique` (`custom_text_id`,`hotel_id`,`language_id`),
  UNIQUE KEY `brand_custom_texts_custom_text_id_chain_id_language_id_unique` (`custom_text_id`,`chain_id`,`language_id`),
  CONSTRAINT `brand_custom_texts_custom_text_id_foreign` FOREIGN KEY (`custom_text_id`) REFERENCES `custom_texts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_custom_vars`
--

DROP TABLE IF EXISTS `brand_custom_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_custom_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `value` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `custom_vars_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_custom_vars_custom_vars_id_hotel_id_unique` (`custom_vars_id`,`hotel_id`),
  UNIQUE KEY `brand_custom_vars_custom_vars_id_chain_id_unique` (`custom_vars_id`,`chain_id`),
  CONSTRAINT `brand_custom_vars_custom_vars_id_foreign` FOREIGN KEY (`custom_vars_id`) REFERENCES `custom_vars` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_eprivacy`
--

DROP TABLE IF EXISTS `brand_eprivacy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_eprivacy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_address` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_nif` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `company_email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `restricted_portal` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_eprivacy_hotel_id_unique` (`hotel_id`),
  UNIQUE KEY `brand_eprivacy_chain_id_unique` (`chain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_protocols`
--

DROP TABLE IF EXISTS `brand_protocols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_protocols` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `service` enum('emails','portal') COLLATE utf8_unicode_ci NOT NULL,
  `treatment` enum('formal','informal') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_protocols_brand_id_service_unique` (`brand_id`,`service`),
  CONSTRAINT `brand_protocols_fk1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_types`
--

DROP TABLE IF EXISTS `brand_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_types_type_unique` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `brand_type_id` int(10) unsigned NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_uuid_unique` (`uuid`),
  UNIQUE KEY `brands_hotel_id_parent_id_brand_type_id_unique` (`hotel_id`,`parent_id`,`brand_type_id`),
  UNIQUE KEY `brands_chain_id_brand_type_id_unique` (`chain_id`,`brand_type_id`),
  KEY `brand_brand_fk1` (`parent_id`),
  KEY `brand_brand_type_fk1` (`brand_type_id`),
  CONSTRAINT `brand_brand_fk1` FOREIGN KEY (`parent_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `brand_brand_type_fk1` FOREIGN KEY (`brand_type_id`) REFERENCES `brand_types` (`id`),
  CONSTRAINT `brand_chain_fk1` FOREIGN KEY (`chain_id`) REFERENCES `cadena` (`id`) ON DELETE CASCADE,
  CONSTRAINT `brand_hotel_fk1` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cadena`
--

DROP TABLE IF EXISTS `cadena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadena` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `logo` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `email_envio` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email_contacto` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `telefono_contacto` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `websiteReserva` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `booking_engine` int(2) DEFAULT NULL,
  `websiteReserva_es` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `websiteReserva_de` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `websiteReserva_fr` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `stay_time` tinyint(4) NOT NULL DEFAULT '7',
  `loyalty_min_visits` tinyint(4) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Table structure for table `cadena_benef_fideliz`
--

DROP TABLE IF EXISTS `cadena_benef_fideliz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadena_benef_fideliz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadena` int(11) NOT NULL,
  `id_benef` int(4) NOT NULL,
  `level` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cadena_fideliz`
--

DROP TABLE IF EXISTS `cadena_fideliz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadena_fideliz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadena` int(11) NOT NULL,
  `n_fideliz` int(11) NOT NULL DEFAULT '0',
  `tipo_fidelizacion` int(2) NOT NULL,
  `fidelizacion_0` int(10) NOT NULL,
  `fidelizacion_1` int(10) NOT NULL,
  `fidelizacion_2` int(10) NOT NULL,
  `fidelizacion_3` int(10) NOT NULL,
  `activado` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cadena_guid`
--

DROP TABLE IF EXISTS `cadena_guid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadena_guid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadena` int(11) NOT NULL,
  `guid` varchar(36) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cadena_hotel`
--

DROP TABLE IF EXISTS `cadena_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadena_hotel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadena` int(5) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel_cadena_hotel_unq` (`id_hotel`),
  KEY `id_hotel` (`id_hotel`),
  KEY `id_cadena` (`id_cadena`),
  KEY `id_hotel_2` (`id_hotel`),
  CONSTRAINT `cadena_hotel_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Table structure for table `cadena_oferta_referral`
--

DROP TABLE IF EXISTS `cadena_oferta_referral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadena_oferta_referral` (
  `id_cadena` int(7) NOT NULL,
  `id_oferta` int(10) NOT NULL,
  UNIQUE KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `captive_portal_options`
--

DROP TABLE IF EXISTS `captive_portal_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `captive_portal_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) NOT NULL,
  `id_chain` int(11) NOT NULL,
  `email_form` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categoria_oferta`
--

DROP TABLE IF EXISTS `categoria_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_oferta` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_tipo_oferta` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_categoria_oferta` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `categoria_es` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `categoria_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_categoria_oferta` (`id_categoria_oferta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chain_privacy_policy`
--

DROP TABLE IF EXISTS `chain_privacy_policy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chain_privacy_policy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chain_id` int(11) NOT NULL,
  `company_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_nif` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy_conditions` text COLLATE utf8_unicode_ci,
  `option` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chain_privacy_policy_chain_id_unique` (`chain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciudad`
--

DROP TABLE IF EXISTS `ciudad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciudad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_es` char(35) NOT NULL,
  `codigo_pais` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_pais` (`codigo_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `connection_history`
--

DROP TABLE IF EXISTS `connection_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `connection_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `first_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mac_address` char(17) NOT NULL DEFAULT 'NA',
  `id_room` char(30) NOT NULL,
  `times_login` int(2) NOT NULL DEFAULT '1',
  `headers` text,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(50) DEFAULT NULL,
  `operating_system` varchar(50) DEFAULT NULL,
  `operating_system_version` varchar(50) DEFAULT NULL,
  `device_family` varchar(50) DEFAULT NULL,
  `device_brand` varchar(50) DEFAULT NULL,
  `device_model` varchar(50) DEFAULT NULL,
  `browser_lang` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel_2` (`id_hotel`,`id_user`,`first_login`,`mac_address`,`id_room`),
  KEY `mac_address` (`mac_address`),
  KEY `id_user` (`id_user`),
  KEY `id_hotel` (`id_hotel`),
  KEY `id_room` (`id_room`),
  KEY `last_login_idx` (`last_login`),
  KEY `id_user_last_login_idx` (`id_user`,`last_login`),
  KEY `conn_hs_hl_index` (`id_hotel`,`first_login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `controladores`
--

DROP TABLE IF EXISTS `controladores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controladores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controlador` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `LY` tinyint(1) NOT NULL DEFAULT '1',
  `RF` tinyint(1) NOT NULL DEFAULT '1',
  `MK` tinyint(1) NOT NULL DEFAULT '1',
  `review` tinyint(1) NOT NULL DEFAULT '0',
  `satisfaction` tinyint(1) NOT NULL DEFAULT '0',
  `wifi_offers` tinyint(1) NOT NULL,
  `birthday_emails` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pushtech` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `require_room_num` tinyint(1) NOT NULL DEFAULT '0',
  `user_enrichment` tinyint(1) NOT NULL DEFAULT '0',
  `loyalty` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `controlador` (`controlador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country_langs`
--

DROP TABLE IF EXISTS `country_langs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_langs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `crons`
--

DROP TABLE IF EXISTS `crons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crons` (
  `cron_id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_time` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cron_name` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `cron_description` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_content`
--

DROP TABLE IF EXISTS `custom_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_content_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_content_modules`
--

DROP TABLE IF EXISTS `custom_content_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_content_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custom_content_state_id` int(10) unsigned NOT NULL,
  `custom_module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_custom_content_modules` (`custom_content_state_id`,`custom_module_id`),
  KEY `custom_content_modules_custom_module_id_foreign` (`custom_module_id`),
  CONSTRAINT `custom_content_modules_custom_content_state_id_foreign` FOREIGN KEY (`custom_content_state_id`) REFERENCES `custom_content_state` (`id`),
  CONSTRAINT `custom_content_modules_custom_module_id_foreign` FOREIGN KEY (`custom_module_id`) REFERENCES `custom_module` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_content_state`
--

DROP TABLE IF EXISTS `custom_content_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_content_state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custom_content_id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_custom_content_state` (`name`,`custom_content_id`),
  KEY `custom_content_state_custom_content_id_foreign` (`custom_content_id`),
  CONSTRAINT `custom_content_state_custom_content_id_foreign` FOREIGN KEY (`custom_content_id`) REFERENCES `custom_content` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_module`
--

DROP TABLE IF EXISTS `custom_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_module_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_module_texts`
--

DROP TABLE IF EXISTS `custom_module_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_module_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custom_module_id` int(10) unsigned NOT NULL,
  `custom_text_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_module_texts_custom_module_id_foreign` (`custom_module_id`),
  KEY `custom_module_texts_custom_text_id_foreign` (`custom_text_id`),
  CONSTRAINT `custom_module_texts_custom_module_id_foreign` FOREIGN KEY (`custom_module_id`) REFERENCES `custom_module` (`id`),
  CONSTRAINT `custom_module_texts_custom_text_id_foreign` FOREIGN KEY (`custom_text_id`) REFERENCES `custom_texts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_module_vars`
--

DROP TABLE IF EXISTS `custom_module_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_module_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custom_module_id` int(10) unsigned NOT NULL,
  `custom_vars_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_module_vars_custom_module_id_foreign` (`custom_module_id`),
  KEY `custom_module_vars_custom_vars_id_foreign` (`custom_vars_id`),
  CONSTRAINT `custom_module_vars_custom_module_id_foreign` FOREIGN KEY (`custom_module_id`) REFERENCES `custom_module` (`id`),
  CONSTRAINT `custom_module_vars_custom_vars_id_foreign` FOREIGN KEY (`custom_vars_id`) REFERENCES `custom_vars` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_texts`
--

DROP TABLE IF EXISTS `custom_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_texts_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_vars`
--

DROP TABLE IF EXISTS `custom_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_vars_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customized_satisfaction_answers`
--

DROP TABLE IF EXISTS `customized_satisfaction_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customized_satisfaction_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_satisfaction_id` int(11) NOT NULL,
  `survey_question_id` int(10) unsigned NOT NULL,
  `answer` double DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `brand_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customized_satisfaction_answers_survey_question_id_foreign` (`survey_question_id`),
  KEY `customized_satisfaction_answers_user_satisfaction_id_foreign` (`user_satisfaction_id`),
  KEY `customized_satisfaction_answers_brand_id_foreign` (`brand_id`),
  CONSTRAINT `customized_satisfaction_answers_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `customized_satisfaction_answers_survey_question_id_foreign` FOREIGN KEY (`survey_question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customized_satisfaction_answers_user_satisfaction_id_foreign` FOREIGN KEY (`user_satisfaction_id`) REFERENCES `user_satisfaction` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dolar_puntos`
--

DROP TABLE IF EXISTS `dolar_puntos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dolar_puntos` (
  `equivalencia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equiv_estrellas`
--

DROP TABLE IF EXISTS `equiv_estrellas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equiv_estrellas` (
  `estrellas` int(1) NOT NULL,
  `equiv` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `external_apis`
--

DROP TABLE IF EXISTS `external_apis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `external_apis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `api_name` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `api_header` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_name` (`api_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `external_apis_params`
--

DROP TABLE IF EXISTS `external_apis_params`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `external_apis_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_api` int(11) NOT NULL,
  `action` varchar(20) NOT NULL DEFAULT '',
  `endpoint` varchar(20) NOT NULL DEFAULT '',
  `params` text,
  `method` varchar(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_api` (`id_api`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `external_hotel_apis`
--

DROP TABLE IF EXISTS `external_hotel_apis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `external_hotel_apis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) DEFAULT NULL,
  `id_api` int(3) DEFAULT NULL,
  `key` varchar(128) DEFAULT NULL,
  `secret` varchar(128) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel` (`id_hotel`,`id_api`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `extras_hotel`
--

DROP TABLE IF EXISTS `extras_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extras_hotel` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_extra` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `extra_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `extra_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gdpr_history`
--

DROP TABLE IF EXISTS `gdpr_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gdpr_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event` enum('client','not_client','conditions','notifications') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gdpr_history_hotel_id` (`hotel_id`),
  KEY `gdpr_history_user_id` (`user_id`),
  CONSTRAINT `gdpr_history_hotel_id` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gdpr_history_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_benef_fideliz`
--

DROP TABLE IF EXISTS `hotel_benef_fideliz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_benef_fideliz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) NOT NULL,
  `id_benef` int(4) NOT NULL,
  `level` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_benef_fideliz_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_benef_fideliz_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_benef_fideliz_ibfk_3` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_charts_data`
--

DROP TABLE IF EXISTS `hotel_charts_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_charts_data` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(7) NOT NULL,
  `nightsGuest` float NOT NULL,
  `pointsGuest` float NOT NULL,
  `spentGuest` float NOT NULL,
  `socialMediaReach` float NOT NULL,
  `todayNewGuests` float NOT NULL,
  `overallReputation` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_charts_data_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_charts_position`
--

DROP TABLE IF EXISTS `hotel_charts_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_charts_position` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `hotel-home-0` int(2) NOT NULL DEFAULT '0',
  `hotel-home-1` int(2) NOT NULL DEFAULT '1',
  `hotel-home-2` int(2) NOT NULL DEFAULT '2',
  `hotel-home-3` int(2) NOT NULL DEFAULT '3',
  `hotel-home-4` int(2) NOT NULL DEFAULT '4',
  `hotel-home-5` int(2) NOT NULL DEFAULT '5',
  `hotel-home-6` int(2) NOT NULL DEFAULT '6',
  `hotel-home-7` int(2) NOT NULL DEFAULT '7',
  `hotel-home-8` int(2) NOT NULL DEFAULT '8',
  `hotel-home-9` int(2) NOT NULL DEFAULT '9',
  `hotel-home-10` int(2) NOT NULL DEFAULT '10',
  `hotel-home-11` int(2) NOT NULL DEFAULT '11',
  `hotel-home-12` int(2) NOT NULL DEFAULT '12',
  `hotel-home-13` int(2) NOT NULL DEFAULT '13',
  `hotel-home-14` int(2) NOT NULL DEFAULT '14',
  `campaigns-dashboard-0` int(2) NOT NULL DEFAULT '0',
  `campaigns-dashboard-1` int(2) NOT NULL DEFAULT '1',
  `campaigns-dashboard-2` int(2) NOT NULL DEFAULT '2',
  `campaigns-dashboard-3` int(2) NOT NULL DEFAULT '3',
  `campaigns-dashboard-4` int(2) DEFAULT '4',
  `campaigns-dashboard-5` int(2) NOT NULL DEFAULT '5',
  `campaigns-dashboard-6` int(2) DEFAULT '6',
  `campaigns-dashboard-7` int(2) NOT NULL DEFAULT '7',
  `campaigns-dashboard-8` int(2) NOT NULL DEFAULT '8',
  `reputation-dashboard-0` int(2) NOT NULL DEFAULT '0',
  `reputation-dashboard-1` int(2) NOT NULL DEFAULT '1',
  `reputation-dashboard-2` int(2) NOT NULL DEFAULT '2',
  `reputation-dashboard-3` int(2) NOT NULL DEFAULT '3',
  `reputation-dashboard-4` int(2) NOT NULL DEFAULT '4',
  `reputation-dashboard-5` int(2) NOT NULL DEFAULT '5',
  `referrals-dashboard-0` int(2) NOT NULL DEFAULT '0',
  `referrals-dashboard-1` int(2) NOT NULL DEFAULT '1',
  `referrals-dashboard-2` int(2) NOT NULL DEFAULT '2',
  `referrals-dashboard-3` int(2) NOT NULL DEFAULT '3',
  `referrals-dashboard-4` int(2) NOT NULL DEFAULT '4',
  `referrals-dashboard-5` int(2) NOT NULL DEFAULT '5',
  `leads-dashboard-0` int(2) NOT NULL DEFAULT '0',
  `leads-dashboard-1` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_charts_position_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_charts_position_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_country_lang_url`
--

DROP TABLE IF EXISTS `hotel_country_lang_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_country_lang_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `country_lang_id` int(11) NOT NULL,
  `url` varchar(200) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_url` (`hotel_id`,`country_lang_id`),
  UNIQUE KEY `unique_url2` (`chain_id`,`country_lang_id`),
  KEY `hotel_has_url_idx` (`hotel_id`),
  KEY `url_has_country_langs_idx` (`country_lang_id`),
  KEY `chain_has_url_idx` (`chain_id`),
  CONSTRAINT `chain_has_url` FOREIGN KEY (`chain_id`) REFERENCES `cadena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `hotel_has_url` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `url_has_country_langs` FOREIGN KEY (`country_lang_id`) REFERENCES `country_langs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_extras`
--

DROP TABLE IF EXISTS `hotel_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_extras` (
  `id_hotel` int(5) NOT NULL,
  `id_extra` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_extras_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_fideliz`
--

DROP TABLE IF EXISTS `hotel_fideliz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_fideliz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `n_fideliz` int(11) NOT NULL DEFAULT '0',
  `tipo_fidelizacion` int(2) NOT NULL,
  `fidelizacion_0` int(10) NOT NULL,
  `fidelizacion_1` int(10) NOT NULL,
  `fidelizacion_2` int(10) NOT NULL,
  `fidelizacion_3` int(10) NOT NULL,
  `activado` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_fideliz_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_guid`
--

DROP TABLE IF EXISTS `hotel_guid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_guid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) NOT NULL,
  `guid` varchar(36) COLLATE utf8_spanish_ci NOT NULL,
  `affilired_hotel` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `affilired_id` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotel_hotel_guid_unique` (`id_hotel`),
  KEY `guid_idx` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_list`
--

DROP TABLE IF EXISTS `hotel_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `lista_acabada` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_list_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_list_users`
--

DROP TABLE IF EXISTS `hotel_list_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_list_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_list` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `puntos` int(3) NOT NULL,
  `idioma` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `total_spent` int(11) NOT NULL,
  `total_noches` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email_valido` tinyint(4) NOT NULL,
  `enviado_ly` int(2) NOT NULL DEFAULT '0',
  `enviado_rf` int(2) NOT NULL DEFAULT '0',
  `marcado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_list` (`id_list`),
  CONSTRAINT `hotel_list_users_ibfk_1` FOREIGN KEY (`id_list`) REFERENCES `hotel_list` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_login_attempts`
--

DROP TABLE IF EXISTS `hotel_login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta`
--

DROP TABLE IF EXISTS `hotel_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `id_cadena` int(5) NOT NULL,
  `id_tipo_oferta` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `id_categoria` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_subcategoria` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `adq_ret` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `inicio` date NOT NULL,
  `fin` date NOT NULL,
  `cupo` int(5) NOT NULL,
  `adquiridas` int(6) NOT NULL DEFAULT '0',
  `canjeadas` int(4) NOT NULL DEFAULT '0',
  `descuento` int(3) NOT NULL DEFAULT '0',
  `coste` float NOT NULL DEFAULT '0',
  `moneda` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `requerimientos` int(5) NOT NULL DEFAULT '0',
  `puntos` int(10) NOT NULL DEFAULT '0',
  `img` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `estado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `fecha_publicada` datetime NOT NULL,
  `booking_engine_code` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_birthday`
--

DROP TABLE IF EXISTS `hotel_oferta_birthday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_birthday` (
  `hotel_id` int(6) unsigned NOT NULL,
  `oferta_id` int(6) unsigned NOT NULL,
  `sendWarningFromNonUsers` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_id`),
  KEY `hotel_id` (`hotel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_lang`
--

DROP TABLE IF EXISTS `hotel_oferta_lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oferta` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` longtext COLLATE utf8_spanish_ci NOT NULL,
  `condiciones` longtext COLLATE utf8_spanish_ci NOT NULL,
  `lang` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `lang_ok` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_oferta&lang` (`id_oferta`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_poststay`
--

DROP TABLE IF EXISTS `hotel_oferta_poststay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_poststay` (
  `id_hotel` int(7) NOT NULL,
  `id_oferta` int(10) NOT NULL,
  UNIQUE KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_prestay`
--

DROP TABLE IF EXISTS `hotel_oferta_prestay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_prestay` (
  `id_hotel` int(7) NOT NULL,
  `id_oferta` int(10) NOT NULL,
  UNIQUE KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_referral`
--

DROP TABLE IF EXISTS `hotel_oferta_referral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_referral` (
  `id_hotel` int(7) NOT NULL,
  `id_oferta` int(10) NOT NULL,
  UNIQUE KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_stay`
--

DROP TABLE IF EXISTS `hotel_oferta_stay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_stay` (
  `id_hotel` int(7) NOT NULL,
  `url` varchar(1000) COLLATE utf8_spanish_ci NOT NULL,
  `form_url` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `secret` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `unifi_time` int(9) DEFAULT NULL,
  `unifi_site_id` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `guest_enabled` tinyint(1) NOT NULL,
  UNIQUE KEY `id_hotel` (`id_hotel`),
  UNIQUE KEY `unifi_site_id` (`unifi_site_id`),
  UNIQUE KEY `UNQ_unifi_site_id` (`unifi_site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_oferta_wifi`
--

DROP TABLE IF EXISTS `hotel_oferta_wifi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_oferta_wifi` (
  `id_hotel` int(11) NOT NULL,
  `id_oferta` int(11) NOT NULL,
  `condicion` enum('facebook_login','siempre','facebook_share') NOT NULL DEFAULT 'facebook_share',
  `tipo_oferta` enum('inmediate','web') NOT NULL DEFAULT 'web',
  `dias` int(5) NOT NULL DEFAULT '30',
  PRIMARY KEY (`id_hotel`),
  KEY `id_oferta` (`id_oferta`),
  CONSTRAINT `id_oferta` FOREIGN KEY (`id_oferta`) REFERENCES `hotel_oferta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_page_ips`
--

DROP TABLE IF EXISTS `hotel_page_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_page_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `fecha` datetime NOT NULL,
  `http_referer` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_page_ips_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_products`
--

DROP TABLE IF EXISTS `hotel_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotels_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotel_products_hotels_id_products_id` (`hotels_id`,`products_id`),
  KEY `hotel_products_idx_1` (`hotels_id`,`products_id`),
  KEY `hotel_products_products` (`products_id`),
  CONSTRAINT `hotel_products_hotels` FOREIGN KEY (`hotels_id`) REFERENCES `hoteles` (`id`),
  CONSTRAINT `hotel_products_products` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_review`
--

DROP TABLE IF EXISTS `hotel_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_review` (
  `id_hotel` int(5) NOT NULL,
  `diasEnvio` int(3) NOT NULL DEFAULT '15',
  `ignoreRating` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_hotel_2` (`id_hotel`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_review_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_rooms`
--

DROP TABLE IF EXISTS `hotel_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `rooms` text NOT NULL,
  `sendOfferToNonUsers` tinyint(4) NOT NULL,
  `access_codes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel_UNIQUE` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_satisfaction`
--

DROP TABLE IF EXISTS `hotel_satisfaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_satisfaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) NOT NULL,
  `diasEnvio` int(3) NOT NULL DEFAULT '0',
  `puntMin` float NOT NULL DEFAULT '0',
  `warning_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `ignoreRating` tinyint(1) NOT NULL,
  `sendThanksMail` tinyint(1) NOT NULL DEFAULT '1',
  `sendToNonCustomers` tinyint(4) NOT NULL DEFAULT '0',
  `send_hour` int(11) DEFAULT '0',
  `total_followup_email` int(11) NOT NULL DEFAULT '0',
  `customized_active` tinyint(1) NOT NULL,
  `customized_type` enum('Joined with satisfaction','In a later email') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customized_send_days` int(10) unsigned NOT NULL,
  `customized_send_hours` int(10) unsigned NOT NULL,
  `customized_chain_activated` tinyint(1) NOT NULL,
  `childBrandQuestionsID` int(11) DEFAULT '0',
  `filter_warning` tinyint(1) DEFAULT '1',
  `force_comment` tinyint(1) NOT NULL DEFAULT '1',
  `customized_comment` tinyint(1) NOT NULL DEFAULT '1',
  `customized_warning_emails` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_satisfaction_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_servicios`
--

DROP TABLE IF EXISTS `hotel_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_servicios` (
  `id_servicio` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `id_hotel` int(5) NOT NULL,
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_servicios_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_share_text`
--

DROP TABLE IF EXISTS `hotel_share_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_share_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `lang` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `pre` text COLLATE utf8_spanish_ci NOT NULL,
  `stay` text COLLATE utf8_spanish_ci NOT NULL,
  `post` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_staff`
--

DROP TABLE IF EXISTS `hotel_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_staff` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `id_role` int(3) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_creado` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_staff_hotels`
--

DROP TABLE IF EXISTS `hotel_staff_hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_staff_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `hotel_staff_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `hotel_staff_id` (`hotel_staff_id`),
  CONSTRAINT `hotel_staff_hotels_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_staff_hotels_ibfk_2` FOREIGN KEY (`hotel_staff_id`) REFERENCES `hotel_staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_staff_inactivo`
--

DROP TABLE IF EXISTS `hotel_staff_inactivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_staff_inactivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_staff` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `id_role` int(3) NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_creado` datetime NOT NULL,
  `fecha_inactivo` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_staff_permisos`
--

DROP TABLE IF EXISTS `hotel_staff_permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_staff_permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_staff_role` int(11) NOT NULL,
  `id_controller` int(11) NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_staff_role` (`id_staff_role`),
  KEY `id_controller` (`id_controller`),
  CONSTRAINT `hotel_staff_permisos_ibfk_1` FOREIGN KEY (`id_staff_role`) REFERENCES `hotel_staff_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_staff_permisos_ibfk_2` FOREIGN KEY (`id_controller`) REFERENCES `controladores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_staff_roles`
--

DROP TABLE IF EXISTS `hotel_staff_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_staff_roles` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `role_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `role_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_statistics`
--

DROP TABLE IF EXISTS `hotel_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_statistics` (
  `id_hotel` int(7) NOT NULL,
  `pre_iframe_opens` int(7) NOT NULL DEFAULT '0',
  `pre_login` int(9) NOT NULL DEFAULT '0',
  `pre_second_click` int(9) NOT NULL DEFAULT '0',
  `pre_share` int(9) NOT NULL DEFAULT '0',
  `pre_declined_user_friends` int(9) NOT NULL DEFAULT '0',
  `pre_declined_public_profile` int(9) NOT NULL DEFAULT '0',
  `pre_declined_email` int(9) NOT NULL DEFAULT '0',
  `pre_declined_publish_actions` int(9) NOT NULL DEFAULT '0',
  `pre_canceled` int(9) NOT NULL DEFAULT '0',
  `pre_reintents` int(9) NOT NULL DEFAULT '0',
  `post_stay_reintents` int(9) NOT NULL DEFAULT '0',
  `stay_first_click` int(9) NOT NULL DEFAULT '0',
  `stay_second_click` int(9) NOT NULL DEFAULT '0',
  `stay_share` int(9) NOT NULL DEFAULT '0',
  `stay_declined_user_friends` int(9) NOT NULL DEFAULT '0',
  `stay_declined_public_profile` int(9) NOT NULL DEFAULT '0',
  `stay_declined_email` int(9) NOT NULL DEFAULT '0',
  `stay_declined_publish_actions` int(9) NOT NULL DEFAULT '0',
  `stay_canceled` int(9) NOT NULL DEFAULT '0',
  `stay_reintents` int(9) NOT NULL DEFAULT '0',
  `post_stay_first_click` int(9) NOT NULL DEFAULT '0',
  `post_stay_second_click` int(9) NOT NULL DEFAULT '0',
  `post_stay_share` int(9) NOT NULL DEFAULT '0',
  `post_stay_declined_user_friends` int(9) NOT NULL DEFAULT '0',
  `post_stay_declined_public_profile` int(9) NOT NULL DEFAULT '0',
  `post_stay_declined_email` int(9) NOT NULL DEFAULT '0',
  `post_stay_declined_publish_actions` int(9) NOT NULL DEFAULT '0',
  `post_stay_canceled` int(9) NOT NULL DEFAULT '0',
  `other_first_click` int(9) NOT NULL DEFAULT '0',
  `other_second_click` int(9) NOT NULL DEFAULT '0',
  `other_share` int(9) NOT NULL DEFAULT '0',
  `other_declined_user_friends` int(9) NOT NULL DEFAULT '0',
  `other_declined_public_profile` int(9) NOT NULL DEFAULT '0',
  `other_declined_email` int(9) NOT NULL DEFAULT '0',
  `other_declined_publish_actions` int(9) NOT NULL DEFAULT '0',
  `other_canceled` int(9) NOT NULL DEFAULT '0',
  `other_reintents` int(9) NOT NULL DEFAULT '0',
  `landing_first_click` int(9) NOT NULL DEFAULT '0',
  `landing_second_click` int(9) NOT NULL DEFAULT '0',
  `landing_share` int(9) NOT NULL DEFAULT '0',
  `landing_declined_user_friends` int(9) NOT NULL DEFAULT '0',
  `landing_declined_public_profile` int(9) NOT NULL DEFAULT '0',
  `landing_declined_email` int(9) NOT NULL DEFAULT '0',
  `landing_declined_publish_actions` int(9) NOT NULL DEFAULT '0',
  `landing_canceled` int(9) NOT NULL DEFAULT '0',
  `landing_reintents` int(9) NOT NULL DEFAULT '0',
  `landing_iframe_opens` int(9) NOT NULL DEFAULT '0',
  `landing_fb_clicks` int(9) NOT NULL DEFAULT '0',
  `landing_mail_clicks` int(9) NOT NULL DEFAULT '0',
  `landing_fb_success` int(9) NOT NULL DEFAULT '0',
  `landing_mail_success` int(9) NOT NULL DEFAULT '0',
  `landing_error` int(9) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_tagline_lang`
--

DROP TABLE IF EXISTS `hotel_tagline_lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_tagline_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) NOT NULL,
  `tagline` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `lang` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_tipos_hab`
--

DROP TABLE IF EXISTS `hotel_tipos_hab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_tipos_hab` (
  `id_hotel` int(5) NOT NULL,
  `id_tipo_hab` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_tipos_hab_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_total_users`
--

DROP TABLE IF EXISTS `hotel_total_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_total_users` (
  `id_hotel` int(5) NOT NULL,
  `usuarios` int(5) NOT NULL DEFAULT '0',
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_total_users_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_user_rate`
--

DROP TABLE IF EXISTS `hotel_user_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_user_rate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `id_checkout` int(7) NOT NULL,
  `rate` float NOT NULL,
  `coment` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hotel_user_rate_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotel_wifi_integrations`
--

DROP TABLE IF EXISTS `hotel_wifi_integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotel_wifi_integrations` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(5) NOT NULL,
  `wifi_id` int(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotel_id_2` (`hotel_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `wifi_id` (`wifi_id`),
  CONSTRAINT `hotel_wifi_integrations_ibfk_1` FOREIGN KEY (`wifi_id`) REFERENCES `wifi_providers` (`id`),
  CONSTRAINT `hotel_wifi_integrations_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hoteles`
--

DROP TABLE IF EXISTS `hoteles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hoteles` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(70) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `hotelName` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `territory` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `street` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `city` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `place_name` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `place_country` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `place_adm_area` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `place_id` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  `cp` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `emailReserva` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `websiteReserva` varchar(255) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `telefonoReservas` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `cargo` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `estrellas` int(1) NOT NULL DEFAULT '0',
  `min_rango` int(5) NOT NULL DEFAULT '0',
  `max_rango` int(5) NOT NULL DEFAULT '0',
  `n_habitaciones` int(4) NOT NULL,
  `tipo_hotel` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `decoracion` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `condiciones` text COLLATE utf8_spanish_ci NOT NULL,
  `notif` tinyint(1) NOT NULL,
  `review` tinyint(1) NOT NULL DEFAULT '0',
  `diasEnvioReview` int(11) NOT NULL DEFAULT '0',
  `alert_friends` int(9) NOT NULL,
  `verificado` int(1) NOT NULL DEFAULT '0',
  `modal` tinyint(1) NOT NULL DEFAULT '0',
  `alert` tinyint(1) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '5',
  `fotoBg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `jan` tinyint(1) NOT NULL DEFAULT '10',
  `feb` tinyint(1) NOT NULL DEFAULT '10',
  `mar` tinyint(1) NOT NULL DEFAULT '10',
  `apr` tinyint(1) NOT NULL DEFAULT '10',
  `may` tinyint(1) NOT NULL DEFAULT '10',
  `jun` tinyint(1) NOT NULL DEFAULT '10',
  `jul` tinyint(1) NOT NULL DEFAULT '10',
  `ago` tinyint(1) NOT NULL DEFAULT '10',
  `sep` tinyint(1) NOT NULL DEFAULT '10',
  `oct` tinyint(1) NOT NULL DEFAULT '10',
  `nov` tinyint(1) NOT NULL DEFAULT '10',
  `dece` tinyint(1) NOT NULL DEFAULT '10',
  `limitGuest` int(6) NOT NULL,
  `quota` int(8) NOT NULL DEFAULT '500',
  `moneda` varchar(4) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'USD',
  `twitterAccount` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `booking_engine` int(2) DEFAULT NULL,
  `lang` varchar(5) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'en',
  `iframe` varchar(12) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'disabled',
  `iframe_style` int(1) NOT NULL DEFAULT '5',
  `landing_iframe` varchar(12) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'disabled',
  `landing_iframe_style` int(1) NOT NULL,
  `facebook_page` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `websiteReserva_es` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `websiteReserva_de` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `websiteReserva_fr` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sending_email` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `stay_time` tinyint(4) NOT NULL DEFAULT '7',
  `loyalty_min_visits` tinyint(4) NOT NULL DEFAULT '2',
  `birthdayAlertEmails` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `loyalty_emails` varchar(191) COLLATE utf8_spanish_ci NOT NULL,
  `loyalty_alerts` tinyint(1) NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sender_email` varchar(191) COLLATE utf8_spanish_ci DEFAULT NULL,
  `bypass_active` int(11) NOT NULL DEFAULT '1',
  `chain_bypass` tinyint(4) NOT NULL,
  `time_zone_id` int(11) DEFAULT '50',
  `activated` tinyint(4) NOT NULL DEFAULT '1',
  `birthday_alarm_days_range` int(11) DEFAULT '3',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `city` (`city`),
  FULLTEXT KEY `fulltext_suggest` (`hotelName`,`place_name`,`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
--
-- Table structure for table `hoteles_img`
--

DROP TABLE IF EXISTS `hoteles_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hoteles_img` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `img` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `pri` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `hoteles_img_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hotels_privacy_policy`
--

DROP TABLE IF EXISTS `hotels_privacy_policy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotels_privacy_policy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_nif` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy_conditions` text COLLATE utf8_unicode_ci,
  `option` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `use_as_chain` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotels_privacy_policy_hotel_id_unique` (`hotel_id`),
  KEY `hotels_privacy_policy_hotel_id_index` (`hotel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invitaciones_hotel`
--

DROP TABLE IF EXISTS `invitaciones_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invitaciones_hotel` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `token` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `quota` int(11) NOT NULL,
  `hotelName` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invitaciones_hotel_pendientes`
--

DROP TABLE IF EXISTS `invitaciones_hotel_pendientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invitaciones_hotel_pendientes` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `website` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `categoria_hotel` int(1) NOT NULL,
  `invite_completed` int(1) NOT NULL DEFAULT '0',
  `ref_code` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `referrals` int(4) NOT NULL,
  `unsub` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invitaciones_users`
--

DROP TABLE IF EXISTS `invitaciones_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invitaciones_users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `created` datetime NOT NULL,
  `token` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `invitador` int(7) NOT NULL,
  `tipo_invitador` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_encuesta` int(10) NOT NULL,
  `puntos` int(7) NOT NULL,
  `puntos_sumados` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invitaciones_users_pendientes`
--

DROP TABLE IF EXISTS `invitaciones_users_pendientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invitaciones_users_pendientes` (
  `twitter_user` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ips_webservice`
--

DROP TABLE IF EXISTS `ips_webservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ips_webservice` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `ip` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `ws` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `access` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lang`
--

DROP TABLE IF EXISTS `lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(6) NOT NULL,
  `img` varchar(50) NOT NULL,
  `country` varchar(20) NOT NULL,
  `system` int(1) NOT NULL DEFAULT '0',
  `content` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_lang_index` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lang_hotel`
--

DROP TABLE IF EXISTS `lang_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang_hotel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_lang` int(3) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_hotel_unique` (`id_lang`,`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `last_checkout_ip`
--

DROP TABLE IF EXISTS `last_checkout_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `last_checkout_ip` (
  `ip` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `date` datetime NOT NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loyalty_configs`
--

DROP TABLE IF EXISTS `loyalty_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loyalty_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `summary_active` tinyint(1) NOT NULL DEFAULT '0',
  `summary_send_days` int(10) unsigned NOT NULL DEFAULT '0',
  `summary_send_hours` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `loyalty_configs_brand_id_foreign` (`brand_id`),
  CONSTRAINT `loyalty_configs_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `marketing_multipliers`
--

DROP TABLE IF EXISTS `marketing_multipliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marketing_multipliers` (
  `impressions_percents` int(3) unsigned NOT NULL,
  `single_click_price` float(5,2) NOT NULL,
  `thousand_impressions_price` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_access_type`
--

DROP TABLE IF EXISTS `new_access_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_access_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_access_type_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_booking_funnel`
--

DROP TABLE IF EXISTS `new_booking_funnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_booking_funnel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `referrer_id` int(11) DEFAULT NULL,
  `brand_id` int(10) unsigned NOT NULL,
  `session` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `booking_action` enum('home','availability','checkout','booking') COLLATE utf8_unicode_ci NOT NULL,
  `source` enum('facebook','pushtech','captive_portal','hotelinking_birthday_email') COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_action` enum('share','campaign','redeem_offer','birthday_offer') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `new_booking_funnel_user_id_foreign` (`user_id`),
  KEY `new_booking_funnel_referrer_id_foreign` (`referrer_id`),
  KEY `new_booking_funnel_brand_id_booking_action_index` (`brand_id`,`booking_action`),
  KEY `new_booking_funnel_brand_id_source_index` (`brand_id`,`source`),
  KEY `new_booking_funnel_brand_id_source_action_index` (`brand_id`,`source_action`),
  CONSTRAINT `new_booking_funnel_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `new_booking_funnel_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `new_booking_funnel_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_connection`
--

DROP TABLE IF EXISTS `new_connection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_connection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `device_id` int(10) unsigned NOT NULL,
  `access_type_id` int(10) unsigned NOT NULL,
  `access_code` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_client` tinyint(1) NOT NULL,
  `headers` text COLLATE utf8_unicode_ci,
  `browser` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `browser_version` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `browser_lang` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operating_system` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operating_system_version` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `new_connection_access_type_id_foreign` (`access_type_id`),
  KEY `new_connection_device_id_foreign` (`device_id`),
  KEY `new_connection_visit_id_device_id_index` (`visit_id`,`device_id`),
  CONSTRAINT `new_connection_access_type_id_foreign` FOREIGN KEY (`access_type_id`) REFERENCES `new_access_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_connection_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `new_device` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_connection_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `new_visit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_device`
--

DROP TABLE IF EXISTS `new_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mac_address` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `device_family` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_brand` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_model` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_device_mac_address_unique` (`mac_address`),
  KEY `new_device_mac_address_index` (`mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_social_media`
--

DROP TABLE IF EXISTS `new_social_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_social_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_user_brand`
--

DROP TABLE IF EXISTS `new_user_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_user_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `unsubscribed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_user_brand_user_id_brand_id_unique` (`user_id`,`brand_id`),
  KEY `date_brand_id_index` (`date`,`brand_id`),
  KEY `new_user_brand_brand_id_foreign` (`brand_id`),
  CONSTRAINT `new_user_brand_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_user_brand_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_user_device`
--

DROP TABLE IF EXISTS `new_user_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_user_device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `device_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `new_user_device_user_id_device_id_unique` (`user_id`,`device_id`),
  KEY `new_user_device_device_id_foreign` (`device_id`),
  CONSTRAINT `new_user_device_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `new_device` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_user_device_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_user_social_media_share`
--

DROP TABLE IF EXISTS `new_user_social_media_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_user_social_media_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_brand_id` int(10) unsigned NOT NULL,
  `social_media_id` int(10) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `share_type_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_brand_social_media_index` (`user_brand_id`,`social_media_id`),
  KEY `social_media_share_type_index` (`social_media_id`,`share_type_id`),
  KEY `share_type_index` (`share_type_id`),
  CONSTRAINT `new_user_social_media_share_social_media_id_foreign` FOREIGN KEY (`social_media_id`) REFERENCES `new_social_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_social_media_share_to_share_types_fk` FOREIGN KEY (`share_type_id`) REFERENCES `tipos_share` (`id`),
  CONSTRAINT `user_social_media_share_to_user_brand_fk` FOREIGN KEY (`user_brand_id`) REFERENCES `new_user_brand` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_users`
--

DROP TABLE IF EXISTS `new_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tarjeta` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `created` datetime NOT NULL,
  `verificado` int(1) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `pais` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `provincia` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `location` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `lang` varchar(5) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'en',
  `fecha_nacimiento` varchar(10) COLLATE utf8_spanish_ci DEFAULT '',
  `sexo` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `minEstrellas` tinyint(1) NOT NULL DEFAULT '0',
  `maxEstrellas` tinyint(1) NOT NULL DEFAULT '0',
  `rango_inf` int(4) NOT NULL DEFAULT '0',
  `rango_sup` int(4) NOT NULL DEFAULT '0',
  `notif_hotelinking` tinyint(1) NOT NULL DEFAULT '1',
  `notif_especiales` tinyint(1) NOT NULL DEFAULT '1',
  `compart_hoteles` tinyint(4) NOT NULL DEFAULT '0',
  `img` varchar(250) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `tw_followers` int(11) NOT NULL DEFAULT '0',
  `fb_friends` int(11) NOT NULL DEFAULT '0',
  `user_card` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email_result` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sendex` decimal(2,2) DEFAULT NULL,
  `unsubscribed` tinyint(1) NOT NULL DEFAULT '0',
  `generation` enum('mature','baby boomer','generation x','millenial','generation z') COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `generation_index` (`generation`),
  KEY `verificado_index` (`verificado`),
  KEY `users_lang_index` (`lang`),
  FULLTEXT KEY `id_tarjeta` (`id_tarjeta`),
  FULLTEXT KEY `nombre_usuario` (`nombre`),
  FULLTEXT KEY `user_email_ftidx` (`nombre`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `new_visit`
--

DROP TABLE IF EXISTS `new_visit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_visit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_brand_id` int(10) unsigned NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NOT NULL,
  `is_client` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `new_visit_user_brand_id_foreign` (`user_brand_id`),
  CONSTRAINT `new_visit_user_brand_id_foreign` FOREIGN KEY (`user_brand_id`) REFERENCES `new_user_brand` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oferta_page_ips`
--

DROP TABLE IF EXISTS `oferta_page_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oferta_page_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `id_oferta` int(10) NOT NULL,
  `fecha` datetime NOT NULL,
  `http_referer` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oferta_referral_token`
--

DROP TABLE IF EXISTS `oferta_referral_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oferta_referral_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oferta` int(5) NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `id_referrer` int(11) DEFAULT NULL,
  `id_encuesta` int(10) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_cupon` int(11) NOT NULL,
  `token` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `id_tipo_share` int(11) NOT NULL,
  `id_origen_oferta` int(2) DEFAULT NULL,
  `transaction` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cookie_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `id_origen_oferta` (`id_origen_oferta`),
  CONSTRAINT `oferta_referral_token_ibfk_1` FOREIGN KEY (`id_origen_oferta`) REFERENCES `origen_cupon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offer_goals`
--

DROP TABLE IF EXISTS `offer_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) unsigned NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `offer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `n_triggers` int(11) NOT NULL,
  `days_to_expire` int(11) NOT NULL,
  `offer_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offer_goals_brand_id_product_id_n_triggers_unique` (`brand_id`,`product_id`,`n_triggers`),
  UNIQUE KEY `offer_goals_unique_hotel_chain_product_n_triggers` (`hotel_id`,`chain_id`,`product_id`,`n_triggers`),
  KEY `offer_goals_chain_idx` (`chain_id`),
  KEY `offer_goals_offers_idx` (`offer_id`),
  KEY `offer_goals_product_idx` (`product_id`),
  KEY `offer_goals_brand_id_index` (`brand_id`),
  CONSTRAINT `offer_goals_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `offer_goals_chain` FOREIGN KEY (`chain_id`) REFERENCES `cadena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `offer_goals_hoteles` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `offer_goals_offers` FOREIGN KEY (`offer_id`) REFERENCES `hotel_oferta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `offer_goals_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offer_method`
--

DROP TABLE IF EXISTS `offer_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_method` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `method_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `method_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `lang_index` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offer_platforms`
--

DROP TABLE IF EXISTS `offer_platforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offer_triggers`
--

DROP TABLE IF EXISTS `offer_triggers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_triggers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotels_id` int(11) NOT NULL,
  `hotel_oferta_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `offer_triggers_id` int(11) NOT NULL,
  `offer_platforms_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '30',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `offers_hotels_products` (`hotels_id`,`products_id`),
  KEY `offers_hotel_oferta` (`hotel_oferta_id`),
  KEY `offers_offer_platforms` (`offer_platforms_id`),
  KEY `offers_offer_triggers` (`offer_triggers_id`),
  KEY `offers_products` (`products_id`),
  CONSTRAINT `offer_hotels` FOREIGN KEY (`hotels_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `offers_hotel_oferta` FOREIGN KEY (`hotel_oferta_id`) REFERENCES `hotel_oferta` (`id`),
  CONSTRAINT `offers_offer_platforms` FOREIGN KEY (`offer_platforms_id`) REFERENCES `offer_platforms` (`id`),
  CONSTRAINT `offers_offer_triggers` FOREIGN KEY (`offer_triggers_id`) REFERENCES `offer_triggers` (`id`),
  CONSTRAINT `offers_products` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `onboarding`
--

DROP TABLE IF EXISTS `onboarding`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onboarding` (
  `id_hotel` int(5) NOT NULL,
  `first_video` int(1) DEFAULT NULL,
  `basic_info` int(1) DEFAULT NULL,
  `hotel_profile` int(1) DEFAULT NULL,
  `booking_info` int(1) DEFAULT NULL,
  `landing_page` int(1) DEFAULT NULL,
  `oferta_1` int(1) DEFAULT NULL,
  `second_video` int(1) DEFAULT NULL,
  `invite_send` int(1) DEFAULT NULL,
  `launch` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_hotel`),
  CONSTRAINT `onboarding_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `origen_cupon`
--

DROP TABLE IF EXISTS `origen_cupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `origen_cupon` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `origen_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `origen_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paises` (
  `id_pais` char(2) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `pais` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id_pais`),
  UNIQUE KEY `paisID` (`id_pais`),
  KEY `paisID_2` (`id_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permisos_cadenas`
--

DROP TABLE IF EXISTS `permisos_cadenas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos_cadenas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadena` int(11) NOT NULL,
  `LY` tinyint(1) NOT NULL DEFAULT '0',
  `RF` tinyint(1) NOT NULL DEFAULT '1',
  `MK` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_cadena` (`id_cadena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permisos_hoteles`
--

DROP TABLE IF EXISTS `permisos_hoteles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos_hoteles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(11) NOT NULL,
  `LY` tinyint(1) NOT NULL DEFAULT '0',
  `RF` tinyint(1) NOT NULL DEFAULT '1',
  `MK` tinyint(1) NOT NULL DEFAULT '0',
  `review` tinyint(1) NOT NULL DEFAULT '0',
  `satisfaction` tinyint(1) NOT NULL DEFAULT '0',
  `wifi_offers` tinyint(1) NOT NULL DEFAULT '0',
  `birthday_emails` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pushtech` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `require_room_num` tinyint(1) NOT NULL DEFAULT '0',
  `display_require_room` tinyint(1) NOT NULL DEFAULT '1',
  `wifi_days` int(3) unsigned NOT NULL DEFAULT '15',
  `user_enrichment` tinyint(4) NOT NULL DEFAULT '0',
  `birthday_alarm` tinyint(1) NOT NULL DEFAULT '1',
  `loyalty` tinyint(1) NOT NULL DEFAULT '0',
  `eprivacy_responsible` tinyint(1) NOT NULL DEFAULT '0',
  `followup_mail` int(11) NOT NULL DEFAULT '0',
  `not_hotel` tinyint(1) DEFAULT '0',
  `portal_pro` tinyint(1) NOT NULL,
  `datamatch` tinyint(4) NOT NULL,
  `widget` tinyint(1) NOT NULL DEFAULT '0',
  `customized_satisfaction_surveys` tinyint(1) DEFAULT '0',
  `trans_comments` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portal_pro_config`
--

DROP TABLE IF EXISTS `portal_pro_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portal_pro_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `room_number` tinyint(1) NOT NULL DEFAULT '1',
  `first_name` tinyint(1) NOT NULL DEFAULT '0',
  `last_name` tinyint(1) NOT NULL DEFAULT '0',
  `document_id` tinyint(1) NOT NULL DEFAULT '0',
  `access_code` tinyint(1) NOT NULL DEFAULT '0',
  `max_validations` int(11) NOT NULL DEFAULT '3',
  `restrictive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `portal_pro_config_brand_id_unique` (`brand_id`),
  CONSTRAINT `portal_pro_config_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pre_oferta_token`
--

DROP TABLE IF EXISTS `pre_oferta_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pre_oferta_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `id_oferta` int(11) DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_oferta` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `id_origen_oferta` int(2) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_oferta` (`id_oferta`),
  KEY `id_origen_oferta` (`id_origen_oferta`),
  CONSTRAINT `pre_oferta_token_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_oferta_token_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_oferta_token_ibfk_3` FOREIGN KEY (`id_oferta`) REFERENCES `hotel_oferta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_oferta_token_ibfk_4` FOREIGN KEY (`id_origen_oferta`) REFERENCES `origen_cupon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `private_login`
--

DROP TABLE IF EXISTS `private_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `private_login` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pushtech_bulk`
--

DROP TABLE IF EXISTS `pushtech_bulk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushtech_bulk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bulk_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `webhook_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `webhook_bin` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `number_contacts` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_check_date` datetime NOT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pushtech_bulk_results`
--

DROP TABLE IF EXISTS `pushtech_bulk_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushtech_bulk_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bin_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pushtech_request` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pushtech_campaigns_mappings`
--

DROP TABLE IF EXISTS `pushtech_campaigns_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushtech_campaigns_mappings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_campaign` varchar(64) NOT NULL DEFAULT '',
  `id_offer` int(11) unsigned NOT NULL,
  `id_hotel` int(11) unsigned NOT NULL,
  `id_cadena` int(11) NOT NULL,
  `days_valid` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offer_campaign_hotel` (`id_campaign`,`id_hotel`),
  KEY `campaign_and_offer` (`id_campaign`,`id_offer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pushtech_data`
--

DROP TABLE IF EXISTS `pushtech_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushtech_data` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `chain_id` int(6) unsigned DEFAULT NULL,
  `hotel_id` int(6) unsigned DEFAULT NULL,
  `token` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `secret` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotel_id` (`hotel_id`),
  UNIQUE KEY `chain_id` (`chain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pushtech_users_mappings`
--

DROP TABLE IF EXISTS `pushtech_users_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushtech_users_mappings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_pushtech` varchar(40) NOT NULL DEFAULT '',
  `id_hotel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_hotel` (`id_user`,`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referral_goal`
--

DROP TABLE IF EXISTS `referral_goal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referral_goal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotel` int(5) NOT NULL,
  `n_referrals` int(7) NOT NULL,
  `id_oferta` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referrer_tokens`
--

DROP TABLE IF EXISTS `referrer_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrer_tokens` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_encuesta` int(7) NOT NULL,
  `token` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `visitas` int(7) NOT NULL,
  `share` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_encuesta` (`id_encuesta`),
  CONSTRAINT `referrer_tokens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referrer_tokens_ips`
--

DROP TABLE IF EXISTS `referrer_tokens_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrer_tokens_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_token` int(7) NOT NULL,
  `ip` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `social_media` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `http_referer` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_token` (`id_token`),
  CONSTRAINT `referrer_tokens_ips_ibfk_1` FOREIGN KEY (`id_token`) REFERENCES `referrer_tokens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referrer_tokens_ips_ibfk_2` FOREIGN KEY (`id_token`) REFERENCES `referrer_tokens` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referrer_users`
--

DROP TABLE IF EXISTS `referrer_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrer_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invitador` int(7) NOT NULL,
  `invitado` int(7) NOT NULL,
  `id_encuesta` int(10) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invitador` (`invitador`),
  KEY `invitado` (`invitado`),
  KEY `id_encuesta` (`id_encuesta`),
  CONSTRAINT `referrer_users_ibfk_1` FOREIGN KEY (`invitador`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referrer_users_ibfk_2` FOREIGN KEY (`invitado`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servicios_hotel`
--

DROP TABLE IF EXISTS `servicios_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios_hotel` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_servicio` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `servicio_es` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `servicio_en` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `spamtraps`
--

DROP TABLE IF EXISTS `spamtraps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spamtraps` (
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subcategoria_oferta`
--

DROP TABLE IF EXISTS `subcategoria_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategoria_oferta` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_categoria_oferta` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `subcategoria_oferta_es` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `subcategoria_oferta_en` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `survey_categories`
--

DROP TABLE IF EXISTS `survey_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `survey_categories_brand_id_foreign` (`brand_id`),
  CONSTRAINT `survey_categories_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `survey_categories_text`
--

DROP TABLE IF EXISTS `survey_categories_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_categories_text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_category_id` int(10) unsigned NOT NULL,
  `lang_value` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `survey_categories_text_lang_value_foreign` (`lang_value`),
  KEY `survey_categories_text_survey_category_id_foreign` (`survey_category_id`),
  CONSTRAINT `survey_categories_text_survey_category_id_foreign` FOREIGN KEY (`survey_category_id`) REFERENCES `survey_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `survey_questions`
--

DROP TABLE IF EXISTS `survey_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `survey_questions_survey_category_id_foreign` (`survey_category_id`),
  CONSTRAINT `survey_questions_survey_category_id_foreign` FOREIGN KEY (`survey_category_id`) REFERENCES `survey_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `survey_questions_brand`
--

DROP TABLE IF EXISTS `survey_questions_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_questions_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `survey_question_id` int(10) unsigned NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `survey_questions_brand_brand_id_foreign` (`brand_id`),
  KEY `survey_questions_brand_survey_question_id_foreign` (`survey_question_id`),
  CONSTRAINT `survey_questions_brand_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `survey_questions_brand_survey_question_id_foreign` FOREIGN KEY (`survey_question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `survey_questions_text`
--

DROP TABLE IF EXISTS `survey_questions_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_questions_text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_question_id` int(10) unsigned NOT NULL,
  `lang_value` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `survey_questions_text_lang_value_foreign` (`lang_value`),
  KEY `survey_questions_text_survey_question_id_foreign` (`survey_question_id`),
  CONSTRAINT `survey_questions_text_survey_question_id_foreign` FOREIGN KEY (`survey_question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `time_zone`
--

DROP TABLE IF EXISTS `time_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gmt` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description_uniq` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_fidelizacion`
--

DROP TABLE IF EXISTS `tipo_fidelizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_fidelizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_es` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_en` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_hab_hotel`
--

DROP TABLE IF EXISTS `tipos_hab_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_hab_hotel` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_tipo_hab` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_hab_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_hab_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_hotel`
--

DROP TABLE IF EXISTS `tipos_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_hotel` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_tipo_hotel` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_hotel_decoracion`
--

DROP TABLE IF EXISTS `tipos_hotel_decoracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_hotel_decoracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_decoracion` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `decoracion_es` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `decoracion_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_oferta`
--

DROP TABLE IF EXISTS `tipos_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_oferta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_oferta` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_adq_ret_es` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_adq_ret_en` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `adq` tinyint(1) NOT NULL,
  `ret` tinyint(1) NOT NULL,
  `ref` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_share`
--

DROP TABLE IF EXISTS `tipos_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_es` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_en` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_sys` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todelete_adq_ret`
--

DROP TABLE IF EXISTS `todelete_adq_ret`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todelete_adq_ret` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adq_es` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `ret_es` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `adq_en` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `ret_en` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `ref_en` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `ref_es` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todelete_invitaciones_hotel_staff`
--

DROP TABLE IF EXISTS `todelete_invitaciones_hotel_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todelete_invitaciones_hotel_staff` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `todelete_user_share_token`
--

DROP TABLE IF EXISTS `todelete_user_share_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todelete_user_share_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `token` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tracking_cookies`
--

DROP TABLE IF EXISTS `tracking_cookies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracking_cookies` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `cookie_id` varchar(128) NOT NULL,
  `referrer_id` int(7) DEFAULT NULL,
  `referral_id` int(7) DEFAULT NULL,
  `hotel_id` int(5) DEFAULT NULL,
  `cadena_id` int(4) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `redeemed_at` datetime NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `checking_date` varchar(20) NOT NULL,
  `checkout_date` varchar(20) NOT NULL,
  `transaction_num` varchar(255) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `amount` float(15,2) NOT NULL,
  `created_times` int(9) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `destination_hotel_id` int(5) DEFAULT NULL,
  UNIQUE KEY `tracking_cookie_id` (`id`),
  KEY `referrer_id` (`referrer_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `referral_id` (`referral_id`),
  KEY `cookie_id` (`cookie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `used_promocode`
--

DROP TABLE IF EXISTS `used_promocode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `used_promocode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oferta` int(5) NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `id_referrer` int(11) DEFAULT NULL,
  `id_encuesta` int(10) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_cupon` int(11) NOT NULL,
  `promo_code` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `api` tinyint(1) NOT NULL,
  `id_transaccion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `id_tipo_share` int(11) NOT NULL,
  `id_origen_oferta` int(2) DEFAULT NULL,
  `be_transaction` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_origen_oferta` (`id_origen_oferta`),
  CONSTRAINT `used_promocode_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `used_promocode_ibfk_2` FOREIGN KEY (`id_origen_oferta`) REFERENCES `origen_cupon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_cadena_follow`
--

DROP TABLE IF EXISTS `user_cadena_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cadena_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cadena` int(5) NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `follow` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_cadena_follow_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_cadena_follow_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_categoria_oferta`
--

DROP TABLE IF EXISTS `user_categoria_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_categoria_oferta` (
  `id_usuario` int(7) NOT NULL,
  `id_tipo_oferta` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_categoria_oferta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_checkin`
--

DROP TABLE IF EXISTS `user_checkin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_checkin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `id_hotel_us` int(3) NOT NULL,
  `puntos` int(5) NOT NULL,
  `chkin_date` date NOT NULL,
  `chkout_date` date NOT NULL,
  `noches` int(3) NOT NULL,
  `checkin_fechahora` datetime NOT NULL,
  `checkout_fechahora` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_checkin_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_cupones`
--

DROP TABLE IF EXISTS `user_cupones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cupones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `id_oferta` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `canjeado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_canj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `compartido` int(1) NOT NULL DEFAULT '0',
  `fecha_last_modified` datetime NOT NULL,
  `tipo_canjeador` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `id_canjeador` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  FULLTEXT KEY `index_name` (`voucher`),
  CONSTRAINT `user_cupones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_decoraciones`
--

DROP TABLE IF EXISTS `user_decoraciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_decoraciones` (
  `id_decoracion` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` int(7) NOT NULL,
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_decoraciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_encuestas`
--

DROP TABLE IF EXISTS `user_encuestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_encuestas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `id_checkout` int(5) NOT NULL,
  `rating` float NOT NULL,
  `positiveComment` text COLLATE utf8_spanish_ci NOT NULL,
  `negativeComment` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `sexo` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `edad` int(2) NOT NULL,
  `done` tinyint(1) NOT NULL,
  `puntos` int(6) NOT NULL,
  `fecha_creada` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `user_encuestas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_encuestas_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enrichment_company`
--

DROP TABLE IF EXISTS `user_enrichment_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enrichment_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_date` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `current` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_enrichment_company_users` (`users_id`),
  CONSTRAINT `user_enrichment_company_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enrichment_interests`
--

DROP TABLE IF EXISTS `user_enrichment_interests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enrichment_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_interest_id` int(11) NOT NULL,
  `provider_name` varchar(50) NOT NULL,
  `interest_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enrichment_profiles`
--

DROP TABLE IF EXISTS `user_enrichment_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enrichment_profiles` (
  `users_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `given_name` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_enrichment_profiles_users` (`users_id`),
  CONSTRAINT `user_enrichment_profiles_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enrichment_sources`
--

DROP TABLE IF EXISTS `user_enrichment_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enrichment_sources` (
  `users_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `bio` varchar(500) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `following` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_enrichment_sources_users` (`users_id`),
  CONSTRAINT `user_enrichment_sources_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enrichment_user_interest`
--

DROP TABLE IF EXISTS `user_enrichment_user_interest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enrichment_user_interest` (
  `users_id` int(11) NOT NULL,
  `user_enrichment_interests_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `user_enrichment_user_interest_users` (`users_id`),
  KEY `user_enrichment_user_interest_user_enrichment_interests` (`user_enrichment_interests_id`),
  CONSTRAINT `user_enrichment_user_interest_user_enrichment_interests` FOREIGN KEY (`user_enrichment_interests_id`) REFERENCES `user_enrichment_interests` (`id`),
  CONSTRAINT `user_enrichment_user_interest_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enricment_interest_relations`
--

DROP TABLE IF EXISTS `user_enricment_interest_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enricment_interest_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_enrichment_interest_id` int(11) NOT NULL,
  `user_enrichment_interests_parent_id` int(11) NOT NULL,
  `score` double(4,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_enrichment_interest_user_enrichment_interest_relations` (`user_enrichment_interest_id`),
  KEY `user_enricment_interest_relations_user_enrichment_interests` (`user_enrichment_interests_parent_id`),
  CONSTRAINT `user_enrichment_interest_user_enrichment_interest_relations` FOREIGN KEY (`user_enrichment_interest_id`) REFERENCES `user_enrichment_interests` (`id`),
  CONSTRAINT `user_enricment_interest_relations_user_enrichment_interests` FOREIGN KEY (`user_enrichment_interests_parent_id`) REFERENCES `user_enrichment_interests` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_extras`
--

DROP TABLE IF EXISTS `user_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_extras` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_extra` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` int(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_extras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_facebook`
--

DROP TABLE IF EXISTS `user_facebook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_facebook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_facebook` bigint(64) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `age_min` int(3) NOT NULL,
  `age_max` int(3) NOT NULL,
  `link` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `locationID` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `locationName` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `locale` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `facebook_img` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `amigos` int(11) NOT NULL,
  `birthday` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `token_fb` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_facebook` (`id_facebook`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_facebook_2` (`id_facebook`),
  CONSTRAINT `user_facebook_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_guid`
--

DROP TABLE IF EXISTS `user_guid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_guid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `guid` varchar(36) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `guid` (`guid`),
  CONSTRAINT `user_guid_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_hotel_gasto_noches_inicial`
--

DROP TABLE IF EXISTS `user_hotel_gasto_noches_inicial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_hotel_gasto_noches_inicial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `total_noches` int(6) NOT NULL,
  `gasto_total` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_hotel_gasto_noches_inicial_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_hotels`
--

DROP TABLE IF EXISTS `user_hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `id_cadena` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `total_noches` int(4) NOT NULL,
  `gasto_total` int(7) NOT NULL,
  `total_checkins` int(5) NOT NULL,
  `follow` int(1) NOT NULL,
  `user_hotel_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer` tinyint(1) DEFAULT '1',
  `guid` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `unsubscribed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_hotel` (`id_usuario`,`id_hotel`),
  KEY `chain_idx` (`id_cadena`),
  KEY `user_id_chain_id_idx` (`id_usuario`,`id_cadena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_hotels_follow`
--

DROP TABLE IF EXISTS `user_hotels_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_hotels_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `follow` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `user_hotels_follow_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_hotels_follow_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_login_attempts`
--

DROP TABLE IF EXISTS `user_login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `attempts` int(11) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_money_hotel`
--

DROP TABLE IF EXISTS `user_money_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_money_hotel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `id_checkout` int(5) NOT NULL,
  `money` int(7) NOT NULL,
  `fecha` date NOT NULL,
  `dollars` float NOT NULL,
  `coin` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_hotel` (`id_hotel`),
  CONSTRAINT `user_money_hotel_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_money_hotel_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_paises`
--

DROP TABLE IF EXISTS `user_paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_paises` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_pais` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_paises_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_points`
--

DROP TABLE IF EXISTS `user_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_emisor` int(5) NOT NULL,
  `puntos` int(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_emisor` (`id_emisor`),
  CONSTRAINT `user_points_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_points_ibfk_2` FOREIGN KEY (`id_emisor`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_points_cadena`
--

DROP TABLE IF EXISTS `user_points_cadena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_points_cadena` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_cadena` int(5) NOT NULL,
  `puntos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_points_cadena_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_points_hl`
--

DROP TABLE IF EXISTS `user_points_hl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_points_hl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `puntos` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_points_hl_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_points_reg`
--

DROP TABLE IF EXISTS `user_points_reg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_points_reg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(5) NOT NULL,
  `id_emisor` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `puntos` int(8) NOT NULL,
  `fecha` datetime NOT NULL,
  `id_action` int(2) NOT NULL,
  `id_regalador` int(7) NOT NULL,
  `id_oferta` int(10) NOT NULL,
  `id_referral` int(7) NOT NULL,
  `id_hotel_action` int(5) NOT NULL,
  `id_cadena` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_points_reg_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_satisfaction`
--

DROP TABLE IF EXISTS `user_satisfaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_satisfaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_cadena` int(11) NOT NULL DEFAULT '0',
  `puntuacion` float NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_creado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_update` datetime NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `review_send` tinyint(1) NOT NULL DEFAULT '0',
  `has_been_seen` tinyint(1) NOT NULL DEFAULT '0',
  `id_room` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `who_has_been_seen` int(11) DEFAULT '-1',
  `send_date` datetime DEFAULT '0000-00-00 00:00:00',
  `customized_send_date` datetime DEFAULT NULL,
  `favorite` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_hotel` (`id_hotel`),
  KEY `user_sat_index` (`id_hotel`,`puntuacion`),
  FULLTEXT KEY `comment_ftidx` (`comentario`),
  CONSTRAINT `user_satisfaction_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_satisfaction_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_servicios`
--

DROP TABLE IF EXISTS `user_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_servicios` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_servicio` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_servicios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_shares`
--

DROP TABLE IF EXISTS `user_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_shares` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `id_hotel` int(5) NOT NULL,
  `id_cadena` int(11) NOT NULL,
  `id_encuesta` int(7) NOT NULL,
  `id_share` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `media` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `id_tipo_share` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_shares_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_tipos_hab`
--

DROP TABLE IF EXISTS `user_tipos_hab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tipos_hab` (
  `id_usuario` int(7) NOT NULL,
  `id_tipo_hab` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_tipos_hab_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_tipos_hotel`
--

DROP TABLE IF EXISTS `user_tipos_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tipos_hotel` (
  `id_tipo_hotel` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` int(7) NOT NULL,
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_tipos_hotel_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_twitter`
--

DROP TABLE IF EXISTS `user_twitter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_twitter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(6) NOT NULL,
  `twitter_user` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `twitter_followers` int(11) NOT NULL,
  `id_twitter` bigint(15) NOT NULL,
  `twitter_img` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `lang` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `location` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `oauth_token` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `oauth_token_secret` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `twitter_email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_twitter` (`id_twitter`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_twitter_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_user_follow`
--

DROP TABLE IF EXISTS `user_user_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_user_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_seguidor` int(11) NOT NULL,
  `id_seguido` int(11) NOT NULL,
  `follow` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_seguidor` (`id_seguidor`),
  KEY `id_seguido` (`id_seguido`),
  CONSTRAINT `user_user_follow_ibfk_1` FOREIGN KEY (`id_seguidor`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_user_follow_ibfk_2` FOREIGN KEY (`id_seguido`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_wishlist`
--

DROP TABLE IF EXISTS `user_wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_wishlist` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_oferta` int(11) NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `user_wishlist_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tarjeta` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `created` datetime NOT NULL,
  `verificado` int(1) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `pais` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `provincia` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `location` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `lang` varchar(5) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'en',
  `fecha_nacimiento` varchar(10) COLLATE utf8_spanish_ci DEFAULT '',
  `sexo` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `minEstrellas` tinyint(1) NOT NULL DEFAULT '0',
  `maxEstrellas` tinyint(1) NOT NULL DEFAULT '0',
  `rango_inf` int(4) NOT NULL DEFAULT '0',
  `rango_sup` int(4) NOT NULL DEFAULT '0',
  `notif_hotelinking` tinyint(1) NOT NULL DEFAULT '1',
  `notif_especiales` tinyint(1) NOT NULL DEFAULT '1',
  `compart_hoteles` tinyint(4) NOT NULL DEFAULT '0',
  `img` varchar(250) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `tw_followers` int(11) NOT NULL DEFAULT '0',
  `fb_friends` int(11) NOT NULL DEFAULT '0',
  `user_card` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email_result` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sendex` decimal(2,2) DEFAULT NULL,
  `unsubscribed` tinyint(1) NOT NULL DEFAULT '0',
  `generation` enum('mature','baby boomer','generation x','millenial','generation z') COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `verificado_index` (`verificado`),
  KEY `users_lang_index` (`lang`),
  FULLTEXT KEY `id_tarjeta` (`id_tarjeta`),
  FULLTEXT KEY `nombre_usuario` (`nombre`),
  FULLTEXT KEY `user_email_ftidx` (`nombre`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_visits`
--

DROP TABLE IF EXISTS `users_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `chain_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `num_visits` int(11) NOT NULL,
  `recurrent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_brand` (`hotel_id`,`chain_id`,`user_id`),
  UNIQUE KEY `user_chain_unique` (`chain_id`,`user_id`),
  UNIQUE KEY `user_hotel_unique` (`user_id`,`hotel_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `user_constrain_id_idx` (`user_id`),
  KEY `uv_last_login_idx` (`last_login`),
  KEY `uv_recurrent_idx` (`recurrent`),
  KEY `uv_hotel_id_idx` (`hotel_id`),
  KEY `uv_chain_id_idx` (`chain_id`),
  CONSTRAINT `chain_id` FOREIGN KEY (`chain_id`) REFERENCES `cadena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `hotel_constrain_id` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_constrain_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verificar_email`
--

DROP TABLE IF EXISTS `verificar_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verificar_email` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) NOT NULL,
  `email` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_email` varchar(5) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vincular_redes_sociales`
--

DROP TABLE IF EXISTS `vincular_redes_sociales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vincular_redes_sociales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(7) NOT NULL,
  `token` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `red_social` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `ruta` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `vincular_redes_sociales_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wifi_providers`
--

DROP TABLE IF EXISTS `wifi_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wifi_providers` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
