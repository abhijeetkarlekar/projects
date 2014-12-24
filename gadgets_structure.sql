-- MySQL dump 10.13  Distrib 5.5.34, for Linux (x86_64)
--
-- Host: localhost    Database: staging_gadgets_india
-- ------------------------------------------------------
-- Server version	5.5.34

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

--
-- Table structure for table `API_REVIEWS_CACHE`
--

DROP TABLE IF EXISTS `API_REVIEWS_CACHE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `API_REVIEWS_CACHE` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `model_id` int(11) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  `rating_array` tinytext NOT NULL,
  `xml` text NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `category_id` (`category_id`,`brand_id`,`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1222 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ARRIVAL_PRODUCT`
--

DROP TABLE IF EXISTS `ARRIVAL_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ARRIVAL_PRODUCT` (
  `arrival_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`arrival_product_id`),
  UNIQUE KEY `unique_arrival_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `BEST_SELLER_PRODUCT`
--

DROP TABLE IF EXISTS `BEST_SELLER_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BEST_SELLER_PRODUCT` (
  `best_seller_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`best_seller_product_id`),
  UNIQUE KEY `unique_best_seller_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `BRAND_MASTER`
--

DROP TABLE IF EXISTS `BRAND_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BRAND_MASTER` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `seo_path` varchar(20) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `brand_image` varchar(255) NOT NULL,
  `brand_research_image` varchar(255) NOT NULL,
  `short_desc` text NOT NULL,
  `long_desc` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Default value is 1.1-Active,0-InActive',
  `upcoming_brand` tinyint(1) NOT NULL DEFAULT '0',
  `discontinue_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-discontinue ,1-continue',
  `discontinue_date` datetime NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `brand_unique` (`category_id`,`brand_name`),
  KEY `discontinue_flag` (`discontinue_flag`),
  KEY `discontinue_date` (`discontinue_date`),
  KEY `brand_path` (`seo_path`),
  CONSTRAINT `BRAND_MASTER_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COMMENT='table is used to store the information of brand';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `BUDGET_PRODUCT`
--

DROP TABLE IF EXISTS `BUDGET_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BUDGET_PRODUCT` (
  `budget_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`budget_product_id`),
  UNIQUE KEY `unique_budget_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CAR_FINDER_FEATURE_OVERVIEW_MASTER`
--

DROP TABLE IF EXISTS `CAR_FINDER_FEATURE_OVERVIEW_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CAR_FINDER_FEATURE_OVERVIEW_MASTER` (
  `overview_id` int(11) NOT NULL AUTO_INCREMENT,
  `overview_sub_group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `abbreviation` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`overview_id`),
  KEY `feature_id` (`feature_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='used to store overview features';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CATEGORY_MASTER`
--

DROP TABLE IF EXISTS `CATEGORY_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATEGORY_MASTER` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `category_level` int(11) NOT NULL DEFAULT '0',
  `seo_path` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`),
  KEY `category_level` (`category_level`),
  KEY `cat_path` (`seo_path`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='table is used to manage categorys';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `COMPARE_OVERVIEW_MASTER`
--

DROP TABLE IF EXISTS `COMPARE_OVERVIEW_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COMPARE_OVERVIEW_MASTER` (
  `overview_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_feature_group` int(11) NOT NULL,
  `feature_group` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`overview_id`),
  UNIQUE KEY `unique_compare_set` (`main_feature_group`,`feature_group`,`category_id`,`feature_id`),
  KEY `feature_id` (`feature_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='used to store overview features';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `COMPARE_TOP_COMPETITOR`
--

DROP TABLE IF EXISTS `COMPARE_TOP_COMPETITOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COMPARE_TOP_COMPETITOR` (
  `competitor_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_info_id` int(11) NOT NULL DEFAULT '0',
  `low_price` int(50) NOT NULL DEFAULT '0',
  `high_price` int(50) NOT NULL DEFAULT '0',
  `product_ids` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`competitor_product_id`),
  UNIQUE KEY `unique_competitor_product` (`category_id`,`brand_id`,`product_id`,`low_price`,`high_price`,`product_ids`),
  KEY `product_id` (`product_id`),
  KEY `brand_id` (`brand_id`),
  KEY `product_info_id` (`product_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURED_ONCARS_COMPARISON`
--

DROP TABLE IF EXISTS `FEATURED_ONCARS_COMPARISON`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURED_ONCARS_COMPARISON` (
  `featured_compare_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncars_compare_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`featured_compare_id`),
  UNIQUE KEY `oncars_compare_id` (`oncars_compare_id`,`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURED_PRODUCT`
--

DROP TABLE IF EXISTS `FEATURED_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURED_PRODUCT` (
  `featured_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`featured_product_id`),
  UNIQUE KEY `unique_featured_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURED_SLIDES`
--

DROP TABLE IF EXISTS `FEATURED_SLIDES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURED_SLIDES` (
  `section_slide_id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`section_slide_id`),
  UNIQUE KEY `slide_id` (`slide_id`,`category_id`),
  UNIQUE KEY `slide_id_2` (`slide_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURED_VIDEOS`
--

DROP TABLE IF EXISTS `FEATURED_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURED_VIDEOS` (
  `section_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`section_video_id`),
  UNIQUE KEY `video_id` (`video_id`,`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURE_MASTER`
--

DROP TABLE IF EXISTS `FEATURE_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURE_MASTER` (
  `feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_feature_group` int(11) NOT NULL,
  `feature_name` varchar(100) NOT NULL,
  `seo_path` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `feature_description` text NOT NULL,
  `feature_group` int(11) NOT NULL,
  `feature_img_path` varchar(250) NOT NULL,
  `unit_id` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Default value is 1.1-Active,0-InActive',
  `feature_display_order` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`feature_id`),
  UNIQUE KEY `feature_unique` (`main_feature_group`,`feature_name`,`category_id`,`feature_group`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `feature_group` (`feature_group`),
  KEY `unit_id` (`unit_id`),
  KEY `main_feature_group` (`main_feature_group`),
  CONSTRAINT `FEATURE_MASTER_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=latin1 COMMENT='table is used to store the information of brand/product feat';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURE_MASTER_12_12_14`
--

DROP TABLE IF EXISTS `FEATURE_MASTER_12_12_14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURE_MASTER_12_12_14` (
  `feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_feature_group` int(11) NOT NULL,
  `feature_name` varchar(100) NOT NULL,
  `seo_path` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `feature_description` text NOT NULL,
  `feature_group` int(11) NOT NULL,
  `feature_img_path` varchar(250) NOT NULL,
  `unit_id` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Default value is 1.1-Active,0-InActive',
  `feature_display_order` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`feature_id`),
  UNIQUE KEY `feature_unique` (`main_feature_group`,`feature_name`,`category_id`,`feature_group`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `feature_group` (`feature_group`),
  KEY `unit_id` (`unit_id`),
  KEY `main_feature_group` (`main_feature_group`),
  CONSTRAINT `FEATURE_MASTER_12_12_14_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=latin1 COMMENT='table is used to store the information of brand/product feat';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURE_OVERVIEW_MASTER`
--

DROP TABLE IF EXISTS `FEATURE_OVERVIEW_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURE_OVERVIEW_MASTER` (
  `overview_id` int(11) NOT NULL AUTO_INCREMENT,
  `overview_sub_group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `abbreviation` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`overview_id`),
  KEY `feature_id` (`feature_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `FEATURE_OVERVIEW_MASTER_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='used to store overview features';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURE_SUB_GROUP`
--

DROP TABLE IF EXISTS `FEATURE_SUB_GROUP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURE_SUB_GROUP` (
  `sub_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `main_group_id` int(11) NOT NULL,
  `sub_group_name` varchar(255) NOT NULL,
  `seo_path` varchar(100) NOT NULL,
  `sub_group_position` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`sub_group_id`),
  UNIQUE KEY `feature_sub_group_unique` (`category_id`,`sub_group_name`,`main_group_id`),
  CONSTRAINT `FEATURE_SUB_GROUP_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FEATURE_UNIT`
--

DROP TABLE IF EXISTS `FEATURE_UNIT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FEATURE_UNIT` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`unit_id`),
  UNIQUE KEY `unit_name` (`unit_name`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `FEATURE_UNIT_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='table is used to store the feature units.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LATEST_PRODUCT`
--

DROP TABLE IF EXISTS `LATEST_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LATEST_PRODUCT` (
  `latest_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`latest_product_id`),
  UNIQUE KEY `unique_latest_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MAIN_FEATURE_GROUP`
--

DROP TABLE IF EXISTS `MAIN_FEATURE_GROUP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MAIN_FEATURE_GROUP` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `main_group_name` varchar(255) NOT NULL COMMENT 'main group is used to show on tabs',
  `seo_path` varchar(100) NOT NULL,
  `overview_display_name` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `main_feature_group_unique` (`category_id`,`main_group_name`),
  CONSTRAINT `MAIN_FEATURE_GROUP_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='used to store main group information.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MOST_POPULAR_COMPARE_SET_MASTER`
--

DROP TABLE IF EXISTS `MOST_POPULAR_COMPARE_SET_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOST_POPULAR_COMPARE_SET_MASTER` (
  `compare_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `compare_set` varchar(255) NOT NULL DEFAULT '0',
  `media_id` int(11) NOT NULL,
  `compare_set_img` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`compare_id`),
  UNIQUE KEY `category_id` (`category_id`,`compare_set`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MOST_POPULAR_VIDEOS`
--

DROP TABLE IF EXISTS `MOST_POPULAR_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOST_POPULAR_VIDEOS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `tbl_type` int(11) NOT NULL COMMENT 'tbl_type=> 1-video,2-reviews,3-article,4-news',
  `status` int(5) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_id` (`video_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MOST_RECENT_VIDEOS`
--

DROP TABLE IF EXISTS `MOST_RECENT_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOST_RECENT_VIDEOS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `tbl_type` int(11) NOT NULL COMMENT 'tbl_type=> 1-video,2-reviews,3-article,4-news',
  `status` int(5) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_id` (`video_id`,`category_id`),
  KEY `status` (`status`),
  KEY `create_date` (`create_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MOST_VIEWED_VIDEOS`
--

DROP TABLE IF EXISTS `MOST_VIEWED_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOST_VIEWED_VIDEOS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `type_id` int(5) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NEWS_FEED_CACHE`
--

DROP TABLE IF EXISTS `NEWS_FEED_CACHE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NEWS_FEED_CACHE` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `model_id` int(11) NOT NULL DEFAULT '0',
  `xml` text NOT NULL,
  `is_news` tinyint(1) NOT NULL DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`news_id`),
  UNIQUE KEY `category_id` (`category_id`,`brand_id`,`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1224 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NEWS_LETTER_SUBCRIPTION`
--

DROP TABLE IF EXISTS `NEWS_LETTER_SUBCRIPTION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NEWS_LETTER_SUBCRIPTION` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `service_id` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL COMMENT '0-not verify,1-verified,2-not valid,3-blocked,4-reject,5-delete',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_id` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OTHER_PRODUCT`
--

DROP TABLE IF EXISTS `OTHER_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OTHER_PRODUCT` (
  `other_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`other_product_id`),
  UNIQUE KEY `unique_other_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PIVOT_DISPLAY_TYPE`
--

DROP TABLE IF EXISTS `PIVOT_DISPLAY_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PIVOT_DISPLAY_TYPE` (
  `pivot_display_id` int(11) NOT NULL AUTO_INCREMENT,
  `pivot_display_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`pivot_display_id`),
  UNIQUE KEY `pivot_display_name` (`pivot_display_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='table is used to store the display tye i.e.checkbox,slider,s';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PIVOT_MASTER`
--

DROP TABLE IF EXISTS `PIVOT_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PIVOT_MASTER` (
  `pivot_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `pivot_group` int(11) NOT NULL,
  `pivot_desc` text NOT NULL,
  `pivot_display_id` tinyint(11) DEFAULT NULL,
  `pivot_image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pivot_display_order` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`pivot_id`),
  UNIQUE KEY `pivot_unique` (`category_id`,`feature_id`,`pivot_group`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1 COMMENT='table is used to store the key features.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PIVOT_MASTER_12_12_14`
--

DROP TABLE IF EXISTS `PIVOT_MASTER_12_12_14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PIVOT_MASTER_12_12_14` (
  `pivot_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `pivot_group` int(11) NOT NULL,
  `pivot_desc` text NOT NULL,
  `pivot_display_id` tinyint(11) DEFAULT NULL,
  `pivot_image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `pivot_display_order` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`pivot_id`),
  UNIQUE KEY `pivot_unique` (`category_id`,`feature_id`,`pivot_group`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1 COMMENT='table is used to store the key features.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PIVOT_SUB_GROUP`
--

DROP TABLE IF EXISTS `PIVOT_SUB_GROUP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PIVOT_SUB_GROUP` (
  `sub_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `sub_group_name` varchar(255) NOT NULL,
  `sub_group_position` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`sub_group_id`),
  UNIQUE KEY `pivot_sub_group_unique` (`category_id`,`sub_group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `POPULAR_BRAND`
--

DROP TABLE IF EXISTS `POPULAR_BRAND`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POPULAR_BRAND` (
  `popular_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_position` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`popular_id`),
  UNIQUE KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `POPULAR_FEATURE_CARS`
--

DROP TABLE IF EXISTS `POPULAR_FEATURE_CARS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `POPULAR_FEATURE_CARS` (
  `popular_feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `pivot_group` int(11) NOT NULL,
  `pivot_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`popular_feature_id`),
  UNIQUE KEY `pivot_id` (`pivot_id`,`brand_id`,`model_id`,`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRICE_VARIANT_FORMULA`
--

DROP TABLE IF EXISTS `PRICE_VARIANT_FORMULA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRICE_VARIANT_FORMULA` (
  `variant_formula_id` int(11) NOT NULL AUTO_INCREMENT,
  `formula` tinytext NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`variant_formula_id`),
  UNIQUE KEY `category_id` (`category_id`),
  CONSTRAINT `PRICE_VARIANT_FORMULA_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='used to store product price variant formula';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRICE_VARIANT_MASTER`
--

DROP TABLE IF EXISTS `PRICE_VARIANT_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRICE_VARIANT_MASTER` (
  `variant_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `variant` varchar(255) NOT NULL,
  `pos_order` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`variant_id`),
  UNIQUE KEY `category_id` (`category_id`,`variant`),
  CONSTRAINT `PRICE_VARIANT_MASTER_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `CATEGORY_MASTER` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='used to store price variant of the product';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRICE_VARIANT_VALUES`
--

DROP TABLE IF EXISTS `PRICE_VARIANT_VALUES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRICE_VARIANT_VALUES` (
  `price_variant` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1',
  `state_id` int(11) NOT NULL DEFAULT '1',
  `city_id` int(11) NOT NULL DEFAULT '1',
  `color_id` int(11) NOT NULL DEFAULT '0',
  `variant_value` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `default_city` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`price_variant`),
  UNIQUE KEY `product_price_unique` (`category_id`,`brand_id`,`product_id`,`country_id`,`state_id`,`city_id`,`variant_id`),
  KEY `product_id` (`product_id`),
  KEY `variant_value` (`variant_value`),
  KEY `variant_id` (`variant_id`),
  KEY `default_city` (`default_city`)
) ENGINE=InnoDB AUTO_INCREMENT=7630 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_FEATURE`
--

DROP TABLE IF EXISTS `PRODUCT_FEATURE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_FEATURE` (
  `product_feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `feature_value` text NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_feature_id`),
  UNIQUE KEY `unique_product_feature` (`product_id`,`feature_id`),
  KEY `feature_id` (`feature_id`)
) ENGINE=InnoDB AUTO_INCREMENT=208978 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_FEATURE_12_12_14`
--

DROP TABLE IF EXISTS `PRODUCT_FEATURE_12_12_14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_FEATURE_12_12_14` (
  `product_feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `feature_value` text NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_feature_id`),
  UNIQUE KEY `unique_product_feature` (`product_id`,`feature_id`),
  KEY `feature_id` (`feature_id`)
) ENGINE=InnoDB AUTO_INCREMENT=144762 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_MASTER`
--

DROP TABLE IF EXISTS `PRODUCT_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_MASTER` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_full_name` varchar(100) NOT NULL,
  `variant` varchar(100) NOT NULL DEFAULT '',
  `seo_path` varchar(250) NOT NULL,
  `product_desc` text NOT NULL,
  `year` year(4) NOT NULL,
  `media_id` int(11) NOT NULL,
  `video_path` text NOT NULL,
  `img_media_id` int(11) NOT NULL,
  `image_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `bgrrating` int(11) NOT NULL DEFAULT '0',
  `discontinue_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-discontinue ,1-continue',
  `discontinue_date` datetime NOT NULL,
  `arrival_date` date NOT NULL,
  `announced_date` datetime NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_unique` (`product_name`,`variant`,`category_id`,`brand_id`,`discontinue_flag`,`discontinue_date`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `discontinue_flag` (`discontinue_flag`),
  KEY `discontinue_date` (`discontinue_date`),
  KEY `product_name` (`product_name`),
  KEY `seo_path` (`seo_path`)
) ENGINE=InnoDB AUTO_INCREMENT=1259 DEFAULT CHARSET=latin1 COMMENT='table is used to store the product information.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_NAME_INFO`
--

DROP TABLE IF EXISTS `PRODUCT_NAME_INFO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_NAME_INFO` (
  `product_name_id` int(11) NOT NULL AUTO_INCREMENT,
  `seo_path` varchar(250) NOT NULL,
  `product_info_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `tags` varchar(50) NOT NULL,
  `abstract` text NOT NULL,
  `product_name_desc` text NOT NULL,
  `media_id` int(11) NOT NULL,
  `video_path` text NOT NULL,
  `img_media_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `arrival_date` date NOT NULL,
  `discontinue_flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-discontinue ,1-continue',
  `upcoming_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1->Upcoming Model, 0->Existing Model',
  `discontinue_date` datetime NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`product_name_id`),
  UNIQUE KEY `product_name` (`product_info_name`),
  UNIQUE KEY `product_info_name` (`product_info_name`,`category_id`,`brand_id`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `discontinue_flag` (`discontinue_flag`),
  KEY `discontinue_date` (`discontinue_date`),
  KEY `seo_path` (`seo_path`)
) ENGINE=InnoDB AUTO_INCREMENT=10553600 DEFAULT CHARSET=latin1 COMMENT='use to store product name info';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_SLIDES`
--

DROP TABLE IF EXISTS `PRODUCT_SLIDES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_SLIDES` (
  `product_slide_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `media_id` int(15) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `publish_time` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`product_slide_id`),
  UNIQUE KEY `product_slide_id` (`product_slide_id`),
  KEY `group_id` (`group_id`),
  KEY `brand_id` (`brand_id`),
  KEY `product_info_id` (`product_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PRODUCT_VIDEOS`
--

DROP TABLE IF EXISTS `PRODUCT_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PRODUCT_VIDEOS` (
  `product_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`product_video_id`),
  UNIQUE KEY `video_id` (`video_id`,`group_id`,`category_id`,`brand_id`,`product_info_id`,`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RELATED_VIDEOS`
--

DROP TABLE IF EXISTS `RELATED_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RELATED_VIDEOS` (
  `section_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`section_video_id`),
  UNIQUE KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SEARCH`
--

DROP TABLE IF EXISTS `SEARCH`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SEARCH` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `search` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '1',
  `category_name` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `is_brand` tinyint(1) NOT NULL,
  `is_model` tinyint(1) NOT NULL,
  `is_variant` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`search_id`),
  UNIQUE KEY `search` (`search`)
) ENGINE=InnoDB AUTO_INCREMENT=2459 DEFAULT CHARSET=latin1 COMMENT='used to product search';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SLIDESHOW`
--

DROP TABLE IF EXISTS `SLIDESHOW`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SLIDESHOW` (
  `slideshow_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_slide_id` int(15) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `meta_description` text NOT NULL,
  `video_img_id` int(11) NOT NULL DEFAULT '0',
  `video_img_path` varchar(255) NOT NULL,
  `media_id` int(11) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `content_type` tinyint(1) NOT NULL DEFAULT '0',
  `is_media_process` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`slideshow_id`),
  UNIQUE KEY `product_slide_id` (`product_slide_id`,`title`,`media_id`),
  UNIQUE KEY `product_slide_id_2` (`product_slide_id`,`title`,`media_id`)
) ENGINE=InnoDB AUTO_INCREMENT=389 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `STAGING_PRICE_VARIANT_VALUES`
--

DROP TABLE IF EXISTS `STAGING_PRICE_VARIANT_VALUES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `STAGING_PRICE_VARIANT_VALUES` (
  `price_variant` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `color_id` int(11) NOT NULL,
  `variant_value` varchar(25) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `default_city` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`price_variant`),
  UNIQUE KEY `product_price_unique` (`category_id`,`brand_id`,`product_id`,`country_id`,`state_id`,`city_id`,`variant_id`,`color_id`),
  KEY `product_id` (`product_id`),
  KEY `variant_value` (`variant_value`),
  KEY `default_city` (`default_city`),
  KEY `color_id` (`color_id`),
  KEY `variant_id` (`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6220 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `STAGING_PRODUCT_MASTER`
--

DROP TABLE IF EXISTS `STAGING_PRODUCT_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `STAGING_PRODUCT_MASTER` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_full_name` varchar(100) NOT NULL,
  `variant` varchar(100) NOT NULL DEFAULT '',
  `seo_path` varchar(20) NOT NULL,
  `product_desc` text NOT NULL,
  `year` year(4) NOT NULL,
  `media_id` int(11) NOT NULL,
  `video_path` text NOT NULL,
  `img_media_id` int(11) NOT NULL,
  `image_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `discontinue_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-discontinue ,1-continue',
  `discontinue_date` datetime NOT NULL,
  `arrival_date` date NOT NULL,
  `announced_date` datetime NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_unique` (`product_name`,`variant`,`category_id`,`brand_id`,`discontinue_flag`,`discontinue_date`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `discontinue_flag` (`discontinue_flag`),
  KEY `discontinue_date` (`discontinue_date`),
  KEY `product_name` (`product_name`),
  KEY `seo_path` (`seo_path`)
) ENGINE=InnoDB AUTO_INCREMENT=1196 DEFAULT CHARSET=latin1 COMMENT='table is used to store the product information.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TOP_COMPETITOR`
--

DROP TABLE IF EXISTS `TOP_COMPETITOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TOP_COMPETITOR` (
  `competitor_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '1',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_info_id` bigint(11) NOT NULL DEFAULT '0',
  `brand_ids` int(11) NOT NULL DEFAULT '0',
  `product_ids` int(11) NOT NULL DEFAULT '0',
  `product_info_ids` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`competitor_product_id`),
  KEY `brand_id` (`brand_id`),
  KEY `product_id` (`product_id`),
  KEY `product_info_id` (`product_info_id`),
  KEY `position` (`position`),
  KEY `product_ids` (`product_ids`),
  KEY `product_info_ids` (`product_info_ids`),
  KEY `brand_ids` (`brand_ids`)
) ENGINE=InnoDB AUTO_INCREMENT=128230 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TOP_ONCARS_COMPARISON`
--

DROP TABLE IF EXISTS `TOP_ONCARS_COMPARISON`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TOP_ONCARS_COMPARISON` (
  `top_compare_id` int(11) NOT NULL AUTO_INCREMENT,
  `oncars_compare_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`top_compare_id`),
  UNIQUE KEY `oncars_compare_id` (`oncars_compare_id`,`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TOP_PRODUCT`
--

DROP TABLE IF EXISTS `TOP_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TOP_PRODUCT` (
  `top_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`top_product_id`),
  UNIQUE KEY `unique_top_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TRENDING_PRODUCT`
--

DROP TABLE IF EXISTS `TRENDING_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TRENDING_PRODUCT` (
  `trending_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `product_position` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`trending_product_id`),
  UNIQUE KEY `unique_trending_product` (`category_id`,`brand_id`,`product_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UPCOMING_PRODUCT_MASTER`
--

DROP TABLE IF EXISTS `UPCOMING_PRODUCT_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UPCOMING_PRODUCT_MASTER` (
  `upcoming_product_id` int(50) NOT NULL AUTO_INCREMENT,
  `product_name_id` int(50) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `min_expected_price` int(11) NOT NULL,
  `min_expected_price_unit` int(11) NOT NULL,
  `max_expected_price` int(11) NOT NULL,
  `max_expected_price_unit` int(11) NOT NULL,
  `expected_date_text` varchar(50) NOT NULL,
  `expected_month` int(11) NOT NULL,
  `expected_year` int(11) NOT NULL,
  `short_description` text NOT NULL,
  `content` text NOT NULL,
  `category_id` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '0',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`upcoming_product_id`),
  UNIQUE KEY `product_name_id` (`product_name_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UPCOMING_PRODUCT_VIDEOS`
--

DROP TABLE IF EXISTS `UPCOMING_PRODUCT_VIDEOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UPCOMING_PRODUCT_VIDEOS` (
  `upcoming_product_video_id` int(50) NOT NULL AUTO_INCREMENT,
  `upcoming_product_id` varchar(50) NOT NULL,
  `category_id` tinyint(1) NOT NULL DEFAULT '1',
  `media_id` int(11) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `video_img_id` int(11) NOT NULL,
  `video_img_path` varchar(255) NOT NULL,
  `media_title` varchar(100) NOT NULL,
  `image_title` varchar(100) NOT NULL,
  `external_media_source` text NOT NULL,
  `media_source_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-oncars media,2-external media',
  `content_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-video,2-image,3-audio',
  `is_media_process` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`upcoming_product_video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_user_id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `source` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `source_user_id` (`source_user_id`,`user_name`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER_OVERALL_RATING`
--

DROP TABLE IF EXISTS `USER_OVERALL_RATING`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_OVERALL_RATING` (
  `overall_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `overallgrade` int(11) NOT NULL,
  `user_review_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`overall_id`),
  KEY `user_review_id` (`user_review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER_REVIEW`
--

DROP TABLE IF EXISTS `USER_REVIEW`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_REVIEW` (
  `user_review_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `location` varchar(50) NOT NULL,
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `product_info_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `running` varchar(250) NOT NULL,
  `year_manufacture` varchar(10) NOT NULL,
  `color` varchar(60) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'approve-1 ,reject-0',
  `review_agree` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`user_review_id`),
  UNIQUE KEY `title` (`title`),
  KEY `brand_id` (`brand_id`),
  KEY `category_id` (`category_id`),
  KEY `product_info_id` (`product_info_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER_REVIEW_ANSWER`
--

DROP TABLE IF EXISTS `USER_REVIEW_ANSWER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_REVIEW_ANSWER` (
  `usr_review_ans_id` int(11) NOT NULL AUTO_INCREMENT,
  `que_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `grade` int(11) NOT NULL COMMENT '1-for grade,0-comments',
  `user_review_id` int(11) NOT NULL,
  `is_rating` tinyint(1) NOT NULL DEFAULT '0',
  `is_comment_ans` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`usr_review_ans_id`),
  KEY `user_review_id` (`user_review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER_REVIEW_LIKES`
--

DROP TABLE IF EXISTS `USER_REVIEW_LIKES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_REVIEW_LIKES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `like_yes` int(11) NOT NULL DEFAULT '0',
  `like_no` int(11) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `review_id` (`review_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER_REVIEW_QUESTIONAIRE`
--

DROP TABLE IF EXISTS `USER_REVIEW_QUESTIONAIRE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_REVIEW_QUESTIONAIRE` (
  `queid` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `quename` varchar(250) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `algorithm` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`queid`),
  UNIQUE KEY `quename` (`quename`),
  KEY `queid` (`queid`),
  KEY `algorithm` (`algorithm`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='table is used to user review questionaire';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER_REVIEW_QUESTIONAIRE_ANSWER`
--

DROP TABLE IF EXISTS `USER_REVIEW_QUESTIONAIRE_ANSWER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_REVIEW_QUESTIONAIRE_ANSWER` (
  `ans_id` int(11) NOT NULL AUTO_INCREMENT,
  `queid` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `ans` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`ans_id`),
  KEY `queid` (`queid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='table is used to user review ANSWER';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VIDEO_GALLERY`
--

DROP TABLE IF EXISTS `VIDEO_GALLERY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VIDEO_GALLERY` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `type_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `media_path` text NOT NULL,
  `external_media_source` varchar(255) NOT NULL,
  `media_source_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-ONCARS MEDIA,2-EXTERNAL MEDIA',
  `video_img_id` int(11) NOT NULL,
  `video_img_path` text NOT NULL,
  `content_type` tinyint(2) NOT NULL,
  `is_media_process` tinyint(2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ordering` tinyint(2) NOT NULL,
  `publish_time` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`video_id`),
  UNIQUE KEY `video_id` (`video_id`,`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-24 15:28:13
