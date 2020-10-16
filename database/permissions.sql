-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 192.168.2.4    Database: ly_crm
-- ------------------------------------------------------
-- Server version	5.7.23

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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,0,'系统管理','','&#xe6ae;','web','2019-11-11 16:53:41','2019-11-21 10:37:33',1,15),(2,1,'系统设置','admin.settings',NULL,'web','2019-11-11 17:57:14','2019-11-13 17:52:30',1,0),(3,4,'用户管理','admin.users.index',NULL,'web','2019-11-12 11:17:19','2019-11-21 14:01:42',1,10),(4,0,'权限分配',NULL,'&#xe70b;','web','2019-11-12 11:18:46','2019-11-21 14:21:37',1,10),(5,0,'客户管理',NULL,'&#xe82a;','web','2019-11-12 11:56:29','2019-11-19 11:40:45',1,1),(9,4,'角色管理','admin.roles.index',NULL,'web','2019-11-13 17:21:48','2019-11-21 14:06:02',1,20),(10,4,'添加角色','admin.roles.create',NULL,'web','2019-11-13 17:55:46','2019-11-21 14:06:18',0,21),(11,4,'编辑角色','admin.roles.edit',NULL,'web','2019-11-13 17:56:13','2019-11-21 14:10:00',0,23),(12,4,'删除角色','admin.roles.destroy',NULL,'web','2019-11-13 17:56:42','2019-11-21 14:11:49',0,25),(13,4,'添加用户','admin.users.create',NULL,'web','2019-11-18 15:35:59','2019-11-21 14:02:07',0,11),(14,4,'编辑用户','admin.users.edit',NULL,'web',NULL,'2019-11-21 14:03:20',0,13),(15,4,'删除用户','admin.users.destroy',NULL,'web',NULL,'2019-11-21 14:05:25',0,15),(16,0,'订单管理',NULL,'&#xe698;','web','2019-11-19 11:41:15','2019-11-21 10:37:15',1,5),(17,0,'系统首页',NULL,NULL,'web','2019-11-21 10:32:22','2019-11-21 11:06:25',0,0),(18,17,'首页','admin.index',NULL,'web','2019-11-21 11:02:45','2019-11-21 11:05:00',1,0),(19,17,'Dashboard','admin.dashboard',NULL,'web','2019-11-21 11:05:34','2019-11-21 11:05:34',1,0),(20,4,'权限菜单','admin.permissions.index',NULL,'web','2019-11-21 11:11:44','2019-11-21 11:29:07',0,0),(21,4,'添加权限菜单','admin.permissions.create',NULL,'web','2019-11-21 11:14:14','2019-11-21 11:17:55',0,0),(22,4,'编辑权限菜单','admin.permissions.edit',NULL,'web','2019-11-21 11:17:01','2019-11-21 11:17:55',0,0),(23,4,'删除权限菜单','admin.permissions.destroy',NULL,'web','2019-11-21 11:17:39','2019-11-21 11:17:39',0,0),(24,4,'保存添加用户','admin.users.store',NULL,'web','2019-11-21 14:02:59','2019-11-21 14:03:07',0,12),(25,4,'保存编辑用户','admin.users.update',NULL,'web','2019-11-21 14:04:06','2019-11-21 14:04:06',0,14),(26,4,'保存添加角色','admin.roles.store',NULL,'web','2019-11-21 14:07:06','2019-11-21 14:07:06',0,22),(27,4,'保存编辑角色','admin.roles.update',NULL,'web','2019-11-21 14:11:26','2019-11-21 14:11:26',0,24),(28,5,'客户列表','admin.customer.index',NULL,'web','2019-11-21 14:37:05','2019-11-21 14:37:05',1,0),(29,5,'添加客户','admin.customer.create',NULL,'web','2019-11-21 15:34:11','2019-11-21 15:34:11',0,0),(30,5,'保存添加客户','admin.customer.store',NULL,'web','2019-11-21 15:34:46','2019-11-21 15:34:46',0,0),(31,5,'编辑客户','admin.customer.edit',NULL,'web','2019-11-21 15:35:38','2019-11-21 15:35:38',0,0),(32,5,'保存编辑客户','admin.customer.update',NULL,'web','2019-11-21 15:36:14','2019-11-21 17:24:47',0,0),(33,5,'删除客户','admin.customer.destroy',NULL,'web','2019-11-21 15:36:56','2019-11-21 15:36:56',0,0),(34,5,'客户标签','admin.tags.index',NULL,'web','2019-11-21 15:36:56',NULL,1,0),(35,5,'添加标签','admin.tags.create',NULL,'web','2019-11-21 15:36:56',NULL,0,0),(36,5,'保存添加标签','admin.tags.store',NULL,'web',NULL,NULL,0,0),(37,5,'编辑标签','admin.tags.edit',NULL,'web',NULL,NULL,0,0),(38,5,'保存编辑标签','admin.tags.update',NULL,'web',NULL,NULL,0,0),(39,5,'删除标签','admin.tags.destroy',NULL,'web',NULL,NULL,0,0),(40,5,'客户来源','admin.source.index',NULL,'web',NULL,NULL,1,0),(41,5,'添加来源','admin.source.create',NULL,'web',NULL,NULL,0,0),(42,5,'保存添加来源','admin.source.store',NULL,'web',NULL,NULL,0,0),(43,5,'编辑来源','admin.source.edit',NULL,'web',NULL,NULL,0,0),(44,5,'保存编辑来源','admin.source.update',NULL,'web',NULL,NULL,0,0),(45,5,'删除来源','admin.source.destroy',NULL,'web',NULL,NULL,0,0),(46,1,'查看全部数据',NULL,NULL,'web','2019-11-22 16:32:55','2019-11-22 16:32:55',0,0),(47,0,'提醒管理',NULL,'&#xe611;','web','2019-11-23 03:15:52','2019-11-23 03:15:52',1,8),(50,47,'回访提醒','admin.remind.index',NULL,'web',NULL,NULL,1,0),(51,47,'添加提醒','admin.remind.create',NULL,'web',NULL,NULL,0,0),(52,47,'保存添加提醒','admin.remind.store',NULL,'web',NULL,NULL,0,0),(53,47,'编辑提醒','admin.remind.edit',NULL,'web',NULL,NULL,0,0),(54,47,'保存编辑提醒','admin.remind.update',NULL,'web',NULL,NULL,0,0),(55,47,'删除提醒','admin.remind.destroy',NULL,'web',NULL,NULL,0,0),(56,5,'退回客户','admin.customer.return',NULL,'web','2019-11-29 10:55:21','2019-11-29 10:55:21',0,0),(57,5,'客户公海','admin.sea.index',NULL,'web','2019-11-29 10:56:02','2019-11-29 10:56:02',1,0),(58,5,'分配客户','admin.sea.update',NULL,'web','2019-11-29 10:56:44','2019-11-29 10:56:44',0,0),(59,5,'领取客户','admin.sea.store',NULL,'web','2019-11-29 10:57:06','2019-11-29 10:57:06',0,0),(60,47,'查看提醒','admin.remind.show',NULL,'web','2019-11-29 10:59:50','2019-11-29 10:59:50',0,0),(61,16,'订单列表','admin.orders.index',NULL,'web',NULL,NULL,1,0),(62,16,'添加订单','admin.orders.create',NULL,'web',NULL,NULL,0,0),(63,16,'保存添加订单','admin.orders.store',NULL,'web',NULL,NULL,0,0),(64,16,'编辑订单','admin.orders.edit',NULL,'web',NULL,NULL,0,0),(65,16,'保存编辑订单','admin.orders.update',NULL,'web',NULL,NULL,0,0),(66,16,'删除订单','admin.orders.destroy',NULL,'web',NULL,NULL,0,0),(67,16,'订单详情','admin.orders.show',NULL,'web',NULL,NULL,0,0),(68,16,'回款记录','admin.orders.back',NULL,'web',NULL,NULL,1,0),(69,16,'合同列表','admin.contract.index',NULL,'web',NULL,NULL,1,0),(70,16,'添加合同','admin.contract.create',NULL,'web',NULL,NULL,0,0),(71,16,'保存添加合同','admin.contract.store',NULL,'web',NULL,NULL,0,0),(72,16,'编辑合同','admin.contract.edit',NULL,'web',NULL,NULL,0,0),(73,16,'保存编辑合同','admin.contract.update',NULL,'web',NULL,NULL,0,0),(74,16,'删除合同','admin.contract.destroy',NULL,'web',NULL,NULL,0,0),(75,16,'业务种类','admin.goods.index',NULL,'web',NULL,NULL,1,0),(76,16,'添加业务种类','admin.goods.create',NULL,'web',NULL,NULL,0,0),(77,16,'保存添加业务种类','admin.goods.store',NULL,'web',NULL,NULL,0,0),(78,16,'编辑业务种类','admin.goods.edit',NULL,'web',NULL,NULL,0,0),(79,16,'保存编辑业务种类','admin.goods.update',NULL,'web',NULL,NULL,0,0),(80,16,'删除业务种类','admin.goods.destroy',NULL,'web',NULL,NULL,0,0);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-29 11:18:28
