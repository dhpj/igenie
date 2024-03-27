-- MySQL dump 10.13  Distrib 5.5.29, for Linux (x86_64)
--
-- Host: localhost    Database: dhn
-- ------------------------------------------------------
-- Server version	5.5.29-log

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
-- Table structure for table `PR_TRAN`
--

DROP TABLE IF EXISTS `PR_TRAN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PR_TRAN` (
  `PR_NUM` bigint(20) NOT NULL AUTO_INCREMENT,
  `PR_SENDDATE` datetime DEFAULT NULL,
  `PR_ID` varchar(20) DEFAULT NULL,
  `PR_STATUS` varchar(1) NOT NULL DEFAULT '0',
  `PR_RSLT` varchar(4) DEFAULT '0000',
  `PR_PHONE` varchar(20) NOT NULL,
  `PR_CALLBACK` varchar(20) DEFAULT NULL,
  `PR_RSLTDATE` datetime DEFAULT NULL,
  `PR_MODIFIED` datetime DEFAULT NULL,
  `PR_MSG` varchar(2000) DEFAULT NULL,
  `PR_SUBJECT` varchar(160) DEFAULT NULL,
  `PR_REALSENDDATE` datetime DEFAULT NULL,
  `PR_FINISHDATE` datetime DEFAULT NULL,
  `PR_ETC1` varchar(160) DEFAULT NULL,
  `PR_ETC2` varchar(160) DEFAULT NULL,
  `PR_ETC3` varchar(160) DEFAULT NULL,
  `PR_ETC4` varchar(160) DEFAULT NULL,
  `PR_ETC5` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`PR_NUM`),
  KEY `PR_SENDDATE` (`PR_SENDDATE`),
  KEY `PR_STATUS` (`PR_STATUS`),
  KEY `PR_PHONE` (`PR_PHONE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PR_TRAN`
--

LOCK TABLES `PR_TRAN` WRITE;
/*!40000 ALTER TABLE `PR_TRAN` DISABLE KEYS */;
/*!40000 ALTER TABLE `PR_TRAN` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TBL_REQUEST`
--

DROP TABLE IF EXISTS `TBL_REQUEST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TBL_REQUEST` (
  `MSGID` varchar(20) NOT NULL,
  `AD_FLAG` varchar(1) DEFAULT NULL,
  `BUTTON1` longtext,
  `BUTTON2` longtext,
  `BUTTON3` longtext,
  `BUTTON4` longtext,
  `BUTTON5` longtext,
  `IMAGE_LINK` longtext,
  `IMAGE_URL` longtext,
  `MESSAGE_TYPE` varchar(2) DEFAULT NULL,
  `MSG` longtext NOT NULL,
  `MSG_SMS` longtext,
  `ONLY_SMS` varchar(1) DEFAULT NULL,
  `P_COM` varchar(2) DEFAULT NULL,
  `P_INVOICE` varchar(100) DEFAULT NULL,
  `PHN` varchar(15) NOT NULL,
  `PROFILE` varchar(50) DEFAULT NULL,
  `REG_DT` datetime NOT NULL,
  `REMARK1` varchar(50) DEFAULT NULL,
  `REMARK2` varchar(50) DEFAULT NULL,
  `REMARK3` varchar(50) DEFAULT NULL,
  `REMARK4` varchar(50) DEFAULT NULL,
  `REMARK5` varchar(50) DEFAULT NULL,
  `RESERVE_DT` varchar(14) NOT NULL,
  `S_CODE` varchar(2) DEFAULT NULL,
  `SMS_KIND` varchar(1) DEFAULT NULL,
  `SMS_LMS_TIT` varchar(120) DEFAULT NULL,
  `SMS_SENDER` varchar(15) DEFAULT NULL,
  `TMPL_ID` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`MSGID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TBL_REQUEST`
--

LOCK TABLES `TBL_REQUEST` WRITE;
/*!40000 ALTER TABLE `TBL_REQUEST` DISABLE KEYS */;
/*!40000 ALTER TABLE `TBL_REQUEST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TBL_REQUEST_RESULT`
--

DROP TABLE IF EXISTS `TBL_REQUEST_RESULT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TBL_REQUEST_RESULT` (
  `MSGID` varchar(20) NOT NULL,
  `AD_FLAG` varchar(1) DEFAULT NULL,
  `BUTTON1` longtext,
  `BUTTON2` longtext,
  `BUTTON3` longtext,
  `BUTTON4` longtext,
  `BUTTON5` longtext,
  `CODE` varchar(4) DEFAULT NULL,
  `IMAGE_LINK` longtext,
  `IMAGE_URL` longtext,
  `KIND` varchar(1) DEFAULT NULL,
  `MESSAGE` longtext,
  `MESSAGE_TYPE` varchar(2) DEFAULT NULL,
  `MSG` longtext NOT NULL,
  `MSG_SMS` longtext,
  `ONLY_SMS` varchar(1) DEFAULT NULL,
  `P_COM` varchar(2) DEFAULT NULL,
  `P_INVOICE` varchar(100) DEFAULT NULL,
  `PHN` varchar(15) NOT NULL,
  `PROFILE` varchar(50) DEFAULT NULL,
  `REG_DT` datetime NOT NULL,
  `REMARK1` varchar(50) DEFAULT NULL,
  `REMARK2` varchar(50) DEFAULT NULL,
  `REMARK3` varchar(50) DEFAULT NULL,
  `REMARK4` varchar(50) DEFAULT NULL,
  `REMARK5` varchar(50) DEFAULT NULL,
  `RES_DT` datetime DEFAULT NULL,
  `RESERVE_DT` varchar(14) NOT NULL,
  `RESULT` varchar(1) DEFAULT NULL,
  `S_CODE` varchar(2) DEFAULT NULL,
  `SMS_KIND` varchar(1) DEFAULT NULL,
  `SMS_LMS_TIT` varchar(120) DEFAULT NULL,
  `SMS_SENDER` varchar(15) DEFAULT NULL,
  `SYNC` varchar(1) NOT NULL,
  `TMPL_ID` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`MSGID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TBL_REQUEST_RESULT`
--

LOCK TABLES `TBL_REQUEST_RESULT` WRITE;
/*!40000 ALTER TABLE `TBL_REQUEST_RESULT` DISABLE KEYS */;
INSERT INTO `TBL_REQUEST_RESULT` VALUES ('1_63','Y','{\"type\":\"WL\",\"name\":\"친구톡test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'E111','http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm','http://dhn.webthink.co.kr/pop/2017/11/b2878669535576b114b12d3bfbcf8ba3.jpg','','RequestImgNotFound','ft','(광고)\n\n금일 오전 특가 행사 진행합니다.\n\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-30 10:13:48','1',NULL,NULL,'83','L',NULL,'00000000000000','N',NULL,'','','0552389456','Y',''),('1_64','Y','{\"type\":\"WL\",\"name\":\"친구톡test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'E111','http://bizalimtalk.kr','http://img.bizmsg.kr/bd7ff995ee.jpg','','RequestImgNotFound','ft','(광고)\n\n이미지 등록 테스트\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-30 13:07:19','1',NULL,NULL,'84','L',NULL,'00000000000000','N',NULL,'','','0552389456','Y',''),('1_65','Y','{\"type\":\"WL\",\"name\":\"에이치엠피\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@에이치엠피\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'E102','http://bizalimtalk.kr','http://img.bizmsg.kr/bd7ff995ee.jpg',NULL,'InvalidProfileKey','ft','(광고)\n\n이미지 테스트 합니다.\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','2f637c7c03d0a88084ab866fd7407a7e6f46e8fd','2017-11-30 13:34:13','1',NULL,NULL,'85','L',NULL,'00000000000000','N',NULL,'','','01065748654','Y',''),('1_66','Y','{\"type\":\"WL\",\"name\":\"에이치엠피\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@에이치엠피\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000','http://bizalimtalk.kr','http://img.bizmsg.kr/bd7ff995ee.jpg','K','','ft','(광고)\n\n이미지 테스트 합니다.\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','2f637c7c03d0a88084ab866fd7407a7e6f46e8fd','2017-11-30 13:36:59','1',NULL,NULL,'86','L',NULL,'00000000000000','Y',NULL,'','','01065748654','Y',''),('1_67','N','{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http:\\/\\/www.bizalimtalk.kr\"}','','','','','K101','','','K','NotAvailableSendMessage','at','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','N',NULL,NULL,'821027877110','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-30 17:08:08','1',NULL,NULL,'90','Y',NULL,'00000000000000','N',NULL,'','[카카오프렌즈샵] 주문완료 안내\n□ ',NULL,'Y','alimtalktest_004'),('1_68','N','{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http:\\/\\/www.bizalimtalk.kr\"}','','','','','K101','','','K','NotAvailableSendMessage','at','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290101\n□ 배송지 : 창원시 진해구\n□ 배송예정일 : 11월 30일\n□ 결제금액 :: 13,600원','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290101\n□ 배송지 : 창원시 진해구\n□ 배송예정일 : 11월 30일\n□ 결제금액 :: 13,600원','N',NULL,NULL,'821093111339','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-30 17:08:08','1',NULL,NULL,'90','Y',NULL,'00000000000000','N',NULL,'','[카카오프렌즈샵] 주문완료 안내\n□ ',NULL,'Y','alimtalktest_004'),('2_1','Y','{\"type\":\"WL\",\"name\":\"(주)웹싱크\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@webthink\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'E102',NULL,NULL,NULL,'InvalidProfileKey','ft','(광고)\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n수신거부 : 홈>친구차단','(광고) (주)웹싱크\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n무료수신거부 : ','N',NULL,NULL,'821076694556','761283e30af0bba98fb0c802a60f6b06619210ff','2017-11-30 09:24:36','1',NULL,NULL,'81','L',NULL,'00000000000000','N',NULL,'','(광고) (주)웹싱크\n\n정부가 100','01065748654','Y',''),('2_2','Y','{\"type\":\"WL\",\"name\":\"(주)웹싱크\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@webthink\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'E102',NULL,NULL,NULL,'InvalidProfileKey','ft','(광고)\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n수신거부 : 홈>친구차단','(광고) (주)웹싱크\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n무료수신거부 : ','N',NULL,NULL,'821076694556','761283e30af0bba98fb0c802a60f6b06619210ff','2017-11-30 09:25:04','1',NULL,NULL,'82','L',NULL,'00000000000000','N',NULL,'','(광고) (주)웹싱크\n\n정부가 100','01065748654','Y','');
/*!40000 ALTER TABLE `TBL_REQUEST_RESULT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_ab_admin`
--

DROP TABLE IF EXISTS `cb_ab_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_ab_admin` (
  `ab_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '�ĺ���ȣ',
  `ab_name` varchar(100) DEFAULT '' COMMENT '������',
  `ab_tel` varchar(15) DEFAULT '' COMMENT '��ȭ��ȣ',
  `ab_kind` varchar(50) DEFAULT '' COMMENT '����',
  `ab_status` char(1) DEFAULT '1' COMMENT '����',
  `ab_memo` varchar(1000) DEFAULT '' COMMENT '�޸�',
  `ab_group` varchar(1000) DEFAULT '' COMMENT '�����з�',
  `ab_send_count` bigint(20) DEFAULT '0' COMMENT '�߼�Ƚ��',
  `ab_datetime` datetime DEFAULT NULL COMMENT '����Ͻ�',
  `ab_last_datetime` datetime DEFAULT NULL COMMENT '�����߼��Ͻ�',
  PRIMARY KEY (`ab_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_ab_admin`
--

LOCK TABLES `cb_ab_admin` WRITE;
/*!40000 ALTER TABLE `cb_ab_admin` DISABLE KEYS */;
INSERT INTO `cb_ab_admin` VALUES (1,'변용식','01093111339','','1','','',0,'2017-11-28 11:29:04',NULL),(2,'김정미','01027877110','','1','','',0,'2017-11-28 11:29:04',NULL),(3,'공지아','01054117500','','1','','',0,'2017-11-28 11:29:04',NULL),(4,'강영식','01065748654','','1','','',0,'2017-11-29 16:52:20',NULL);
/*!40000 ALTER TABLE `cb_ab_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_ab_webthink`
--

DROP TABLE IF EXISTS `cb_ab_webthink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_ab_webthink` (
  `ab_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `ab_name` varchar(100) DEFAULT '' COMMENT '고객명',
  `ab_tel` varchar(15) DEFAULT '' COMMENT '전화번호',
  `ab_kind` varchar(50) DEFAULT '' COMMENT '구분',
  `ab_status` char(1) DEFAULT '1' COMMENT '상태',
  `ab_memo` varchar(1000) DEFAULT '' COMMENT '메모',
  `ab_group` varchar(1000) DEFAULT '' COMMENT '고객분류',
  `ab_send_count` bigint(20) DEFAULT '0' COMMENT '발송횟수',
  `ab_datetime` datetime DEFAULT NULL COMMENT '등록일시',
  `ab_last_datetime` datetime DEFAULT NULL COMMENT '최종발송일시',
  PRIMARY KEY (`ab_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_ab_webthink`
--

LOCK TABLES `cb_ab_webthink` WRITE;
/*!40000 ALTER TABLE `cb_ab_webthink` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_ab_webthink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_ab_webthink7`
--

DROP TABLE IF EXISTS `cb_ab_webthink7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_ab_webthink7` (
  `ab_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `ab_name` varchar(100) DEFAULT '' COMMENT '고객명',
  `ab_tel` varchar(15) DEFAULT '' COMMENT '전화번호',
  `ab_kind` varchar(50) DEFAULT '' COMMENT '구분',
  `ab_status` char(1) DEFAULT '1' COMMENT '상태',
  `ab_memo` varchar(1000) DEFAULT '' COMMENT '메모',
  `ab_group` varchar(1000) DEFAULT '' COMMENT '고객분류',
  `ab_send_count` bigint(20) DEFAULT '0' COMMENT '발송횟수',
  `ab_datetime` datetime DEFAULT NULL COMMENT '등록일시',
  `ab_last_datetime` datetime DEFAULT NULL COMMENT '최종발송일시',
  PRIMARY KEY (`ab_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_ab_webthink7`
--

LOCK TABLES `cb_ab_webthink7` WRITE;
/*!40000 ALTER TABLE `cb_ab_webthink7` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_ab_webthink7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_amt_admin`
--

DROP TABLE IF EXISTS `cb_amt_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_amt_admin` (
  `amt_datetime` datetime DEFAULT NULL COMMENT '�߻��Ͻ�',
  `amt_kind` char(1) DEFAULT '' COMMENT '����(����,���,����ȯ��,������)',
  `amt_amount` decimal(14,2) DEFAULT '0.00' COMMENT '�ݾ�',
  `amt_deposit` decimal(14,2) DEFAULT '0.00' COMMENT '예치금사용금액',
  `amt_point` decimal(14,2) DEFAULT '0.00' COMMENT '포인트사용금액',
  `amt_memo` varchar(100) DEFAULT '' COMMENT '����',
  `amt_reason` varchar(50) DEFAULT '' COMMENT '�ٰ��ڷ�'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_amt_admin`
--

LOCK TABLES `cb_amt_admin` WRITE;
/*!40000 ALTER TABLE `cb_amt_admin` DISABLE KEYS */;
INSERT INTO `cb_amt_admin` VALUES ('2017-11-27 15:12:08','1',10000.00,0.00,0.00,'예치금 적립 (무통장입금)','2017111317333229'),('2017-11-27 15:37:19','1',10000.00,0.00,0.00,'예치금 적립 (신용카드결제)','2017112715303344'),('2017-11-27 20:06:53','F',9.50,0.00,0.00,'친구톡발송(821093111339)','1_19'),('2017-11-27 21:23:22','F',9.50,0.00,0.00,'친구톡발송(821093111339)','1_20'),('2017-11-27 21:33:01','F',9.50,0.00,0.00,'친구톡발송(821093111339)','1_21'),('2017-11-28 10:15:34','F',9.50,0.00,0.00,'친구톡발송(821093111339)','1_22'),('2017-11-28 10:27:16','F',9.50,0.00,0.00,'친구톡발송(821093111339)','1_23'),('2017-11-28 10:29:36','F',9.50,0.00,9.50,'친구톡발송(821093111339)','1_24'),('2017-11-28 10:48:15','F',9.50,0.00,9.50,'친구톡발송(821093111339)','1_25'),('2017-11-28 10:48:15','F',9.50,0.00,9.50,'친구톡발송(821054117500)','1_26'),('2017-11-28 10:48:49','F',9.50,0.00,9.50,'친구톡발송(821093111339)','1_28'),('2017-11-28 10:48:49','F',9.50,0.00,9.50,'친구톡발송(821054117500)','1_29'),('2017-11-28 10:48:49','F',9.50,0.00,9.50,'친구톡발송(821027877110)','1_30'),('2017-11-28 11:30:56','F',9.50,0.00,9.50,'친구톡발송(821093111339)','1_31'),('2017-11-28 20:56:36','A',15.00,0.00,15.00,'알림톡발송(821093111339)','1_42'),('2017-11-28 20:56:36','F',15.00,0.00,15.00,'친구톡발송(821093111339)','1_43'),('2017-11-28 20:56:36','F',15.00,0.00,15.00,'친구톡발송(821093111339)','1_46');
/*!40000 ALTER TABLE `cb_amt_admin` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ayt`@`%`*/ /*!50003 TRIGGER `ins_cb_amt_admin` BEFORE INSERT ON `cb_amt_admin`
  FOR EACH ROW
BEGIN
	if NEW.amt_kind='1' then
		/* 예치금 입금 */
		update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='admin';
		set NEW.amt_deposit := NEW.amt_amount;
	else
		/* 예치금과 포인트를 가져온다. */
		select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='admin';

		/* 차감 : 포인트 우선 차감 */
		IF NEW.amt_kind='A' or NEW.amt_kind='F' or NEW.amt_kind='I' or NEW.amt_kind='S' or NEW.amt_kind='L' or NEW.amt_kind='M' or NEW.amt_kind='P' or NEW.amt_kind='R' or NEW.amt_kind='O' THEN
			if @nPoint > 0 then
				set @nPoint := @nPoint - NEW.amt_amount;
				if @nPoint < 0 then
					set NEW.amt_point := NEW.amt_amount - @nPoint;
					set NEW.amt_deposit := abs(@nPoint);
					set @nDeposit := @nDeposit + @nPoint;
					set @nPoint := 0;
				else
					set NEW.amt_point := NEW.amt_amount;
				end if;
			else
				set @nDeposit := @nDeposit - NEW.amt_amount;
				set NEW.amt_deposit := NEW.amt_amount;
			end if;
		else
		/* 환불 : 포인트로 환불 */
			set @nPoint := @nPoint + NEW.amt_amount;
			set NEW.amt_point := NEW.amt_amount;
		end if;
		/* 회원정보 업데이트 */
		update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='admin';
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cb_amt_darkskill`
--

DROP TABLE IF EXISTS `cb_amt_darkskill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_amt_darkskill` (
  `amt_datetime` datetime DEFAULT NULL COMMENT '발생일시',
  `amt_kind` char(1) DEFAULT '' COMMENT '구분(충전,사용,현금환불,사용취소)',
  `amt_amount` decimal(14,2) DEFAULT '0.00' COMMENT '금액',
  `amt_deposit` decimal(14,2) DEFAULT '0.00' COMMENT '예치금사용금액',
  `amt_point` decimal(14,2) DEFAULT '0.00' COMMENT '포인트사용금액',
  `amt_memo` varchar(100) DEFAULT '' COMMENT '내용',
  `amt_reason` varchar(50) DEFAULT '' COMMENT '근거자료'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_amt_darkskill`
--

LOCK TABLES `cb_amt_darkskill` WRITE;
/*!40000 ALTER TABLE `cb_amt_darkskill` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_amt_darkskill` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`dhn`@`localhost`*/ /*!50003 TRIGGER `ins_cb_amt_darkskill` BEFORE INSERT ON `cb_amt_darkskill`
			  FOR EACH ROW
			BEGIN
				if NEW.amt_kind='1' then
					update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='darkskill';
					set NEW.amt_deposit := NEW.amt_amount;
				else
					select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='darkskill';
					IF NEW.amt_kind='A' or NEW.amt_kind='F' or NEW.amt_kind='I' or NEW.amt_kind='S' or NEW.amt_kind='L' or NEW.amt_kind='M' or NEW.amt_kind='P' or NEW.amt_kind='R' or NEW.amt_kind='O' THEN
						if @nPoint > 0 then
							set @nPoint := @nPoint - NEW.amt_amount;
							if @nPoint < 0 then
								set NEW.amt_point := NEW.amt_amount - @nPoint;
								set NEW.amt_deposit := abs(@nPoint);
								set @nDeposit := @nDeposit + @nPoint;
								set @nPoint := 0;
							else
								set NEW.amt_point := NEW.amt_amount;
							end if;
						else
							set @nDeposit := @nDeposit - NEW.amt_amount;
							set NEW.amt_deposit := NEW.amt_amount;
						end if;
					else
						set @nPoint := @nPoint + NEW.amt_amount;
						set NEW.amt_point := NEW.amt_amount;
					end if;
					update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='darkskill';
				END IF;
			END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cb_amt_dhn`
--

DROP TABLE IF EXISTS `cb_amt_dhn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_amt_dhn` (
  `amt_datetime` datetime DEFAULT NULL COMMENT '발생일시',
  `amt_kind` char(1) DEFAULT '' COMMENT '구분(충전,사용,현금환불,사용취소)',
  `amt_amount` decimal(14,2) DEFAULT '0.00' COMMENT '금액',
  `amt_deposit` decimal(14,2) DEFAULT '0.00' COMMENT '예치금사용금액',
  `amt_point` decimal(14,2) DEFAULT '0.00' COMMENT '포인트사용금액',
  `amt_memo` varchar(100) DEFAULT '' COMMENT '내용',
  `amt_reason` varchar(50) DEFAULT '' COMMENT '근거자료'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_amt_dhn`
--

LOCK TABLES `cb_amt_dhn` WRITE;
/*!40000 ALTER TABLE `cb_amt_dhn` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_amt_dhn` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`dhn`@`localhost`*/ /*!50003 TRIGGER `ins_cb_amt_dhn` BEFORE INSERT ON `cb_amt_dhn`
			  FOR EACH ROW
			BEGIN
				if NEW.amt_kind='1' then
					update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='dhn';
					set NEW.amt_deposit := NEW.amt_amount;
				else
					select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='dhn';
					IF NEW.amt_kind='A' or NEW.amt_kind='F' or NEW.amt_kind='I' or NEW.amt_kind='S' or NEW.amt_kind='L' or NEW.amt_kind='M' or NEW.amt_kind='P' or NEW.amt_kind='R' or NEW.amt_kind='O' THEN
						if @nPoint > 0 then
							set @nPoint := @nPoint - NEW.amt_amount;
							if @nPoint < 0 then
								set NEW.amt_point := NEW.amt_amount - @nPoint;
								set NEW.amt_deposit := abs(@nPoint);
								set @nDeposit := @nDeposit + @nPoint;
								set @nPoint := 0;
							else
								set NEW.amt_point := NEW.amt_amount;
							end if;
						else
							set @nDeposit := @nDeposit - NEW.amt_amount;
							set NEW.amt_deposit := NEW.amt_amount;
						end if;
					else
						set @nPoint := @nPoint + NEW.amt_amount;
						set NEW.amt_point := NEW.amt_amount;
					end if;
					update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='dhn';
				END IF;
			END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cb_amt_webthink`
--

DROP TABLE IF EXISTS `cb_amt_webthink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_amt_webthink` (
  `amt_datetime` datetime DEFAULT NULL COMMENT '발생일시',
  `amt_kind` char(1) DEFAULT '' COMMENT '구분(충전,사용,현금환불,사용취소)',
  `amt_amount` decimal(14,2) DEFAULT '0.00' COMMENT '금액',
  `amt_deposit` decimal(14,2) DEFAULT '0.00' COMMENT '예치금사용금액',
  `amt_point` decimal(14,2) DEFAULT '0.00' COMMENT '포인트사용금액',
  `amt_memo` varchar(100) DEFAULT '' COMMENT '내용',
  `amt_reason` varchar(50) DEFAULT '' COMMENT '근거자료'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_amt_webthink`
--

LOCK TABLES `cb_amt_webthink` WRITE;
/*!40000 ALTER TABLE `cb_amt_webthink` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_amt_webthink` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`dhn`@`localhost`*/ /*!50003 TRIGGER `ins_cb_amt_webthink` BEFORE INSERT ON `cb_amt_webthink`
			  FOR EACH ROW
			BEGIN
				if NEW.amt_kind='1' then
					update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='webthink';
					set NEW.amt_deposit := NEW.amt_amount;
				else
					select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='webthink';
					IF NEW.amt_kind='A' or NEW.amt_kind='F' or NEW.amt_kind='I' or NEW.amt_kind='S' or NEW.amt_kind='L' or NEW.amt_kind='M' or NEW.amt_kind='P' or NEW.amt_kind='R' or NEW.amt_kind='O' THEN
						if @nPoint > 0 then
							set @nPoint := @nPoint - NEW.amt_amount;
							if @nPoint < 0 then
								set NEW.amt_point := NEW.amt_amount - @nPoint;
								set NEW.amt_deposit := abs(@nPoint);
								set @nDeposit := @nDeposit + @nPoint;
								set @nPoint := 0;
							else
								set NEW.amt_point := NEW.amt_amount;
							end if;
						else
							set @nDeposit := @nDeposit - NEW.amt_amount;
							set NEW.amt_deposit := NEW.amt_amount;
						end if;
					else
						set @nPoint := @nPoint + NEW.amt_amount;
						set NEW.amt_point := NEW.amt_amount;
					end if;
					update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='webthink';
				END IF;
			END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cb_amt_webthink7`
--

DROP TABLE IF EXISTS `cb_amt_webthink7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_amt_webthink7` (
  `amt_datetime` datetime DEFAULT NULL COMMENT '발생일시',
  `amt_kind` char(1) DEFAULT '' COMMENT '구분(충전,사용,현금환불,사용취소)',
  `amt_amount` decimal(14,2) DEFAULT '0.00' COMMENT '금액',
  `amt_deposit` decimal(14,2) DEFAULT '0.00' COMMENT '예치금사용금액',
  `amt_point` decimal(14,2) DEFAULT '0.00' COMMENT '포인트사용금액',
  `amt_memo` varchar(100) DEFAULT '' COMMENT '내용',
  `amt_reason` varchar(50) DEFAULT '' COMMENT '근거자료'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_amt_webthink7`
--

LOCK TABLES `cb_amt_webthink7` WRITE;
/*!40000 ALTER TABLE `cb_amt_webthink7` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_amt_webthink7` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`dhn`@`localhost`*/ /*!50003 TRIGGER `ins_cb_amt_webthink7` BEFORE INSERT ON `cb_amt_webthink7`
			  FOR EACH ROW
			BEGIN
				if NEW.amt_kind='1' then
					update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='webthink7';
					set NEW.amt_deposit := NEW.amt_amount;
				else
					select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='webthink7';
					IF NEW.amt_kind='A' or NEW.amt_kind='F' or NEW.amt_kind='I' or NEW.amt_kind='S' or NEW.amt_kind='L' or NEW.amt_kind='M' or NEW.amt_kind='P' or NEW.amt_kind='R' or NEW.amt_kind='O' THEN
						if @nPoint > 0 then
							set @nPoint := @nPoint - NEW.amt_amount;
							if @nPoint < 0 then
								set NEW.amt_point := NEW.amt_amount - @nPoint;
								set NEW.amt_deposit := abs(@nPoint);
								set @nDeposit := @nDeposit + @nPoint;
								set @nPoint := 0;
							else
								set NEW.amt_point := NEW.amt_amount;
							end if;
						else
							set @nDeposit := @nDeposit - NEW.amt_amount;
							set NEW.amt_deposit := NEW.amt_amount;
						end if;
					else
						set @nPoint := @nPoint + NEW.amt_amount;
						set NEW.amt_point := NEW.amt_amount;
					end if;
					update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='webthink7';
				END IF;
			END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cb_attendance`
--

DROP TABLE IF EXISTS `cb_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_attendance` (
  `att_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `att_point` int(11) NOT NULL DEFAULT '0',
  `att_memo` varchar(255) NOT NULL DEFAULT '',
  `att_continuity` int(11) unsigned NOT NULL DEFAULT '0',
  `att_ranking` int(11) unsigned NOT NULL DEFAULT '0',
  `att_date` date DEFAULT NULL,
  `att_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`att_id`),
  UNIQUE KEY `att_date_mem_id` (`att_date`,`mem_id`),
  KEY `att_datetime_mem_id` (`att_datetime`,`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_attendance`
--

LOCK TABLES `cb_attendance` WRITE;
/*!40000 ALTER TABLE `cb_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_autologin`
--

DROP TABLE IF EXISTS `cb_autologin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_autologin` (
  `aul_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `aul_key` varchar(255) NOT NULL DEFAULT '',
  `aul_ip` varchar(50) NOT NULL DEFAULT '',
  `aul_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`aul_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_autologin`
--

LOCK TABLES `cb_autologin` WRITE;
/*!40000 ALTER TABLE `cb_autologin` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_autologin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_banner`
--

DROP TABLE IF EXISTS `cb_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_banner` (
  `ban_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ban_start_date` date DEFAULT NULL,
  `ban_end_date` date DEFAULT NULL,
  `bng_name` varchar(255) NOT NULL DEFAULT '',
  `ban_title` varchar(255) NOT NULL DEFAULT '',
  `ban_url` varchar(255) NOT NULL DEFAULT '',
  `ban_target` varchar(255) NOT NULL DEFAULT '',
  `ban_device` varchar(255) NOT NULL DEFAULT '',
  `ban_width` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `ban_height` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `ban_hit` int(11) unsigned NOT NULL DEFAULT '0',
  `ban_order` int(11) unsigned NOT NULL DEFAULT '0',
  `ban_image` varchar(255) NOT NULL DEFAULT '',
  `ban_activated` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `ban_datetime` datetime DEFAULT NULL,
  `ban_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ban_id`),
  KEY `bng_name` (`bng_name`),
  KEY `ban_start_date` (`ban_start_date`),
  KEY `ban_end_date` (`ban_end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_banner`
--

LOCK TABLES `cb_banner` WRITE;
/*!40000 ALTER TABLE `cb_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_banner_click_log`
--

DROP TABLE IF EXISTS `cb_banner_click_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_banner_click_log` (
  `bcl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ban_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bcl_datetime` datetime DEFAULT NULL,
  `bcl_ip` varchar(50) NOT NULL DEFAULT '',
  `bcl_referer` varchar(255) NOT NULL DEFAULT '',
  `bcl_url` varchar(255) NOT NULL DEFAULT '',
  `bcl_useragent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`bcl_id`),
  KEY `ban_id` (`ban_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_banner_click_log`
--

LOCK TABLES `cb_banner_click_log` WRITE;
/*!40000 ALTER TABLE `cb_banner_click_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_banner_click_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_banner_group`
--

DROP TABLE IF EXISTS `cb_banner_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_banner_group` (
  `bng_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bng_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`bng_id`),
  KEY `bng_name` (`bng_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_banner_group`
--

LOCK TABLES `cb_banner_group` WRITE;
/*!40000 ALTER TABLE `cb_banner_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_banner_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_blame`
--

DROP TABLE IF EXISTS `cb_blame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_blame` (
  `bla_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bla_datetime` datetime DEFAULT NULL,
  `bla_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`bla_id`),
  KEY `mem_id` (`mem_id`),
  KEY `target_mem_id` (`target_mem_id`),
  KEY `target_id` (`target_id`),
  KEY `brd_id` (`brd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_blame`
--

LOCK TABLES `cb_blame` WRITE;
/*!40000 ALTER TABLE `cb_blame` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_blame` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board`
--

DROP TABLE IF EXISTS `cb_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board` (
  `brd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bgr_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_key` varchar(255) NOT NULL DEFAULT '',
  `brd_name` varchar(255) NOT NULL DEFAULT '',
  `brd_mobile_name` varchar(255) NOT NULL DEFAULT '',
  `brd_order` int(11) NOT NULL DEFAULT '0',
  `brd_search` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`brd_id`),
  UNIQUE KEY `brd_key` (`brd_key`),
  KEY `bgr_id` (`bgr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board`
--

LOCK TABLES `cb_board` WRITE;
/*!40000 ALTER TABLE `cb_board` DISABLE KEYS */;
INSERT INTO `cb_board` VALUES (1,1,'notice','공지사항','공지사항',3,1),(2,2,'qna','Q&A','Q&A',2,1),(10,0,'request','상담신청','상담신청',1,1),(11,0,'data','자료실','자료실',4,1),(12,0,'notice_01','공지/뉴스','공지/뉴스',0,1),(13,0,'faq','이용절차','이용절차',0,1);
/*!40000 ALTER TABLE `cb_board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board_admin`
--

DROP TABLE IF EXISTS `cb_board_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board_admin` (
  `bam_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bam_id`),
  KEY `brd_id` (`brd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board_admin`
--

LOCK TABLES `cb_board_admin` WRITE;
/*!40000 ALTER TABLE `cb_board_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_board_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board_category`
--

DROP TABLE IF EXISTS `cb_board_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board_category` (
  `bca_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bca_key` varchar(255) NOT NULL DEFAULT '',
  `bca_value` varchar(255) NOT NULL DEFAULT '',
  `bca_parent` varchar(255) NOT NULL DEFAULT '',
  `bca_order` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bca_id`),
  KEY `brd_id` (`brd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board_category`
--

LOCK TABLES `cb_board_category` WRITE;
/*!40000 ALTER TABLE `cb_board_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_board_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board_group`
--

DROP TABLE IF EXISTS `cb_board_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board_group` (
  `bgr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bgr_key` varchar(255) NOT NULL DEFAULT '',
  `bgr_name` varchar(255) NOT NULL DEFAULT '',
  `bgr_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bgr_id`),
  UNIQUE KEY `bgr_key` (`bgr_key`),
  KEY `bgr_order` (`bgr_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board_group`
--

LOCK TABLES `cb_board_group` WRITE;
/*!40000 ALTER TABLE `cb_board_group` DISABLE KEYS */;
INSERT INTO `cb_board_group` VALUES (1,'g-a','그룹 A',1),(2,'g-b','그룹 B',2),(3,'g-c','그룹 C',3);
/*!40000 ALTER TABLE `cb_board_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board_group_admin`
--

DROP TABLE IF EXISTS `cb_board_group_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board_group_admin` (
  `bga_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bgr_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bga_id`),
  KEY `bgr_id` (`bgr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board_group_admin`
--

LOCK TABLES `cb_board_group_admin` WRITE;
/*!40000 ALTER TABLE `cb_board_group_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_board_group_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board_group_meta`
--

DROP TABLE IF EXISTS `cb_board_group_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board_group_meta` (
  `bgr_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bgm_key` varchar(255) NOT NULL DEFAULT '',
  `bgm_value` text,
  UNIQUE KEY `bgr_id_bgm_key` (`bgr_id`,`bgm_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board_group_meta`
--

LOCK TABLES `cb_board_group_meta` WRITE;
/*!40000 ALTER TABLE `cb_board_group_meta` DISABLE KEYS */;
INSERT INTO `cb_board_group_meta` VALUES (1,'footer_content',''),(1,'header_content',''),(1,'mobile_footer_content',''),(1,'mobile_header_content',''),(2,'footer_content',''),(2,'header_content',''),(2,'mobile_footer_content',''),(2,'mobile_header_content',''),(3,'footer_content',''),(3,'header_content',''),(3,'mobile_footer_content',''),(3,'mobile_header_content','');
/*!40000 ALTER TABLE `cb_board_group_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_board_meta`
--

DROP TABLE IF EXISTS `cb_board_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_board_meta` (
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bmt_key` varchar(255) NOT NULL DEFAULT '',
  `bmt_value` text,
  UNIQUE KEY `brd_id_bmt_key` (`brd_id`,`bmt_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_board_meta`
--

LOCK TABLES `cb_board_meta` WRITE;
/*!40000 ALTER TABLE `cb_board_meta` DISABLE KEYS */;
INSERT INTO `cb_board_meta` VALUES (1,'blame_blind_count','3'),(1,'board_layout',''),(1,'board_mobile_layout',''),(1,'board_mobile_sidebar',''),(1,'board_mobile_skin',''),(1,'board_sidebar',''),(1,'board_skin',''),(1,'board_use_captcha',''),(1,'comment_blame_blind_count','3'),(1,'comment_count','20'),(1,'comment_order','asc'),(1,'comment_page_count','5'),(1,'comment_syntax_highlighter','1'),(1,'content_target_blank','1'),(1,'footer_content',''),(1,'gallery_cols','4'),(1,'gallery_image_height','90'),(1,'gallery_image_width','120'),(1,'header_content',''),(1,'hot_icon_day','30'),(1,'hot_icon_hit','100'),(1,'link_num','2'),(1,'list_count','20'),(1,'mobile_comment_count','20'),(1,'mobile_comment_page_count','3'),(1,'mobile_footer_content',''),(1,'mobile_gallery_cols','2'),(1,'mobile_gallery_image_height','90'),(1,'mobile_gallery_image_width','120'),(1,'mobile_header_content',''),(1,'mobile_list_count','10'),(1,'mobile_page_count','3'),(1,'mobile_subject_length','40'),(1,'mobile_upload_file_num','2'),(1,'new_icon_hour','24'),(1,'order_by_field','post_num, post_reply'),(1,'page_count','5'),(1,'point_comment',''),(1,'point_download',''),(1,'point_read',''),(1,'point_write',''),(1,'post_image_width','700'),(1,'post_mobile_image_width','400'),(1,'protect_comment_num','5'),(1,'reply_order','asc'),(1,'show_list_from_view','1'),(1,'subject_length','60'),(1,'syntax_highlighter','1'),(1,'upload_file_max_size','32'),(1,'upload_file_num','2'),(1,'use_autoplay','1'),(1,'use_auto_url','1'),(1,'use_blame','1'),(1,'use_comment_blame','1'),(1,'use_comment_dislike','1'),(1,'use_comment_like','1'),(1,'use_comment_secret','1'),(1,'use_mobile_auto_url','1'),(1,'use_mobile_prev_next_post','1'),(1,'use_post_dhtml','1'),(1,'use_post_dislike','1'),(1,'use_post_like','1'),(1,'use_prev_next_post','1'),(1,'use_print','1'),(1,'use_scrap','1'),(1,'use_sideview','1'),(1,'use_sns','1'),(1,'use_tempsave','1'),(1,'use_upload_file','1'),(2,'blame_blind_count','3'),(2,'board_layout',''),(2,'board_mobile_layout',''),(2,'board_mobile_sidebar',''),(2,'board_mobile_skin',''),(2,'board_sidebar',''),(2,'board_skin',''),(2,'board_use_captcha',''),(2,'comment_blame_blind_count','3'),(2,'comment_count','20'),(2,'comment_order','asc'),(2,'comment_page_count','5'),(2,'comment_syntax_highlighter','1'),(2,'content_target_blank','1'),(2,'footer_content',''),(2,'gallery_cols','4'),(2,'gallery_image_height','90'),(2,'gallery_image_width','120'),(2,'header_content',''),(2,'hot_icon_day','30'),(2,'hot_icon_hit','100'),(2,'link_num','2'),(2,'list_count','20'),(2,'mobile_comment_count','20'),(2,'mobile_comment_page_count','3'),(2,'mobile_footer_content',''),(2,'mobile_gallery_cols','2'),(2,'mobile_gallery_image_height','90'),(2,'mobile_gallery_image_width','120'),(2,'mobile_header_content',''),(2,'mobile_list_count','10'),(2,'mobile_page_count','3'),(2,'mobile_subject_length','40'),(2,'mobile_upload_file_num','2'),(2,'new_icon_hour','24'),(2,'order_by_field','post_num, post_reply'),(2,'page_count','5'),(2,'point_comment',''),(2,'point_download',''),(2,'point_read',''),(2,'point_write',''),(2,'post_image_width','700'),(2,'post_mobile_image_width','400'),(2,'protect_comment_num','5'),(2,'reply_order','asc'),(2,'show_list_from_view','1'),(2,'subject_length','60'),(2,'syntax_highlighter','1'),(2,'upload_file_max_size','32'),(2,'upload_file_num','2'),(2,'use_autoplay','1'),(2,'use_auto_url','1'),(2,'use_blame','1'),(2,'use_comment_blame','1'),(2,'use_comment_dislike','1'),(2,'use_comment_like','1'),(2,'use_comment_secret','1'),(2,'use_mobile_auto_url','1'),(2,'use_mobile_prev_next_post','1'),(2,'use_post_dhtml','1'),(2,'use_post_dislike','1'),(2,'use_post_like','1'),(2,'use_prev_next_post','1'),(2,'use_print','1'),(2,'use_scrap','1'),(2,'use_sideview','1'),(2,'use_sns','1'),(2,'use_tempsave','1'),(2,'use_upload_file','1'),(10,'blame_blind_count','3'),(10,'board_layout','homepage'),(10,'board_mobile_layout',''),(10,'board_mobile_sidebar',''),(10,'board_mobile_skin',''),(10,'board_sidebar',''),(10,'board_skin',''),(10,'board_use_captcha',''),(10,'comment_best','1'),(10,'comment_best_like_num','3'),(10,'comment_blame_blind_count','3'),(10,'comment_count','20'),(10,'comment_order','asc'),(10,'comment_page_count','5'),(10,'comment_syntax_highlighter','1'),(10,'content_target_blank','1'),(10,'footer_content',''),(10,'gallery_cols','4'),(10,'gallery_image_height','80'),(10,'gallery_image_width','120'),(10,'header_content',''),(10,'hot_icon_day','30'),(10,'hot_icon_hit','100'),(10,'link_num','2'),(10,'list_count','20'),(10,'mobile_comment_best','1'),(10,'mobile_comment_count','20'),(10,'mobile_comment_page_count','3'),(10,'mobile_footer_content',''),(10,'mobile_gallery_cols','2'),(10,'mobile_gallery_image_height','80'),(10,'mobile_gallery_image_width','120'),(10,'mobile_header_content',''),(10,'mobile_list_count','10'),(10,'mobile_page_count','3'),(10,'mobile_subject_length','40'),(10,'mobile_upload_file_num','2'),(10,'new_icon_hour','24'),(10,'order_by_field','post_num, post_reply'),(10,'page_count','5'),(10,'point_comment',''),(10,'point_download',''),(10,'point_read',''),(10,'point_write',''),(10,'post_image_width','600'),(10,'post_mobile_image_width','400'),(10,'protect_comment_num','5'),(10,'reply_order','asc'),(10,'show_list_from_view','1'),(10,'subject_length','60'),(10,'syntax_highlighter','1'),(10,'upload_file_max_size','2045'),(10,'upload_file_num','2'),(10,'use_autoplay','1'),(10,'use_auto_url','1'),(10,'use_blame','1'),(10,'use_comment_blame','1'),(10,'use_comment_dislike','1'),(10,'use_comment_like','1'),(10,'use_comment_profile','1'),(10,'use_comment_secret','1'),(10,'use_download_log','1'),(10,'use_link_click_log','1'),(10,'use_mobile_auto_url','1'),(10,'use_mobile_comment_profile','1'),(10,'use_mobile_prev_next_post','1'),(10,'use_posthistory','1'),(10,'use_post_dhtml','1'),(10,'use_post_dislike','1'),(10,'use_post_like','1'),(10,'use_prev_next_post','1'),(10,'use_print','1'),(10,'use_scrap','1'),(10,'use_sideview','1'),(10,'use_sideview_icon','1'),(10,'use_sitemap','1'),(10,'use_sns','1'),(10,'use_tempsave','1'),(10,'use_upload_file','1'),(11,'blame_blind_count','3'),(11,'board_layout','homepage'),(11,'board_mobile_layout',''),(11,'board_mobile_sidebar',''),(11,'board_mobile_skin',''),(11,'board_sidebar',''),(11,'board_skin',''),(11,'board_use_captcha',''),(11,'comment_best','1'),(11,'comment_best_like_num','3'),(11,'comment_blame_blind_count','3'),(11,'comment_count','20'),(11,'comment_order','asc'),(11,'comment_page_count','5'),(11,'comment_syntax_highlighter','1'),(11,'content_target_blank','1'),(11,'footer_content',''),(11,'gallery_cols','4'),(11,'gallery_image_height','80'),(11,'gallery_image_width','120'),(11,'header_content',''),(11,'hot_icon_day','30'),(11,'hot_icon_hit','100'),(11,'link_num','2'),(11,'list_count','20'),(11,'mobile_comment_best','1'),(11,'mobile_comment_count','20'),(11,'mobile_comment_page_count','3'),(11,'mobile_footer_content',''),(11,'mobile_gallery_cols','2'),(11,'mobile_gallery_image_height','80'),(11,'mobile_gallery_image_width','120'),(11,'mobile_header_content',''),(11,'mobile_list_count','10'),(11,'mobile_page_count','3'),(11,'mobile_subject_length','40'),(11,'mobile_upload_file_num','2'),(11,'new_icon_hour','24'),(11,'order_by_field','post_num, post_reply'),(11,'page_count','5'),(11,'point_comment',''),(11,'point_download',''),(11,'point_read',''),(11,'point_write',''),(11,'post_image_width','600'),(11,'post_mobile_image_width','400'),(11,'protect_comment_num','5'),(11,'reply_order','asc'),(11,'show_list_from_view','1'),(11,'subject_length','60'),(11,'syntax_highlighter','1'),(11,'upload_file_max_size','2045'),(11,'upload_file_num','2'),(11,'use_autoplay','1'),(11,'use_auto_url','1'),(11,'use_blame','1'),(11,'use_comment_blame','1'),(11,'use_comment_dislike','1'),(11,'use_comment_like','1'),(11,'use_comment_profile','1'),(11,'use_comment_secret','1'),(11,'use_download_log','1'),(11,'use_link_click_log','1'),(11,'use_mobile_auto_url','1'),(11,'use_mobile_comment_profile','1'),(11,'use_mobile_prev_next_post','1'),(11,'use_posthistory','1'),(11,'use_post_dhtml','1'),(11,'use_post_dislike','1'),(11,'use_post_like','1'),(11,'use_prev_next_post','1'),(11,'use_print','1'),(11,'use_scrap','1'),(11,'use_sideview','1'),(11,'use_sideview_icon','1'),(11,'use_sitemap','1'),(11,'use_sns','1'),(11,'use_tempsave','1'),(11,'use_upload_file','1'),(12,'blame_blind_count','3'),(12,'board_layout','homepage'),(12,'board_mobile_layout',''),(12,'board_mobile_sidebar',''),(12,'board_mobile_skin',''),(12,'board_sidebar',''),(12,'board_skin',''),(12,'board_use_captcha',''),(12,'comment_best','1'),(12,'comment_best_like_num','3'),(12,'comment_blame_blind_count','3'),(12,'comment_count','20'),(12,'comment_order','asc'),(12,'comment_page_count','5'),(12,'comment_syntax_highlighter','1'),(12,'comment_to_download',''),(12,'content_target_blank','1'),(12,'footer_content',''),(12,'gallery_cols','4'),(12,'gallery_image_height','80'),(12,'gallery_image_width','120'),(12,'header_content',''),(12,'hot_icon_day','30'),(12,'hot_icon_hit','100'),(12,'like_to_download',''),(12,'link_num','2'),(12,'list_count','20'),(12,'mobile_comment_best','1'),(12,'mobile_comment_count','20'),(12,'mobile_comment_page_count','3'),(12,'mobile_footer_content',''),(12,'mobile_gallery_cols','2'),(12,'mobile_gallery_image_height','80'),(12,'mobile_gallery_image_width','120'),(12,'mobile_header_content',''),(12,'mobile_link_num',''),(12,'mobile_list_count','10'),(12,'mobile_page_count','3'),(12,'mobile_post_default_content',''),(12,'mobile_post_default_title',''),(12,'mobile_subject_length','40'),(12,'mobile_upload_file_num','2'),(12,'new_icon_hour','24'),(12,'order_by_field','post_num, post_reply'),(12,'page_count','5'),(12,'point_comment',''),(12,'point_download',''),(12,'point_read',''),(12,'point_write',''),(12,'post_default_content',''),(12,'post_default_title',''),(12,'post_image_width','600'),(12,'post_max_length',''),(12,'post_min_length',''),(12,'post_mobile_image_width','400'),(12,'protect_comment_num','5'),(12,'reply_order','asc'),(12,'save_external_image',''),(12,'show_list_from_view','1'),(12,'subject_length','60'),(12,'syntax_highlighter','1'),(12,'upload_file_extension',''),(12,'upload_file_max_size','2045'),(12,'upload_file_num','2'),(12,'use_autoplay','1'),(12,'use_auto_url','1'),(12,'use_blame','1'),(12,'use_comment_blame','1'),(12,'use_comment_dislike','1'),(12,'use_comment_like','1'),(12,'use_comment_profile','1'),(12,'use_comment_secret','1'),(12,'use_download_log','1'),(12,'use_google_map',''),(12,'use_link_click_log','1'),(12,'use_mobile_auto_url','1'),(12,'use_mobile_comment_profile','1'),(12,'use_mobile_post_dhtml',''),(12,'use_mobile_post_emoticon',''),(12,'use_mobile_post_specialchars',''),(12,'use_mobile_prev_next_post','1'),(12,'use_mobile_subject_style',''),(12,'use_only_one_post',''),(12,'use_posthistory','1'),(12,'use_post_dhtml',''),(12,'use_post_dislike','1'),(12,'use_post_emoticon',''),(12,'use_post_like','1'),(12,'use_post_receive_email',''),(12,'use_post_secret',''),(12,'use_post_secret_selected',''),(12,'use_post_specialchars',''),(12,'use_post_tag',''),(12,'use_prev_next_post','1'),(12,'use_print','1'),(12,'use_scrap','1'),(12,'use_sideview','1'),(12,'use_sideview_icon','1'),(12,'use_sitemap','1'),(12,'use_sns','1'),(12,'use_subject_style',''),(12,'use_tempsave','1'),(12,'use_upload_file','1'),(12,'write_possible_days',''),(13,'blame_blind_count','3'),(13,'board_layout','qna'),(13,'board_mobile_layout',''),(13,'board_mobile_sidebar',''),(13,'board_mobile_skin',''),(13,'board_sidebar',''),(13,'board_skin',''),(13,'board_use_captcha',''),(13,'comment_best','1'),(13,'comment_best_like_num','3'),(13,'comment_blame_blind_count','3'),(13,'comment_count','20'),(13,'comment_order','asc'),(13,'comment_page_count','5'),(13,'comment_syntax_highlighter','1'),(13,'content_target_blank','1'),(13,'footer_content',''),(13,'gallery_cols','4'),(13,'gallery_image_height','80'),(13,'gallery_image_width','120'),(13,'header_content',''),(13,'hot_icon_day','30'),(13,'hot_icon_hit','100'),(13,'link_num','2'),(13,'list_count','20'),(13,'mobile_comment_best','1'),(13,'mobile_comment_count','20'),(13,'mobile_comment_page_count','3'),(13,'mobile_footer_content',''),(13,'mobile_gallery_cols','2'),(13,'mobile_gallery_image_height','80'),(13,'mobile_gallery_image_width','120'),(13,'mobile_header_content',''),(13,'mobile_list_count','10'),(13,'mobile_page_count','3'),(13,'mobile_subject_length','40'),(13,'mobile_upload_file_num','2'),(13,'new_icon_hour','24'),(13,'order_by_field','post_num, post_reply'),(13,'page_count','5'),(13,'point_comment',''),(13,'point_download',''),(13,'point_read',''),(13,'point_write',''),(13,'post_image_width','600'),(13,'post_mobile_image_width','400'),(13,'protect_comment_num','5'),(13,'reply_order','asc'),(13,'show_list_from_view','1'),(13,'subject_length','60'),(13,'syntax_highlighter','1'),(13,'upload_file_max_size','2045'),(13,'upload_file_num','2'),(13,'use_autoplay','1'),(13,'use_auto_url','1'),(13,'use_blame','1'),(13,'use_comment_blame','1'),(13,'use_comment_dislike','1'),(13,'use_comment_like','1'),(13,'use_comment_profile','1'),(13,'use_comment_secret','1'),(13,'use_download_log','1'),(13,'use_link_click_log','1'),(13,'use_mobile_auto_url','1'),(13,'use_mobile_comment_profile','1'),(13,'use_mobile_prev_next_post','1'),(13,'use_posthistory','1'),(13,'use_post_dhtml','1'),(13,'use_post_dislike','1'),(13,'use_post_like','1'),(13,'use_prev_next_post','1'),(13,'use_print','1'),(13,'use_scrap','1'),(13,'use_sideview','1'),(13,'use_sideview_icon','1'),(13,'use_sitemap','1'),(13,'use_sns','1'),(13,'use_tempsave','1'),(13,'use_upload_file','1');
/*!40000 ALTER TABLE `cb_board_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_cart`
--

DROP TABLE IF EXISTS `cb_cmall_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_cart` (
  `cct_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cde_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cct_count` int(11) unsigned NOT NULL DEFAULT '0',
  `cct_cart` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cct_order` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cct_datetime` datetime DEFAULT NULL,
  `cct_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cct_id`),
  KEY `mem_id` (`mem_id`),
  KEY `cit_id` (`cit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_cart`
--

LOCK TABLES `cb_cmall_cart` WRITE;
/*!40000 ALTER TABLE `cb_cmall_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_category`
--

DROP TABLE IF EXISTS `cb_cmall_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_category` (
  `cca_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cca_value` varchar(255) NOT NULL DEFAULT '',
  `cca_parent` varchar(255) NOT NULL DEFAULT '',
  `cca_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_category`
--

LOCK TABLES `cb_cmall_category` WRITE;
/*!40000 ALTER TABLE `cb_cmall_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_category_rel`
--

DROP TABLE IF EXISTS `cb_cmall_category_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_category_rel` (
  `ccr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cca_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ccr_id`),
  KEY `cit_id` (`cit_id`),
  KEY `cca_id` (`cca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_category_rel`
--

LOCK TABLES `cb_cmall_category_rel` WRITE;
/*!40000 ALTER TABLE `cb_cmall_category_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_category_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_config`
--

DROP TABLE IF EXISTS `cb_cmall_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_config` (
  `ccf_key` varchar(255) NOT NULL DEFAULT '',
  `ccf_value` text,
  UNIQUE KEY `ccf_key` (`ccf_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_config`
--

LOCK TABLES `cb_cmall_config` WRITE;
/*!40000 ALTER TABLE `cb_cmall_config` DISABLE KEYS */;
INSERT INTO `cb_cmall_config` VALUES ('access_cmall_buy','1'),('cmall_cart_keep_days','14'),('cmall_email_admin_approve_bank_to_contents_title','[입금처리완료] {회원닉네임}님의 입금처리요청이 완료되었습니다'),('cmall_email_admin_bank_to_contents_title','[주문안내] {회원닉네임}님이 무통장입금 요청하셨습니다'),('cmall_email_admin_cash_to_contents_title','[주문안내] {회원닉네임}님이 결제하셨습니다'),('cmall_email_admin_write_product_qna_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[문의내용]</strong></div><div>{문의내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_email_admin_write_product_qna_reply_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[답변내용]</strong></div><div>{답변내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_email_admin_write_product_qna_reply_title','[상품문의] {상품명} 상품 문의에 대한 답변이 등록되었습니다'),('cmall_email_admin_write_product_qna_title','[상품문의] {상품명} 상품 문의가 작성되었습니다'),('cmall_email_admin_write_product_review_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[후기제목]</strong></div><div>{후기제목}</div><div>&nbsp;</div><div><strong>[후기내용]</strong></div><div>{후기내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_email_admin_write_product_review_title','[상품후기] {상품명} 상품 후기가 작성되었습니다'),('cmall_email_user_approve_bank_to_contents_title','[{홈페이지명}] 입금이 확인되어 주문처리가 완료되었습니다'),('cmall_email_user_bank_to_contents_title','[{홈페이지명}] 구매신청이접수되었습니다.입금확인후상품이용가능합니다'),('cmall_email_user_cash_to_contents_title','[{홈페이지명}] 상품을 구매해주셔서 감사합니다'),('cmall_email_user_write_product_qna_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[문의내용]</strong></div><div>{문의내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_email_user_write_product_qna_reply_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[답변내용]</strong></div><div>{답변내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_email_user_write_product_qna_reply_title','[홈페이지명] {상품명} 상품 문의에 대한 답변입니다'),('cmall_email_user_write_product_qna_title','[홈페이지명] {상품명} 상품 문의가 접수되었습니다'),('cmall_email_user_write_product_review_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[후기제목]</strong></div><div>{후기제목}</div><div>&nbsp;</div><div><strong>[후기내용]</strong></div><div>{후기내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_email_user_write_product_review_title','[홈페이지명] {상품명} 상품 후기를 작성해주셔서 감사합니다'),('cmall_name','컨텐츠몰'),('cmall_note_admin_approve_bank_to_contents_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님의 입금확인 처리가 완료되었습니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('cmall_note_admin_approve_bank_to_contents_title','[입금처리완료] {회원닉네임}님의 입금처리요청이 완료되었습니다'),('cmall_note_admin_bank_to_contents_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 무통장입금요청하셨습니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('cmall_note_admin_bank_to_contents_title','[주문안내] {회원닉네임}님이 무통장입금 요청하셨습니다'),('cmall_note_admin_cash_to_contents_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 상품을 구매하셨습니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('cmall_note_admin_cash_to_contents_title','[주문안내] {회원닉네임}님이 결제하셨습니다'),('cmall_note_admin_write_product_qna_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[문의내용]</strong></div><div>{문의내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_note_admin_write_product_qna_reply_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[답변내용]</strong></div><div>{답변내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_note_admin_write_product_qna_reply_title','[상품문의] {상품명} 상품 문의에 대한 답변이 등록되었습니다'),('cmall_note_admin_write_product_qna_title','[상품문의] {상품명} 상품 문의가 작성되었습니다'),('cmall_note_admin_write_product_review_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[후기제목]</strong></div><div>{후기제목}</div><div>&nbsp;</div><div><strong>[후기내용]</strong></div><div>{후기내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_note_admin_write_product_review_title','[상품후기] {상품명} 상품 후기가 작성되었습니다'),('cmall_note_user_approve_bank_to_contents_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>구매해주셔서 감사합니다</p><p>입금이 확인되어 이제 정상적으로 상품 이용이 가능합니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('cmall_note_user_approve_bank_to_contents_title','입금이 확인되어 주문처리가 완료되었습니다'),('cmall_note_user_bank_to_contents_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>구매해주셔서 감사합니다</p><p>입금이 확인되는대로 승인처리해드리겠습니다</p><p>결제금액 : {결제금액}원</p><p>은행계좌안내 : {은행계좌안내}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('cmall_note_user_bank_to_contents_title','구매신청이접수되었습니다.입금확인후상품이용가능합니다'),('cmall_note_user_cash_to_contents_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>구매해주셔서 감사합니다</p><p>구매하신 상품 이용이 가능합니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('cmall_note_user_cash_to_contents_title','상품을 구매해주셔서 감사합니다'),('cmall_note_user_write_product_qna_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[문의내용]</strong></div><div>{문의내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_note_user_write_product_qna_reply_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[문의제목]</strong></div><div>{문의제목}</div><div>&nbsp;</div><div><strong>[답변내용]</strong></div><div>{답변내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_note_user_write_product_qna_reply_title','{상품명} 상품 문의에 대한 답변입니다'),('cmall_note_user_write_product_qna_title','{상품명} 상품 문의가 접수되었습니다'),('cmall_note_user_write_product_review_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><strong>[후기제목]</strong></div><div>{후기제목}</div><div>&nbsp;</div><div><strong>[후기내용]</strong></div><div>{후기내용}</div><div>&nbsp;</div><div><a href=\"{상품주소}\" target=\"_blank\"><strong>[상품페이지 보기]</strong></a></div><p>&nbsp;</p></td></tr></table>'),('cmall_note_user_write_product_review_title','{상품명} 상품 후기를 작성해주셔서 감사합니다'),('cmall_product_editor_type','smarteditor'),('cmall_product_qna_editor_type','smarteditor'),('cmall_product_review_editor_type','smarteditor'),('cmall_sendcont_admin_approve_bank_to_contents','입금확인함 - {고객명}님\n주문번호 : {주문번호}\n주문금액 : {주문금액}'),('cmall_sendcont_admin_bank_to_contents','무통장입금요청 - {고객명}님\n주문번호 : {주문번호}\n주문금액 : {주문금액}\n결제완료'),('cmall_sendcont_admin_cash_to_contents','컨텐츠몰 - {고객명}님\n주문번호 : {주문번호}\n주문금액 : {주문금액}\n결제완료'),('cmall_sendcont_admin_write_product_qna','상품문의작성 - {고객명}님\n상품명 : {상품명}'),('cmall_sendcont_admin_write_product_qna_reply','상품답변작성 - {고객명}님\n상품명 : {상품명}'),('cmall_sendcont_admin_write_product_review','상품후기작성 - {고객명}님\n상품명 : {상품명}'),('cmall_sendcont_user_approve_bank_to_contents','안녕하세요 {고객명}님\n주문번호 : {주문번호}\n주문금액 : {주문금액}\n입금이 확인되었습니다. 감사합니다 '),('cmall_sendcont_user_bank_to_contents','안녕하세요 {고객명}님\n주문번호 : {주문번호}\n주문금액 : {주문금액}\n입금확인후 주문이 완료됩니다. 감사합니다 '),('cmall_sendcont_user_cash_to_contents','안녕하세요 {고객명}님\n주문번호 : {주문번호}\n주문금액 : {주문금액}\n결제완료, 감사합니다 - {회사명}'),('cmall_sendcont_user_write_product_qna','안녕하세요 {고객명}님\n상품문의가 접수되었습니다 감사합니다 '),('cmall_sendcont_user_write_product_qna_reply','안녕하세요 {고객명}님\n문의하신 상품문의에 답변이 작성되었습니다. 감사합니다 '),('cmall_sendcont_user_write_product_review','안녕하세요 {고객명}님\n상품후기를 작성해주셔서 감사합니다 '),('cmall_sms_admin_approve_bank_to_contents_content','[무통장입금확인] {회원닉네임}님의 무통장입금요청이확인되었습니다'),('cmall_sms_admin_bank_to_contents_content','[무통장입금요청] {회원닉네임}님이 무통장입금요청하였습니다'),('cmall_sms_admin_cash_to_contents_content','[구매알림] {회원닉네임}님이 구매하셨습니다'),('cmall_sms_admin_write_product_qna_content','[상품문의] {상품명} 상품문의가 접수되었습니다'),('cmall_sms_admin_write_product_qna_reply_content','[상품문의] {상품명} 상품문의답변이 등록되었습니다'),('cmall_sms_admin_write_product_review_content','[상품후기] {상품명} 상품후기가 작성되었습니다'),('cmall_sms_user_approve_bank_to_contents_content','[{홈페이지명}] 입금이확인되었습니다. 구매하신상품다운로드가가능합니다'),('cmall_sms_user_bank_to_contents_content','[{홈페이지명}] 구매신청이접수되었습니다.입금확인후상품이용가능합니다'),('cmall_sms_user_cash_to_contents_content','[{홈페이지명}] 구매가완료되었습니다 감사합니다'),('cmall_sms_user_write_product_qna_content','[홈페이지명] {상품명} 상품문의가 접수되었습니다 감사합니다'),('cmall_sms_user_write_product_qna_reply_content','[홈페이지명] {상품명} 상품문의에 대한 답변이 등록되었습니다 감사합니다'),('cmall_sms_user_write_product_review_content','[홈페이지명] {상품명} 상품후기를 작성해주셔서 감사합니다'),('site_meta_title_cmall','{컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_cart','장바구니 > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_item','{상품명} > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_list','{컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_order','상품주문 > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_orderlist','주문내역 > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_orderresult','주문결과 > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_qna_write','상품문의작성 > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_review_write','상품후기작성 > {컨텐츠몰명} - {홈페이지제목}'),('site_meta_title_cmall_wishlist','찜한 목록 > {컨텐츠몰명} - {홈페이지제목}'),('use_cmall_deposit_to_contents','1'),('use_cmall_product_dhtml','1'),('use_cmall_product_qna_dhtml','1'),('use_cmall_product_review_dhtml','1');
/*!40000 ALTER TABLE `cb_cmall_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_demo_click_log`
--

DROP TABLE IF EXISTS `cb_cmall_demo_click_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_demo_click_log` (
  `cdc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cdc_type` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cdc_datetime` datetime DEFAULT NULL,
  `cdc_ip` varchar(50) NOT NULL DEFAULT '',
  `cdc_useragent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`cdc_id`),
  KEY `cit_id` (`cit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_demo_click_log`
--

LOCK TABLES `cb_cmall_demo_click_log` WRITE;
/*!40000 ALTER TABLE `cb_cmall_demo_click_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_demo_click_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_download_log`
--

DROP TABLE IF EXISTS `cb_cmall_download_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_download_log` (
  `cdo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cde_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cdo_datetime` datetime DEFAULT NULL,
  `cdo_ip` varchar(50) NOT NULL DEFAULT '',
  `cdo_useragent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`cdo_id`),
  KEY `cde_id` (`cde_id`),
  KEY `cit_id` (`cit_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_download_log`
--

LOCK TABLES `cb_cmall_download_log` WRITE;
/*!40000 ALTER TABLE `cb_cmall_download_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_download_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_item`
--

DROP TABLE IF EXISTS `cb_cmall_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_item` (
  `cit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_key` varchar(255) NOT NULL DEFAULT '',
  `cit_name` varchar(255) NOT NULL DEFAULT '',
  `cit_order` int(11) NOT NULL DEFAULT '0',
  `cit_type1` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cit_type2` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cit_type3` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cit_type4` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cit_status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cit_summary` text,
  `cit_content` mediumtext,
  `cit_mobile_content` mediumtext,
  `cit_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cit_price` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_file_1` varchar(255) NOT NULL DEFAULT '',
  `cit_file_2` varchar(255) NOT NULL DEFAULT '',
  `cit_file_3` varchar(255) NOT NULL DEFAULT '',
  `cit_file_4` varchar(255) NOT NULL DEFAULT '',
  `cit_file_5` varchar(255) NOT NULL DEFAULT '',
  `cit_file_6` varchar(255) NOT NULL DEFAULT '',
  `cit_file_7` varchar(255) NOT NULL DEFAULT '',
  `cit_file_8` varchar(255) NOT NULL DEFAULT '',
  `cit_file_9` varchar(255) NOT NULL DEFAULT '',
  `cit_file_10` varchar(255) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_hit` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_datetime` datetime DEFAULT NULL,
  `cit_updated_datetime` datetime DEFAULT NULL,
  `cit_sell_count` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_wish_count` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_download_days` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_review_count` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_review_average` decimal(2,1) NOT NULL DEFAULT '0.0',
  `cit_qna_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cit_id`),
  UNIQUE KEY `cit_key` (`cit_key`),
  KEY `cit_order` (`cit_order`),
  KEY `cit_price` (`cit_price`),
  KEY `cit_sell_count` (`cit_sell_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_item`
--

LOCK TABLES `cb_cmall_item` WRITE;
/*!40000 ALTER TABLE `cb_cmall_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_item_detail`
--

DROP TABLE IF EXISTS `cb_cmall_item_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_item_detail` (
  `cde_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cde_title` varchar(255) NOT NULL DEFAULT '',
  `cde_price` int(11) unsigned NOT NULL DEFAULT '0',
  `cde_originname` varchar(255) NOT NULL DEFAULT '',
  `cde_filename` varchar(255) NOT NULL DEFAULT '',
  `cde_download` int(11) unsigned NOT NULL DEFAULT '0',
  `cde_filesize` int(11) unsigned NOT NULL DEFAULT '0',
  `cde_type` varchar(10) NOT NULL DEFAULT '',
  `cde_is_image` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cde_datetime` datetime DEFAULT NULL,
  `cde_ip` varchar(50) NOT NULL DEFAULT '',
  `cde_status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cde_id`),
  KEY `cit_id` (`cit_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_item_detail`
--

LOCK TABLES `cb_cmall_item_detail` WRITE;
/*!40000 ALTER TABLE `cb_cmall_item_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_item_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_item_history`
--

DROP TABLE IF EXISTS `cb_cmall_item_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_item_history` (
  `chi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `chi_title` varchar(255) NOT NULL DEFAULT '',
  `chi_content` mediumtext,
  `chi_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `chi_ip` varchar(50) NOT NULL DEFAULT '',
  `chi_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`chi_id`),
  KEY `cit_id` (`cit_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_item_history`
--

LOCK TABLES `cb_cmall_item_history` WRITE;
/*!40000 ALTER TABLE `cb_cmall_item_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_item_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_item_meta`
--

DROP TABLE IF EXISTS `cb_cmall_item_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_item_meta` (
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cim_key` varchar(255) NOT NULL DEFAULT '',
  `cim_value` text,
  UNIQUE KEY `cit_id_cim_key` (`cit_id`,`cim_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_item_meta`
--

LOCK TABLES `cb_cmall_item_meta` WRITE;
/*!40000 ALTER TABLE `cb_cmall_item_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_item_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_order`
--

DROP TABLE IF EXISTS `cb_cmall_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_order` (
  `cor_id` bigint(20) unsigned NOT NULL,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_nickname` varchar(100) NOT NULL DEFAULT '',
  `mem_realname` varchar(100) NOT NULL DEFAULT '',
  `mem_email` varchar(255) NOT NULL DEFAULT '',
  `mem_phone` varchar(255) NOT NULL DEFAULT '',
  `cor_memo` text,
  `cor_total_money` int(11) NOT NULL DEFAULT '0',
  `cor_deposit` int(11) NOT NULL DEFAULT '0',
  `cor_cash_request` int(11) NOT NULL DEFAULT '0',
  `cor_cash` int(11) NOT NULL DEFAULT '0',
  `cor_content` text,
  `cor_pay_type` varchar(100) NOT NULL DEFAULT '',
  `cor_pg` varchar(255) NOT NULL DEFAULT '',
  `cor_tno` varchar(255) NOT NULL DEFAULT '',
  `cor_app_no` varchar(255) NOT NULL DEFAULT '',
  `cor_bank_info` varchar(255) NOT NULL DEFAULT '',
  `cor_admin_memo` text,
  `cor_datetime` datetime DEFAULT NULL,
  `cor_approve_datetime` datetime DEFAULT NULL,
  `cor_ip` varchar(50) NOT NULL DEFAULT '',
  `cor_useragent` varchar(255) NOT NULL DEFAULT '',
  `cor_status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cor_vbank_expire` datetime DEFAULT NULL,
  `is_test` char(1) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `cor_refund_price` int(11) NOT NULL DEFAULT '0',
  `cor_order_history` text,
  PRIMARY KEY (`cor_id`),
  KEY `mem_id` (`mem_id`),
  KEY `cor_pay_type` (`cor_pay_type`),
  KEY `cor_datetime` (`cor_datetime`),
  KEY `cor_approve_datetime` (`cor_approve_datetime`),
  KEY `cor_status` (`cor_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_order`
--

LOCK TABLES `cb_cmall_order` WRITE;
/*!40000 ALTER TABLE `cb_cmall_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_order_detail`
--

DROP TABLE IF EXISTS `cb_cmall_order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_order_detail` (
  `cod_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cor_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cde_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cod_download_days` int(11) NOT NULL DEFAULT '0',
  `cod_count` int(11) unsigned NOT NULL DEFAULT '0',
  `cod_status` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cod_id`),
  KEY `cor_id` (`cor_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_order_detail`
--

LOCK TABLES `cb_cmall_order_detail` WRITE;
/*!40000 ALTER TABLE `cb_cmall_order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_qna`
--

DROP TABLE IF EXISTS `cb_cmall_qna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_qna` (
  `cqa_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cqa_title` varchar(255) NOT NULL DEFAULT '',
  `cqa_content` mediumtext,
  `cqa_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cqa_reply_content` mediumtext,
  `cqa_reply_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cqa_secret` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cqa_receive_email` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cqa_receive_sms` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cqa_datetime` datetime DEFAULT NULL,
  `cqa_ip` varchar(50) NOT NULL DEFAULT '',
  `cqa_reply_datetime` datetime DEFAULT NULL,
  `cqa_reply_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cqa_reply_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cqa_id`),
  KEY `cit_id` (`cit_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_qna`
--

LOCK TABLES `cb_cmall_qna` WRITE;
/*!40000 ALTER TABLE `cb_cmall_qna` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_qna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_review`
--

DROP TABLE IF EXISTS `cb_cmall_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_review` (
  `cre_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cre_title` varchar(255) NOT NULL DEFAULT '',
  `cre_content` mediumtext,
  `cre_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cre_score` tinyint(4) NOT NULL DEFAULT '0',
  `cre_datetime` datetime DEFAULT NULL,
  `cre_ip` varchar(50) NOT NULL DEFAULT '',
  `cre_status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cre_id`),
  KEY `cit_id` (`cit_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_review`
--

LOCK TABLES `cb_cmall_review` WRITE;
/*!40000 ALTER TABLE `cb_cmall_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_cmall_wishlist`
--

DROP TABLE IF EXISTS `cb_cmall_wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_cmall_wishlist` (
  `cwi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cit_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cwi_datetime` datetime DEFAULT NULL,
  `cwi_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cwi_id`),
  UNIQUE KEY `mem_id_cit_id` (`mem_id`,`cit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_cmall_wishlist`
--

LOCK TABLES `cb_cmall_wishlist` WRITE;
/*!40000 ALTER TABLE `cb_cmall_wishlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_cmall_wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_comment`
--

DROP TABLE IF EXISTS `cb_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_comment` (
  `cmt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_num` int(11) NOT NULL DEFAULT '0',
  `cmt_reply` varchar(20) NOT NULL DEFAULT '',
  `cmt_html` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cmt_secret` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cmt_content` text,
  `mem_id` int(11) NOT NULL DEFAULT '0',
  `cmt_password` varchar(255) NOT NULL DEFAULT '',
  `cmt_userid` varchar(100) NOT NULL DEFAULT '',
  `cmt_username` varchar(100) NOT NULL DEFAULT '',
  `cmt_nickname` varchar(100) NOT NULL DEFAULT '',
  `cmt_email` varchar(255) NOT NULL DEFAULT '',
  `cmt_homepage` text,
  `cmt_datetime` datetime DEFAULT NULL,
  `cmt_updated_datetime` datetime DEFAULT NULL,
  `cmt_ip` varchar(50) NOT NULL DEFAULT '',
  `cmt_like` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_dislike` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_blame` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `cmt_device` varchar(10) NOT NULL DEFAULT '',
  `cmt_del` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `post_id_cmt_num_cmt_reply` (`post_id`,`cmt_num`,`cmt_reply`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_comment`
--

LOCK TABLES `cb_comment` WRITE;
/*!40000 ALTER TABLE `cb_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_comment_meta`
--

DROP TABLE IF EXISTS `cb_comment_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_comment_meta` (
  `cmt_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cme_key` varchar(255) NOT NULL DEFAULT '',
  `cme_value` text,
  UNIQUE KEY `cmt_id_cme_key` (`cmt_id`,`cme_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_comment_meta`
--

LOCK TABLES `cb_comment_meta` WRITE;
/*!40000 ALTER TABLE `cb_comment_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_comment_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_config`
--

DROP TABLE IF EXISTS `cb_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_config` (
  `cfg_key` varchar(255) NOT NULL DEFAULT '',
  `cfg_value` text,
  UNIQUE KEY `cfg_key` (`cfg_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_config`
--

LOCK TABLES `cb_config` WRITE;
/*!40000 ALTER TABLE `cb_config` DISABLE KEYS */;
INSERT INTO `cb_config` VALUES ('admin_logo','Admin'),('attendance_default_memo','안녕하세요^^\n오늘 하루도 신나게~\n좋은 하루입니다 ^^*\n오늘도 행복하시길 !!\n상쾌한 하루되세요 @,.@\n반갑습니다.'),('attendance_end_time','23:59:59'),('attendance_memo_length','30'),('attendance_mobile_show_attend_time','1'),('attendance_page_count','100'),('attendance_point','10'),('attendance_show_attend_time','1'),('attendance_start_time','00:00:00'),('bitly_access_token',''),('block_download_zeropoint',''),('block_read_zeropoint',''),('block_write_zeropoint',''),('cb_version','2.0.1'),('change_nickname_date','10000'),('change_open_profile_date','60'),('change_password_date','180'),('change_use_note_date','60'),('company_added_sale_no',''),('company_address','경남 창원시 의창구 명서동 104-1 KT빌딩'),('company_admin_email','pm@webthink.co.kr'),('company_admin_name','강영식'),('company_fax','0505-299-0001'),('company_name','(주)대형네트웍스'),('company_owner',''),('company_phone','1522-7985'),('company_reg_no',''),('company_retail_sale_no',''),('company_zipcode',''),('currentvisitor_minute','10'),('denied_email_list',''),('denied_nickname_list','admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객'),('denied_userid_list','admin,administrator,webmaster,sysop,manager,root,su,guest,super'),('document_content_target_blank','1'),('document_editor_type','smarteditor'),('document_mobile_thumb_width','400'),('document_thumb_width','700'),('faq_content_target_blank','1'),('faq_editor_type','smarteditor'),('faq_mobile_thumb_width','400'),('faq_thumb_width','700'),('footer_script',''),('formmail_editor_type','smarteditor'),('ip_display_style','1001'),('jwplayer6_key',''),('kakao_apikey',''),('layout_board',''),('layout_currentvisitor',''),('layout_default','bootstrap'),('layout_document',''),('layout_faq',''),('layout_findaccount',''),('layout_formmail',''),('layout_group',''),('layout_helptool',''),('layout_login',''),('layout_main',''),('layout_mypage',''),('layout_note',''),('layout_notification',''),('layout_pointranking',''),('layout_poll',''),('layout_profile',''),('layout_register',''),('layout_search',''),('layout_tag',''),('list_count','20'),('max_level','100'),('max_login_try_count','5'),('max_login_try_limit_second','30'),('member_dormant_auto_clean','1'),('member_dormant_auto_email','1'),('member_dormant_auto_email_days','30'),('member_dormant_days','365'),('member_dormant_method','archive'),('member_icon_height','20'),('member_icon_width','20'),('member_photo_height','1920'),('member_photo_width','1080'),('member_register_policy1','“비즈알림톡” 서비스 이용약관\r\n\r\n제 1 조 [목적]\r\n\r\n본 이용약관(이하 “약관”)은 주식회사 대형네트웍스(이하 “회사”)가 제공하는 카카오 비즈메시지 서비스 및/또는 문자메시지 서비스인 비즈알림톡(www.bizalimtalk.kr, 이하 “비즈알림톡”)의 이용과 관련하여 회사와 이용자 간의 권리와 의무 및 책임사항, 기타 필요한 사항을 규정함을 목적으로 합니다.\r\n\r\n제 2 조 [용어의 정의]\r\n\r\n본 약관에서 사용하는 용어의 정의는 다음 각호와 같습니다.\r\n\r\n① 비즈알림톡 : “회사”의 서비스명으로 이용자가 전송하고자 하는 내용 및 정보를 “카카오톡” 또는 문자메시지 이용하여 전송하는 서비스로 “이용자” 또는 “이용자”의 “고객”에게 메시지를 전송하는 서비스를 통칭한다.\r\n② 알림톡 : “카카오톡” API를 이용하여 “이용자” 또는 “이용자”의 “고객”의 “카카오톡”으로 정보통신망이용촉진및정보보호등에관한법률 관련 한국인터넷진흥원 가이드상 ‘광고성 정보의 예외’ 중 이용자의 보호 차원에서 적합하다고 해당되는 메시지를 전송해 주는 서비스 및 기타 부가하여 제공하는 서비스를 말한다.\r\n③ 친구톡 : “카카오톡” API를 이용하여 “이용자” 또는 “이용자”의 “고객” 중 친구로 등록된 고객의 “카카오톡”에 메시지를 전송해 주는 서비스 및 기타 부가하여 제공하는 서비스를 말한다.\r\n④ 문자메시지 : 이동전화의 데이터 통신기능을 활용하여 컴퓨터 등 정보처리 능력을 가지고 있는 장치와 무선단말기 사이에 80Byte 이하의 단문메시지(SMS), 한글 1,000자 이하의 장문메시지(LMS), 2,000Byte 이하의 장문메시지, 이미지, 오디오, 동영상의 멀티미디어 메시지(MMS)를 전송해주는 서비스 및 기타 부가하여 제공하는 서비스를 통칭한다.\r\n⑤ 이용자 : “회사”와 “비즈알림톡” 이용계약을 체결한 자를 말한다.\r\n⑥ 아이디(ID) : \"이용자“의 식별과 “비즈알림톡“ 이용을 위하여 ”이용자“가 정하고 ”회사“가 승인하는 문자와 숫자의 조합을 의미한다.\r\n⑦ 비밀번호 : “이용자”가 부여 받은 “아이디”와 일치하는 “이용자”임을 확인하고 비밀보호를 위해 “이용자” 자신이 정한 문자 또는 숫자의 조합을 의미한다.\r\n⑧ 이용신청 : “회사”가 정한 별도의 기준과 절차에 따라 “비즈알림톡” 이용을 신청하는 것을 말한다.\r\n⑨ 이용계약 : “비즈알림톡”을 제공 받기 위하여 “회사”와 “이용자”간에 체결되는 계약을 말한다.\r\n⑩ 이용정지 : “회사”가 정한 일정한 요건에 따라 일정기간 동안 이용자의 “비즈알림톡” 이용을 보류하는 것을 말한다.\r\n⑪ 해지 : “회사”와 “이용자” 간 체결되어 있는 이용계약을 해약하는 것을 말한다.\r\n⑫ 선불충전 : “회사”가 “이용자”에게 유료로 제공하는 “비즈알림톡”을 이용하기 위하여 사용 예상량만큼 “회사”가 정한 결재수단(핸드폰, ARS, 신용카드, 계좌이체 등)을 이용하여 선불결재를 하는 것을 말한다.\r\n⑬ 고객 : “이용자”와 상거래 관계에 있거나, “이용자”로부터 정보를 제공받기로 동의한 “이용자”의 고객을 말한다.\r\n⑭ 정보 : “이용자”가 “고객”과 상거래를 함에 있어 “고객”이 인지하여야 할 필요성이 있는 재화, 용역에 대한 정보 및 상거래 진행 과정과 관련된 정보로서 홍보성 정보를 포함하지 않은 것을 말한다. 이는 “비즈알림톡” 중 알림톡서비스 또는 단문메시지(SMS)로 발송이 가능하다.\r\n⑮ 카카오톡 : 주식회사 카카오에서 제공하고 있는 모바일 채팅 메신저이다.\r\n⑯ 공식딜러 : “비즈알림톡”의 영업과 판매를 ㈜카카오로부터 위탁 받아 제반 업무를 수행할 수 있도록 ㈜카카오와 계약을 체결한 업체를 말한다.\r\n⑰ 템플릿 : “비즈알림톡” 발송에 반복적으로 사용하는 문구를 일정한 작성 규칙에 따라 고정적인 표현 영역과 가변적인 표현 영역을 구분하여 표시한 것이다.\r\n⑱ 광고 : “카카오톡”의 플러스친구 또는 옐로아이디로 친구를 맺은 “고객”에게 홍보/광고 정보를 발송하는 것을 말하며, 이는 “비즈메시지” 중 친구톡서비스로 발송이 가능하다.\r\n\r\n제 3 조 [약관의 게시와 개정]\r\n\r\n1. 회사는 이 약관의 내용을 이용자가 쉽게 알 수 있도록 서비스 초기 화면에 게시합니다.\r\n2. 회사는 \"약관규제에관한법률\", \"정보통신망이용촉진및정보보호등에관한법률(이하 \"정보통신망법\")\", \"전자상거래 등에서의 소비자보호에관한법률\" 등 관련법을 위배하지 않는 범위에서 이 약관을 개정할 수 있습니다.\r\n3. 회사가 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행약관과 함께 제1항의 방식에 따라 그 개정약관의 적용일자 7일 전부터 적용일자 전까지 공지합니다. 다만, 이용자의 권리 또는 의무에 관한 중요한 규정의 변경은 최소한 30일 전에 공지하고 일정기간 서비스내 공지사항, 전자우편, 로그인시 동의창 등의 전자적 수단을 통해 따로 명확히 통지하도록 합니다.\r\n4. 회사가 전항에 따라 개정약관을 공지 또는 통지하면서 이용자에게 약관 변경 적용일까지 거부의사를 표시하지 않으면 동의한 것으로 본다는 뜻을 명확하게 공지 또는 통지하였음에도 이용자가 명시적으로 거부의 의사표시를 하지 아니한 경우 이용자가 개정약관에 동의한 것으로 봅니다.\r\n5. 이용자가 개정약관의 적용에 동의하지 않을 경우 회사는 개정약관의 내용을 적용할 수 없으며, 이 경우 이용자는 이용계약을 해지할 수 있습니다. 다만, 기존 약관을 적용할 수 없는 특별한 사정이 있는 경우에는 회사는 이용계약을 해지할 수 있습니다.\r\n6. 이용자는 약관의 변경에 대하여 주의의무를 다하여야 하며 변경된 약관으로 인한 이용자의 피해는 회사가 책임지지 않습니다.\r\n7. 이 약관의 적용기간은 이용자의 가입일부터 해지일까지로 규정합니다. 단, 채권 또는 채무관계가 있을 경우에는 채권, 채무의 완료일 까지로 규정합니다.\r\n\r\n제 4 조 [이용계약 체결]\r\n\r\n1. 이용계약은 이용자가 되고자 하는 자(이하 \"가입신청자\")가 약관의 내용에 대하여 동의를 한 다음 회원가입신청을 하고 회사가 이러한 신청에 대하여 승낙함으로써 체결됩니다.\r\n2. 회사는 가입신청자의 신청에 대하여 “비즈알림톡” 이용을 승낙함을 원칙으로 합니다. 다만, 회사는 다음 각 호에 해당하는 신청에 대하여는 승낙을 하지 않거나 사후에 이용계약을 해지할 수 있습니다.\r\n① 가입신청자가 이 약관에 의하여 이전에 이용자 자격을 상실한 적이 있는 경우. 단 회사의 이용자 재가입 승낙을 얻을 경우에는 예외로 함.\r\n② 실명이 아니거나 타인의 명의를 이용한 경우\r\n③ 허위의 정보를 기재하거나, 회사가 제시하는 내용을 기재하지 않은 경우\r\n④ 만 14세 미만의 가입신청자인 경우\r\n⑤ 가입신청자가 “비즈알림톡”의 정상적인 제공을 저해하거나 다른 이용자의 “비즈알림톡” 이용에 지장을 줄 것으로 예상되는 경우\r\n⑥ 가입신청자의 귀책사유로 인하여 승인이 불가능하거나 기타 규정한 제반 사항을 위반하며 신청하는 경우\r\n⑦ 기타 회사가 관련법령 등을 기준으로 하여 명백하게 사회질서 및 미풍양속에 반할 우려가 있음을 인정하는 경우\r\n⑧ 회사가 제공하는 모든 “비즈알림톡” 서비스 중 어느 하나에 대하여 제17조[계약해지] 제2항에 의하여 회사로부터 계약해지를 당한 이후 1년이 경과하지 않은 경우\r\n3. 제1항에 따른 신청에 있어 회사는 가입신청자의 종류에 따라 전문기관을 통한 실명확인 및 본인인증을 요청할 수 있습니다.\r\n4. 회사는 “비즈알림톡” 관련 설비의 여유가 없거나 기술상 또는 업무상 문제가 있는 경우에는 승낙을 유보할 수 있습니다.\r\n5. 이용계약의 성립 시기는 회사가 가입완료를 신청절차 상에서 표시한 시점으로 합니다.\r\n6. 회사는 가입신청에 대해 회사정책에 따라 등급별로 구분하여 이용시간, 이용횟수, “비즈알림톡” 서비스 메뉴 등을 세분하여 이용에 차등을 둘 수 있습니다.\r\n7. 회사는 “비즈알림톡”의 대량이용 등 특별한 이용에 관한 계약은 별도 계약을 통하여 제공합니다.\r\n\r\n제 5 조 [개인정보 수집]\r\n\r\n1. 회사는 적법하고 공정한 수단에 의하여 이용계약의 성립 및 이행에 필요한 최소한의 개인정보를 수집합니다.\r\n2. 회사는 개인정보의 수집 시 관련법규에 따라 개인정보처리방침에 그 수집범위 및 목적을 사전 고지합니다.\r\n\r\n제 6 조 [개인정보보호 의무]\r\n\r\n회사는 \"정보통신망법\" 등 관계 법령이 정하는 바에 따라 이용자의 개인정보를 보호하기 위해 노력합니다. 개인정보의 보호 및 사용에 대해서는 관련법 및 회사의 개인정보처리방침이 적용됩니다. 다만, 회사의 공식 사이트 이외의 링크된 사이트에서는 회사의 개인정보처리방침이 적용되지 않습니다.\r\n\r\n제 7 조 [이용자의 아이디 및 비밀번호의 관리에 대한 의무]\r\n\r\n1. 이용자의 아이디와 비밀번호에 관한 관리책임은 이용자에게 있으며 이를 제3자가 이용하도록 하여서는 안 됩니다.\r\n2. 회사는 이용자의 아이디가 개인정보 유출 우려가 있거나 반사회적 또는 미풍양속에 어긋나거나 회사 및 회사의 운영자로 오인할 우려가 있는 경우 해당 아이디의 활용을 제한할 수 있습니다.\r\n3. 이용자는 아이디 및 비밀번호가 도용되거나 제3자가 사용하고 있음을 인지한 경우에는 이를 즉시 회사에 통지하고 회사의 안내에 따라야 합니다.\r\n4. 제3항의 경우에 해당 이용자가 회사에 그 사실을 통지하지 않거나 통지한 경우에도 회사의 안내에 따르지 않아 발생한 불이익에 대하여 회사는 책임지지 않습니다.\r\n\r\n제 8 조 [이용자정보의 변경]\r\n\r\n1. 이용자는 개인정보관리화면을 통하여 언제든지 본인의 개인정보를 열람하고 수정할 수 있습니다. 다만, “비즈알림톡” 서비스 관리를 위해 필요한 실명, 아이디 등은 수정이 불가능합니다.\r\n2. 이용자는 회원가입신청 시 기재한 사항이 변경되었을 경우 사이트에 접속하여 변경사항을 수정하여야 합니다.\r\n3. 제2항의 변경사항을 수정하지 않아 발생한 불이익에 대하여 회사는 책임지지 않습니다.\r\n\r\n제 9 조 [이용자에 대한 통지]\r\n\r\n1. 회사가 이용자에 대한 통지를 하는 경우 이 약관에 별도 규정이 없는 한 이용자의 등록된 이메일, 문자메시지 등으로 통지할 수 있습니다.\r\n2. 회사는 전체 또는 불특정 다수 이용자에 대한 통지의 경우 7일 이상 회사의 홈페이지 등에 게시함으로써 제1항의 통지에 갈음할 수 있습니다.\r\n\r\n제 10 조 [회사의 의무]\r\n\r\n1. 회사는 관련법과 이 약관이 금지하거나 미풍양속에 반하는 행위를 하지 않으며, 계속적이고 안정적으로 “비즈알림톡” 서비스를 제공하기 위하여 최선을 다하여 노력합니다.\r\n2. 회사는 이용자가 안전하게 “비즈알림톡”을 이용할 수 있도록 개인정보(신용정보 포함)보호를 위해 보안시스템을 갖추어야 하며 개인정보처리방침을 공시하고 준수합니다.\r\n3. 회사는 “비즈알림톡” 제공과 관련하여 알고 있는 이용자의 개인정보를 본인의 승낙 없이 제3자에게 누설, 배포하지 않습니다. 다만, 관계법령에 의한 관계기관으로부터의 요청 등 법률의 규정에 따른 적법한 절차에 의한 경우에는 그러하지 않습니다.\r\n4. 회사는 “비즈알림톡” 제공목적에 맞는 “비즈알림톡” 이용 여부를 확인하기 위하여 상시적으로 모니터링을 실시합니다.\r\n5. 회사는 스팸메세지 수신거부 처리 등 스팸메세지 관련 민원을 자체적으로 처리하기 위한 고객센터를 운영합니다.\r\n6. 회사는 이용자가 스팸메세지ㆍ문자피싱메세지를 전송 등 불법행위를 한 사실을 확인한 경우 한국인터넷진흥원 불법스팸대응센터 등 관계기관에 관련 자료를 첨부하여 신고할 수 있습니다.\r\n7. 회사는 이용자에게 제공하는 유료 및 무료 “비즈알림톡” 서비스를 계속적이고 안정적으로 제공하기 위하여 설비에 장애가 생기거나 멸실되었을 때 지체 없이 이를 수리 또는 복구합니다. 다만, 천재지변이나 비상사태 등 부득이한 경우에는 서비스를 일시 중단하거나 영구 중지할 수 있습니다.\r\n8. 회사는 스팸메세지ㆍ문자피싱메세지ㆍ발신번호조작 등으로 인지되는 문자메시지에 대해서 차단을 할 수 있습니다.\r\n9. 회사는 이용고객의 회원가입 시 타인의 명의를 도용한 부정가입을 방지하기 위해 본인인증 서비스 사업자가 제공하는 인증방법 또는 대면인증을 통해 본인인증서비스를 제공ㆍ운영 합니다.\r\n10. 회사는 이용자가 발신번호 사전등록 및 본인인증절차, 템플릿등록을 거친 후 “비즈알림톡”을 제공합니다.\r\n\r\n제 11 조 [이용자의 의무]\r\n\r\n1. 이용자는 다음 행위를 하여서는 안 됩니다.\r\n① 신청 또는 변경시 허위내용을 등록하는 행위\r\n② 타인의 정보를 도용하는 행위\r\n③ 다른 이용자의 개인정보를 그 동의 없이 수집, 저장, 공개하는 행위\r\n④ 회사가 게시한 정보를 변경하거나 제3자에게 제공하는 행위\r\n⑤ 회사와 기타 제3자의 저작권 등 지적재산권에 대한 침해 행위\r\n⑥ 회사 및 기타 제3자의 명예를 손상시키거나 업무를 방해하는 행위\r\n⑦ 외설 또는 폭력적인 메시지, 팩스, 음성, 메일, 기타 공서양속에 반하는 정보를 서비스에 공개 또는 게시하는 행위\r\n⑧ 회사의 동의 없이 영리를 목적으로 서비스를 사용하는 행위\r\n⑨ 타인의 의사에 반하는 내용을 지속적으로 전송하는 행위\r\n⑩ 범죄행위를 목적으로 하거나 범죄행위를 교사하는 행위\r\n⑪ 선량한 풍속 또는 기타 사회질서를 해치는 행위\r\n⑫ 현행 법령, 회사가 제공하는 “비즈알림톡”에 정한 약관, 이용안내 및 “비즈알림톡”과 관련하여 공지한 주의사항, 회사가 통지하는 사항, 기타 “비즈알림톡” 이용에 관한 규정을 위반하는 행위\r\n⑬ “비즈알림톡”의 안정적인 운영에 지장을 주거나 줄 우려가 있는 일체의 행위\r\n⑭ 제3자에게 임의로 “비즈알림톡”을 임대하는 행위\r\n⑮ 기타 불법적이거나 부당한 행위\r\n2. 회사는 이용자가 제1항의 행위를 하는 경우 이용자의 “비즈알림톡” 이용을 정지하고 일방적으로 본 계약을 해지할 수 있습니다.\r\n3. 이용자는 정보통신망법의 광고성 정보 전송 시 의무사항 및 회사의 이용약관을 준수하여야 합니다.\r\n4. 이용자는 스팸메세지ㆍ문자피싱메세지 전송 등 불법행위를 하거나 전기통신사업법 등 관련 법령을 준수하지 않아 발생하는 모든 민ㆍ형사 상의 책임을 부담합니다.\r\n5. 회사는 이용자가 본인 명의가 아닌 타인의 전화번호를 부정하게 사용하는 경우에 “비즈알림톡”의 전부 또는 일부의 이용을 제한할 수 있습니다. 단, 회사는 전단의 “비즈알림톡” 서비스 차단 후 지체 없이 당해 차단 사실을 이용자에게 통지합니다.\r\n6. 본 조 제 5항의 경우, 회사는 차단된 “비즈알림톡” 서비스에 관한 자료(변작된 발신번호, 차단시각, 전송자명 등)를 1년간 보관, 관리하고 이를 한국인터넷진흥원 등 관계기관에 제출할 수 있습니다.\r\n7. 이용자는 회원가입 시 부정가입 방지를 위해 회사가 제공하는 본인인증방법으로 본인인증을 거친 후 “비즈알림톡”을 이용하여야 합니다.\r\n\r\n제 12 조 [동의의 철회]\r\n\r\n회사는 이용자가 “비즈알림톡” 서비스 화면에서 자신의 개인정보에 대한 수집, 이용 또는 제공에 대한 동의를 철회 할 수 있도록 필요한 조치를 취해야 합니다.\r\n\r\n제 13 조 [불만처리]\r\n\r\n1. 회사는 개인정보와 관련하여 이용자의 의견을 수렴하고 불만을 처리하기 위한 절차를 마련하여야 합니다.\r\n2. 회사는 전화, 전자우편 또는 “비즈알림톡” 서비스 화면의 상담창구를 통하여 이용고객의 불만사항을 접수, 처리 하여야 합니다.\r\n\r\n제 14 조 [“비즈알림톡” 제공]\r\n\r\n1. 회사는 이용자에게 아래와 같은 “비즈알림톡”을 제공합니다.\r\n① “알림톡” 대량발송 서비스\r\n② “친구톡” 대량발송 서비스\r\n③ “문자메시지” 대량발송 서비스\r\n④ “알림톡” 및 “친구톡” 발송 실패시 “문자메시지” 대체 발송서비스\r\n⑤ 기타 회사가 추가 개발하거나 다른 회사와의 제휴계약 등을 통해 이용자에게 제공하는 일체의 서비스\r\n2. 회사는 “비즈알림톡”을 일정범위로 분할하여 각 범위 별로 이용가능시간을 별도로 지정할 수 있습니다. 다만 이러한 경우에는 그 내용을 사전에 공지합니다.\r\n3. “비즈알림톡”은 연중무휴, 1일 24시간 제공함을 원칙으로 합니다.\r\n4. 회사는 컴퓨터 등 정보통신설비의 보수점검ㆍ교체 및 고장, 통신두절 또는 운영상의 상당한 이유가 있는 경우 “비즈알림톡”의 제공을 일시적으로 중단할 수 있습니다. 이 경우 회사는 제9조[이용자에 대한 통지]에 정한 방법으로 이용자에게 통지합니다. 다만, 회사가 사전에 통지할 수 없는 부득이한 사유가 있는 경우 사후에 통지할 수 있습니다.\r\n5. 회사는 “비즈알림톡”의 제공에 필요한 경우 정기점검을 실시할 수 있으며 정기점검시간은 “비즈알림톡” 서비스화면에 공지한 바에 따릅니다.\r\n\r\n제 15 조 [“비즈알림톡”의 변경]\r\n\r\n1. 회사는 상당한 이유가 있는 경우에 운영상, 기술상의 필요에 따라 제공하고 있는 전부 또는 일부 “비즈알림톡” 서비스를 변경할 수 있습니다.\r\n2. “비즈알림톡” 서비스의 내용, 이용방법, 이용시간에 대하여 변경이 있는 경우에는 변경사유, 변경될 “비즈알림톡” 서비스의 내용 및 제공일자 등을 그 변경 전에 해당 “비즈알림톡” 서비스 초기화면에 게시하여야 합니다.\r\n3. 회사는 무료로 제공되는 “비즈알림톡”의 일부 또는 전부를 회사의 정책 및 운영의 필요상 수정, 중단, 변경할 수 있으며 이에 대하여 관련법에 특별한 규정이 없는 한 이용자에게 별도의 보상을 하지 않습니다.\r\n\r\n제 16 조 [“비즈알림톡” 이용의 제한 및 정지]\r\n\r\n1. 회사는 이용자가 이 약관의 의무를 위반하거나 “비즈알림톡”의 정상적인 운영을 방해한 경우 “비즈알림톡” 이용을 제한하거나 정지할 수 있습니다.\r\n2. 회사는 전항에도 불구하고 주민등록법을 위반한 명의도용 및 결제도용, 저작권법을 위반한 불법프로그램의 제공 및 운영방해, 정보통신망법을 위반한 스팸메세지 및 불법통신, 해킹, 악성프로그램의 배포, 접속권한 초과행위 등과 같이 관련법을 위반한 경우에는 즉시 영구이용정지를 할 수 있습니다. 본 항에 따른 “비즈알림톡” 서비스 이용정지 시 “비즈알림톡” 내의 선불충전금액, 포인트, 혜택 및 권리 등도 모두 소멸되며 회사는 이에 대해 별도로 보상하지 않습니다.\r\n3. 회사는 이용자가 다음 중 하나에 해당하는 경우 1개월 동안의 기간을 정하여 당해 “비즈알림톡”의 이용을 정지 할 수 있습니다.\r\n① 방송통신위원회ㆍ한국인터넷진흥원ㆍ미래창조과학부 등 관계기관이 스팸메세지ㆍ문자피싱메세지 등 불법행위의 전송사실을 확인하여 이용정지를 요청하는 경우\r\n② 이용자가 전송하는 광고로 인하여 회사의 “비즈알림톡” 제공에 장애를 야기하거나 야기할 우려가 있는 경우\r\n③ 이용자가 전송하는 광고의 수신자가 스팸메세지로 신고하는 경우\r\n④ 이용자에게 제공하는 “비즈알림톡”이 스팸메세지 전송에 이용되고 있는 경우\r\n⑤ 이용자가 제11조[이용자의 의무] 제5항을 위반하여 발신번호를 변작하는 등 거짓으로 표시한 경우\r\n⑥ 미래창조과학부장관 또는 한국인터넷진흥원 등 관련 기관이 발신번호 변작 등을 확인하여 이용 정지를 요청하는 경우\r\n4. 회사는 이용자의 정보가 부당한 목적으로 사용되는 것을 방지하고 보다 원활한 “비즈알림톡” 서비스 제공을 위하여 12개월 이상 계속해서 로그인을 포함한 “비즈알림톡” 이용이 없는 아이디를 휴면아이디로 분류하고 “비즈알림톡” 이용을 정지할 수 있습니다.\r\n5. 휴면아이디로 분류되기 30일 전까지 전자우편 등으로 휴면아이디로 분류된다는 사실, 일시 및 개인정보 항목을 이용자에게 통지합니다. 휴면아이디로 분류 시 개인정보는 “비즈알림톡”에서 이용중인 개인정보와 별도 분리하여 보관합니다. 보관되는 정보는 보관 외 다른 목적으로 이용되지 않으며, 관련 업무 담당자만 열람할 수 있도록 접근을 제한 합니다.\r\n6. 이용자는 휴면아이디 보관기간 내에 로그인을 통해 휴면아이디 상태를 해제할 수 있습니다.\r\n7. 회사는 스팸메세지ㆍ문자피싱메세지 전송을 방지하기 위하여 “비즈알림톡”의 일일 발송량을 제한 할 수 있으며 자체 모니터링을 강화할 수 있습니다.\r\n\r\n제 17 조 [계약해지]\r\n\r\n1. 이용자는 이용계약을 해지 하고자 할 때 본인이 직접 “비즈알림톡” 해당화면을 통하여 신청할 수 있으며 회사는 관련법 등이 정하는 바에 따라 이를 즉시 처리하여야 합니다.\r\n2. 회사는 이용자가 다음 각 호에 해당할 경우에는 이용자의 동의 없이 이용계약을 해지할 수 있으며 그 사실을 이용자에게 통지합니다. 다만 회사가 긴급하게 해지할 필요가 있다고 인정하는 경우나 이용자의 귀책사유로 인하여 통지할 수 없는 경우에는 통지를 생략할 수 있습니다.\r\n① 이용자가 이 약관을 위반하고 일정 기간 이내에 위반 내용을 해소하지 않는 경우\r\n② 회사의 “비즈알림톡” 제공목적 외의 용도로 서비스를 이용하거나 제3자에게 임의로 “비즈알림톡” 서비스를 임대한 경우\r\n③ 방송통신위원회ㆍ한국인터넷진흥원ㆍ미래창조과학부 등 관계기관이 스팸메세지ㆍ문자피싱메세지 등 불법행위의 전송사실을 확인하여 계약해지를 요청하는 경우\r\n④ 제11조[이용자의 의무] 규정을 위반한 경우\r\n⑤ 제16조[“비즈알림톡” 이용의 제한 및 정지] 규정에 의하여 이용정지를 당한 이후 1년 이내에 이용정지 사유가 재발한 경우\r\n⑥ 회사의 이용요금 등의 납입청구에 대하여 이용자가 이용요금을 체납할 경우\r\n3. 회사는 휴면아이디가 휴면상태로 2년 이상 지속될 경우 본 계약을 해지할 수 있습니다. 단, 이용잔액의 상사소멸시효가 휴면상태로 2년이 지난 시점에 완성되는 경우 회사는 이용잔액의 상사소멸시효가 완성된 후에 계약을 해지할 수 있습니다.\r\n4. 이용자 또는 회사가 계약을 해지할 경우 관련법 및 개인정보처리방침에 따라 회사가 이용자정보를 보유하는 경우를 제외하고는 해지 즉시 이용자의 모든 데이터는 소멸됩니다.\r\n5. “비즈알림톡” 서비스의 양도, 서비스 종료(폐업) 등에 해당하는 경우, 이를 최소 7일 전에 이용자에게 공지 및 통보합니다.\r\n\r\n제 18 조 [“비즈알림톡” 발송량 제한]\r\n\r\n회사는 이용자의 아이디 당 “비즈알림톡”의 1일 전송량을 1만통 이내로 제한할 수 있습니다. 다만, 이용자의 “고객”의 불편을 최소화하기 위하여 회사와 사전 협의를 한 경우 발송량 제한 없이 발송 가능하도록 예외를 둘 수 있습니다.\r\n\r\n제 19 조 [각종 자료의 저장기간]\r\n\r\n회사는 “비즈알림톡” 서비스 별로 이용자가 필요에 의해 저장하고 있는 자료에 대하여 일정한 저장기간을 정할 수 있으며 필요에 따라 그 기간을 변경할 수 있습니다.\r\n\r\n제 20 조 [게시물의 저작권]\r\n\r\n1. 이용자가 “비즈알림톡” 서비스 페이지에 게시하거나 등록한 자료의 지적재산권은 이용자에게 귀속됩니다. 단, 회사는 “비즈알림톡” 홈페이지의 게재권을 가지며 회사의 “비즈알림톡” 서비스 내에 한하여 이용자의 게시물을 활용할 수 있습니다.\r\n2. 이용자는 “비즈알림톡”을 이용하여 얻은 정보를 가공, 판매하는 행위 등 게재된 자료를 상업적으로 이용할 수 없으며 이를 위반하여 발생하는 제반 문제에 대한 책임은 이용자에게 있습니다.\r\n\r\n제 21 조 [요금 등의 계산]\r\n\r\n1. 회사가 제공하는 유료서비스 이용과 관련하여 이용자가 납부하여야 할 요금은 이용료 안내에 게재한 바에 따릅니다.\r\n2. 요금 등은 “비즈알림톡” 서비스 별로 정하는 바에 따라 선불충전을 기본으로 하며 필요에 따라 회사와 별도 계약을 통하여 후불제로 변경할 수 있습니다.\r\n\r\n제 22 조 [불법 면탈 요금의 청구]\r\n\r\n1. 이용자가 불법으로 이용요금 등을 면탈할 경우에는 면탈한 금액의 2배에 해당하는 금액을 청구합니다.\r\n2. 후불제에 한하여 회사는 2개월 이상 요금이 연체된 이용자를 신용기관에 신용불량자로 등록할 수 있습니다.\r\n\r\n제 23 조 [요금 등의 이의신청]\r\n\r\n1. 이용자는 청구된 요금 등에 대하여 이의가 있는 경우 청구일로부터 3개월 이내에 이의 신청을 할 수 있습니다.\r\n2. 회사는 제1항의 이의 신청 접수 후 2주 이내에 해당 이의신청의 타당성 여부를 조사하여 그 결과를 이용자에게 통지합니다.\r\n3. 부득이한 사유로 인하여 제2항에서 정한 기간 내에 이의신청결과를 통지할 수 없는 경우에는 그 사유와 재 지정된 처리기한을 명시하여 이용자에게 통지합니다.\r\n\r\n제 24 조 [요금 등의 반환]\r\n\r\n1. 회사는 요금 등의 과납 또는 오납이 있을 때에는 그 과납 또는 오납된 요금을 반환하고, 회사의 귀책사유로 발생한 경우에는 법정이율로서 적정이자를 함께 반환합니다.\r\n2. 선불충전에 한하여 이용자는 “비즈알림톡”을 이용하기 전 선납된 요금 등의 반환을 요청할 수 있습니다. 이러한 경우 회사는 이용자가 반환을 요청한 시점으로부터 익월 말일까지 이용자가 결제한 은행계좌로 반환요청 요금을 반환합니다. 단, 요금반환은 반환요청 금액이 최소1만원 이상인 경우에만 가능하며 요금 반환 시 결제수수료를 차감할 수 있습니다.\r\n\r\n제 25 조 [손해배상의 범위 및 청구]\r\n\r\n1. 회사는 회사의 귀책사유로 이용자가 유료 서비스를 이용하지 못하는 경우에는 다음과 같이 배상합니다.\r\n① 건수별 요금을 부과하는 “비즈알림톡”에 대해서는 발송신청 하였으나 2시간 동안 발송 완료되지 않은 “비즈알림톡” 또는 수신되지 않은 “비즈알림톡”은 발송을 완료한 후에도 이용요금을 부과하지 않습니다. \r\n② “비즈알림톡” 중 24시간을 초과하여 발송 완료되지 않는 “비즈알림톡”에 대해서는 발송을 완료한 후에도 건수별 요금의 2배를 배상합니다.\r\n③ 손해배상으로 지불되는 금액의 총액은 어떠한 경우에도 이용자가 지불한 이용요금의 2배를 초과할 수 없습니다.\r\n2. 회사가 제공하는 “비즈알림톡” 중 무료서비스의 경우에는 손해배상에 해당되지 않습니다.\r\n3. 회사는 그 손해가 천재지변 등 불가항력이거나 이용자의 고의 또는 과실로 인하여 발생된 때에는 손해배상을 하지 않습니다.\r\n4. 손해배상의 청구는 회사에 청구사유, 청구금액 및 산출근거를 기재하여 전자우편, 전화 등으로 신청하여야 합니다.\r\n5. 회사 및 타인에게 피해를 주어 피해자의 고발 또는 소송 제기로 인하여 손해배상이 청구된 이용자는 이에 응하여야 합니다.\r\n\r\n제 26 조 [면책]\r\n\r\n1. 회사는 다음 각 호의 경우로 “비즈알림톡”을 제공할 수 없는 경우 이로 인하여 이용자에게 발생한 손해에 대해서는 책임을 부담하지 않습니다.\r\n① 천재지변 또는 이에 준하는 불가항력의 상태가 있는 경우\r\n② “비즈알림톡”의 효율적인 제공을 위한 시스템 개선, 장비 증설 등 계획된 “비즈알림톡” 서비스 중지 일정을 사전에 공지한 경우\r\n③ “비즈알림톡” 제공을 위하여 회사와 “비즈알림톡” 제휴계약을 체결한 제3자의 고의적인 방해가 있는 경우\r\n④ 이용자의 귀책사유로 “비즈알림톡” 이용에 장애가 있는 경우\r\n⑤ 회사의 고의 과실이 없는 사유로 인한 경우\r\n2. 회사는 이용자가 “비즈알림톡”을 통해 얻은 정보 또는 자료 등으로 인해 발생한 손해와 “비즈알림톡”을 이용하거나 이용할 것으로부터 발생하거나 기대하는 손익 등에 대하여 책임을 면합니다.\r\n3. 회사는 이용자가 게시 또는 전송한 자료의 내용에 대해서는 책임을 면합니다.\r\n4. 회사는 이용자 상호간 또는 이용자와 제3자 상호간에 “비즈알림톡”을 매개로 하여 물품거래 등을 한 경우에는 책임을 면합니다.\r\n5. 회사는 무료로 제공하는 “비즈알림톡” 서비스에 대하여 회사의 귀책사유로 이용자에게 “비즈알림톡” 서비스를 제공하지 못하는 경우 책임을 면합니다.\r\n6. 이 약관의 적용은 이용계약을 체결한 이용자에 한하며 제3자로부터의 어떠한 배상, 소송 등에 대하여 회사는 책임을 면합니다.\r\n\r\n제 27 조 [분쟁조정]\r\n\r\n1. 본 약관은 대한민국법령에 의하여 규정되고 이행됩니다.\r\n2. “비즈알림톡” 이용과 관련하여 회사와 이용자 간에 발생한 분쟁에 대해서는 민사소송법상의 주소지를 관할하는 법원을 합의관할로 합니다.\r\n\r\n* 부 칙 *\r\n\r\n1. 이 약관은 2016년 10월 1일부터 적용됩니다.\r\n\r\n\r\n서비스이용약관 버전번호 : v1.0\r\n서비스이용약관 시행일자 : 2016년 10월 01일\r\n\r\n“비즈알림톡” 서비스 이용료 안내\r\n\r\n■ 발송건수별 단가표\r\n1. 발송건수 : 10만건 미만\r\n2. 공급가액\r\n (1) 서비스명\r\n    - 카카오 알림톡 : 12원\r\n    - 카카오 친구톡(텍스트) : 17원\r\n    - 카카오 친구톡(이미지) : 30원\r\n    - 단문(SMS) : 12원\r\n    - 장문(LMS) : 30원\r\n    - 멀티(MMS) : 90원\r\n (2) 비고 : 이메일을 통한 기술 지원\r\n\r\n1. 상기 이용 금액은 부가세 별도 금액입니다.\r\n2. 알림톡, 친구톡 및 문자메시지는 1회 발송 금액입니다.\r\n3. 시스템 연동과 관련하여 유선 및 이메일을 통한 기술 지원이 가능합니다.\r\n4. 총 발송건수는 모든 카카오 비즈메시지 및 문자메시지를 합산하여 산정합니다.\r\n5. 카카오 비즈메시지 중 알림톡을 사용하지 않고 친구톡 및 문자메시지만 사용할 경우에는 발송량에 따라 기본 요금이 적용됩니다.\r\n6. 월 발송량이 10만건 이상일 경우에는 별도 단가협의를 진행합니다.\r\n7. 문자메시지는 이용자의 필요에 의해 선택 할 수 있습니다.\r\n8. 월 기본료는 요금제에 포함되어 있습니다.'),('member_register_policy2','개요 (필수고지사항)\r\n정보통신망법 규정에 따라 비즈알림톡에 회원가입 신청하시는 분께 수집하는 개인정보의 항목, 개인정보의 수집 및 이용목적, 개인정보의 보유 및 이용기간을 안내 드리오니 자세히 읽은 후 동의하여 주시기 바랍니다.\r\n\r\n1.수집하는 개인정보의 항목\r\n비즈알림톡은 회원가입, 상담, 서비스신청 등을 위해 아래와 같은 개인정보를 수집하고 있습니다.\r\n\r\n① 회원 : 아이디, 비밀번호, 이름, 이메일주소, 핸드폰번호, 서비스 이용기록, 접속 로그, 쿠키, 접속 IP 정보, 사업자 등록증, 옐로아이디\r\n② 수집방법 : 홈페이지를 통한 회원가입, 고객센터, 유료결제\r\n\r\n2. 개인정보의 수집 및 이용목적\r\n회사는 수집한 개인정보를 다음의 목적을 위해 활용합니다. 회원이 제공한 모든 정보는 하기 목적에 필요한 용도 이외로는 사용되지 않으며 이용 목적이 변경될 시에는 사전 동의를 구할 것입니다.\r\n\r\n① 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산\r\n  - 콘텐츠 제공, 구매 및 요금결제, 요금반환 등\r\n② 회원관리\r\n  - 회원제 서비스 제공, 개인식별, 불량회원의 부정 이용방지와 비인가 사용방지, 가입의사 확인, 가입 및 가입횟수 제한, 연령확인, 분쟁 조정을 위한 기록보존, 불만처리 등 민원처리, 고지사항 전달, 회원탈퇴 의사의 확인\r\n③ 마케팅 및 광고에 활용\r\n  - 신규 서비스 개발과 이벤트 행사에 따른 정보 전달 및 맞춤 서비스 제공, 인구통계학적 특성에 따른 서비스 제공 및 광고 게재, 접속 빈도 파악 또는 회원의 서비스 이용에 대한 통계 \r\n\r\n3.개인정보의 보유기간 및 이용기간\r\n① 이용자의 개인정보는 이용자가 회원탈퇴를 요청하거나 제공한 개인정보의 수집 및 이용에 대한 동의를 철회하는 경우, 또는 개인정보의 수집 및 이용목적이 달성되면 지체 없이 파기합니다. 단, 다음의 정보에 대해서는 보존근거에서 명시한 근거에 따라 보존기간 동안 보존합니다.\r\n\r\n가. 보존항목 : 아이디, 비밀번호, 이름, 이메일주소, 핸드폰번호, 서비스 이용기록, 접속 로그, 쿠키, 접속 IP 정보, 사업자 등록증, 옐로아이디, 결제기록,\r\n나. 보존근거 : 회원 탈퇴 시 소비자의 불만 및 분쟁해결 등을 위한 목적, 부정 이용 방지, 불법적 이용자에 대한 관련 기관 수사협조\r\n다. 보존기간 : 3개월\r\n\r\n② 상법, 전자상거래 등에서의 소비자보호에 관한 법률 등 관계 법령의 규정에 의해 보존할 필요성이 있는 경우, 회사는 관계법령에서 정한 일정한 기간 이상 이용자의 개인정보를 보관할 수 있습니다. 이 경우 회사는 보관하는 정보를 그 보관의 목적으로만 이용하며, 보존근거에서 명시한 근거에 따라 보존기간 동안 보존합니다.\r\n\r\n1. 계약 또는 청약철회 등에 관한 기록\r\n   ㆍ 보존근거: 전자상거래 등에서의 소비자보호에 관한 법률\r\n   ㆍ 보존기간: 5년\r\n2. 대금결제 및 재화 등의 공급에 관한 기록\r\n   ㆍ 보존근거: 전자상거래 등에서의 소비자보호에 관한 법률\r\n   ㆍ 보존기간: 5년\r\n3. 전자금융 거래에 관한 기록\r\n   ㆍ 보존근거: 전자금융거래법\r\n   ㆍ 보존기간: 5년\r\n4. 소비자의 불만 또는 분쟁처리에 관한 기록\r\n   ㆍ 보존근거: 전자상거래 등에서의 소비자보호에 관한 법률\r\n   ㆍ 보존기간: 3년\r\n5. 본인확인에 관한 기록\r\n   ㆍ 보존근거: 정보통신망 이용촉진 및 정보보호 등에 관한 법률\r\n   ㆍ 보존기간: 6개월\r\n6. 표시/광고에 관한 기록\r\n   ㆍ 보존근거: 전자상거래 등에서의 소비자보호에 관한 법률\r\n   ㆍ 보존기간: 6개월\r\n7. 웹사이트 방문 기록\r\n   ㆍ 보존근거: 통신비밀보호법\r\n   ㆍ 보존기간: 3개월'),('mobile_layout_board',''),('mobile_layout_currentvisitor',''),('mobile_layout_default','bootstrap'),('mobile_layout_document',''),('mobile_layout_faq',''),('mobile_layout_findaccount',''),('mobile_layout_formmail',''),('mobile_layout_group',''),('mobile_layout_helptool',''),('mobile_layout_login',''),('mobile_layout_main',''),('mobile_layout_mypage',''),('mobile_layout_note',''),('mobile_layout_notification',''),('mobile_layout_pointranking',''),('mobile_layout_poll',''),('mobile_layout_profile',''),('mobile_layout_register',''),('mobile_layout_search',''),('mobile_layout_tag',''),('mobile_sidebar_board',''),('mobile_sidebar_currentvisitor',''),('mobile_sidebar_default',''),('mobile_sidebar_document',''),('mobile_sidebar_faq',''),('mobile_sidebar_findaccount',''),('mobile_sidebar_group',''),('mobile_sidebar_login',''),('mobile_sidebar_main',''),('mobile_sidebar_mypage',''),('mobile_sidebar_notification',''),('mobile_sidebar_pointranking',''),('mobile_sidebar_poll',''),('mobile_sidebar_register',''),('mobile_sidebar_search',''),('mobile_sidebar_tag',''),('mobile_skin_board',''),('mobile_skin_currentvisitor',''),('mobile_skin_default','bootstrap'),('mobile_skin_document',''),('mobile_skin_faq',''),('mobile_skin_findaccount',''),('mobile_skin_formmail',''),('mobile_skin_group',''),('mobile_skin_helptool',''),('mobile_skin_login',''),('mobile_skin_main',''),('mobile_skin_mypage',''),('mobile_skin_note',''),('mobile_skin_notification',''),('mobile_skin_pointranking',''),('mobile_skin_poll',''),('mobile_skin_popup','basic'),('mobile_skin_profile',''),('mobile_skin_register',''),('mobile_skin_search',''),('mobile_skin_tag',''),('naver_blog_api_key',''),('naver_syndi_key',''),('new_post_second','30'),('note_editor_type','smarteditor'),('note_list_page','10'),('note_mobile_list_page','10'),('notification_comment','1'),('notification_comment_comment','1'),('notification_note','1'),('notification_reply','1'),('open_currentvisitor',''),('password_length','8'),('password_numbers_length',''),('password_specialchars_length','1'),('password_uppercase_length',''),('payment_bank_info','우리은행 1002-439-302538 예금주:송종근'),('pg_inicis_key',''),('pg_inicis_mid','INIpayTest'),('pg_inicis_websign',''),('pg_kcp_key',''),('pg_kcp_mid',''),('pg_lg_key',''),('pg_lg_mid',''),('point_login',''),('point_note','10'),('point_note_file',''),('point_recommended',''),('point_recommender',''),('point_register',''),('popup_content_target_blank','1'),('popup_editor_type','smarteditor'),('popup_mobile_thumb_width','400'),('popup_thumb_width','700'),('post_editor_type','smarteditor'),('recaptcha_secret',''),('recaptcha_sitekey',''),('registerform','{\"mem_userid\":{\"field_name\":\"mem_userid\",\"func\":\"basic\",\"display_name\":\"\\uc544\\uc774\\ub514\",\"field_type\":\"text\",\"use\":\"1\",\"open\":\"1\",\"required\":\"1\"},\"mem_password\":{\"field_name\":\"mem_password\",\"func\":\"basic\",\"display_name\":\"\\ube44\\ubc00\\ubc88\\ud638\",\"field_type\":\"password\",\"use\":\"1\",\"open\":\"\",\"required\":\"1\"},\"mem_username\":{\"field_name\":\"mem_username\",\"func\":\"basic\",\"display_name\":\"\\uc774\\ub984\",\"field_type\":\"text\",\"use\":\"1\",\"open\":null,\"required\":\"1\"},\"mem_biz_no\":{\"field_name\":\"mem_biz_no\",\"func\":\"added\",\"display_name\":\"\\uc0ac\\uc5c5\\uc790\\ub4f1\\ub85d\\ubc88\\ud638\",\"field_type\":\"text\",\"use\":\"1\",\"open\":null,\"required\":\"1\",\"options\":\"\"},\"mem_nickname\":{\"field_name\":\"mem_nickname\",\"func\":\"basic\",\"display_name\":\"\\ub2c9\\ub124\\uc784\",\"field_type\":\"text\",\"use\":\"1\",\"open\":\"1\",\"required\":\"1\"},\"mem_phone\":{\"field_name\":\"mem_phone\",\"func\":\"basic\",\"display_name\":\"\\uc804\\ud654\\ubc88\\ud638\",\"field_type\":\"phone\",\"use\":\"1\",\"open\":null,\"required\":\"1\"},\"mem_email\":{\"field_name\":\"mem_email\",\"func\":\"basic\",\"display_name\":\"\\uc774\\uba54\\uc77c\\uc8fc\\uc18c\",\"field_type\":\"email\",\"use\":\"1\",\"open\":\"\",\"required\":\"1\"},\"mem_recommend\":{\"field_name\":\"mem_recommend\",\"func\":\"basic\",\"display_name\":\"\\ucd94\\ucc9c\\uc778\",\"field_type\":\"text\",\"use\":null,\"open\":\"\",\"required\":null},\"mem_homepage\":{\"field_name\":\"mem_homepage\",\"func\":\"basic\",\"display_name\":\"\\ud648\\ud398\\uc774\\uc9c0\",\"field_type\":\"url\",\"use\":null,\"open\":null,\"required\":null},\"mem_birthday\":{\"field_name\":\"mem_birthday\",\"func\":\"basic\",\"display_name\":\"\\uc0dd\\ub144\\uc6d4\\uc77c\",\"field_type\":\"date\",\"use\":null,\"open\":null,\"required\":null},\"mem_sex\":{\"field_name\":\"mem_sex\",\"func\":\"basic\",\"display_name\":\"\\uc131\\ubcc4\",\"field_type\":\"radio\",\"use\":null,\"open\":null,\"required\":null},\"mem_address\":{\"field_name\":\"mem_address\",\"func\":\"basic\",\"display_name\":\"\\uc8fc\\uc18c\",\"field_type\":\"address\",\"use\":null,\"open\":null,\"required\":null},\"mem_profile_content\":{\"field_name\":\"mem_profile_content\",\"func\":\"basic\",\"display_name\":\"\\uc790\\uae30\\uc18c\\uac1c\",\"field_type\":\"textarea\",\"use\":null,\"open\":null,\"required\":null}}'),('register_level','1'),('scheduler','{\"Sample_scheduler\":{\"library_name\":\"Sample_scheduler\",\"interval_field_name\":\"hourly\"}}'),('scheduler_interval','{\"hourly\":{\"field_name\":\"hourly\",\"interval\":\"3600\",\"display_name\":\"\\ub9e4\\uc2dc\\uac04\\ub9c8\\ub2e4\"},\"twicedaily\":{\"field_name\":\"twicedaily\",\"interval\":\"43200\",\"display_name\":\"\\ud558\\ub8e8\\uc5d02\\ubc88\"},\"daily\":{\"field_name\":\"daily\",\"interval\":\"86400\",\"display_name\":\"\\ud558\\ub8e8\\uc5d01\\ubc88\"}}'),('selfcert_kcb_mid',''),('selfcert_kcp_mid',''),('selfcert_lg_key',''),('selfcert_lg_mid',''),('selfcert_try_limit','5'),('send_email_blame_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_blame_admin_title','[{게시판명}] {게시글제목} - 신고가접수되었습니다'),('send_email_blame_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_blame_post_writer_title','[{게시판명}] {게시글제목} - 신고가접수되었습니다'),('send_email_changeemail_user_content','<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원님의 이메일 주소가 변경되어 알려드립니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>회원님의 이메일 주소가 변경되었으므로 다시 인증을 받아주시기 바랍니다.</p><p>&nbsp;</p><p>아래 링크를 클릭하시면 주소변경 인증이 완료됩니다.</p><p><a href=\"{메일인증주소}\" target=\"_blank\" style=\"font-weight:bold;\">메일인증 받기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_changeemail_user_title','[{홈페이지명}] 회원님의 이메일정보가 변경되었습니다'),('send_email_changepw_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 관리자님,</span><br /></td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>{회원닉네임} 님이 패스워드를 변경하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>변경한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr></table>'),('send_email_changepw_admin_title','{회원닉네임}님이 패스워드를 변경하셨습니다'),('send_email_changepw_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원님의 패스워드가 변경되었습니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원님의 패스워드가 변경되었습니다.</p><p>변경한 곳 IP : {회원아이피}</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_changepw_user_title','[{홈페이지명}] 패스워드가 변경되었습니다'),('send_email_comment_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_comment_admin_title','[{게시판명}] {게시글제목} - 댓글이 등록되었습니다'),('send_email_comment_blame_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />댓글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_comment_blame_admin_title','[{게시판명}] {게시글제목} - 댓글에신고가접수되었습니다'),('send_email_comment_blame_comment_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />댓글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_comment_blame_comment_writer_title','[{게시판명}] {게시글제목} - 댓글에신고가접수되었습니다'),('send_email_comment_blame_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />댓글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_comment_blame_post_writer_title','[{게시판명}] {게시글제목} - 댓글에신고가접수되었습니다'),('send_email_comment_comment_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_comment_comment_writer_title','[{게시판명}] {게시글제목} - 댓글이 등록되었습니다'),('send_email_comment_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_comment_post_writer_title','[{게시판명}] {게시글제목} - 댓글이 등록되었습니다'),('send_email_dormant_notify_user_content','<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tbody><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br>항상 믿고 이용해주시는 회원님께 깊은 감사를 드립니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>{정리기준} 이상 서비스를 이용하지 않은 계정 ‘정보통신망 이용 촉진 및 정보보호 등에 관한 법률 및 시행령 제16조에 따라 휴면 계정으로 전환되며, 해당 계정 정보는 별도 분리 보관될 예정입니다. </p><p>(법령 시행일 : 2015년 8월 18일)</P><p>&nbsp;</p><p><strong>1. 적용 대상 :</strong> {정리기준}간 로그인 기록이 없는 고객의 개인정보</p><p><strong>2. 적용 시점 :</strong> {정리예정날짜}</p><p><strong>3. 처리 방법 :</strong> {정리방법}</p><p>&nbsp;</p><p>{홈페이지명}에서는 앞으로도 회원님의 개인정보를 소중하게 관리하여 보다 더 안전하게 서비스를 이용하실 수 있도록 최선의 노력을 다하겠습니다. 많은 관심과 참여 부탁 드립니다. 감사합니다.</p></td></tr><tr><td style=\"padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;\">{홈페이지명}</td></tr></tbody></table>'),('send_email_dormant_notify_user_title','[{홈페이지명}] 휴면 계정 전환 예정 안내'),('send_email_findaccount_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원님의 아이디와 패스워드를 보내드립니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>&nbsp;</p><p>회원님의 아이디는 <strong>{회원아이디}</strong> 입니다.</p><p>아래 링크를 클릭하시면 회원님의 패스워드 변경이 가능합니다.</p><p><a href=\"{패스워드변경주소}\" target=\"_blank\" style=\"font-weight:bold;\">패스워드 변경하기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_findaccount_user_title','{회원닉네임}님의 아이디와 패스워드를 보내드립니다'),('send_email_memberleave_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 관리자님,</span><br /></td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>{회원닉네임} 님이 회원탈퇴하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>탈퇴한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr></table>'),('send_email_memberleave_admin_title','{회원닉네임}님이 회원탈퇴하셨습니다'),('send_email_memberleave_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원님의 탈퇴가 처리되었습니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요 {회원닉네임} 회원님,</p><p>그 동안 {홈페이지명} 이용을 해주셔서 감사드립니다</p><p>요청하신대로 회원님의 탈퇴가 정상적으로 처리되었습니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_memberleave_user_title','[{홈페이지명}] 회원탈퇴가 완료되었습니다'),('send_email_post_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_post_admin_title','[{게시판명}] {게시글제목}'),('send_email_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_email_post_writer_title','[{게시판명}] {게시글제목}'),('send_email_register_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 관리자님,</span><br /></td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>{회원닉네임} 님이 회원가입 하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>가입한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr></table>'),('send_email_register_admin_title','[회원가입알림] {회원닉네임}님이 회원가입하셨습니다'),('send_email_register_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원가입을 축하드립니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원가입을 축하드립니다.</p><p>{홈페이지명} 회원으로 가입해주셔서 감사합니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_register_user_title','[{홈페이지명}] {회원닉네임}님의 회원가입을 축하드립니다'),('send_email_register_user_verifycontent','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원가입을 축하드립니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원가입을 축하드립니다.</p><p>{홈페이지명} 회원으로 가입해주셔서 감사합니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>아래 링크를 클릭하시면 회원가입이 완료됩니다.</p><p><a href=\"{메일인증주소}\" target=\"_blank\" style=\"font-weight:bold;\">메일인증 받기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_register_user_verifytitle','[{홈페이지명}] {회원닉네임}님의 회원가입을 축하드립니다'),('send_email_resendverify_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원님의 인증메일을 다시 보내드립니다..</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>&nbsp;</p><p>아래 링크를 클릭하시면 이메일 인증이 완료됩니다.</p><p><a href=\"{메일인증주소}\" target=\"_blank\" style=\"font-weight:bold;\">메일인증 받기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_email_resendverify_user_title','{회원닉네임}님의 인증메일이 재발송되었습니다'),('send_note_blame_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_blame_admin_title','[{게시판명}] {게시글제목} - 신고가접수되었습니다'),('send_note_blame_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_blame_post_writer_title','[{게시판명}] {게시글제목} - 신고가접수되었습니다'),('send_note_changepw_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 관리자님,</span><br /></td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>{회원닉네임} 님이 패스워드를 변경하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>변경한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr></table>'),('send_note_changepw_admin_title','{회원닉네임}님이 패스워드를 변경하셨습니다'),('send_note_changepw_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원님의 패스워드가 변경되었습니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원님의 패스워드가 변경되었습니다.</p><p>변경한 곳 IP : {회원아이피}</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_note_changepw_user_title','패스워드가 변경되었습니다'),('send_note_comment_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_comment_admin_title','[{게시판명}] {게시글제목} - 댓글이 등록되었습니다'),('send_note_comment_blame_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />댓글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_comment_blame_admin_title','[{게시판명}] {게시글제목} - 댓글에신고가접수되었습니다'),('send_note_comment_blame_comment_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />댓글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_comment_blame_comment_writer_title','[{게시판명}] {게시글제목} - 댓글에신고가접수되었습니다'),('send_note_comment_blame_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />댓글에 신고가 접수되었습니다</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_comment_blame_post_writer_title','[{게시판명}] {게시글제목} - 댓글에신고가접수되었습니다'),('send_note_comment_comment_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_comment_comment_writer_title','[{게시판명}] {게시글제목} - 댓글이 등록되었습니다'),('send_note_comment_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{댓글내용}</div><p><a href=\"{댓글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 댓글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_comment_post_writer_title','[{게시판명}] {게시글제목} - 댓글이 등록되었습니다'),('send_note_memberleave_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 관리자님,</span><br /></td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>{회원닉네임} 님이 회원탈퇴하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>탈퇴한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr></table>'),('send_note_memberleave_admin_title','{회원닉네임}님이 회원탈퇴하셨습니다'),('send_note_post_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_post_admin_title','[{게시판명}] {게시글제목}'),('send_note_post_writer_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">{게시글제목}</span><br />작성자 : {게시글작성자닉네임}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div>{게시글내용}</div><p><a href=\"{게시글주소}\" target=\"_blank\" style=\"font-weight:bold;\">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr></table>'),('send_note_post_writer_title','[{게시판명}] {게시글제목}'),('send_note_register_admin_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 관리자님,</span><br /></td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요,</p><p>{회원닉네임} 님이 회원가입 하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>가입한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr></table>'),('send_note_register_admin_title','[회원가입알림] {회원닉네임}님이 회원가입하셨습니다'),('send_note_register_user_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td width=\"200\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><span style=\"font-size:14px;font-weight:bold;color:rgb(0,0,0)\">안녕하세요 {회원닉네임}님,</span><br />회원가입을 축하드립니다.</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td colspan=\"2\" style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원가입을 축하드립니다.</p><p>{홈페이지명} 회원으로 가입해주셔서 감사합니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr></table>'),('send_note_register_user_title','회원가입을 축하드립니다'),('send_sms_blame_admin_content','[게시글신고알림] {게시판명} - {게시글제목}'),('send_sms_blame_post_writer_content','[게시글신고알림] {게시판명} - {게시글제목}'),('send_sms_changepw_admin_content','[패스워드변경알림] {회원닉네임}님이 패스워드를변경하셨습니다'),('send_sms_changepw_user_content','[{홈페이지명}] 회원님의 패스워드가 변경되었습니다. 감사합니다'),('send_sms_comment_admin_content','[댓글작성알림] {게시판명} - {게시글제목}'),('send_sms_comment_blame_admin_content','[댓글신고알림] {게시판명} - {게시글제목}'),('send_sms_comment_blame_comment_writer_content','[댓글신고알림] {게시판명} - {게시글제목}'),('send_sms_comment_blame_post_writer_content','[댓글신고알림] {게시판명} - {게시글제목}'),('send_sms_comment_comment_writer_content','[댓글작성알림] {게시판명} - {게시글제목}'),('send_sms_comment_post_writer_content','[댓글작성알림] {게시판명} - {게시글제목}'),('send_sms_memberleave_admin_content','[회원탈퇴알림] {회원닉네임}님이 회원탈퇴하셨습니다'),('send_sms_memberleave_user_content','[{홈페이지명}] 회원탈퇴완료 - 그동안이용해주셔서감사합니다'),('send_sms_post_admin_content','[게시글작성알림] {게시판명} - {게시글제목}'),('send_sms_post_writer_content','[게시글작성알림] {게시판명} - {게시글제목}'),('send_sms_register_admin_content','[회원가입알림] {회원닉네임}님이 회원가입하셨습니다'),('send_sms_register_user_content','[{홈페이지명}] 회원가입을 축하드립니다. 감사합니다'),('sidebar_board',''),('sidebar_currentvisitor',''),('sidebar_default','1'),('sidebar_document',''),('sidebar_faq',''),('sidebar_findaccount',''),('sidebar_group',''),('sidebar_login',''),('sidebar_main',''),('sidebar_mypage',''),('sidebar_notification',''),('sidebar_pointranking',''),('sidebar_poll',''),('sidebar_register',''),('sidebar_search',''),('sidebar_tag',''),('site_blacklist_content','<p>안녕하세요</p><p>블편을 드려 죄송합니다. 지금 이 사이트는 접근이 금지되어있습니다</p><p>감사합니다</p>'),('site_blacklist_title','사이트가 공사중에 있습니다'),('site_logo','홈페이지'),('site_meta_author_pointranking',''),('site_meta_author_pointranking_month',''),('site_meta_author_poll',''),('site_meta_description_pointranking',''),('site_meta_description_pointranking_month',''),('site_meta_description_poll',''),('site_meta_keywords_pointranking',''),('site_meta_keywords_pointranking_month',''),('site_meta_keywords_poll',''),('site_meta_title_attendance','출석체크 - {홈페이지제목}'),('site_meta_title_board_list','{게시판명} - {홈페이지제목}'),('site_meta_title_board_modify','{글제목} 글수정 - {홈페이지제목}'),('site_meta_title_board_post','{글제목} > {게시판명} - {홈페이지제목}'),('site_meta_title_board_write','{게시판명} 글쓰기 - {홈페이지제목}'),('site_meta_title_currentvisitor','현재접속자 - {홈페이지제목}'),('site_meta_title_default','{홈페이지제목}'),('site_meta_title_document','{문서제목} - {홈페이지제목}'),('site_meta_title_faq','{FAQ제목} - {홈페이지제목}'),('site_meta_title_findaccount','회원정보찾기 - {홈페이지제목}'),('site_meta_title_formmail','메일발송 - {홈페이지제목}'),('site_meta_title_group','{그룹명} - {홈페이지제목}'),('site_meta_title_levelup','레벨업 - {홈페이지제목}'),('site_meta_title_login','로그인 - {홈페이지제목}'),('site_meta_title_main','{홈페이지제목}'),('site_meta_title_membermodify','회원정보수정 - {홈페이지제목}'),('site_meta_title_membermodify_memberleave','회원탈퇴 - {홈페이지제목}'),('site_meta_title_mypage','{회원닉네임}님의 마이페이지 - {홈페이지제목}'),('site_meta_title_mypage_comment','{회원닉네임}님의 작성댓글 - {홈페이지제목}'),('site_meta_title_mypage_followedlist','{회원닉네임}님의 팔로우 - {홈페이지제목}'),('site_meta_title_mypage_followinglist','{회원닉네임}님의 팔로우 - {홈페이지제목}'),('site_meta_title_mypage_like_comment','{회원닉네임}님의 추천댓글 - {홈페이지제목}'),('site_meta_title_mypage_like_post','{회원닉네임}님의 추천글 - {홈페이지제목}'),('site_meta_title_mypage_loginlog','{회원닉네임}님의 로그인기록 - {홈페이지제목}'),('site_meta_title_mypage_point','{회원닉네임}님의 포인트 - {홈페이지제목}'),('site_meta_title_mypage_post','{회원닉네임}님의 작성글 - {홈페이지제목}'),('site_meta_title_mypage_scrap','{회원닉네임}님의 스크랩 - {홈페이지제목}'),('site_meta_title_note_list','{회원닉네임}님의 쪽지함 - {홈페이지제목}'),('site_meta_title_note_view','{회원닉네임}님의 쪽지함 - {홈페이지제목}'),('site_meta_title_note_write','{회원닉네임}님의 쪽지함 - {홈페이지제목}'),('site_meta_title_notification','{회원닉네임}님의 알림 - {홈페이지제목}'),('site_meta_title_pointranking','전체 포인트 랭킹 - {홈페이지제목}'),('site_meta_title_pointranking_month','월별 포인트 랭킹 - {홈페이지제목}'),('site_meta_title_poll','설문조사모음 - {홈페이지제목}'),('site_meta_title_profile','{회원닉네임}님의 프로필 - {홈페이지제목}'),('site_meta_title_register','회원가입 - {홈페이지제목}'),('site_meta_title_register_form','회원가입 - {홈페이지제목}'),('site_meta_title_register_result','회원가입결과 - {홈페이지제목}'),('site_meta_title_search','{검색어} - {홈페이지제목}'),('site_meta_title_tag','{태그명} - {홈페이지제목}'),('site_page_name_pointranking',''),('site_page_name_pointranking_month',''),('site_page_name_poll',''),('site_title','홈페이지'),('skin_board',''),('skin_currentvisitor',''),('skin_default','bootstrap'),('skin_document',''),('skin_emailform','basic'),('skin_faq',''),('skin_findaccount',''),('skin_formmail',''),('skin_group',''),('skin_helptool',''),('skin_login',''),('skin_main',''),('skin_mypage',''),('skin_note',''),('skin_notification',''),('skin_pointranking',''),('skin_poll',''),('skin_popup','basic'),('skin_profile',''),('skin_register',''),('skin_search',''),('skin_tag',''),('spam_word','18아,18놈,18새끼,18년,18뇬,18노,18것,18넘,개년,개놈,개뇬,개새,개색끼,개세끼,개세이,개쉐이,개쉑,개쉽,개시키,개자식,개좆,게색기,게색끼,광뇬,뇬,눈깔,뉘미럴,니귀미,니기미,니미,도촬,되질래,뒈져라,뒈진다,디져라,디진다,디질래,병쉰,병신,뻐큐,뻑큐,뽁큐,삐리넷,새꺄,쉬발,쉬밸,쉬팔,쉽알,스패킹,스팽,시벌,시부랄,시부럴,시부리,시불,시브랄,시팍,시팔,시펄,실밸,십8,십쌔,십창,싶알,쌉년,썅놈,쌔끼,쌩쑈,썅,써벌,썩을년,쎄꺄,쎄엑,쓰바,쓰발,쓰벌,쓰팔,씨8,씨댕,씨바,씨발,씨뱅,씨봉알,씨부랄,씨부럴,씨부렁,씨부리,씨불,씨브랄,씨빠,씨빨,씨뽀랄,씨팍,씨팔,씨펄,씹,아가리,아갈이,엄창,접년,잡놈,재랄,저주글,조까,조빠,조쟁이,조지냐,조진다,조질래,존나,존니,좀물,좁년,좃,좆,좇,쥐랄,쥐롤,쥬디,지랄,지럴,지롤,지미랄,쫍빱,凸,퍽큐,뻑큐,빠큐,ㅅㅂㄹㅁ'),('total_rss_feed_count','100'),('use_copy_log','1'),('use_document_auto_url','1'),('use_document_dhtml','1'),('use_faq_auto_url','1'),('use_faq_dhtml','1'),('use_formmail_dhtml','1'),('use_login_account','userid'),('use_member_icon','1'),('use_member_photo','1'),('use_mobile_sideview','1'),('use_mobile_sideview_email','1'),('use_naver_syndi_log',''),('use_note',''),('use_note_dhtml','1'),('use_note_file',''),('use_note_mobile_dhtml','1'),('use_notification','1'),('use_payment_bank','1'),('use_payment_card','1'),('use_payment_easy',''),('use_payment_pg','inicis'),('use_payment_phone',''),('use_payment_realtime','1'),('use_payment_vbank','1'),('use_pg_no_interest',''),('use_pg_test','1'),('use_point','1'),('use_pointranking',''),('use_poll_list',''),('use_popup_auto_url','1'),('use_popup_dhtml','1'),('use_recaptcha',''),('use_register_block',''),('use_register_email_auth',''),('use_selfcert',''),('use_selfcert_ipin',''),('use_selfcert_phone',''),('use_selfcert_required',''),('use_selfcert_test',''),('use_sideview','1'),('use_sideview_email','1'),('webmaster_email','pm@webthink.co.kr'),('webmaster_name','관리자'),('white_iframe','www.youtube.com\r\nwww.youtube-nocookie.com\r\nmaps.google.co.kr\r\nmaps.google.com\r\nflvs.daum.net\r\nplayer.vimeo.com\r\nsbsplayer.sbs.co.kr\r\nserviceapi.rmcnmv.naver.com\r\nserviceapi.nmv.naver.com\r\nwww.mgoon.com\r\nvideofarm.daum.net\r\nplayer.sbs.co.kr\r\nsbsplayer.sbs.co.kr\r\nwww.tagstory.com\r\nplay.tagstory.com\r\nflvr.pandora.tv');
/*!40000 ALTER TABLE `cb_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_currentvisitor`
--

DROP TABLE IF EXISTS `cb_currentvisitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_currentvisitor` (
  `cur_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cur_mem_name` varchar(255) NOT NULL DEFAULT '',
  `cur_datetime` datetime DEFAULT NULL,
  `cur_page` text,
  `cur_url` text,
  `cur_referer` text,
  `cur_useragent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`cur_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_currentvisitor`
--

LOCK TABLES `cb_currentvisitor` WRITE;
/*!40000 ALTER TABLE `cb_currentvisitor` DISABLE KEYS */;
INSERT INTO `cb_currentvisitor` VALUES ('1.226.241.15',1,'관리자','2017-11-30 00:13:54','홈페이지','http://dhn.webthink.co.kr/biz/sender/send/talk','http://dhn.webthink.co.kr/biz/sender/send/talk','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),('112.163.89.66',1,'관리자','2017-11-30 17:07:49','홈페이지','http://dhn.webthink.co.kr/biz/sender/send/talk','http://dhn.webthink.co.kr/biz/sender/send/talk','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),('116.45.151.116',2,'강영식','2017-11-29 22:42:28','홈페이지','http://dhn.webthink.co.kr/biz/main','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36'),('117.111.28.178',0,'','2017-11-29 18:09:12','로그인 - 홈페이지','http://dhn.webthink.co.kr/login','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),('211.114.35.194',0,'','2017-11-30 11:22:00','로그인 - 홈페이지','http://dhn.webthink.co.kr/login','','Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'),('211.197.42.37',2,'강영식','2017-11-30 17:11:16','홈페이지','http://dhn.webthink.co.kr/biz/template/lists','http://dhn.webthink.co.kr/biz/customer/lists','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),('211.36.132.212',0,'','2017-11-30 09:05:17','공지/뉴스 - 홈페이지','http://dhn.webthink.co.kr/board/notice_01','http://dhn.webthink.co.kr/homepage/sms','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),('211.36.134.81',2,'강영식','2017-11-23 20:38:04','홈페이지','http://dhn.webthink.co.kr/biz/main','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-G925L Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36;KAKAOTALK 1600306'),('61.75.253.253',2,'강영식','2017-11-24 08:58:27','홈페이지','http://dhn.webthink.co.kr/biz/refund/lists','http://dhn.webthink.co.kr/biz/template/write','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Whale/1.0.37.16 Safari/537.36');
/*!40000 ALTER TABLE `cb_currentvisitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_deposit`
--

DROP TABLE IF EXISTS `cb_deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_deposit` (
  `dep_id` bigint(20) unsigned NOT NULL,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_nickname` varchar(100) NOT NULL DEFAULT '',
  `mem_realname` varchar(100) NOT NULL DEFAULT '',
  `mem_email` varchar(255) NOT NULL DEFAULT '',
  `mem_phone` varchar(255) NOT NULL DEFAULT '',
  `dep_from_type` varchar(100) NOT NULL DEFAULT '',
  `dep_to_type` varchar(100) NOT NULL DEFAULT '',
  `dep_deposit_request` int(11) NOT NULL DEFAULT '0',
  `dep_deposit` int(11) NOT NULL DEFAULT '0',
  `dep_deposit_sum` int(11) NOT NULL DEFAULT '0',
  `dep_cash_request` int(11) NOT NULL DEFAULT '0',
  `dep_cash` int(11) NOT NULL DEFAULT '0',
  `dep_point` int(11) NOT NULL DEFAULT '0',
  `dep_content` varchar(255) NOT NULL DEFAULT '',
  `dep_pay_type` varchar(100) NOT NULL DEFAULT '',
  `dep_pg` varchar(255) NOT NULL DEFAULT '',
  `dep_tno` varchar(255) NOT NULL DEFAULT '',
  `dep_app_no` varchar(255) NOT NULL DEFAULT '',
  `dep_bank_info` varchar(255) NOT NULL DEFAULT '',
  `dep_admin_memo` text,
  `dep_datetime` datetime DEFAULT NULL,
  `dep_deposit_datetime` datetime DEFAULT NULL,
  `dep_ip` varchar(50) NOT NULL DEFAULT '',
  `dep_useragent` varchar(255) NOT NULL DEFAULT '',
  `dep_status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `dep_vbank_expire` datetime DEFAULT NULL,
  `is_test` char(1) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `dep_refund_price` int(11) NOT NULL DEFAULT '0',
  `dep_order_history` text,
  PRIMARY KEY (`dep_id`),
  KEY `mem_id` (`mem_id`),
  KEY `dep_pay_type` (`dep_pay_type`),
  KEY `dep_datetime` (`dep_datetime`),
  KEY `dep_deposit_datetime` (`dep_deposit_datetime`),
  KEY `dep_status` (`dep_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_deposit`
--

LOCK TABLES `cb_deposit` WRITE;
/*!40000 ALTER TABLE `cb_deposit` DISABLE KEYS */;
INSERT INTO `cb_deposit` VALUES (2017111317333229,1,'관리자','관리자','pm@webthink.co.kr','01093111339','cash','deposit',10000,10000,10000,10000,10000,0,'예치금 적립 (무통장입금)','bank','','','','','','2017-11-13 17:33:48','2017-11-27 15:12:05','112.163.89.66','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',1,NULL,'0','',0,NULL),(2017112711352488,2,'강영식','강영식','uwooto@gmail.com','01093111339','cash','deposit',10000,10000,10000,10000,10000,0,'예치금 적립 (무통장입금)','bank','','','','','','2017-11-27 11:35:45','2017-11-27 15:29:28','112.163.89.66','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',1,NULL,'1','',0,NULL),(2017112715303344,1,'관리자','관리자','pm@webthink.co.kr','01093111339','cash','deposit',10000,10000,0,10000,10000,0,'예치금 적립 (신용카드결제)','card','inicis','StdpayCARDINIpayTest20171127153720506452','38137156','신한',NULL,'2017-11-27 15:37:20','2017-11-27 15:37:21','112.163.89.66','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',1,NULL,'1','',0,NULL),(2017112911374546,5,'강영식','강영식','shigy22@gmail.com','01065748654','cash','deposit',20000,0,0,20000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 11:37:57',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL),(2017112913455784,5,'강영식','강영식','shigy22@gmail.com','01065748654','cash','deposit',20000,0,0,20000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 13:46:13',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL),(2017112913463371,5,'강영식','강영식','shigy22@gmail.com','01065748654','cash','deposit',20000,0,0,20000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 13:46:43',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL),(2017112913465529,5,'강영식','강영식','shigy22@gmail.com','01065748654','cash','deposit',20000,0,0,20000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 13:47:30',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL),(2017112913480751,5,'강영식','강영식','shigy22@gmail.com','01065748654','cash','deposit',50000,0,0,50000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 13:48:14',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL),(2017112913481774,5,'강영식','강영식','shigy22@gmail.com','01065748654','cash','deposit',50000,0,0,50000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 13:49:02',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL),(2017112916532859,1,'관리자','관리자','pm@webthink.co.kr','01065748654','cash','deposit',10000,0,0,10000,0,0,'예치금 적립 (무통장입금)','bank','','','','',NULL,'2017-11-29 16:53:49',NULL,'112.163.89.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',0,NULL,'1','',0,NULL);
/*!40000 ALTER TABLE `cb_deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_deposit_config`
--

DROP TABLE IF EXISTS `cb_deposit_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_deposit_config` (
  `dcf_key` varchar(255) NOT NULL DEFAULT '',
  `dcf_value` text,
  UNIQUE KEY `dcf_key` (`dcf_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_deposit_config`
--

LOCK TABLES `cb_deposit_config` WRITE;
/*!40000 ALTER TABLE `cb_deposit_config` DISABLE KEYS */;
INSERT INTO `cb_deposit_config` VALUES ('deposit_cash_to_deposit_unit','10000:10000\r\n20000:20000\r\n30000:30000\r\n50000:50000'),('deposit_charge_point',''),('deposit_email_admin_approve_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님의 입금처리요청이 완료되었습니다</p><p>회원님께서 구매하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_admin_approve_bank_to_deposit_title','[입금처리완료] {회원닉네임}님의 입금처리요청이 완료되었습니다'),('deposit_email_admin_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 무통장입금 요청하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>통장에 입금된 내역이 확인되면 관리자페이지에서 입금완료 승인을 해주시기 바랍니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_admin_bank_to_deposit_title','[무통장입금요청] {회원닉네임}님이 무통장입금 요청하셨습니다'),('deposit_email_admin_cash_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 결제하셨습니다</p><p>회원님께서 결제하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_admin_cash_to_deposit_title','[결제 알림] {회원닉네임}님이 결제하셨습니다'),('deposit_email_admin_deposit_to_point_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임} 님이 포인트를 구매하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p> 포인트 : {전환포인트}점</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_admin_deposit_to_point_title','[포인트 전환 알림] {회원닉네임}님이 포인트를 구매하셨습니다'),('deposit_email_admin_point_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 포인트로 {예치금명} 구매하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_admin_point_to_deposit_title','[구매 알림] {회원닉네임}님이 포인트로 {예치금명} 구매 하셨습니다'),('deposit_email_user_approve_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>{회원닉네임}님께서 구매요청하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>정상 구매가 완료되었습니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_user_approve_bank_to_deposit_title','[{홈페이지명}] 구매해주셔서 감사합니다'),('deposit_email_user_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>{회원닉네임}님께서 구매요청하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>아래의 계좌번호로 입금부탁드립니다</p><p>은행안내 : {은행계좌안내}</p><p>입금이 확인되면 24시간 내에 처리가 완료됩니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_user_bank_to_deposit_title','[{홈페이지명}] 무통장입금요청을 하셨습니다'),('deposit_email_user_cash_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>구매해주셔서 감사합니다</p><p>{회원닉네임}님께서 구매하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_user_cash_to_deposit_title','[{홈페이지명}] 결제가 완료되었습니다'),('deposit_email_user_deposit_to_point_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>포인트를 구매해주셔서 감사합니다</p><p>{회원닉네임}님께서 구매하신 내용입니다</p><p> 포인트 : {전환포인트}점</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_user_deposit_to_point_title','[{홈페이지명}] 포인트구매가 완료되었습니다'),('deposit_email_user_point_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>구매해주셔서 감사합니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_email_user_point_to_deposit_title','[{홈페이지명}] 포인트 결제가 완료되었습니다'),('deposit_name','예치금'),('deposit_note_admin_approve_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님의 입금처리요청이 완료되었습니다</p><p>회원님께서 구매하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_admin_approve_bank_to_deposit_title','[입금처리완료] {회원닉네임}님의 입금처리요청이 완료되었습니다'),('deposit_note_admin_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 무통장입금 요청하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>통장에 입금된 내역이 확인되면 관리자페이지에서 입금완료 승인을 해주시기 바랍니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_admin_bank_to_deposit_title','[무통장입금요청] {회원닉네임}님이 무통장입금 요청하셨습니다'),('deposit_note_admin_cash_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 결제하셨습니다</p><p>회원님께서 결제하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_admin_cash_to_deposit_title','[결제 알림] {회원닉네임}님이 결제하셨습니다'),('deposit_note_admin_deposit_to_point_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임} 님이 포인트를 구매하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p> 포인트 : {전환포인트}점</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_admin_deposit_to_point_title','[포인트 전환 알림] {회원닉네임}님이 포인트를 구매하셨습니다'),('deposit_note_admin_point_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>{회원닉네임}님이 포인트로 {예치금명} 구매하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_admin_point_to_deposit_title','[구매 알림] {회원닉네임}님이 포인트로 {예치금명} 구매 하셨습니다'),('deposit_note_user_approve_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>{회원닉네임}님께서 구매요청하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>정상 구매가 완료되었습니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_user_approve_bank_to_deposit_title','구매해주셔서 감사합니다'),('deposit_note_user_bank_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>{회원닉네임}님께서 구매요청하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>아래의 계좌번호로 입금부탁드립니다</p><p>은행안내 : {은행계좌안내}</p><p>입금이 확인되면 24시간 내에 처리가 완료됩니다</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_user_bank_to_deposit_title','무통장입금요청을 하셨습니다'),('deposit_note_user_cash_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>구매해주셔서 감사합니다</p><p>{회원닉네임}님께서 구매하신 내용입니다</p><p>결제금액 : {결제금액} 원</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_user_cash_to_deposit_title','결제가 완료되었습니다'),('deposit_note_user_deposit_to_point_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요 {회원닉네임}님</p><p>포인트를 구매해주셔서 감사합니다</p><p>{회원닉네임}님께서 구매하신 내용입니다</p><p> 포인트 : {전환포인트}점</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_user_deposit_to_point_title','포인트구매가 완료되었습니다'),('deposit_note_user_point_to_deposit_content','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;\"><tr><td style=\"font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\">{홈페이지명}</td></tr><tr style=\"border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;\"><td style=\"padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;\"><div><p>안녕하세요</p><p>구매해주셔서 감사합니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href=\"{홈페이지주소}\" target=\"_blank\" style=\"font-weight:bold;\">홈페이지 가기</a></p><p>&nbsp;</p></td></tr></table>'),('deposit_note_user_point_to_deposit_title','포인트 결제가 완료되었습니다'),('deposit_point','1'),('deposit_point_min','1000'),('deposit_refund_point','1'),('deposit_refund_point_min','1000'),('deposit_sendcont_admin_approve_bank_to_deposit','{고객명}님 입금처리완료 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}'),('deposit_sendcont_admin_bank_to_deposit','{고객명}님 무통장요청 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}'),('deposit_sendcont_admin_cash_to_deposit','{고객명}님 충전 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}'),('deposit_sendcont_admin_deposit_to_point','{고객명}님 포인트로 전환 - 예치금 : {예치금액}원, 포인트 : {포인트}점 - {회사명}'),('deposit_sendcont_admin_point_to_deposit','{고객명}님 포인트로 예치금구매 - 예치금 : {예치금액}원, 포인트 : {포인트}점 - {회사명}'),('deposit_sendcont_user_approve_bank_to_deposit','입금처리완료- 예치금 : {예치금액}원, 결제금액 : {결제금액}원 - {회사명}'),('deposit_sendcont_user_bank_to_deposit','무통장입금요청, 입금확인시 자동 충전 됩니다, 결제금액 : {결제금액}원 - {회사명}'),('deposit_sendcont_user_cash_to_deposit','충전완료 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}'),('deposit_sendcont_user_deposit_to_point','포인트로 전환 : 예치금 : {예치금액}원, 전환포인트 : {포인트}점 - {회사명}'),('deposit_sendcont_user_point_to_deposit','예치금구매 : {예치금액}원, 결제포인트 : {포인트}점 - {회사명}'),('deposit_sms_admin_approve_bank_to_deposit_content','[입금처리완료] {회원닉네임}님의 {결제금액} 원 입금처리요청 완료'),('deposit_sms_admin_bank_to_deposit_content','[무통장입금요청] {회원닉네임}님, 결제요청금액 : {결제금액} 원'),('deposit_sms_admin_cash_to_deposit_content','[결제알림] {회원닉네임}님, 결제금액 : {결제금액} 원'),('deposit_sms_admin_deposit_to_point_content','[예치금->포인트 결제] {회원닉네임} 님 결제 완료'),('deposit_sms_admin_point_to_deposit_content','[포인트->예치금 결제] {회원닉네임} 님 결제 완료'),('deposit_sms_user_approve_bank_to_deposit_content','[{홈페이지명}] {결제금액}원 입금처리완료되었습니다. 감사합니다'),('deposit_sms_user_bank_to_deposit_content','[{홈페이지명}] 입금요청 : {결제금액} 원 - 감사합니다'),('deposit_sms_user_cash_to_deposit_content','[{홈페이지명}] 결제완료 : {결제금액} 원 - 감사합니다'),('deposit_sms_user_deposit_to_point_content','[{홈페이지명}] 결제완료 - 적립포인트 {전환포인트}점. 감사합니다'),('deposit_sms_user_point_to_deposit_content','[{홈페이지명}] 결제완료 - 전환{예치금명}:{전환예치금액}{예치금단위} 감사합니다'),('deposit_unit','원'),('site_meta_title_deposit','예치금 관리 - {홈페이지제목}'),('site_meta_title_deposit_mylist','나의 예치금 내역 - {홈페이지제목}'),('site_meta_title_deposit_result','예치금 충전 결과 - {홈페이지제목}'),('use_deposit','1'),('use_deposit_cash_to_deposit','1'),('use_deposit_deposit_to_point',''),('use_deposit_point_to_deposit','');
/*!40000 ALTER TABLE `cb_deposit_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_document`
--

DROP TABLE IF EXISTS `cb_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_document` (
  `doc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_key` varchar(255) NOT NULL DEFAULT '',
  `doc_title` varchar(255) NOT NULL DEFAULT '',
  `doc_content` mediumtext,
  `doc_mobile_content` mediumtext,
  `doc_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `doc_layout` varchar(255) NOT NULL DEFAULT '',
  `doc_mobile_layout` varchar(255) NOT NULL DEFAULT '',
  `doc_sidebar` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `doc_mobile_sidebar` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `doc_skin` varchar(255) NOT NULL DEFAULT '',
  `doc_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `doc_hit` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `doc_datetime` datetime DEFAULT NULL,
  `doc_updated_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `doc_updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`doc_id`),
  UNIQUE KEY `doc_key` (`doc_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_document`
--

LOCK TABLES `cb_document` WRITE;
/*!40000 ALTER TABLE `cb_document` DISABLE KEYS */;
INSERT INTO `cb_document` VALUES (1,'aboutus','회사소개','회사소개 내용을 입력해주세요',NULL,1,'','',0,0,'','',1,1,'2017-11-10 14:11:15',1,'2017-11-10 14:11:15'),(2,'provision','이용약관','이용약관 내용을 입력해주세요',NULL,1,'','',0,0,'','',0,1,'2017-11-10 14:11:15',1,'2017-11-10 14:11:15'),(3,'privacy','개인정보 취급방침','개인정보 취급방침 내용을 입력해주세요',NULL,1,'','',0,0,'','',0,1,'2017-11-10 14:11:15',1,'2017-11-10 14:11:15'),(4,'cmall','이용안내','이용안내 내용을 입력해주세요',NULL,1,'cmall_bootstrap','',0,0,'','',0,1,'2017-11-10 14:11:15',1,'2017-11-10 14:11:15');
/*!40000 ALTER TABLE `cb_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_editor_image`
--

DROP TABLE IF EXISTS `cb_editor_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_editor_image` (
  `eim_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `eim_originname` varchar(255) NOT NULL DEFAULT '',
  `eim_filename` varchar(255) NOT NULL DEFAULT '',
  `eim_filesize` int(11) unsigned NOT NULL DEFAULT '0',
  `eim_width` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `eim_height` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `eim_type` varchar(10) NOT NULL DEFAULT '',
  `eim_datetime` datetime DEFAULT NULL,
  `eim_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`eim_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_editor_image`
--

LOCK TABLES `cb_editor_image` WRITE;
/*!40000 ALTER TABLE `cb_editor_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_editor_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_faq`
--

DROP TABLE IF EXISTS `cb_faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_faq` (
  `faq_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fgr_id` int(11) unsigned NOT NULL DEFAULT '0',
  `faq_title` text,
  `faq_content` mediumtext,
  `faq_mobile_content` mediumtext,
  `faq_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `faq_order` int(11) NOT NULL DEFAULT '0',
  `faq_datetime` datetime DEFAULT NULL,
  `faq_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`faq_id`),
  KEY `fgr_id` (`fgr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_faq`
--

LOCK TABLES `cb_faq` WRITE;
/*!40000 ALTER TABLE `cb_faq` DISABLE KEYS */;
INSERT INTO `cb_faq` VALUES (1,1,'자주하는 질문 제목1 입니다','자주하는 질문 답변1 입니다',NULL,1,1,'2017-11-10 14:11:15','112.163.89.66',1),(2,1,'자주하는 질문 제목2 입니다','자주하는 질문 답변2 입니다',NULL,1,2,'2017-11-10 14:11:15','112.163.89.66',1),(3,1,'자주하는 질문 제목3 입니다','자주하는 질문 답변3 입니다',NULL,1,3,'2017-11-10 14:11:15','112.163.89.66',1);
/*!40000 ALTER TABLE `cb_faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_faq_group`
--

DROP TABLE IF EXISTS `cb_faq_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_faq_group` (
  `fgr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fgr_title` varchar(255) NOT NULL DEFAULT '',
  `fgr_key` varchar(255) NOT NULL DEFAULT '',
  `fgr_layout` varchar(255) NOT NULL DEFAULT '',
  `fgr_mobile_layout` varchar(255) NOT NULL DEFAULT '',
  `fgr_sidebar` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `fgr_mobile_sidebar` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `fgr_skin` varchar(255) NOT NULL DEFAULT '',
  `fgr_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `fgr_datetime` datetime DEFAULT NULL,
  `fgr_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fgr_id`),
  UNIQUE KEY `fgr_key` (`fgr_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_faq_group`
--

LOCK TABLES `cb_faq_group` WRITE;
/*!40000 ALTER TABLE `cb_faq_group` DISABLE KEYS */;
INSERT INTO `cb_faq_group` VALUES (1,'자주하는 질문','faq','','',0,0,'','','2017-11-10 14:11:15','112.163.89.66',1);
/*!40000 ALTER TABLE `cb_faq_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_follow`
--

DROP TABLE IF EXISTS `cb_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_follow` (
  `fol_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `fol_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fol_id`),
  KEY `mem_id` (`mem_id`),
  KEY `target_mem_id` (`target_mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_follow`
--

LOCK TABLES `cb_follow` WRITE;
/*!40000 ALTER TABLE `cb_follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_img_admin`
--

DROP TABLE IF EXISTS `cb_img_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_img_admin` (
  `img_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `img_filename` varchar(200) DEFAULT '' COMMENT '파일명',
  `img_url` varchar(200) DEFAULT '' COMMENT '이미지URL',
  `img_originname` varchar(200) DEFAULT '' COMMENT '실제이름',
  `img_filesize` bigint(20) unsigned DEFAULT '0' COMMENT '파일크기',
  `img_width` int(10) unsigned DEFAULT '0' COMMENT '넓이',
  `img_height` int(10) unsigned DEFAULT '0' COMMENT '높이',
  `img_type` varchar(20) DEFAULT '' COMMENT 'MIME',
  `img_is_image` char(1) DEFAULT '1' COMMENT '이미지여부',
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_img_admin`
--

LOCK TABLES `cb_img_admin` WRITE;
/*!40000 ALTER TABLE `cb_img_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_img_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_img_webthink7`
--

DROP TABLE IF EXISTS `cb_img_webthink7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_img_webthink7` (
  `img_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `img_filename` varchar(200) DEFAULT '' COMMENT '파일명',
  `img_url` varchar(200) DEFAULT '' COMMENT '이미지URL',
  `img_originname` varchar(200) DEFAULT '' COMMENT '실제이름',
  `img_filesize` bigint(20) unsigned DEFAULT '0' COMMENT '파일크기',
  `img_width` int(10) unsigned DEFAULT '0' COMMENT '넓이',
  `img_height` int(10) unsigned DEFAULT '0' COMMENT '높이',
  `img_type` varchar(20) DEFAULT '' COMMENT 'MIME',
  `img_is_image` char(1) DEFAULT '1' COMMENT '이미지여부',
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_img_webthink7`
--

LOCK TABLES `cb_img_webthink7` WRITE;
/*!40000 ALTER TABLE `cb_img_webthink7` DISABLE KEYS */;
INSERT INTO `cb_img_webthink7` VALUES (2,'2017/11/6bc0f7c58fc8a48117696d817160a3c6.jpg','','httpblog.naver.comkojuboo540167199924_코주보님_블로그에서.jpg',378439,732,542,'jpg','1');
/*!40000 ALTER TABLE `cb_img_webthink7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_like`
--

DROP TABLE IF EXISTS `cb_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_like` (
  `lik_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lik_type` tinyint(4) unsigned NOT NULL,
  `lik_datetime` datetime DEFAULT NULL,
  `lik_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`lik_id`),
  KEY `target_id` (`target_id`),
  KEY `mem_id` (`mem_id`),
  KEY `target_mem_id` (`target_mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_like`
--

LOCK TABLES `cb_like` WRITE;
/*!40000 ALTER TABLE `cb_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member`
--

DROP TABLE IF EXISTS `cb_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member` (
  `mem_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_userid` varchar(100) NOT NULL DEFAULT '',
  `mem_email` varchar(255) NOT NULL DEFAULT '',
  `mem_password` varchar(255) NOT NULL DEFAULT '',
  `mem_username` varchar(100) NOT NULL DEFAULT '',
  `mem_nickname` varchar(100) NOT NULL DEFAULT '',
  `mem_level` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `mem_deposit` decimal(14,2) DEFAULT '0.00' COMMENT '예치금',
  `mem_point` decimal(14,2) DEFAULT '0.00' COMMENT '포인트',
  `mem_homepage` text,
  `mem_phone` varchar(255) NOT NULL DEFAULT '',
  `mem_birthday` char(10) NOT NULL DEFAULT '',
  `mem_sex` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_zipcode` varchar(7) NOT NULL DEFAULT '',
  `mem_address1` varchar(255) NOT NULL DEFAULT '',
  `mem_address2` varchar(255) NOT NULL DEFAULT '',
  `mem_address3` varchar(255) NOT NULL DEFAULT '',
  `mem_address4` varchar(255) NOT NULL DEFAULT '',
  `mem_receive_email` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_use_note` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_receive_sms` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_open_profile` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_denied` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_email_cert` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_register_datetime` datetime DEFAULT NULL,
  `mem_register_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_lastlogin_datetime` datetime DEFAULT NULL,
  `mem_lastlogin_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_is_admin` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_profile_content` text,
  `mem_adminmemo` text,
  `mem_following` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_followed` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_icon` varchar(255) NOT NULL DEFAULT '',
  `mem_photo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`mem_id`),
  UNIQUE KEY `mem_userid` (`mem_userid`),
  KEY `mem_email` (`mem_email`),
  KEY `mem_lastlogin_datetime` (`mem_lastlogin_datetime`),
  KEY `mem_register_datetime` (`mem_register_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member`
--

LOCK TABLES `cb_member` WRITE;
/*!40000 ALTER TABLE `cb_member` DISABLE KEYS */;
INSERT INTO `cb_member` VALUES (1,'admin','pm@webthink.co.kr','$2y$10$BesqDn4tmF53j5J/s2FmV.aTcvA0v9t78LcwEEBUsVEwiu371BPzq','관리자','관리자',100,29971.50,869.50,'','','',0,'','','','','',0,1,0,0,0,1,'2017-11-10 14:11:15','112.163.89.66','2017-11-30 00:03:04','1.226.241.15',1,'','',0,0,'','2017/11/ff11cb55f53b73a26d9361640a619196.jpg'),(2,'webthink','uwooto@gmail.com','$2y$10$tA7lCTkY0m.OGq7fukvCK.KRigFT4uVDeriGO483ekTLxmHt.EmVW','(주)웹싱크','강영식',1,10000.00,1000.00,'','055-238-9456','',0,'','','','','',0,0,0,0,0,1,'2017-11-23 16:21:49','112.163.89.66','2017-11-30 09:19:31','211.197.42.37',0,'','',0,0,'',''),(3,'dhn','alimtalk@gmail.com','$2y$10$zFBpVfyzjCnEJRVIYKrA5u876ZWjfo9JupmKljeGMe/8fh/t9.Kl6','(주)대형네트웍스','홍길동',1,0.00,0.00,NULL,'055-238-9456','',0,'','','','','',0,0,0,0,0,0,NULL,'','2017-11-27 22:54:16','1.226.241.15',0,NULL,NULL,0,0,'',''),(4,'darkskill','aa@aa.net','$2y$10$tR7CX5KN6MB1gfvHb05vheY1pQE6LV5VTZnKvw585N.wvrFqudAeG','테스트','홍길동',1,0.00,0.00,NULL,'010-1234-5678','',0,'','','','','',0,0,0,0,0,0,'2017-11-14 15:57:01','112.163.89.66','2017-11-27 22:54:45','1.226.241.15',0,NULL,NULL,0,0,'',''),(5,'webthink7','shigy22@gmail.com','$2y$10$dmZWIPhbT0Ic3xL5dqM0KOma7W3CUsx88uAscS1YEtCcOmDixxM2a','(주)웹싱크','강영식',1,0.00,NULL,NULL,'01065748654','',0,'','','','','',0,0,0,0,0,1,'2017-11-29 11:29:13','112.163.89.66','2017-11-29 11:30:05','112.163.89.66',0,NULL,NULL,0,0,'','');
/*!40000 ALTER TABLE `cb_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_auth_email`
--

DROP TABLE IF EXISTS `cb_member_auth_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_auth_email` (
  `mae_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mae_key` varchar(255) NOT NULL DEFAULT '',
  `mae_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mae_generate_datetime` datetime DEFAULT NULL,
  `mae_use_datetime` datetime DEFAULT NULL,
  `mae_expired` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mae_id`),
  KEY `mae_key_mem_id` (`mae_key`,`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_auth_email`
--

LOCK TABLES `cb_member_auth_email` WRITE;
/*!40000 ALTER TABLE `cb_member_auth_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_member_auth_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_certify`
--

DROP TABLE IF EXISTS `cb_member_certify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_certify` (
  `mce_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mce_type` varchar(255) NOT NULL DEFAULT '',
  `mce_datetime` datetime DEFAULT NULL,
  `mce_ip` varchar(50) NOT NULL DEFAULT '',
  `mce_content` text,
  PRIMARY KEY (`mce_id`),
  KEY `mem_id` (`mem_id`),
  KEY `mce_type` (`mce_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_certify`
--

LOCK TABLES `cb_member_certify` WRITE;
/*!40000 ALTER TABLE `cb_member_certify` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_member_certify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_dormant`
--

DROP TABLE IF EXISTS `cb_member_dormant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_dormant` (
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_userid` varchar(100) NOT NULL DEFAULT '',
  `mem_email` varchar(255) NOT NULL DEFAULT '',
  `mem_password` varchar(255) NOT NULL DEFAULT '',
  `mem_username` varchar(100) NOT NULL DEFAULT '',
  `mem_nickname` varchar(100) NOT NULL DEFAULT '',
  `mem_level` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `mem_point` int(11) NOT NULL DEFAULT '0',
  `mem_homepage` text,
  `mem_phone` varchar(255) NOT NULL DEFAULT '',
  `mem_birthday` char(10) NOT NULL DEFAULT '',
  `mem_sex` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_zipcode` varchar(7) NOT NULL DEFAULT '',
  `mem_address1` varchar(255) NOT NULL DEFAULT '',
  `mem_address2` varchar(255) NOT NULL DEFAULT '',
  `mem_address3` varchar(255) NOT NULL DEFAULT '',
  `mem_address4` varchar(255) NOT NULL DEFAULT '',
  `mem_receive_email` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_use_note` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_receive_sms` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_open_profile` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_denied` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_email_cert` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_register_datetime` datetime DEFAULT NULL,
  `mem_register_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_lastlogin_datetime` datetime DEFAULT NULL,
  `mem_lastlogin_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_is_admin` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_profile_content` text,
  `mem_adminmemo` text,
  `mem_following` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_followed` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_icon` varchar(255) NOT NULL DEFAULT '',
  `mem_photo` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `mem_userid` (`mem_userid`),
  KEY `mem_id` (`mem_id`),
  KEY `mem_email` (`mem_email`),
  KEY `mem_lastlogin_datetime` (`mem_lastlogin_datetime`),
  KEY `mem_register_datetime` (`mem_register_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_dormant`
--

LOCK TABLES `cb_member_dormant` WRITE;
/*!40000 ALTER TABLE `cb_member_dormant` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_member_dormant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_dormant_notify`
--

DROP TABLE IF EXISTS `cb_member_dormant_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_dormant_notify` (
  `mdn_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_userid` varchar(100) NOT NULL DEFAULT '',
  `mem_email` varchar(255) NOT NULL DEFAULT '',
  `mem_username` varchar(100) NOT NULL DEFAULT '',
  `mem_nickname` varchar(100) NOT NULL DEFAULT '',
  `mem_register_datetime` datetime DEFAULT NULL,
  `mem_lastlogin_datetime` datetime DEFAULT NULL,
  `mdn_dormant_datetime` datetime DEFAULT NULL,
  `mdn_dormant_notify_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`mdn_id`),
  KEY `mem_id` (`mem_id`),
  KEY `mem_email` (`mem_email`),
  KEY `mem_register_datetime` (`mem_register_datetime`),
  KEY `mem_lastlogin_datetime` (`mem_lastlogin_datetime`),
  KEY `mdn_dormant_datetime` (`mdn_dormant_datetime`),
  KEY `mdn_dormant_notify_datetime` (`mdn_dormant_notify_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_dormant_notify`
--

LOCK TABLES `cb_member_dormant_notify` WRITE;
/*!40000 ALTER TABLE `cb_member_dormant_notify` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_member_dormant_notify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_extra_vars`
--

DROP TABLE IF EXISTS `cb_member_extra_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_extra_vars` (
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mev_key` varchar(255) NOT NULL DEFAULT '',
  `mev_value` text,
  UNIQUE KEY `mem_id_mev_key` (`mem_id`,`mev_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_extra_vars`
--

LOCK TABLES `cb_member_extra_vars` WRITE;
/*!40000 ALTER TABLE `cb_member_extra_vars` DISABLE KEYS */;
INSERT INTO `cb_member_extra_vars` VALUES (1,'mem_biz_no','1'),(2,'mem_biz_no','609-86-00081');
/*!40000 ALTER TABLE `cb_member_extra_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_group`
--

DROP TABLE IF EXISTS `cb_member_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_group` (
  `mgr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mgr_title` varchar(255) NOT NULL DEFAULT '',
  `mgr_is_default` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mgr_datetime` datetime DEFAULT NULL,
  `mgr_order` int(11) NOT NULL DEFAULT '0',
  `mgr_description` text,
  PRIMARY KEY (`mgr_id`),
  KEY `mgr_order` (`mgr_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_group`
--

LOCK TABLES `cb_member_group` WRITE;
/*!40000 ALTER TABLE `cb_member_group` DISABLE KEYS */;
INSERT INTO `cb_member_group` VALUES (1,'기본그룹',1,'2017-11-10 14:11:15',1,NULL),(2,'특별그룹',0,'2017-11-10 14:11:15',2,NULL),(3,'우수그룹',0,'2017-11-10 14:11:15',3,NULL);
/*!40000 ALTER TABLE `cb_member_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_group_member`
--

DROP TABLE IF EXISTS `cb_member_group_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_group_member` (
  `mgm_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mgr_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mgm_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`mgm_id`),
  KEY `mgr_id` (`mgr_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_group_member`
--

LOCK TABLES `cb_member_group_member` WRITE;
/*!40000 ALTER TABLE `cb_member_group_member` DISABLE KEYS */;
INSERT INTO `cb_member_group_member` VALUES (3,1,2,'2017-11-15 09:59:29');
/*!40000 ALTER TABLE `cb_member_group_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_level_history`
--

DROP TABLE IF EXISTS `cb_member_level_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_level_history` (
  `mlh_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mlh_from` int(11) unsigned NOT NULL DEFAULT '0',
  `mlh_to` int(11) unsigned NOT NULL DEFAULT '0',
  `mlh_datetime` datetime DEFAULT NULL,
  `mlh_reason` varchar(255) NOT NULL DEFAULT '',
  `mlh_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`mlh_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_level_history`
--

LOCK TABLES `cb_member_level_history` WRITE;
/*!40000 ALTER TABLE `cb_member_level_history` DISABLE KEYS */;
INSERT INTO `cb_member_level_history` VALUES (1,2,0,1,'2017-11-14 15:57:01','회원가입','112.163.89.66');
/*!40000 ALTER TABLE `cb_member_level_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_login_log`
--

DROP TABLE IF EXISTS `cb_member_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_login_log` (
  `mll_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mll_success` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mll_userid` varchar(255) NOT NULL DEFAULT '',
  `mll_datetime` datetime DEFAULT NULL,
  `mll_ip` varchar(50) NOT NULL DEFAULT '',
  `mll_reason` varchar(255) NOT NULL DEFAULT '',
  `mll_useragent` varchar(255) NOT NULL DEFAULT '',
  `mll_url` text,
  `mll_referer` text,
  PRIMARY KEY (`mll_id`),
  KEY `mll_success` (`mll_success`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_login_log`
--

LOCK TABLES `cb_member_login_log` WRITE;
/*!40000 ALTER TABLE `cb_member_login_log` DISABLE KEYS */;
INSERT INTO `cb_member_login_log` VALUES (1,1,1,'admin','2017-11-10 15:10:33','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(2,1,1,'admin','2017-11-13 09:40:34','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(3,1,1,'admin','2017-11-13 11:04:43','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(4,1,1,'admin','2017-11-13 15:58:35','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login?url=http%3A%2F%2Fdhn.webthink.co.kr%2Fadmin%2Fconfig%2Fmemberconfig','http://dhn.webthink.co.kr/admin/config/memberconfig'),(5,1,1,'admin','2017-11-13 16:03:07','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(6,1,1,'admin','2017-11-13 16:15:26','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login?url=http%3A%2F%2Fdhn.webthink.co.kr%2Fadmin%2Fconfig%2Fmemberconfig%2Fregisterform','http://dhn.webthink.co.kr/admin/config/memberconfig/registerform'),(7,1,1,'admin','2017-11-13 16:17:57','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(8,1,1,'admin','2017-11-13 16:55:27','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login?url=http%3A%2F%2Fdhn.webthink.co.kr%2Fdeposit','http://dhn.webthink.co.kr/deposit'),(9,1,1,'admin','2017-11-14 09:37:33','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(10,1,1,'admin','2017-11-14 10:03:01','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(11,1,1,'admin','2017-11-14 10:08:40','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(12,1,1,'admin','2017-11-14 10:26:01','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(13,1,1,'admin','2017-11-14 10:31:23','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(14,1,1,'admin','2017-11-15 09:30:34','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(15,1,4,'darkskill','2017-11-15 15:02:42','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(16,1,4,'darkskill','2017-11-15 15:09:57','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(17,1,1,'admin','2017-11-15 15:10:51','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(18,1,1,'admin','2017-11-15 16:04:41','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(19,1,1,'admin','2017-11-15 18:08:28','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(20,1,1,'admin','2017-11-16 09:40:04','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(21,1,2,'webthink','2017-11-16 14:54:00','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(22,1,1,'admin','2017-11-16 14:55:28','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(23,1,1,'admin','2017-11-17 09:32:49','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(24,1,1,'admin','2017-11-17 14:18:42','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/login',''),(25,1,1,'admin','2017-11-20 09:35:56','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(26,1,1,'admin','2017-11-21 09:43:10','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(27,1,1,'admin','2017-11-22 09:29:57','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(28,1,2,'webthink','2017-11-22 09:40:57','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(29,1,1,'admin','2017-11-22 13:12:23','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(30,1,2,'webthink','2017-11-22 14:33:37','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(31,1,1,'admin','2017-11-22 20:39:44','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(32,1,1,'admin','2017-11-22 23:14:31','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(33,1,1,'admin','2017-11-23 09:29:46','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(34,1,1,'admin','2017-11-23 13:26:00','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(35,1,1,'admin','2017-11-23 16:19:32','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299','http://dhn.webthink.co.kr/login',''),(36,1,2,'webthink','2017-11-23 16:22:03','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(37,1,1,'admin','2017-11-23 16:22:11','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(38,1,2,'webthink','2017-11-23 16:24:34','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299','http://dhn.webthink.co.kr/login',''),(39,1,2,'webthink','2017-11-23 16:25:01','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(40,1,2,'webthink','2017-11-23 16:28:32','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(41,1,1,'admin','2017-11-23 16:40:30','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(42,1,1,'admin','2017-11-23 20:04:57','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(43,1,2,'webthink','2017-11-23 20:34:13','211.36.134.81','로그인 성공','Mozilla/5.0 (Linux; Android 7.0; SM-G925L Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36;KAKAOTALK 1600306','http://dhn.webthink.co.kr/login',''),(44,1,1,'admin','2017-11-23 21:28:25','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(45,1,2,'webthink','2017-11-24 08:58:06','61.75.253.253','로그인 성공','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Whale/1.0.37.16 Safari/537.36','http://dhn.webthink.co.kr/login',''),(46,1,1,'admin','2017-11-24 09:55:03','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(47,1,1,'admin','2017-11-24 09:55:41','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(48,1,1,'admin','2017-11-24 11:18:55','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(49,1,1,'admin','2017-11-24 11:19:51','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(50,1,1,'admin','2017-11-24 11:42:37','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(51,1,1,'admin','2017-11-24 20:06:49','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(52,1,2,'webthink','2017-11-25 10:45:51','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(53,1,1,'admin','2017-11-26 01:52:15','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(54,1,1,'admin','2017-11-26 17:17:16','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(55,1,1,'admin','2017-11-26 21:36:52','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(56,1,1,'admin','2017-11-27 09:44:38','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(57,1,2,'webthink','2017-11-27 11:02:00','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(58,1,2,'webthink','2017-11-27 11:35:05','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(59,0,1,'admin','2017-11-27 15:15:02','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(60,1,1,'admin','2017-11-27 15:15:08','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(61,1,1,'admin','2017-11-27 15:17:45','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(62,1,2,'webthink','2017-11-27 15:53:47','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(63,1,1,'admin','2017-11-27 19:18:14','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(64,0,2,'webthink','2017-11-27 22:53:54','1.226.241.15','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(65,1,2,'webthink','2017-11-27 22:54:00','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(66,1,3,'dhn','2017-11-27 22:54:15','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(67,0,4,'darkskill','2017-11-27 22:54:33','1.226.241.15','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(68,0,4,'darkskill','2017-11-27 22:54:38','1.226.241.15','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(69,1,4,'darkskill','2017-11-27 22:54:44','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(70,1,1,'admin','2017-11-27 22:55:15','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(71,1,1,'admin','2017-11-28 09:49:07','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(72,1,2,'webthink','2017-11-28 10:02:21','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(73,1,1,'admin','2017-11-28 12:00:40','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(74,1,1,'admin','2017-11-28 12:03:54','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(75,0,1,'admin','2017-11-28 14:58:42','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299','http://dhn.webthink.co.kr/login',''),(76,0,2,'webthink','2017-11-28 14:58:55','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299','http://dhn.webthink.co.kr/login',''),(77,1,2,'webthink','2017-11-28 15:01:17','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(78,0,2,'webthink','2017-11-28 15:12:33','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299','http://dhn.webthink.co.kr/login',''),(79,1,1,'admin','2017-11-28 15:12:49','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299','http://dhn.webthink.co.kr/login',''),(80,1,1,'admin','2017-11-28 15:17:48','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(81,1,1,'admin','2017-11-28 15:38:59','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(82,1,2,'webthink','2017-11-28 17:54:27','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(83,1,1,'admin','2017-11-28 19:47:21','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(84,1,2,'webthink','2017-11-29 09:03:01','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(85,1,1,'admin','2017-11-29 09:37:06','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(86,0,2,'webthink','2017-11-29 10:05:46','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(87,0,2,'webthink','2017-11-29 10:05:55','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(88,0,2,'webthink','2017-11-29 10:06:09','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(89,0,2,'webthink','2017-11-29 10:06:21','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(90,0,2,'webthink','2017-11-29 10:06:28','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(91,0,2,'webthink','2017-11-29 10:07:03','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(92,0,2,'webthink','2017-11-29 10:07:13','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(93,1,2,'webthink','2017-11-29 10:07:30','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(94,1,5,'webthink7','2017-11-29 11:30:05','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(95,1,2,'webthink','2017-11-29 11:33:52','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(96,1,1,'admin','2017-11-29 14:18:52','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(97,1,1,'admin','2017-11-29 14:56:27','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(98,1,1,'admin','2017-11-29 15:09:04','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(99,0,1,'admin','2017-11-29 15:40:05','112.163.89.66','패스워드가 올바르지 않습니다','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(100,1,1,'admin','2017-11-29 15:40:14','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(101,1,2,'webthink','2017-11-29 15:49:48','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(102,1,1,'admin','2017-11-29 16:07:10','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(103,1,1,'admin','2017-11-29 16:08:18','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(104,1,2,'webthink','2017-11-29 16:12:06','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(105,1,1,'admin','2017-11-29 16:16:30','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(106,1,1,'admin','2017-11-29 16:21:15','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(107,1,1,'admin','2017-11-29 16:22:22','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(108,1,1,'admin','2017-11-29 17:41:17','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login?url=http%3A%2F%2Fdhn.webthink.co.kr%2Fadmin%2Fboard%2Fboards%2Fwrite%2F11','http://dhn.webthink.co.kr/admin/board/boards/write/11'),(109,1,1,'admin','2017-11-29 19:51:59','1.226.241.15','로그인 성공','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(110,0,2,'webthink','2017-11-29 22:40:45','116.45.151.116','패스워드가 올바르지 않습니다','Mozilla/5.0 (Linux; Android 7.0; SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36','http://dhn.webthink.co.kr/login',''),(111,1,2,'webthink','2017-11-29 22:41:26','116.45.151.116','로그인 성공','Mozilla/5.0 (Linux; Android 7.0; SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36','http://dhn.webthink.co.kr/login',''),(112,1,2,'webthink','2017-11-30 09:19:30','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(113,1,1,'admin','2017-11-30 09:26:59','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(114,1,2,'webthink','2017-11-30 09:50:00','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(115,1,1,'admin','2017-11-30 09:52:11','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/login',''),(116,1,1,'admin','2017-11-30 10:08:11','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(117,1,1,'admin','2017-11-30 13:32:03','112.163.89.66','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(118,1,2,'webthink','2017-11-30 14:36:09','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login',''),(119,1,2,'webthink','2017-11-30 17:10:47','211.197.42.37','로그인 성공','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','http://dhn.webthink.co.kr/login','');
/*!40000 ALTER TABLE `cb_member_login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_meta`
--

DROP TABLE IF EXISTS `cb_member_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_meta` (
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mmt_key` varchar(255) NOT NULL DEFAULT '',
  `mmt_value` text,
  UNIQUE KEY `mem_id_mmt_key` (`mem_id`,`mmt_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_meta`
--

LOCK TABLES `cb_member_meta` WRITE;
/*!40000 ALTER TABLE `cb_member_meta` DISABLE KEYS */;
INSERT INTO `cb_member_meta` VALUES (1,'meta_change_pw_datetime','2017-11-10 14:11:15'),(1,'meta_email_cert_datetime','2017-11-10 14:11:15'),(1,'meta_nickname_datetime','2017-11-10 14:11:15'),(1,'meta_open_profile_datetime','2017-11-10 14:11:15'),(1,'meta_use_note_datetime','2017-11-10 14:11:15'),(1,'total_deposit','20000'),(2,'meta_change_pw_datetime','2017-11-14 15:57:01'),(2,'meta_email_cert_datetime','2017-11-14 15:57:01'),(2,'meta_nickname_datetime','2017-11-14 15:57:01'),(2,'meta_open_profile_datetime','2017-11-14 15:57:01'),(2,'total_deposit','10000'),(5,'meta_change_pw_datetime','2017-11-29 11:30:29'),(5,'total_deposit','');
/*!40000 ALTER TABLE `cb_member_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_nickname`
--

DROP TABLE IF EXISTS `cb_member_nickname`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_nickname` (
  `mni_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mni_nickname` varchar(100) NOT NULL DEFAULT '',
  `mni_start_datetime` datetime DEFAULT NULL,
  `mni_end_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`mni_id`),
  KEY `mem_id` (`mem_id`),
  KEY `mni_nickname` (`mni_nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_nickname`
--

LOCK TABLES `cb_member_nickname` WRITE;
/*!40000 ALTER TABLE `cb_member_nickname` DISABLE KEYS */;
INSERT INTO `cb_member_nickname` VALUES (1,1,'관리자','2017-11-10 14:11:15',NULL),(2,2,'강영식','2017-11-14 15:57:01',NULL);
/*!40000 ALTER TABLE `cb_member_nickname` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_register`
--

DROP TABLE IF EXISTS `cb_member_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_register` (
  `mrg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mrg_ip` varchar(50) NOT NULL DEFAULT '',
  `mrg_datetime` datetime DEFAULT NULL,
  `mrg_recommend_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mrg_useragent` varchar(255) NOT NULL DEFAULT '',
  `mrg_referer` text,
  PRIMARY KEY (`mrg_id`),
  UNIQUE KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_register`
--

LOCK TABLES `cb_member_register` WRITE;
/*!40000 ALTER TABLE `cb_member_register` DISABLE KEYS */;
INSERT INTO `cb_member_register` VALUES (1,1,'112.163.89.66','2017-11-10 14:11:15',0,'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',NULL),(2,2,'112.163.89.66','2017-11-14 15:57:01',1,'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36','http://dhn.webthink.co.kr/'),(3,3,'','0000-00-00 00:00:00',2,'',''),(4,4,'','0000-00-00 00:00:00',3,'',''),(5,5,'112.163.89.66','2017-11-29 11:28:24',2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','http://dhn.webthink.co.kr/');
/*!40000 ALTER TABLE `cb_member_register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_selfcert_history`
--

DROP TABLE IF EXISTS `cb_member_selfcert_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_selfcert_history` (
  `msh_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msh_company` varchar(255) NOT NULL DEFAULT '',
  `msh_certtype` varchar(255) NOT NULL DEFAULT '',
  `msh_cert_key` varchar(255) NOT NULL DEFAULT '',
  `msh_datetime` datetime DEFAULT NULL,
  `msh_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`msh_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_selfcert_history`
--

LOCK TABLES `cb_member_selfcert_history` WRITE;
/*!40000 ALTER TABLE `cb_member_selfcert_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_member_selfcert_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_member_userid`
--

DROP TABLE IF EXISTS `cb_member_userid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_member_userid` (
  `mem_id` int(11) unsigned NOT NULL,
  `mem_userid` varchar(255) NOT NULL DEFAULT '',
  `mem_status` int(11) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `mem_userid` (`mem_userid`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_member_userid`
--

LOCK TABLES `cb_member_userid` WRITE;
/*!40000 ALTER TABLE `cb_member_userid` DISABLE KEYS */;
INSERT INTO `cb_member_userid` VALUES (1,'admin',0),(2,'webthink',0);
/*!40000 ALTER TABLE `cb_member_userid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_menu`
--

DROP TABLE IF EXISTS `cb_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_menu` (
  `men_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `men_parent` int(11) unsigned NOT NULL DEFAULT '0',
  `men_name` varchar(255) NOT NULL DEFAULT '',
  `men_link` text,
  `men_show` char(1) DEFAULT 'Y' COMMENT '메뉴공개',
  `men_allow_lv` mediumint(8) unsigned DEFAULT '1' COMMENT '접근회원레벨',
  `men_target` varchar(255) NOT NULL DEFAULT '',
  `men_desktop` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `men_mobile` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `men_custom` varchar(255) NOT NULL DEFAULT '',
  `men_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`men_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_menu`
--

LOCK TABLES `cb_menu` WRITE;
/*!40000 ALTER TABLE `cb_menu` DISABLE KEYS */;
INSERT INTO `cb_menu` VALUES (1,0,'발신','','Y',1,'',1,1,'',10),(2,1,'발신','/biz/sender/send/friend','Y',1,'',1,1,'',11),(3,1,'발신 목록','/biz/sender/history','Y',1,'',1,1,'',12),(4,1,'이미지 목록','/biz/sender/image_list','Y',1,'',1,1,'',13),(5,0,'고객관리','','Y',1,'',1,1,'',20),(6,5,'고객 목록','/biz/customer/lists','Y',1,'',1,1,'',21),(7,5,'고객 등록','/biz/customer/write','Y',1,'',1,1,'',22),(9,0,'템플릿','','Y',1,'',1,1,'',30),(10,9,'템플릿 목록','/biz/template/lists','Y',1,'',1,1,'',31),(11,9,'템플릿 등록','/biz/template/write','Y',1,'',1,1,'',32),(13,0,'통계','','Y',1,'',1,1,'',40),(14,13,'발송통계','/biz/statistics/day','Y',1,'',1,1,'',41),(15,0,'발신프로필','','Y',1,'',1,1,'',50),(16,15,'발신 프로필 목록','/biz/sendprofile/lists','Y',1,'',1,1,'',51),(17,15,'발신 프로필 그룹','/biz/sendprofile/group','N',50,'',1,1,'',52),(18,15,'발신 프로필 등록','/biz/sendprofile/write','Y',1,'',1,1,'',53),(19,15,'플러스친구 통계','/biz/sendprofile/plusfriend','N',50,'',1,1,'',54),(20,0,'파트너관리','','Y',50,'',1,1,'',60),(21,20,'파트너 목록','/biz/partner/lists','Y',50,'',1,1,'',61),(22,0,'공지사항','','Y',1,'',1,1,'',80),(24,20,'파트너 등록','/biz/partner/write','Y',50,'',1,1,'',62),(25,0,'고객센터','','Y',1,'',1,1,'',90),(26,25,'FAQ','/faq/faq','Y',1,'',1,1,'',91),(27,25,'Q&A','/board/qna','Y',1,'',1,1,'',92),(28,22,'공지사항','/board/notice','Y',1,'',1,1,'',81),(29,0,'상품 관리','','N',100,'',1,1,'',70),(30,29,'메세지 요금관리','/biz/service/manage_price','N',100,'',1,1,'',71),(31,0,'환불 관리','','Y',1,'',1,1,'',100),(32,31,'환불 요청 목록','/biz/refund/lists','Y',1,'',1,1,'',101);
/*!40000 ALTER TABLE `cb_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_migrations`
--

DROP TABLE IF EXISTS `cb_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_migrations`
--

LOCK TABLES `cb_migrations` WRITE;
/*!40000 ALTER TABLE `cb_migrations` DISABLE KEYS */;
INSERT INTO `cb_migrations` VALUES (20160327000000);
/*!40000 ALTER TABLE `cb_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_msg_admin`
--

DROP TABLE IF EXISTS `cb_msg_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_msg_admin` (
  `MSGID` varchar(20) NOT NULL,
  `AD_FLAG` varchar(1) DEFAULT NULL,
  `BUTTON1` longtext,
  `BUTTON2` longtext,
  `BUTTON3` longtext,
  `BUTTON4` longtext,
  `BUTTON5` longtext,
  `CODE` varchar(4) DEFAULT NULL,
  `IMAGE_LINK` longtext,
  `IMAGE_URL` longtext,
  `KIND` varchar(1) DEFAULT NULL,
  `MESSAGE` longtext,
  `MESSAGE_TYPE` varchar(2) DEFAULT NULL,
  `MSG` longtext NOT NULL,
  `MSG_SMS` longtext,
  `ONLY_SMS` varchar(1) DEFAULT NULL,
  `P_COM` varchar(2) DEFAULT NULL,
  `P_INVOICE` varchar(100) DEFAULT NULL,
  `PHN` varchar(15) NOT NULL,
  `PROFILE` varchar(50) DEFAULT NULL,
  `REG_DT` datetime NOT NULL,
  `REMARK1` varchar(50) DEFAULT NULL,
  `REMARK2` varchar(50) DEFAULT NULL,
  `REMARK3` varchar(50) DEFAULT NULL,
  `REMARK4` varchar(50) DEFAULT NULL,
  `REMARK5` varchar(50) DEFAULT NULL,
  `RES_DT` datetime DEFAULT NULL,
  `RESERVE_DT` varchar(14) NOT NULL,
  `RESULT` varchar(1) DEFAULT NULL,
  `S_CODE` varchar(2) DEFAULT NULL,
  `SMS_KIND` varchar(1) DEFAULT NULL,
  `SMS_LMS_TIT` varchar(120) DEFAULT NULL,
  `SMS_SENDER` varchar(15) DEFAULT NULL,
  `SYNC` varchar(1) NOT NULL,
  `TMPL_ID` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_msg_admin`
--

LOCK TABLES `cb_msg_admin` WRITE;
/*!40000 ALTER TABLE `cb_msg_admin` DISABLE KEYS */;
INSERT INTO `cb_msg_admin` VALUES ('1_20','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n??? ?? ??? ?? ??\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 21:22:15','1',NULL,NULL,'24','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_21','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n???? ?? ?? (12/01~12/07)\n??, ??, ??, ?????? ?? ???? ?? 50% ??\n?? ??? ???? ??, ??? ?? (12/01 10:30 ?? 8? ?????)\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 21:30:04','1',NULL,NULL,'25','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_19','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','(??)\r\n\r\nsadsdsadsdsadsa\r\n\r\n???? : ?>????','ft','(??)\n\nsadsdsadsdsadsa\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 17:52:09','1',NULL,NULL,'1','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',NULL),('1_22','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n??? ??? ??? ??? ??? ? ???.\n??? ????\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 21:43:36','1',NULL,NULL,'26','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_23','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:27:10','1',NULL,NULL,'27','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_24','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:29:31','1',NULL,NULL,'28','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_25','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:09','1',NULL,NULL,'29','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_26','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821054117500','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:09','1',NULL,NULL,'29','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_27','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K101',NULL,NULL,'K','NotAvailableSendMessage','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821065748654','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:09','1',NULL,NULL,'29','L',NULL,'00000000000000','N',NULL,'','','0552389456','Y',''),('1_28','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:44','1',NULL,NULL,'30','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_29','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821054117500','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:44','1',NULL,NULL,'30','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_30','Y','{\"type\":\"WL\",\"name\":\"???test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(??)\n\n1234567890\n\n???? : ?>????','','N',NULL,NULL,'821027877110','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:44','1',NULL,NULL,'30','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_31','Y','{\"type\":\"WL\",\"name\":\"친구톡test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(광고)\n\n한글 등록 테스트\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 11:30:53','1',NULL,NULL,'31','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_38','N','{\"type\":\"BK\",\"name\":\"집으로 직접 받고싶어요!\"}','{\"type\":\"BK\",\"name\":\"경비실에 부탁드릴께요~\"}','{\"type\":\"BK\",\"name\":\"나만의 시크릿 장소?! (상세히 설명해 주세요)\"}',NULL,NULL,'K105',NULL,NULL,'K','NoMatchedTemplate','at','고객명 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처 대리점명 배송기사명 배송기사 연락처\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','1','N',NULL,NULL,'821093111339','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:46:53','1',NULL,NULL,'38','L',NULL,'00000000000000','N',NULL,'','1','01065748654','Y','alimtalktest_006'),('1_39','N','{\"type\":\"BK\",\"name\":\"집으로 직접 받고싶어요!\"}','{\"type\":\"BK\",\"name\":\"경비실에 부탁드릴께요~\"}','{\"type\":\"BK\",\"name\":\"나만의 시크릿 장소?! (상세히 설명해 주세요)\"}',NULL,NULL,'K105',NULL,NULL,'K','NoMatchedTemplate','at','고객명 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처\n\n대리점명 배송기사명\n배송기사 연락처\n\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','1','N',NULL,NULL,'821093111339','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:49:06','1',NULL,NULL,'39','L',NULL,'00000000000000','N',NULL,'','1','01065748654','Y','alimtalktest_006'),('1_40','N','{\"type\":\"BK\",\"name\":\"집으로 직접 받고싶어요!\"}','{\"type\":\"BK\",\"name\":\"경비실에 부탁드릴께요~\"}','{\"type\":\"BK\",\"name\":\"나만의 시크릿 장소?! (상세히 설명해 주세요)\"}',NULL,NULL,'K105',NULL,NULL,'K','NoMatchedTemplate','at','고객명 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처\n대리점명 배송기사명\n배송기사 연락처\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시\n겠어요? ^^','1','N',NULL,NULL,'821093111339','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:50:11','1',NULL,NULL,'40','L',NULL,'00000000000000','N',NULL,'','1','01065748654','Y','alimtalktest_006'),('1_41','N','{\"type\":\"BK\",\"name\":\"집으로 직접 받고싶어요!\"}','{\"type\":\"BK\",\"name\":\"경비실에 부탁드릴께요~\"}','{\"type\":\"BK\",\"name\":\"나만의 시크릿 장소?! (상세히 설명해 주세요)\"}',NULL,NULL,'K105',NULL,NULL,'K','NoMatchedTemplate','at','#{고객명} 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처\n\n대리점명 배송기사명\n배송기사 연락처\n\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','1','N',NULL,NULL,'821093111339','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:53:22','1',NULL,NULL,'41','L',NULL,'00000000000000','N',NULL,'','1','01065748654','Y','alimtalktest_006'),('1_42','N','{\"type\":\"WL\",\"name\":\"주문내역 상세보기\",\"url_mobile\":\"http://www.kakao.com\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','at','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 12341234\n□ 배송지 : 구/면  동/리\n□ 배송예정일 : 00월 00일\n□ 결제금액 :: 결제금액원','1','N',NULL,NULL,'821093111339','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:55:49','1',NULL,NULL,'42','L',NULL,'00000000000000','Y',NULL,'','1','01065748654','Y','alimtalktest_004'),('1_43','Y','{\"type\":\"WL\",\"name\":\"친구톡test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:53:24','1',NULL,NULL,'49','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y',''),('1_46','Y','{\"type\":\"WL\",\"name\":\"친구톡test\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@clsrnxhrxptmxm\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'K000',NULL,NULL,'K','','ft','(광고)\n\n\n\n수신거부 : 홈>친구차단','','N',NULL,NULL,'821093111339','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:54:29','1',NULL,NULL,'50','L',NULL,'00000000000000','Y',NULL,'','','0552389456','Y','');
/*!40000 ALTER TABLE `cb_msg_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_msg_webthink7`
--

DROP TABLE IF EXISTS `cb_msg_webthink7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_msg_webthink7` (
  `MSGID` varchar(20) NOT NULL,
  `AD_FLAG` varchar(1) DEFAULT NULL,
  `BUTTON1` longtext,
  `BUTTON2` longtext,
  `BUTTON3` longtext,
  `BUTTON4` longtext,
  `BUTTON5` longtext,
  `CODE` varchar(4) DEFAULT NULL,
  `IMAGE_LINK` longtext,
  `IMAGE_URL` longtext,
  `KIND` varchar(1) DEFAULT NULL,
  `MESSAGE` longtext,
  `MESSAGE_TYPE` varchar(2) DEFAULT NULL,
  `MSG` longtext NOT NULL,
  `MSG_SMS` longtext,
  `ONLY_SMS` varchar(1) DEFAULT NULL,
  `P_COM` varchar(2) DEFAULT NULL,
  `P_INVOICE` varchar(100) DEFAULT NULL,
  `PHN` varchar(15) NOT NULL,
  `PROFILE` varchar(50) DEFAULT NULL,
  `REG_DT` datetime NOT NULL,
  `REMARK1` varchar(50) DEFAULT NULL,
  `REMARK2` varchar(50) DEFAULT NULL,
  `REMARK3` varchar(50) DEFAULT NULL,
  `REMARK4` varchar(50) DEFAULT NULL,
  `REMARK5` varchar(50) DEFAULT NULL,
  `RES_DT` datetime DEFAULT NULL,
  `RESERVE_DT` varchar(14) NOT NULL,
  `RESULT` varchar(1) DEFAULT NULL,
  `S_CODE` varchar(2) DEFAULT NULL,
  `SMS_KIND` varchar(1) DEFAULT NULL,
  `SMS_LMS_TIT` varchar(120) DEFAULT NULL,
  `SMS_SENDER` varchar(15) DEFAULT NULL,
  `SYNC` varchar(1) NOT NULL,
  `TMPL_ID` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_msg_webthink7`
--

LOCK TABLES `cb_msg_webthink7` WRITE;
/*!40000 ALTER TABLE `cb_msg_webthink7` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_msg_webthink7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_note`
--

DROP TABLE IF EXISTS `cb_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_note` (
  `nte_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `recv_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `nte_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `related_note_id` int(11) unsigned NOT NULL DEFAULT '0',
  `nte_title` varchar(255) NOT NULL DEFAULT '',
  `nte_content` text,
  `nte_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `nte_datetime` datetime DEFAULT NULL,
  `nte_read_datetime` datetime DEFAULT NULL,
  `nte_originname` varchar(255) DEFAULT '',
  `nte_filename` varchar(255) DEFAULT '',
  PRIMARY KEY (`nte_id`),
  KEY `send_mem_id` (`send_mem_id`),
  KEY `recv_mem_id` (`recv_mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_note`
--

LOCK TABLES `cb_note` WRITE;
/*!40000 ALTER TABLE `cb_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_notification`
--

DROP TABLE IF EXISTS `cb_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_notification` (
  `not_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `not_type` varchar(255) NOT NULL DEFAULT '',
  `not_content_id` int(11) unsigned NOT NULL DEFAULT '0',
  `not_message` varchar(255) NOT NULL DEFAULT '',
  `not_url` varchar(255) NOT NULL DEFAULT '',
  `not_datetime` datetime DEFAULT NULL,
  `not_read_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`not_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_notification`
--

LOCK TABLES `cb_notification` WRITE;
/*!40000 ALTER TABLE `cb_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_payment_inicis_log`
--

DROP TABLE IF EXISTS `cb_payment_inicis_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_payment_inicis_log` (
  `pil_id` bigint(11) unsigned NOT NULL,
  `pil_type` varchar(255) NOT NULL DEFAULT '',
  `P_TID` varchar(255) NOT NULL DEFAULT '',
  `P_MID` varchar(255) NOT NULL DEFAULT '',
  `P_AUTH_DT` varchar(255) NOT NULL DEFAULT '',
  `P_STATUS` varchar(255) NOT NULL DEFAULT '',
  `P_TYPE` varchar(255) NOT NULL DEFAULT '',
  `P_OID` varchar(255) NOT NULL DEFAULT '',
  `P_FN_NM` varchar(255) NOT NULL DEFAULT '',
  `P_AMT` int(11) unsigned NOT NULL DEFAULT '0',
  `P_AUTH_NO` varchar(255) NOT NULL DEFAULT '',
  `P_RMESG1` varchar(255) NOT NULL DEFAULT '',
  KEY `pil_id` (`pil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_payment_inicis_log`
--

LOCK TABLES `cb_payment_inicis_log` WRITE;
/*!40000 ALTER TABLE `cb_payment_inicis_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_payment_inicis_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_payment_order_data`
--

DROP TABLE IF EXISTS `cb_payment_order_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_payment_order_data` (
  `pod_id` bigint(11) unsigned NOT NULL,
  `pod_pg` varchar(255) NOT NULL DEFAULT '',
  `pod_type` varchar(255) NOT NULL DEFAULT '',
  `pod_data` text,
  `pod_datetime` datetime DEFAULT NULL,
  `pod_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) NOT NULL DEFAULT '0',
  `cart_id` varchar(255) NOT NULL DEFAULT '0',
  KEY `pod_id` (`pod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_payment_order_data`
--

LOCK TABLES `cb_payment_order_data` WRITE;
/*!40000 ALTER TABLE `cb_payment_order_data` DISABLE KEYS */;
INSERT INTO `cb_payment_order_data` VALUES (2017111318033539,'inicis','deposit','YTozNjp7czo1OiJwdHlwZSI7czo3OiJkZXBvc2l0IjtzOjc6InZlcnNpb24iO3M6MzoiMS4wIjtzOjM6Im1pZCI7czoxMDoiSU5JcGF5VGVzdCI7czozOiJvaWQiO3M6MTY6IjIwMTcxMTEzMTgwMzM1MzkiO3M6ODoiZ29vZG5hbWUiO3M6MTY6IuyYiOy5mOq4iCDstqnsoIQiO3M6NToicHJpY2UiO3M6NToiMTAwMDAiO3M6OToiYnV5ZXJuYW1lIjtzOjk6Iuq0gOumrOyekCI7czoxMDoiYnV5ZXJlbWFpbCI7czoxNzoicG1Ad2VidGhpbmsuY28ua3IiO3M6MTE6InBhcmVudGVtYWlsIjtzOjA6IiI7czo4OiJidXllcnRlbCI7czoxMToiMDEwOTMxMTEzMzkiO3M6ODoicmVjdm5hbWUiO3M6OToi6rSA66as7J6QIjtzOjc6InJlY3Z0ZWwiO3M6MTE6IjAxMDkzMTExMzM5IjtzOjg6InJlY3ZhZGRyIjtzOjA6IiI7czoxMToicmVjdnBvc3RudW0iO3M6MDoiIjtzOjg6ImN1cnJlbmN5IjtzOjM6IldPTiI7czoxMToiZ29wYXltZXRob2QiO3M6ODoib25seWNhcmQiO3M6MTI6ImFjY2VwdG1ldGhvZCI7czozNToiSFBQKDIpOkNhcmQoMCk6bm9fcmVjZWlwdDpjYXJkcG9pbnQiO3M6OToidGltZXN0YW1wIjtzOjA6IiI7czo5OiJzaWduYXR1cmUiO3M6MDoiIjtzOjk6InJldHVyblVybCI7czo0MzoiaHR0cDovL2Robi53ZWJ0aGluay5jby5rci9kZXBvc2l0L2luaWNpc3dlYiI7czo0OiJtS2V5IjtzOjA6IiI7czo3OiJjaGFyc2V0IjtzOjU6IlVURi04IjtzOjExOiJwYXlWaWV3VHlwZSI7czo3OiJvdmVybGF5IjtzOjg6ImNsb3NlVXJsIjtzOjQ2OiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL3BheW1lbnQvaW5pY2lzX2Nsb3NlIjtzOjg6InBvcHVwVXJsIjtzOjQ2OiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL3BheW1lbnQvaW5pY2lzX3BvcHVwIjtzOjEwOiJub2ludGVyZXN0IjtzOjA6IiI7czo5OiJxdW90YWJhc2UiO3M6MjQ6IjI6Mzo0OjU6Njo3Ojg6OToxMDoxMToxMiI7czoxMjoiZGVwb3NpdF9yZWFsIjtzOjU6IjEwMDAwIjtzOjk6InVuaXF1ZV9pZCI7czoxNjoiMjAxNzExMTMxODAzMzUzOSI7czo4OiJnb29kX21ueSI7czo1OiIxMDAwMCI7czoxMToibW9uZXlfdmFsdWUiO3M6NToiMTAwMDAiO3M6MTM6ImRlcG9zaXRfdmFsdWUiO2E6NDp7aTowO3M6NToiMTAwMDAiO2k6MTtzOjU6IjIwMDAwIjtpOjI7czo1OiIzMDAwMCI7aTozO3M6NToiNTAwMDAiO31zOjEyOiJtZW1fcmVhbG5hbWUiO3M6OToi6rSA66as7J6QIjtzOjk6Im1lbV9lbWFpbCI7czoxNzoicG1Ad2VidGhpbmsuY28ua3IiO3M6OToibWVtX3Bob25lIjtzOjExOiIwMTA5MzExMTMzOSI7czo4OiJwYXlfdHlwZSI7czo0OiJjYXJkIjt9','2017-11-13 18:03:43','112.163.89.66',1,''),(2017111318135735,'inicis','deposit','YTozNjp7czo1OiJwdHlwZSI7czo3OiJkZXBvc2l0IjtzOjc6InZlcnNpb24iO3M6MzoiMS4wIjtzOjM6Im1pZCI7czoxMDoiSU5JcGF5VGVzdCI7czozOiJvaWQiO3M6MTY6IjIwMTcxMTEzMTgxMzU3MzUiO3M6ODoiZ29vZG5hbWUiO3M6MTY6IuyYiOy5mOq4iCDstqnsoIQiO3M6NToicHJpY2UiO3M6NToiMTAwMDAiO3M6OToiYnV5ZXJuYW1lIjtzOjk6Iuq0gOumrOyekCI7czoxMDoiYnV5ZXJlbWFpbCI7czoxNzoicG1Ad2VidGhpbmsuY28ua3IiO3M6MTE6InBhcmVudGVtYWlsIjtzOjA6IiI7czo4OiJidXllcnRlbCI7czoxMToiMDEwOTMxMTEzMzkiO3M6ODoicmVjdm5hbWUiO3M6OToi6rSA66as7J6QIjtzOjc6InJlY3Z0ZWwiO3M6MTE6IjAxMDkzMTExMzM5IjtzOjg6InJlY3ZhZGRyIjtzOjA6IiI7czoxMToicmVjdnBvc3RudW0iO3M6MDoiIjtzOjg6ImN1cnJlbmN5IjtzOjM6IldPTiI7czoxMToiZ29wYXltZXRob2QiO3M6ODoib25seWNhcmQiO3M6MTI6ImFjY2VwdG1ldGhvZCI7czozNToiSFBQKDIpOkNhcmQoMCk6bm9fcmVjZWlwdDpjYXJkcG9pbnQiO3M6OToidGltZXN0YW1wIjtzOjA6IiI7czo5OiJzaWduYXR1cmUiO3M6MDoiIjtzOjk6InJldHVyblVybCI7czo0MzoiaHR0cDovL2Robi53ZWJ0aGluay5jby5rci9kZXBvc2l0L2luaWNpc3dlYiI7czo0OiJtS2V5IjtzOjA6IiI7czo3OiJjaGFyc2V0IjtzOjU6IlVURi04IjtzOjExOiJwYXlWaWV3VHlwZSI7czo3OiJvdmVybGF5IjtzOjg6ImNsb3NlVXJsIjtzOjQ2OiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL3BheW1lbnQvaW5pY2lzX2Nsb3NlIjtzOjg6InBvcHVwVXJsIjtzOjQ2OiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL3BheW1lbnQvaW5pY2lzX3BvcHVwIjtzOjEwOiJub2ludGVyZXN0IjtzOjA6IiI7czo5OiJxdW90YWJhc2UiO3M6MjQ6IjI6Mzo0OjU6Njo3Ojg6OToxMDoxMToxMiI7czoxMjoiZGVwb3NpdF9yZWFsIjtzOjU6IjEwMDAwIjtzOjk6InVuaXF1ZV9pZCI7czoxNjoiMjAxNzExMTMxODEzNTczNSI7czo4OiJnb29kX21ueSI7czo1OiIxMDAwMCI7czoxMToibW9uZXlfdmFsdWUiO3M6NToiMTAwMDAiO3M6MTM6ImRlcG9zaXRfdmFsdWUiO2E6NDp7aTowO3M6NToiMTAwMDAiO2k6MTtzOjU6IjIwMDAwIjtpOjI7czo1OiIzMDAwMCI7aTozO3M6NToiNTAwMDAiO31zOjEyOiJtZW1fcmVhbG5hbWUiO3M6OToi6rSA66as7J6QIjtzOjk6Im1lbV9lbWFpbCI7czoxNzoicG1Ad2VidGhpbmsuY28ua3IiO3M6OToibWVtX3Bob25lIjtzOjExOiIwMTA5MzExMTMzOSI7czo4OiJwYXlfdHlwZSI7czo0OiJjYXJkIjt9','2017-11-13 18:14:04','112.163.89.66',1,''),(2017112715303344,'inicis','deposit','YTozNjp7czo1OiJwdHlwZSI7czo3OiJkZXBvc2l0IjtzOjc6InZlcnNpb24iO3M6MzoiMS4wIjtzOjM6Im1pZCI7czoxMDoiSU5JcGF5VGVzdCI7czozOiJvaWQiO3M6MTY6IjIwMTcxMTI3MTUzMDMzNDQiO3M6ODoiZ29vZG5hbWUiO3M6MTY6IuyYiOy5mOq4iCDstqnsoIQiO3M6NToicHJpY2UiO3M6NToiMTAwMDAiO3M6OToiYnV5ZXJuYW1lIjtzOjk6Iuq0gOumrOyekCI7czoxMDoiYnV5ZXJlbWFpbCI7czoxNzoicG1Ad2VidGhpbmsuY28ua3IiO3M6MTE6InBhcmVudGVtYWlsIjtzOjA6IiI7czo4OiJidXllcnRlbCI7czoxMToiMDEwOTMxMTEzMzkiO3M6ODoicmVjdm5hbWUiO3M6OToi6rSA66as7J6QIjtzOjc6InJlY3Z0ZWwiO3M6MTE6IjAxMDkzMTExMzM5IjtzOjg6InJlY3ZhZGRyIjtzOjA6IiI7czoxMToicmVjdnBvc3RudW0iO3M6MDoiIjtzOjg6ImN1cnJlbmN5IjtzOjM6IldPTiI7czoxMToiZ29wYXltZXRob2QiO3M6ODoib25seWNhcmQiO3M6MTI6ImFjY2VwdG1ldGhvZCI7czozNToiSFBQKDIpOkNhcmQoMCk6bm9fcmVjZWlwdDpjYXJkcG9pbnQiO3M6OToidGltZXN0YW1wIjtzOjA6IiI7czo5OiJzaWduYXR1cmUiO3M6MDoiIjtzOjk6InJldHVyblVybCI7czo0MzoiaHR0cDovL2Robi53ZWJ0aGluay5jby5rci9kZXBvc2l0L2luaWNpc3dlYiI7czo0OiJtS2V5IjtzOjA6IiI7czo3OiJjaGFyc2V0IjtzOjU6IlVURi04IjtzOjExOiJwYXlWaWV3VHlwZSI7czo3OiJvdmVybGF5IjtzOjg6ImNsb3NlVXJsIjtzOjQ2OiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL3BheW1lbnQvaW5pY2lzX2Nsb3NlIjtzOjg6InBvcHVwVXJsIjtzOjQ2OiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL3BheW1lbnQvaW5pY2lzX3BvcHVwIjtzOjEwOiJub2ludGVyZXN0IjtzOjA6IiI7czo5OiJxdW90YWJhc2UiO3M6MjQ6IjI6Mzo0OjU6Njo3Ojg6OToxMDoxMToxMiI7czoxMjoiZGVwb3NpdF9yZWFsIjtzOjU6IjEwMDAwIjtzOjk6InVuaXF1ZV9pZCI7czoxNjoiMjAxNzExMjcxNTMwMzM0NCI7czo4OiJnb29kX21ueSI7czo1OiIxMDAwMCI7czoxMToibW9uZXlfdmFsdWUiO3M6NToiMTAwMDAiO3M6MTM6ImRlcG9zaXRfdmFsdWUiO2E6NDp7aTowO3M6NToiMTAwMDAiO2k6MTtzOjU6IjIwMDAwIjtpOjI7czo1OiIzMDAwMCI7aTozO3M6NToiNTAwMDAiO31zOjEyOiJtZW1fcmVhbG5hbWUiO3M6OToi6rSA66as7J6QIjtzOjk6Im1lbV9lbWFpbCI7czoxNzoicG1Ad2VidGhpbmsuY28ua3IiO3M6OToibWVtX3Bob25lIjtzOjExOiIwMTA5MzExMTMzOSI7czo4OiJwYXlfdHlwZSI7czo0OiJjYXJkIjt9','2017-11-27 15:31:14','112.163.89.66',1,''),(2017113009194070,'inicis','deposit','YTozNjp7czo1OiJwdHlwZSI7czo3OiJkZXBvc2l0IjtzOjc6InZlcnNpb24iO3M6MzoiMS4wIjtzOjM6Im1pZCI7czoxMDoiSU5JcGF5VGVzdCI7czozOiJvaWQiO3M6MTY6IjIwMTcxMTMwMDkxOTQwNzAiO3M6ODoiZ29vZG5hbWUiO3M6MTY6IuyYiOy5mOq4iCDstqnsoIQiO3M6NToicHJpY2UiO3M6NToiNTAwMDAiO3M6OToiYnV5ZXJuYW1lIjtzOjk6IuqwleyYgeyLnSI7czoxMDoiYnV5ZXJlbWFpbCI7czoxNjoidXdvb3RvQGdtYWlsLmNvbSI7czoxMToicGFyZW50ZW1haWwiO3M6MDoiIjtzOjg6ImJ1eWVydGVsIjtzOjExOiIwMTA3NjY5NDU1NiI7czo4OiJyZWN2bmFtZSI7czo5OiLqsJXsmIHsi50iO3M6NzoicmVjdnRlbCI7czoxMToiMDEwNzY2OTQ1NTYiO3M6ODoicmVjdmFkZHIiO3M6MDoiIjtzOjExOiJyZWN2cG9zdG51bSI7czowOiIiO3M6ODoiY3VycmVuY3kiO3M6MzoiV09OIjtzOjExOiJnb3BheW1ldGhvZCI7czo4OiJvbmx5Y2FyZCI7czoxMjoiYWNjZXB0bWV0aG9kIjtzOjM1OiJIUFAoMik6Q2FyZCgwKTpub19yZWNlaXB0OmNhcmRwb2ludCI7czo5OiJ0aW1lc3RhbXAiO3M6MDoiIjtzOjk6InNpZ25hdHVyZSI7czowOiIiO3M6OToicmV0dXJuVXJsIjtzOjQzOiJodHRwOi8vZGhuLndlYnRoaW5rLmNvLmtyL2RlcG9zaXQvaW5pY2lzd2ViIjtzOjQ6Im1LZXkiO3M6MDoiIjtzOjc6ImNoYXJzZXQiO3M6NToiVVRGLTgiO3M6MTE6InBheVZpZXdUeXBlIjtzOjc6Im92ZXJsYXkiO3M6ODoiY2xvc2VVcmwiO3M6NDY6Imh0dHA6Ly9kaG4ud2VidGhpbmsuY28ua3IvcGF5bWVudC9pbmljaXNfY2xvc2UiO3M6ODoicG9wdXBVcmwiO3M6NDY6Imh0dHA6Ly9kaG4ud2VidGhpbmsuY28ua3IvcGF5bWVudC9pbmljaXNfcG9wdXAiO3M6MTA6Im5vaW50ZXJlc3QiO3M6MDoiIjtzOjk6InF1b3RhYmFzZSI7czoyNDoiMjozOjQ6NTo2Ojc6ODo5OjEwOjExOjEyIjtzOjEyOiJkZXBvc2l0X3JlYWwiO3M6NToiNTAwMDAiO3M6OToidW5pcXVlX2lkIjtzOjE2OiIyMDE3MTEzMDA5MTk0MDcwIjtzOjg6Imdvb2RfbW55IjtzOjU6IjUwMDAwIjtzOjEzOiJkZXBvc2l0X3ZhbHVlIjthOjQ6e2k6MDtzOjU6IjEwMDAwIjtpOjE7czo1OiIyMDAwMCI7aToyO3M6NToiMzAwMDAiO2k6MztzOjU6IjUwMDAwIjt9czoxMToibW9uZXlfdmFsdWUiO3M6NToiNTAwMDAiO3M6MTI6Im1lbV9yZWFsbmFtZSI7czo5OiLqsJXsmIHsi50iO3M6OToibWVtX2VtYWlsIjtzOjE2OiJ1d29vdG9AZ21haWwuY29tIjtzOjk6Im1lbV9waG9uZSI7czoxMToiMDEwNzY2OTQ1NTYiO3M6ODoicGF5X3R5cGUiO3M6NDoiY2FyZCI7fQ==','2017-11-30 09:20:02','211.197.42.37',2,'');
/*!40000 ALTER TABLE `cb_payment_order_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_point`
--

DROP TABLE IF EXISTS `cb_point`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_point` (
  `poi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `poi_datetime` datetime DEFAULT NULL,
  `poi_content` varchar(255) NOT NULL DEFAULT '',
  `poi_point` int(11) NOT NULL DEFAULT '0',
  `poi_type` varchar(20) NOT NULL DEFAULT '',
  `poi_related_id` varchar(20) NOT NULL DEFAULT '',
  `poi_action` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`poi_id`),
  KEY `mem_id_poi_type_poi_related_id_poi_action` (`mem_id`,`poi_type`,`poi_related_id`,`poi_action`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_point`
--

LOCK TABLES `cb_point` WRITE;
/*!40000 ALTER TABLE `cb_point` DISABLE KEYS */;
INSERT INTO `cb_point` VALUES (1,1,'2017-11-13 09:40:34','2017-11-13 첫로그인',5,'login','1','2017-11-13 로그인'),(2,2,'2017-11-14 15:57:01','회원가입을 축하합니다',1000,'member','2','회원가입');
/*!40000 ALTER TABLE `cb_point` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_popup`
--

DROP TABLE IF EXISTS `cb_popup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_popup` (
  `pop_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pop_start_date` date DEFAULT NULL,
  `pop_end_date` date DEFAULT NULL,
  `pop_is_center` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `pop_left` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `pop_top` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `pop_width` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `pop_height` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `pop_device` varchar(10) NOT NULL DEFAULT '',
  `pop_title` varchar(255) NOT NULL DEFAULT '',
  `pop_content` text,
  `pop_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `pop_disable_hours` int(11) unsigned NOT NULL DEFAULT '0',
  `pop_activated` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `pop_page` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `pop_datetime` datetime DEFAULT NULL,
  `pop_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pop_id`),
  KEY `pop_start_date` (`pop_start_date`),
  KEY `pop_end_date` (`pop_end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_popup`
--

LOCK TABLES `cb_popup` WRITE;
/*!40000 ALTER TABLE `cb_popup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_popup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post`
--

DROP TABLE IF EXISTS `cb_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_num` int(11) NOT NULL DEFAULT '0',
  `post_reply` varchar(10) NOT NULL DEFAULT '',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_title` varchar(255) NOT NULL DEFAULT '',
  `post_content` mediumtext,
  `post_category` varchar(255) NOT NULL DEFAULT '',
  `mem_id` int(11) NOT NULL DEFAULT '0',
  `post_userid` varchar(100) NOT NULL DEFAULT '',
  `post_username` varchar(100) NOT NULL DEFAULT '',
  `post_nickname` varchar(100) NOT NULL DEFAULT '',
  `post_email` varchar(255) NOT NULL DEFAULT '',
  `post_homepage` text,
  `post_datetime` datetime DEFAULT NULL,
  `post_password` varchar(255) NOT NULL DEFAULT '',
  `post_updated_datetime` datetime DEFAULT NULL,
  `post_update_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_comment_count` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `post_comment_updated_datetime` datetime DEFAULT NULL,
  `post_link_count` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `post_secret` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_html` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_hide_comment` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_notice` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_receive_email` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_hit` int(11) unsigned NOT NULL DEFAULT '0',
  `post_like` int(11) unsigned NOT NULL DEFAULT '0',
  `post_dislike` int(11) unsigned NOT NULL DEFAULT '0',
  `post_ip` varchar(50) NOT NULL DEFAULT '',
  `post_blame` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `post_device` varchar(10) NOT NULL DEFAULT '',
  `post_file` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_image` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `post_del` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `ppo_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `post_num_post_reply` (`post_num`,`post_reply`),
  KEY `brd_id` (`brd_id`),
  KEY `post_datetime` (`post_datetime`),
  KEY `post_updated_datetime` (`post_updated_datetime`),
  KEY `post_comment_updated_datetime` (`post_comment_updated_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post`
--

LOCK TABLES `cb_post` WRITE;
/*!40000 ALTER TABLE `cb_post` DISABLE KEYS */;
INSERT INTO `cb_post` VALUES (1,-1,'',1,'공지사항 글쓰기 테스트 : 2017년 11월 7일 15:10:20 글을 작성하여 등록합니다.','<p><br></p>','',1,'admin','관리자','관리자','pm@webthink.co.kr','','2017-11-17 15:12:25','','2017-11-17 15:13:12',1,0,NULL,0,0,1,0,0,0,3,0,0,'112.163.89.66',0,'desktop',0,0,0,0),(2,-2,'',1,'ㄴㄴ','<p>ㄴㄴㄴㄹ</p>','',5,'webthink7','(주)웹싱크','강영식','shigy22@gmail.com','0','2017-11-29 13:41:02','','2017-11-29 13:41:02',0,0,NULL,2,0,1,0,0,0,1,0,0,'112.163.89.66',0,'desktop',0,0,0,0),(4,-3,'',2,'ㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴ','<p>ㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴㄴ</p>','',5,'webthink7','(주)웹싱크','강영식','shigy22@gmail.com','0','2017-11-29 13:44:13','','2017-11-29 13:44:13',0,0,NULL,2,0,1,0,0,0,1,0,0,'112.163.89.66',0,'desktop',0,0,0,0),(5,-3,'A',2,'ㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁ','<p>ㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁㅁ</p>','',5,'webthink7','(주)웹싱크','강영식','shigy22@gmail.com','0','2017-11-29 13:45:08','','2017-11-29 13:45:08',0,0,NULL,2,0,1,0,0,0,1,0,0,'112.163.89.66',0,'desktop',0,0,0,0),(6,-4,'',12,'321323','31232132131','',1,'admin','관리자','관리자','pm@webthink.co.kr','','2017-11-29 16:10:05','','2017-11-29 16:10:05',0,0,NULL,2,0,0,0,0,0,7,0,0,'112.163.89.66',0,'desktop',0,0,0,0);
/*!40000 ALTER TABLE `cb_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_extra_vars`
--

DROP TABLE IF EXISTS `cb_post_extra_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_extra_vars` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pev_key` varchar(255) NOT NULL DEFAULT '',
  `pev_value` text,
  UNIQUE KEY `post_id_pev_key` (`post_id`,`pev_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_extra_vars`
--

LOCK TABLES `cb_post_extra_vars` WRITE;
/*!40000 ALTER TABLE `cb_post_extra_vars` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_extra_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_file`
--

DROP TABLE IF EXISTS `cb_post_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_file` (
  `pfi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pfi_originname` varchar(255) NOT NULL DEFAULT '',
  `pfi_filename` varchar(255) NOT NULL DEFAULT '',
  `pfi_download` int(11) unsigned NOT NULL DEFAULT '0',
  `pfi_filesize` int(11) unsigned NOT NULL DEFAULT '0',
  `pfi_width` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `pfi_height` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `pfi_type` varchar(10) NOT NULL DEFAULT '',
  `pfi_is_image` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `pfi_datetime` datetime DEFAULT NULL,
  `pfi_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pfi_id`),
  KEY `post_id` (`post_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_file`
--

LOCK TABLES `cb_post_file` WRITE;
/*!40000 ALTER TABLE `cb_post_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_file_download_log`
--

DROP TABLE IF EXISTS `cb_post_file_download_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_file_download_log` (
  `pfd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pfi_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pfd_datetime` datetime DEFAULT NULL,
  `pfd_ip` varchar(50) NOT NULL DEFAULT '',
  `pfd_useragent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pfd_id`),
  KEY `pfi_id` (`pfi_id`),
  KEY `post_id` (`post_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_file_download_log`
--

LOCK TABLES `cb_post_file_download_log` WRITE;
/*!40000 ALTER TABLE `cb_post_file_download_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_file_download_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_history`
--

DROP TABLE IF EXISTS `cb_post_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_history` (
  `phi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `phi_title` varchar(255) NOT NULL DEFAULT '',
  `phi_content` mediumtext,
  `phi_content_html_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `phi_ip` varchar(50) NOT NULL DEFAULT '',
  `phi_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`phi_id`),
  KEY `post_id` (`post_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_history`
--

LOCK TABLES `cb_post_history` WRITE;
/*!40000 ALTER TABLE `cb_post_history` DISABLE KEYS */;
INSERT INTO `cb_post_history` VALUES (1,6,12,1,'321323','31232132131',0,'112.163.89.66','2017-11-29 16:10:05');
/*!40000 ALTER TABLE `cb_post_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_link`
--

DROP TABLE IF EXISTS `cb_post_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_link` (
  `pln_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pln_url` text,
  `pln_hit` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pln_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_link`
--

LOCK TABLES `cb_post_link` WRITE;
/*!40000 ALTER TABLE `cb_post_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_link_click_log`
--

DROP TABLE IF EXISTS `cb_post_link_click_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_link_click_log` (
  `plc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pln_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `plc_datetime` datetime DEFAULT NULL,
  `plc_ip` varchar(50) NOT NULL DEFAULT '',
  `plc_useragent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`plc_id`),
  KEY `pln_id` (`pln_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_link_click_log`
--

LOCK TABLES `cb_post_link_click_log` WRITE;
/*!40000 ALTER TABLE `cb_post_link_click_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_link_click_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_meta`
--

DROP TABLE IF EXISTS `cb_post_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_meta` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pmt_key` varchar(255) NOT NULL DEFAULT '',
  `pmt_value` text,
  UNIQUE KEY `post_id_pmt_key` (`post_id`,`pmt_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_meta`
--

LOCK TABLES `cb_post_meta` WRITE;
/*!40000 ALTER TABLE `cb_post_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_naver_syndi_log`
--

DROP TABLE IF EXISTS `cb_post_naver_syndi_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_naver_syndi_log` (
  `pns_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pns_status` varchar(255) NOT NULL DEFAULT '',
  `pns_return_code` varchar(255) NOT NULL DEFAULT '',
  `pns_return_message` varchar(255) NOT NULL DEFAULT '',
  `pns_receipt_number` varchar(255) NOT NULL DEFAULT '',
  `pns_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pns_id`),
  KEY `post_id` (`post_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_naver_syndi_log`
--

LOCK TABLES `cb_post_naver_syndi_log` WRITE;
/*!40000 ALTER TABLE `cb_post_naver_syndi_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_naver_syndi_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_poll`
--

DROP TABLE IF EXISTS `cb_post_poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_poll` (
  `ppo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ppo_start_datetime` datetime DEFAULT NULL,
  `ppo_end_datetime` datetime DEFAULT NULL,
  `ppo_title` varchar(255) NOT NULL DEFAULT '',
  `ppo_count` int(11) unsigned NOT NULL DEFAULT '0',
  `ppo_choose_count` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `ppo_after_comment` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `ppo_point` int(11) NOT NULL DEFAULT '0',
  `ppo_datetime` datetime DEFAULT NULL,
  `ppo_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ppo_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_poll`
--

LOCK TABLES `cb_post_poll` WRITE;
/*!40000 ALTER TABLE `cb_post_poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_poll_item`
--

DROP TABLE IF EXISTS `cb_post_poll_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_poll_item` (
  `ppi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ppo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ppi_item` varchar(255) NOT NULL DEFAULT '',
  `ppi_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ppi_id`),
  KEY `ppo_id` (`ppo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_poll_item`
--

LOCK TABLES `cb_post_poll_item` WRITE;
/*!40000 ALTER TABLE `cb_post_poll_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_poll_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_poll_item_poll`
--

DROP TABLE IF EXISTS `cb_post_poll_item_poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_poll_item_poll` (
  `ppp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ppo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ppi_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ppp_datetime` datetime DEFAULT NULL,
  `ppp_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`ppp_id`),
  KEY `ppo_id` (`ppo_id`),
  KEY `ppi_id` (`ppi_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_poll_item_poll`
--

LOCK TABLES `cb_post_poll_item_poll` WRITE;
/*!40000 ALTER TABLE `cb_post_poll_item_poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_poll_item_poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_post_tag`
--

DROP TABLE IF EXISTS `cb_post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_post_tag` (
  `pta_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pta_tag` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pta_id`),
  KEY `post_id` (`post_id`),
  KEY `pta_tag` (`pta_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_post_tag`
--

LOCK TABLES `cb_post_tag` WRITE;
/*!40000 ALTER TABLE `cb_post_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_post_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sc_admin`
--

DROP TABLE IF EXISTS `cb_sc_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sc_admin` (
  `sc_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `sc_name` varchar(100) DEFAULT '' COMMENT '고객명',
  `sc_tel` varchar(15) DEFAULT '' COMMENT '전화번호',
  `sc_content` varchar(5000) DEFAULT '' COMMENT '내용(친구톡/알림톡)',
  `sc_button1` varchar(600) DEFAULT '' COMMENT '버튼1',
  `sc_button2` varchar(600) DEFAULT '' COMMENT '버튼2',
  `sc_button3` varchar(600) DEFAULT '' COMMENT '버튼3',
  `sc_button4` varchar(600) DEFAULT '' COMMENT '버튼4',
  `sc_button5` varchar(600) DEFAULT '' COMMENT '버튼5',
  `sc_sms_yn` char(1) DEFAULT 'N' COMMENT 'SMS재발신여부',
  `sc_lms_content` varchar(5000) DEFAULT '' COMMENT '내용LMS',
  `sc_sms_callback` varchar(15) DEFAULT '' COMMENT 'SMS발신번호',
  `sc_img_url` varchar(1000) DEFAULT '' COMMENT '이미지URL',
  `sc_img_link` varchar(1000) DEFAULT '' COMMENT '이미지Link',
  `sc_template` varchar(30) DEFAULT '' COMMENT '템플릿코드',
  `sc_p_com` varchar(5) DEFAULT '' COMMENT '택배사코드',
  `sc_p_invoice` varchar(100) DEFAULT '' COMMENT '택배송장번호',
  `sc_s_code` varchar(5) DEFAULT '' COMMENT '쇼핑몰코드',
  `sc_reserve_dt` varchar(14) DEFAULT '' COMMENT '예약전송일시',
  PRIMARY KEY (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sc_admin`
--

LOCK TABLES `cb_sc_admin` WRITE;
/*!40000 ALTER TABLE `cb_sc_admin` DISABLE KEYS */;
INSERT INTO `cb_sc_admin` VALUES (67,'','01027877110','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http:\\/\\/www.bizalimtalk.kr\"}','','','','','Y','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원',NULL,'','','alimtalktest_004','','','',''),(68,'','01093111339','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290101\n□ 배송지 : 창원시 진해구\n□ 배송예정일 : 11월 30일\n□ 결제금액 :: 13,600원','{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http:\\/\\/www.bizalimtalk.kr\"}','','','','','Y','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290101\n□ 배송지 : 창원시 진해구\n□ 배송예정일 : 11월 30일\n□ 결제금액 :: 13,600원',NULL,'','','alimtalktest_004','','','','');
/*!40000 ALTER TABLE `cb_sc_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sc_webthink`
--

DROP TABLE IF EXISTS `cb_sc_webthink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sc_webthink` (
  `sc_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `sc_name` varchar(100) DEFAULT '' COMMENT '고객명',
  `sc_tel` varchar(15) DEFAULT '' COMMENT '전화번호',
  `sc_content` varchar(5000) DEFAULT '' COMMENT '내용(친구톡/알림톡)',
  `sc_button1` varchar(600) DEFAULT '' COMMENT '버튼1',
  `sc_button2` varchar(600) DEFAULT '' COMMENT '버튼2',
  `sc_button3` varchar(600) DEFAULT '' COMMENT '버튼3',
  `sc_button4` varchar(600) DEFAULT '' COMMENT '버튼4',
  `sc_button5` varchar(600) DEFAULT '' COMMENT '버튼5',
  `sc_sms_yn` char(1) DEFAULT 'N' COMMENT 'SMS재발신여부',
  `sc_lms_content` varchar(5000) DEFAULT '' COMMENT '내용LMS',
  `sc_sms_callback` varchar(15) DEFAULT '' COMMENT 'SMS발신번호',
  `sc_img_url` varchar(1000) DEFAULT '' COMMENT '이미지URL',
  `sc_img_link` varchar(1000) DEFAULT '' COMMENT '이미지Link',
  `sc_template` varchar(30) DEFAULT '' COMMENT '템플릿코드',
  `sc_p_com` varchar(5) DEFAULT '' COMMENT '택배사코드',
  `sc_p_invoice` varchar(100) DEFAULT '' COMMENT '택배송장번호',
  `sc_s_code` varchar(5) DEFAULT '' COMMENT '쇼핑몰코드',
  `sc_reserve_dt` varchar(14) DEFAULT '' COMMENT '예약전송일시',
  PRIMARY KEY (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sc_webthink`
--

LOCK TABLES `cb_sc_webthink` WRITE;
/*!40000 ALTER TABLE `cb_sc_webthink` DISABLE KEYS */;
INSERT INTO `cb_sc_webthink` VALUES (2,'','01076694556','(광고)\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n수신거부 : 홈>친구차단','{\"type\":\"WL\",\"name\":\"(주)웹싱크\",\"url_mobile\":\"http://plus-talk.kakao.com/plus/home/@webthink\",\"url_pc\":\"\"}',NULL,NULL,NULL,NULL,'L','(광고) (주)웹싱크\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n무료수신거부 : ','01065748654',NULL,NULL,'','','','','');
/*!40000 ALTER TABLE `cb_sc_webthink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sc_webthink7`
--

DROP TABLE IF EXISTS `cb_sc_webthink7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sc_webthink7` (
  `sc_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
  `sc_name` varchar(100) DEFAULT '' COMMENT '고객명',
  `sc_tel` varchar(15) DEFAULT '' COMMENT '전화번호',
  `sc_content` varchar(5000) DEFAULT '' COMMENT '내용(친구톡/알림톡)',
  `sc_button1` varchar(600) DEFAULT '' COMMENT '버튼1',
  `sc_button2` varchar(600) DEFAULT '' COMMENT '버튼2',
  `sc_button3` varchar(600) DEFAULT '' COMMENT '버튼3',
  `sc_button4` varchar(600) DEFAULT '' COMMENT '버튼4',
  `sc_button5` varchar(600) DEFAULT '' COMMENT '버튼5',
  `sc_sms_yn` char(1) DEFAULT 'N' COMMENT 'SMS재발신여부',
  `sc_lms_content` varchar(5000) DEFAULT '' COMMENT '내용LMS',
  `sc_sms_callback` varchar(15) DEFAULT '' COMMENT 'SMS발신번호',
  `sc_img_url` varchar(1000) DEFAULT '' COMMENT '이미지URL',
  `sc_img_link` varchar(1000) DEFAULT '' COMMENT '이미지Link',
  `sc_template` varchar(30) DEFAULT '' COMMENT '템플릿코드',
  `sc_p_com` varchar(5) DEFAULT '' COMMENT '택배사코드',
  `sc_p_invoice` varchar(100) DEFAULT '' COMMENT '택배송장번호',
  `sc_s_code` varchar(5) DEFAULT '' COMMENT '쇼핑몰코드',
  `sc_reserve_dt` varchar(14) DEFAULT '' COMMENT '예약전송일시',
  PRIMARY KEY (`sc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sc_webthink7`
--

LOCK TABLES `cb_sc_webthink7` WRITE;
/*!40000 ALTER TABLE `cb_sc_webthink7` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_sc_webthink7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_scrap`
--

DROP TABLE IF EXISTS `cb_scrap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_scrap` (
  `scr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `scr_datetime` datetime DEFAULT NULL,
  `scr_title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`scr_id`),
  KEY `mem_id` (`mem_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_scrap`
--

LOCK TABLES `cb_scrap` WRITE;
/*!40000 ALTER TABLE `cb_scrap` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_scrap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_search_keyword`
--

DROP TABLE IF EXISTS `cb_search_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_search_keyword` (
  `sek_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sek_keyword` varchar(255) NOT NULL DEFAULT '',
  `sek_datetime` datetime DEFAULT NULL,
  `sek_ip` varchar(50) NOT NULL DEFAULT '',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sek_id`),
  KEY `sek_keyword_sek_datetime_sek_ip` (`sek_keyword`,`sek_datetime`,`sek_ip`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_search_keyword`
--

LOCK TABLES `cb_search_keyword` WRITE;
/*!40000 ALTER TABLE `cb_search_keyword` DISABLE KEYS */;
INSERT INTO `cb_search_keyword` VALUES (1,'11','2017-11-14 14:44:05','112.163.89.66',1);
/*!40000 ALTER TABLE `cb_search_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_session`
--

DROP TABLE IF EXISTS `cb_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_session` (
  `id` varchar(120) NOT NULL DEFAULT '',
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `timestamp` int(10) NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_session`
--

LOCK TABLES `cb_session` WRITE;
/*!40000 ALTER TABLE `cb_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sms_favorite`
--

DROP TABLE IF EXISTS `cb_sms_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sms_favorite` (
  `sfa_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sfa_title` varchar(255) NOT NULL DEFAULT '',
  `sfa_content` text,
  `sfa_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`sfa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sms_favorite`
--

LOCK TABLES `cb_sms_favorite` WRITE;
/*!40000 ALTER TABLE `cb_sms_favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_sms_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sms_member`
--

DROP TABLE IF EXISTS `cb_sms_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sms_member` (
  `sme_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `smg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `sme_name` varchar(255) NOT NULL DEFAULT '',
  `sme_phone` varchar(255) NOT NULL DEFAULT '',
  `sme_receive` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `sme_datetime` datetime DEFAULT NULL,
  `sme_memo` text,
  PRIMARY KEY (`sme_id`),
  KEY `smg_id` (`smg_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sms_member`
--

LOCK TABLES `cb_sms_member` WRITE;
/*!40000 ALTER TABLE `cb_sms_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_sms_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sms_member_group`
--

DROP TABLE IF EXISTS `cb_sms_member_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sms_member_group` (
  `smg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `smg_name` varchar(255) NOT NULL DEFAULT '',
  `smg_order` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `smg_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`smg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sms_member_group`
--

LOCK TABLES `cb_sms_member_group` WRITE;
/*!40000 ALTER TABLE `cb_sms_member_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_sms_member_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sms_send_content`
--

DROP TABLE IF EXISTS `cb_sms_send_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sms_send_content` (
  `ssc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ssc_content` text,
  `send_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ssc_send_phone` varchar(255) NOT NULL DEFAULT '',
  `ssc_booking` datetime DEFAULT NULL,
  `ssc_total` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `ssc_success` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `ssc_fail` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `ssc_datetime` datetime DEFAULT NULL,
  `ssc_memo` text,
  PRIMARY KEY (`ssc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sms_send_content`
--

LOCK TABLES `cb_sms_send_content` WRITE;
/*!40000 ALTER TABLE `cb_sms_send_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_sms_send_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_sms_send_history`
--

DROP TABLE IF EXISTS `cb_sms_send_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_sms_send_history` (
  `ssh_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ssc_id` int(11) unsigned NOT NULL DEFAULT '0',
  `send_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `recv_mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ssh_name` varchar(255) NOT NULL DEFAULT '',
  `ssh_phone` varchar(255) NOT NULL DEFAULT '',
  `ssh_success` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `ssh_datetime` datetime DEFAULT NULL,
  `ssh_memo` text,
  `ssh_log` text,
  PRIMARY KEY (`ssh_id`),
  KEY `ssc_id` (`ssc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_sms_send_history`
--

LOCK TABLES `cb_sms_send_history` WRITE;
/*!40000 ALTER TABLE `cb_sms_send_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_sms_send_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_social`
--

DROP TABLE IF EXISTS `cb_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_social` (
  `soc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `soc_type` varchar(255) NOT NULL DEFAULT '',
  `soc_account_id` varchar(255) NOT NULL DEFAULT '',
  `soc_key` varchar(255) NOT NULL DEFAULT '',
  `soc_value` text,
  PRIMARY KEY (`soc_id`),
  KEY `soc_account_id` (`soc_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_social`
--

LOCK TABLES `cb_social` WRITE;
/*!40000 ALTER TABLE `cb_social` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_social_meta`
--

DROP TABLE IF EXISTS `cb_social_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_social_meta` (
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `smt_key` varchar(255) NOT NULL DEFAULT '',
  `smt_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `mem_id_smt_key` (`mem_id`,`smt_key`),
  KEY `smt_value` (`smt_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_social_meta`
--

LOCK TABLES `cb_social_meta` WRITE;
/*!40000 ALTER TABLE `cb_social_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_social_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_stat_count`
--

DROP TABLE IF EXISTS `cb_stat_count`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_stat_count` (
  `sco_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sco_ip` varchar(50) NOT NULL DEFAULT '',
  `sco_date` date NOT NULL,
  `sco_time` time NOT NULL,
  `sco_referer` text,
  `sco_current` text,
  `sco_agent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sco_id`),
  KEY `sco_date` (`sco_date`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_stat_count`
--

LOCK TABLES `cb_stat_count` WRITE;
/*!40000 ALTER TABLE `cb_stat_count` DISABLE KEYS */;
INSERT INTO `cb_stat_count` VALUES (1,'112.163.89.66','2017-11-10','14:11:56','http://dhn.webthink.co.kr/install/step5','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(2,'112.163.89.66','2017-11-10','14:32:20','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(3,'112.163.89.66','2017-11-13','09:39:24','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(4,'112.163.89.66','2017-11-13','16:17:50','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(5,'112.163.89.66','2017-11-14','09:47:32','http://dhn.webthink.co.kr/biz/partner/add','http://dhn.webthink.co.kr/biz/partner/catalog','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(6,'112.163.89.66','2017-11-14','10:21:12','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(7,'112.163.89.66','2017-11-14','15:40:51','','http://dhn.webthink.co.kr/biz/partner/lists','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(8,'112.163.89.66','2017-11-15','09:48:48','http://dhn.webthink.co.kr/biz/partner/view?webthink','http://dhn.webthink.co.kr/biz/partner/edit?webthink','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(9,'112.163.89.66','2017-11-15','11:18:07','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(10,'112.163.89.66','2017-11-16','09:48:58','','http://dhn.webthink.co.kr/biz/partner/partner_charge?a01074993322','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(11,'112.163.89.66','2017-11-16','17:01:45','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(12,'112.163.89.66','2017-11-16','18:37:27','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299'),(13,'112.163.89.66','2017-11-17','09:58:36','http://dhn.webthink.co.kr/biz/sendprofile/write','http://dhn.webthink.co.kr/biz/template/write','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),(14,'112.163.89.66','2017-11-20','09:35:51','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(15,'112.163.89.66','2017-11-20','09:36:01','http://deve.webthink.co.kr/hosts/site.list','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(16,'112.163.89.66','2017-11-20','10:13:42','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(17,'112.163.89.66','2017-11-20','10:47:02','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(18,'112.163.89.66','2017-11-21','09:43:06','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(19,'112.163.89.66','2017-11-21','16:13:55','http://dhn.webthink.co.kr/shop/','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(20,'112.163.89.66','2017-11-22','09:30:21','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(21,'112.163.89.66','2017-11-22','09:31:27','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(22,'112.163.89.66','2017-11-22','11:37:43','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/biz/sender/send/friend','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(23,'112.163.89.66','2017-11-22','14:33:29','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(24,'1.226.241.15','2017-11-22','20:39:35','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(25,'112.163.89.66','2017-11-23','13:25:50','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(26,'112.163.89.66','2017-11-23','13:47:38','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299'),(27,'211.197.42.37','2017-11-23','16:19:11','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(28,'211.197.42.37','2017-11-23','16:24:40','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(29,'112.163.89.66','2017-11-23','16:40:13','http://deve.webthink.co.kr/hosts/site.list','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(30,'27.0.238.117','2017-11-23','17:48:55','','http://dhn.webthink.co.kr/','facebookexternalhit/1.1;kakaotalk-scrap/1.0; +https://devtalk.kakao.com/t/scrap/33984'),(31,'211.36.136.111','2017-11-23','17:49:08','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-G925L Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36;KAKAOTALK 1600306'),(32,'211.36.134.81','2017-11-23','20:33:01','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-G925L Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36;KAKAOTALK 1600306'),(33,'1.226.241.15','2017-11-23','20:40:05','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/biz/myinfo/info','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(34,'211.231.103.107','2017-11-24','08:52:48','','http://dhn.webthink.co.kr/','facebookexternalhit/1.1;kakaotalk-scrap/1.0; +https://devtalk.kakao.com/t/scrap/33984'),(35,'211.36.156.72','2017-11-24','08:56:28','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-G925L Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36;KAKAOTALK 1600306'),(36,'61.75.253.253','2017-11-24','08:56:58','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Whale/1.0.37.16 Safari/537.36'),(37,'211.197.42.37','2017-11-24','12:55:12','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(38,'112.163.89.66','2017-11-24','14:22:12','http://dhn.webthink.co.kr/biz/sender/send/friend','http://dhn.webthink.co.kr/biz/sender/send/talk','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(39,'112.163.89.66','2017-11-24','15:11:35','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(40,'211.197.42.37','2017-11-24','15:28:56','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(41,'112.163.89.66','2017-11-24','15:33:52','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(42,'112.163.89.66','2017-11-24','17:20:59','http://dhn.webthink.co.kr/shop/','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(43,'1.226.241.15','2017-11-24','20:40:33','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/biz/sendprofile/write','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(44,'116.45.151.116','2017-11-24','23:50:57','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(45,'116.45.151.116','2017-11-24','23:51:47','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-N920L Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36;KAKAOTALK 1600306'),(46,'211.197.42.37','2017-11-25','10:45:11','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(47,'117.111.8.114','2017-11-25','20:01:12','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(48,'211.114.35.194','2017-11-26','00:36:30','','http://dhn.webthink.co.kr/','Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'),(49,'1.226.241.15','2017-11-26','01:52:12','http://210.114.225.53/','http://dhn.webthink.co.kr/login','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(50,'218.146.252.38','2017-11-26','17:01:09','','http://dhn.webthink.co.kr/','curl/7.19.7 (x86_64-redhat-linux-gnu) libcurl/7.19.7 NSS/3.13.1.0 zlib/1.2.3 libidn/1.18 libssh2/1.2.2'),(51,'218.146.252.38','2017-11-26','17:02:28','','http://dhn.webthink.co.kr/','curl/7.19.7 (x86_64-redhat-linux-gnu) libcurl/7.19.7 NSS/3.13.1.0 zlib/1.2.3 libidn/1.18 libssh2/1.2.2'),(52,'218.146.252.38','2017-11-26','17:03:34','','http://dhn.webthink.co.kr/','curl/7.19.7 (x86_64-redhat-linux-gnu) libcurl/7.19.7 NSS/3.13.1.0 zlib/1.2.3 libidn/1.18 libssh2/1.2.2'),(53,'218.146.252.38','2017-11-26','17:17:55','','http://dhn.webthink.co.kr/biz/main/check_msg','curl/7.19.7 (x86_64-redhat-linux-gnu) libcurl/7.19.7 NSS/3.13.1.0 zlib/1.2.3 libidn/1.18 libssh2/1.2.2'),(54,'210.114.225.53','2017-11-26','17:23:15','','http://dhn.webthink.co.kr/biz/main/check_msg','curl/7.29.0'),(55,'66.102.6.57','2017-11-26','17:34:37','','http://dhn.webthink.co.kr/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon'),(56,'112.163.89.66','2017-11-27','09:36:01','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(57,'112.163.89.66','2017-11-27','09:44:33','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(58,'211.197.42.37','2017-11-27','10:16:56','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(59,'211.197.42.37','2017-11-27','10:18:39','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(60,'66.102.6.57','2017-11-27','10:20:16','','http://dhn.webthink.co.kr/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon'),(61,'66.102.6.56','2017-11-27','10:20:17','http://www.google.com/search','http://dhn.webthink.co.kr/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko; Google Web Preview) Chrome/41.0.2272.118 Safari/537.36'),(62,'112.163.89.66','2017-11-27','11:34:45','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(63,'211.197.42.37','2017-11-27','12:25:07','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(64,'112.163.89.66','2017-11-27','13:17:26','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(65,'117.111.10.178','2017-11-27','16:45:23','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(66,'1.226.241.15','2017-11-27','19:18:09','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(67,'116.45.151.116','2017-11-27','19:46:25','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(68,'116.45.151.116','2017-11-27','23:19:57','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(69,'117.111.23.211','2017-11-28','09:26:52','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(70,'117.111.23.211','2017-11-28','09:31:04','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(71,'112.163.89.66','2017-11-28','09:37:54','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(72,'112.163.89.66','2017-11-28','09:49:02','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(73,'211.197.42.37','2017-11-28','09:53:35','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(74,'211.197.42.37','2017-11-28','10:01:09','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(75,'112.163.89.66','2017-11-28','12:13:02','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(76,'66.102.6.57','2017-11-28','12:24:15','','http://dhn.webthink.co.kr/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon'),(77,'112.163.89.66','2017-11-28','13:18:10','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(78,'112.163.89.66','2017-11-28','14:58:26','http://deve.webthink.co.kr/hosts/site.list','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299'),(79,'211.197.42.37','2017-11-28','14:59:40','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(80,'112.163.89.66','2017-11-28','15:17:24','http://deve.webthink.co.kr/hosts/site.list','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(81,'112.163.89.66','2017-11-28','18:18:37','','http://dhn.webthink.co.kr/homepage/friendtalk','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(82,'117.111.28.229','2017-11-28','18:22:05','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(83,'117.111.28.229','2017-11-28','18:23:41','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(84,'1.226.241.15','2017-11-28','19:47:15','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(85,'116.45.151.116','2017-11-28','20:13:38','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(86,'211.197.42.37','2017-11-29','09:02:25','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(87,'112.163.89.66','2017-11-29','09:41:22','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(88,'112.163.89.66','2017-11-29','09:59:13','http://dhn.webthink.co.kr/biz/statistics/day','http://dhn.webthink.co.kr/biz/sendprofile/write','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(89,'211.197.42.37','2017-11-29','11:33:42','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(90,'112.163.89.66','2017-11-29','11:39:53','','http://dhn.webthink.co.kr/homepage/sangdamtalk','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(91,'66.102.6.234','2017-11-29','11:48:22','','http://dhn.webthink.co.kr/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon'),(92,'112.163.89.66','2017-11-29','15:36:43','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(93,'112.163.89.66','2017-11-29','15:49:35','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(94,'112.163.89.66','2017-11-29','16:05:37','http://dhn.webthink.co.kr/biz/template/write','http://dhn.webthink.co.kr/biz/template/lists','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(95,'112.163.89.66','2017-11-29','16:07:52','','http://dhn.webthink.co.kr/write/notice_01','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(96,'211.197.42.37','2017-11-29','16:11:14','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko'),(97,'117.111.28.178','2017-11-29','17:50:00','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(98,'1.226.241.15','2017-11-29','19:51:55','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(99,'211.114.35.194','2017-11-29','21:43:53','','http://dhn.webthink.co.kr/homepage/sangdamtalk','Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'),(100,'116.45.151.116','2017-11-29','22:39:44','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36'),(101,'211.36.132.212','2017-11-30','09:04:38','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(102,'211.197.42.37','2017-11-30','09:19:05','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(103,'211.197.42.37','2017-11-30','09:22:48','http://dhn.webthink.co.kr/homepage/friendtalk','http://dhn.webthink.co.kr/homepage/sangdamtalk','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36'),(104,'66.102.6.58','2017-11-30','09:41:43','','http://dhn.webthink.co.kr/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon'),(105,'112.163.89.66','2017-11-30','09:42:12','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/board/faq','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(106,'211.197.42.37','2017-11-30','09:42:52','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(107,'211.197.42.37','2017-11-30','09:49:35','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(108,'211.197.42.37','2017-11-30','09:49:47','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(109,'112.163.89.66','2017-11-30','10:06:57','http://dhn.webthink.co.kr/','http://dhn.webthink.co.kr/biz/sender/send/friend','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(110,'112.163.89.66','2017-11-30','10:07:04','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'),(111,'211.114.35.194','2017-11-30','11:22:00','','http://dhn.webthink.co.kr/login','Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'),(112,'211.197.42.37','2017-11-30','14:35:58','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(113,'112.163.89.66','2017-11-30','14:53:15','','http://dhn.webthink.co.kr/board/faq','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),(114,'211.197.42.37','2017-11-30','17:09:54','','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
/*!40000 ALTER TABLE `cb_stat_count` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_stat_count_board`
--

DROP TABLE IF EXISTS `cb_stat_count_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_stat_count_board` (
  `scb_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `scb_date` date NOT NULL,
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `scb_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`scb_id`),
  KEY `scb_date_brd_id` (`scb_date`,`brd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_stat_count_board`
--

LOCK TABLES `cb_stat_count_board` WRITE;
/*!40000 ALTER TABLE `cb_stat_count_board` DISABLE KEYS */;
INSERT INTO `cb_stat_count_board` VALUES (1,'2017-11-10',1,1),(2,'2017-11-10',2,1),(6,'2017-11-13',1,1),(7,'2017-11-13',1,1),(8,'2017-11-14',1,1),(9,'2017-11-17',1,1),(10,'2017-11-22',1,1),(11,'2017-11-22',2,1),(12,'2017-11-23',2,1),(13,'2017-11-23',1,1),(14,'2017-11-27',2,1),(15,'2017-11-27',2,1),(16,'2017-11-28',1,1),(17,'2017-11-28',2,1),(18,'2017-11-28',2,1),(19,'2017-11-28',2,1),(20,'2017-11-28',2,1),(21,'2017-11-28',2,1),(22,'2017-11-28',1,1),(23,'2017-11-28',2,1),(24,'2017-11-29',2,1),(25,'2017-11-29',2,1),(26,'2017-11-29',1,1),(27,'2017-11-29',10,1),(28,'2017-11-29',12,1),(29,'2017-11-29',12,1),(30,'2017-11-29',1,1),(31,'2017-11-29',1,1),(32,'2017-11-29',10,1),(33,'2017-11-29',2,1),(34,'2017-11-29',12,1),(35,'2017-11-29',10,1),(36,'2017-11-29',12,1),(37,'2017-11-29',2,1),(38,'2017-11-29',1,1),(39,'2017-11-29',12,1),(40,'2017-11-29',11,1),(41,'2017-11-29',10,1),(42,'2017-11-29',2,1),(43,'2017-11-29',13,1),(44,'2017-11-29',13,1),(45,'2017-11-29',2,1),(46,'2017-11-30',10,1),(47,'2017-11-30',13,1),(48,'2017-11-30',12,1),(49,'2017-11-30',11,1),(50,'2017-11-30',11,1),(51,'2017-11-30',12,1),(52,'2017-11-30',13,1),(53,'2017-11-30',13,1),(54,'2017-11-30',10,1),(55,'2017-11-30',13,1),(56,'2017-11-30',10,1),(57,'2017-11-30',13,1),(58,'2017-11-30',12,1),(59,'2017-11-30',11,1),(60,'2017-11-30',10,1),(61,'2017-11-30',12,1),(62,'2017-11-30',10,1),(63,'2017-11-30',11,1),(64,'2017-11-30',12,1);
/*!40000 ALTER TABLE `cb_stat_count_board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_stat_count_date`
--

DROP TABLE IF EXISTS `cb_stat_count_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_stat_count_date` (
  `scd_date` date NOT NULL,
  `scd_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`scd_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_stat_count_date`
--

LOCK TABLES `cb_stat_count_date` WRITE;
/*!40000 ALTER TABLE `cb_stat_count_date` DISABLE KEYS */;
INSERT INTO `cb_stat_count_date` VALUES ('2017-11-10',2),('2017-11-13',2),('2017-11-14',3),('2017-11-15',2),('2017-11-16',3),('2017-11-17',1),('2017-11-20',4),('2017-11-21',2),('2017-11-22',5),('2017-11-23',9),('2017-11-24',12),('2017-11-25',2),('2017-11-26',8),('2017-11-27',13),('2017-11-28',17),('2017-11-29',15),('2017-11-30',14);
/*!40000 ALTER TABLE `cb_stat_count_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_tempsave`
--

DROP TABLE IF EXISTS `cb_tempsave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_tempsave` (
  `tmp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brd_id` int(11) unsigned NOT NULL DEFAULT '0',
  `tmp_title` varchar(255) NOT NULL DEFAULT '',
  `tmp_content` mediumtext,
  `mem_id` int(11) unsigned NOT NULL DEFAULT '0',
  `tmp_ip` varchar(50) NOT NULL DEFAULT '',
  `tmp_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`tmp_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_tempsave`
--

LOCK TABLES `cb_tempsave` WRITE;
/*!40000 ALTER TABLE `cb_tempsave` DISABLE KEYS */;
INSERT INTO `cb_tempsave` VALUES (6,1,'','<p><br></p>',1,'112.163.89.66','2017-11-29 16:00:07'),(7,10,'fdsdf','<p><br></p>',1,'112.163.89.66','2017-11-29 16:07:47');
/*!40000 ALTER TABLE `cb_tempsave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_unique_id`
--

DROP TABLE IF EXISTS `cb_unique_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_unique_id` (
  `unq_id` bigint(20) unsigned NOT NULL,
  `unq_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`unq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_unique_id`
--

LOCK TABLES `cb_unique_id` WRITE;
/*!40000 ALTER TABLE `cb_unique_id` DISABLE KEYS */;
INSERT INTO `cb_unique_id` VALUES (2017111316542198,'112.163.89.66'),(2017111316552827,'112.163.89.66'),(2017111316595157,'112.163.89.66'),(2017111317000213,'112.163.89.66'),(2017111317001108,'112.163.89.66'),(2017111317034173,'112.163.89.66'),(2017111317043274,'112.163.89.66'),(2017111317045861,'112.163.89.66'),(2017111317050151,'112.163.89.66'),(2017111317051141,'112.163.89.66'),(2017111317054281,'112.163.89.66'),(2017111317055551,'112.163.89.66'),(2017111317075797,'112.163.89.66'),(2017111317093670,'112.163.89.66'),(2017111317101054,'112.163.89.66'),(2017111317143930,'112.163.89.66'),(2017111317150308,'112.163.89.66'),(2017111317151033,'112.163.89.66'),(2017111317152705,'112.163.89.66'),(2017111317154929,'112.163.89.66'),(2017111317181271,'112.163.89.66'),(2017111317185086,'112.163.89.66'),(2017111317245076,'112.163.89.66'),(2017111317333229,'112.163.89.66'),(2017111317424164,'112.163.89.66'),(2017111318033539,'112.163.89.66'),(2017111318135449,'112.163.89.66'),(2017111318135735,'112.163.89.66'),(2017112113532763,'112.163.89.66'),(2017112316283880,'211.197.42.37'),(2017112711031451,'211.197.42.37'),(2017112711352488,'112.163.89.66'),(2017112715303344,'112.163.89.66'),(2017112810155661,'112.163.89.66'),(2017112817120227,'112.163.89.66'),(2017112817130246,'112.163.89.66'),(2017112910592551,'112.163.89.66'),(2017112911374546,'112.163.89.66'),(2017112911380565,'112.163.89.66'),(2017112913455784,'112.163.89.66'),(2017112913461938,'112.163.89.66'),(2017112913463371,'112.163.89.66'),(2017112913465529,'112.163.89.66'),(2017112913480751,'112.163.89.66'),(2017112913481774,'112.163.89.66'),(2017112914244736,'112.163.89.66'),(2017112914245167,'112.163.89.66'),(2017112914262925,'112.163.89.66'),(2017112916532859,'112.163.89.66'),(2017112916535391,'112.163.89.66'),(2017113009194070,'211.197.42.37');
/*!40000 ALTER TABLE `cb_unique_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_member_addon`
--

DROP TABLE IF EXISTS `cb_wt_member_addon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_member_addon` (
  `mad_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `mad_free_hp` varchar(15) NOT NULL DEFAULT '' COMMENT '무료수신번호',
  `mad_weight1` decimal(6,2) DEFAULT '0.00' COMMENT '가중치1',
  `mad_weight2` decimal(6,2) DEFAULT '0.00' COMMENT '가중치2',
  `mad_weight3` decimal(6,2) DEFAULT '0.00' COMMENT '가중치3',
  `mad_weight4` decimal(6,2) DEFAULT '0.00' COMMENT '가중치4',
  `mad_weight5` decimal(6,2) DEFAULT '0.00' COMMENT '가중치5',
  `mad_weight6` decimal(6,2) DEFAULT '0.00' COMMENT '가중치6',
  `mad_weight7` decimal(6,2) DEFAULT '0.00' COMMENT '가중치7',
  `mad_weight8` decimal(6,2) DEFAULT '0.00' COMMENT '가중치8',
  `mad_weight9` decimal(6,2) DEFAULT '0.00' COMMENT '가중치9',
  `mad_weight10` decimal(6,2) DEFAULT '0.00' COMMENT '가중치10',
  PRIMARY KEY (`mad_mem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_member_addon`
--

LOCK TABLES `cb_wt_member_addon` WRITE;
/*!40000 ALTER TABLE `cb_wt_member_addon` DISABLE KEYS */;
INSERT INTO `cb_wt_member_addon` VALUES (2,'01093111339',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(3,'',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(4,'',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(5,'0552389456',0.00,0.00,0.00,1.00,2.00,3.00,5.00,0.00,0.00,0.00);
/*!40000 ALTER TABLE `cb_wt_member_addon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_msg_price`
--

DROP TABLE IF EXISTS `cb_wt_msg_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_msg_price` (
  `mpr_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `mpr_kind` varchar(15) NOT NULL DEFAULT '' COMMENT '구분(알림톡,친구톡,이미지,실패시)',
  `mpr_s_qty1` bigint(20) DEFAULT '0' COMMENT '1구간시작건수',
  `mpr_e_qty1` bigint(20) DEFAULT '0' COMMENT '1구간종료건수',
  `mpr_price1` decimal(6,2) DEFAULT '0.00' COMMENT '1구간단가(or SMS)',
  `mpr_s_qty2` bigint(20) DEFAULT '0' COMMENT '2구간시작건수',
  `mpr_e_qty2` bigint(20) DEFAULT '0' COMMENT '2구간종료건수',
  `mpr_price2` decimal(6,2) DEFAULT '0.00' COMMENT '2구간단가(or LMS)',
  `mpr_s_qty3` bigint(20) DEFAULT '0' COMMENT '3구간시작건수',
  `mpr_e_qty3` bigint(20) DEFAULT '0' COMMENT '3구간종료건수',
  `mpr_price3` decimal(6,2) DEFAULT '0.00' COMMENT '3구간단가(or MMS)',
  `mpr_s_qty4` bigint(20) DEFAULT '0' COMMENT '4구간시작건수',
  `mpr_e_qty4` bigint(20) DEFAULT '0' COMMENT '4구간종료건수',
  `mpr_price4` decimal(6,2) DEFAULT '0.00' COMMENT '4구간단가',
  `mpr_s_qty5` bigint(20) DEFAULT '0' COMMENT '5구간시작건수',
  `mpr_e_qty5` bigint(20) DEFAULT '0' COMMENT '5구간종료건수',
  `mpr_price5` decimal(6,2) DEFAULT '0.00' COMMENT '5구간단가',
  PRIMARY KEY (`mpr_mem_id`,`mpr_kind`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_msg_price`
--

LOCK TABLES `cb_wt_msg_price` WRITE;
/*!40000 ALTER TABLE `cb_wt_msg_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_wt_msg_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_msg_sent`
--

DROP TABLE IF EXISTS `cb_wt_msg_sent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_msg_sent` (
  `mst_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별값',
  `mst_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `mst_template` varchar(100) NOT NULL COMMENT '템플릿코드',
  `mst_profile` varchar(50) DEFAULT '' COMMENT '프로필키',
  `mst_datetime` datetime DEFAULT NULL COMMENT '발송일시',
  `mst_kind` varchar(5) DEFAULT '' COMMENT '구분(알림톡,친구톡,이미지,SMS,LMS,MMS)',
  `mst_content` varchar(5000) DEFAULT '' COMMENT '내용(친구톡/알림톡)',
  `mst_sms_send` char(1) DEFAULT 'N' COMMENT 'SMS발송여부',
  `mst_lms_send` char(1) DEFAULT 'N' COMMENT 'LMS발송여부',
  `mst_mms_send` char(1) DEFAULT 'N' COMMENT 'MMS발송여부',
  `mst_sms_content` varchar(500) DEFAULT '' COMMENT 'SMS내용',
  `mst_lms_content` varchar(5000) DEFAULT '' COMMENT '내용(LMS/SMS)',
  `mst_mms_content` varchar(1000) DEFAULT '' COMMENT '내용MMS',
  `mst_img_content` varchar(1000) DEFAULT '' COMMENT '내용(이미지)',
  `mst_button` varchar(5000) DEFAULT '' COMMENT '버튼',
  `mst_sms_callback` varchar(15) DEFAULT '' COMMENT 'SMS발송번호',
  `mst_status` char(1) DEFAULT '' COMMENT '상태',
  `mst_qty` bigint(20) DEFAULT '0' COMMENT '수량',
  `mst_amount` decimal(14,2) DEFAULT '0.00' COMMENT '금액',
  `mst_ft` int(10) unsigned DEFAULT '0' COMMENT '친구톡성공',
  `mst_ft_img` int(10) unsigned DEFAULT '0' COMMENT '친구톡이미지성공',
  `mst_at` int(10) unsigned DEFAULT '0' COMMENT '알림톡성공',
  `mst_sms` int(10) unsigned DEFAULT '0' COMMENT 'SMS성공',
  `mst_lms` int(10) unsigned DEFAULT '0' COMMENT 'LMS성공',
  `mst_mms` int(10) unsigned DEFAULT '0' COMMENT 'MMS성공',
  `mst_phn` int(10) unsigned DEFAULT '0' COMMENT '폰문자성공',
  PRIMARY KEY (`mst_id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_msg_sent`
--

LOCK TABLES `cb_wt_msg_sent` WRITE;
/*!40000 ALTER TABLE `cb_wt_msg_sent` DISABLE KEYS */;
INSERT INTO `cb_wt_msg_sent` VALUES (1,1,' ','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 17:52:09','ft','(광고)\n\nsadsdsadsdsadsa\n\n수신거부 : 홈>수신거부','N','N','N','(광고)\n\nsadsdsadsdsadsa\n\n수신거부 : 홈>수신거부','(광고)\n\nsadsdsadsdsadsa\n\n수신거부 : 홈>수신거부','','','','','',1,9.50,1,0,0,0,0,0,0),(24,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 21:22:15','ft','(광고)\n\n친구톡 발송 테스트 하고 있음\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,9.50,1,0,0,0,0,0,0),(25,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 21:30:04','ft','(광고)\n겨울맞이 특가 세일 (12/01~12/07)\n밀레, 몽벨, 네파, 코오롱스포츠 인기 아웃도어 의류 50% 세일\n평창 롱패딩 한정수량 입고, 선착순 판매 (12/01 10:30 본관 8층 이벤트매장)\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,9.50,1,0,0,0,0,0,0),(26,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-27 21:43:36','ft','(광고)\n\n카카오 친구톡 한글을 바르게 표시할 수 없어요.\n어쩌면 좋아요?\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,9.50,1,0,0,0,0,0,0),(27,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:27:10','ft','(광고)\n\n1234567890\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,9.50,1,0,0,0,0,0,0),(28,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:29:31','ft','(광고)\n\n1234567890\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,9.50,1,0,0,0,0,0,0),(29,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:09','ft','(광고)\n\n1234567890\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,28.50,3,0,0,0,0,0,0),(30,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 10:48:44','ft','(광고)\n\n1234567890\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,28.50,3,0,0,0,0,0,0),(31,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 11:30:53','ft','(광고)\n\n한글 등록 테스트\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,9.50,0,0,1,0,0,0,0),(32,1,'alimtalktest_007','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:34:09','at','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\n예약자 : #{고객명}\n예약번호 : #{예약번호}\n상품명 : #{상품명}\n여행기간 : #{여행기간}\n상세내역 : #{url}\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"\\uc0c1\\ub2f4\\uc6d0\\uc5d0\\uac8c \\ubb38\\uc758\\ud558\\uae30\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(33,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:35:46','at','#{고객명} 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : #{12341234}\n발송인 : #{발송처} #{대리점명} #{배송기사명} #{배송기사 연락처}\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(34,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:40:08','at','#{고객명} 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : #{12341234}\n발송인 : #{발송처} #{대리점명} #{배송기사명} #{배송기사 연락처}\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(35,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:41:47','at','#{고객명} 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : #{12341234}\n발송인 : #{발송처} #{대리점명} #{배송기사명} #{배송기사 연락처}\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(36,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:43:58','at','#{고객명} 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : #{12341234}\n발송인 : #{발송처} #{대리점명} #{배송기사명} #{배송기사 연락처}\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(37,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:45:37','at','홍길동 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처 대리점명 배송기사명 배송기사 연락처\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(38,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:46:53','at','고객명 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처 대리점명 배송기사명 배송기사 연락처\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(39,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:49:06','at','고객명 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처\n\n대리점명 배송기사명\n배송기사 연락처\n\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(40,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:50:11','at','고객명 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처\n대리점명 배송기사명\n배송기사 연락처\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시\n겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(41,1,'alimtalktest_006','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:53:22','at','#{고객명} 고객님 카카오택배입니다.\n오늘 택배를 배송할 예정입니다.\n운송장번호 : 12341234\n발송인 : 발송처\n\n대리점명 배송기사명\n배송기사 연락처\n\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시겠어요? ^^','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uc9d1\\uc73c\\ub85c \\uc9c1\\uc811 \\ubc1b\\uace0\\uc2f6\\uc5b4\\uc694!\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\uacbd\\ube44\\uc2e4\\uc5d0 \\ubd80\\ud0c1\\ub4dc\\ub9b4\\uaed8\\uc694~\\\"}]\",\"[{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"\\ub098\\ub9cc\\uc758 \\uc2dc\\ud06c\\ub9bf \\uc7a5\\uc18c?! (\\uc0c1\\uc138\\ud788 \\uc124\\uba85\\ud574 \\uc8fc\\uc138\\uc694)\\\"}]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(42,1,'alimtalktest_004','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-28 16:55:49','at','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 12341234\n□ 배송지 : 구/면  동/리\n□ 배송예정일 : 00월 00일\n□ 결제금액 :: 결제금액원','N','L','N','','1','',NULL,'[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uc8fc\\ubb38\\ub0b4\\uc5ed \\uc0c1\\uc138\\ubcf4\\uae30\\\",\\\"url_mobile\\\":\\\"http:\\/\\/www.kakao.com\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,1,0,0,0,0,0,0),(43,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:41:17','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,2,0,0,0,0,0,0),(44,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:44:25','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,2,0,0,0,0,0,0),(45,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:45:35','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,1,0,0,0,0,0,0),(46,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:47:37','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,1,0,0,0,0,0,0),(47,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:49:50','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,1,0,0,0,0,0,0),(48,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:52:38','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,1,0,0,0,0,0,0),(49,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:53:24','ft','(광고)\n\n오늘 하루도 수고많았어요~~\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,1,0,0,0,0,0,0),(50,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 20:54:29','ft','(광고)\n\n\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"\\uce5c\\uad6c\\ud1a1test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',3,45.00,2,0,0,0,0,0,0),(51,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 21:05:39','ft','(광고)\n\n\n\n수신거부 : 홈>친구차단','N','L','N','','','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"친구톡test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,15.00,1,0,0,0,0,0,0),(52,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 21:12:41','ft','(광고)\n\n\n\n수신거부 : 홈>친구차단','N','L','N','','','','/uploads/user_images/2017/11/b2878669535576b114b12d3bfbcf8ba3.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"친구톡test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,15.00,1,0,0,0,0,0,0),(53,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:36:31','ft',NULL,'N',NULL,'N','',NULL,'',NULL,'[null,null,null,null,null]',NULL,'1',0,0.00,0,0,0,0,0,0,0),(54,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:37:44','ft',NULL,'N',NULL,'N','',NULL,'',NULL,'[null,null,null,null,null]',NULL,'1',0,0.00,0,0,0,0,0,0,0),(55,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:43:53','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(56,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:46:36','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(57,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:48:32','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(58,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:49:08','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(59,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:49:57','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(60,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:50:24','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(61,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:52:44','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(62,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:53:18','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(63,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:54:05','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(64,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:55:02','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(65,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:55:19','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(66,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:55:34','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(67,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:56:53','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(68,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:57:45','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(69,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:59:00','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(70,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 22:59:57','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(71,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 23:00:14','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,1,0,0,0,0,0,0),(72,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 23:01:06','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]',NULL,'1',2,30.00,2,0,0,0,0,0,0),(73,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 23:06:56','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]','0552389456','1',2,30.00,1,0,0,0,0,0,0),(74,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-28 23:09:07','ft','template content','N',NULL,'N','','lms content','','image url','[\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":\\\"PC링크(선택)\\\"}\",\"{\\\"type\\\":\\\"AL\\\",\\\"name\\\":\\\"앱링크 버튼이름\\\",\\\"scheme_android\\\":\\\"android 스킴\\\",\\\"scheme_ios\\\":\\\"ios 스킴\\\"}\",\"{\\\"type\\\":\\\"BK\\\",\\\"name\\\":\\\"봇키워드 버튼이름\\\"}\",\"{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"메시지전달 버튼이름\\\"}\",\"{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"웹링크 버튼이름2\\\",\\\"url_mobile\\\":\\\"모바일링크(http(s):\\\\\\/\\\\\\/ 포함)\\\",\\\"url_pc\\\":null}\"]','0552389456','1',2,30.00,2,0,0,0,0,0,0),(75,1,'alimtalktest_007','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-29 16:24:00','at','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\n예약자 : #{고객명}\n예약번호 : #{예약번호}\n상품명 : #{상품명}\n여행기간 : #{여행기간}\n상세내역 : #{url}\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.','N','L','N','','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\n예약자 : #{고객명}\n예약번호 : #{예약번호}\n상품명 : #{상품명}\n여행기간 : #{여행기간}\n상세내역 : #{url}\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.\nhttps://goo.gl/d5QwzA','',NULL,'[\"[{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"\\uc0c1\\ub2f4\\uc6d0\\uc5d0\\uac8c \\ubb38\\uc758\\ud558\\uae30\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(76,1,'','761283e30af0bba98fb0c802a60f6b06619210ff','2017-11-29 16:30:01','ft','(광고)\n친구톡 광고 확인\n\n\n수신거부 : 홈>친구차단','N','L','N','','','','/uploads/user_images/2017/11/b2878669535576b114b12d3bfbcf8ba3.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"(주)웹싱크\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@webthink\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(77,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-29 16:33:58','ft','(광고)\n\nssssssssssss\n\n수신거부 : 홈>친구차단','N','L','N','','','','/uploads/user_images/2017/11/b2878669535576b114b12d3bfbcf8ba3.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"친구톡확인\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,15.00,0,0,0,0,0,0,0),(78,1,'alimtalktest_007','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-29 18:13:48','at','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\n예약자 : #{고객명}\n예약번호 : #{예약번호}\n상품명 : #{상품명}\n여행기간 : #{여행기간}\n상세내역 : #{url}\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.','N','L','N','','','',NULL,'[\"[{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"\\uc0c1\\ub2f4\\uc6d0\\uc5d0\\uac8c \\ubb38\\uc758\\ud558\\uae30\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(79,1,'alimtalktest_007','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-29 18:35:57','at','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\n예약자 : #{고객명}\n예약번호 : #{예약번호}\n상품명 : #{상품명}\n여행기간 : #{여행기간}\n상세내역 : #{url}\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.','N','L','N','','','',NULL,'[\"[{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"\\uc0c1\\ub2f4\\uc6d0\\uc5d0\\uac8c \\ubb38\\uc758\\ud558\\uae30\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(80,1,'alimtalktest_007','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-29 18:36:11','at','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\n예약자 : #{고객명}\n예약번호 : #{예약번호}\n상품명 : #{상품명}\n여행기간 : #{여행기간}\n상세내역 : #{url}\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.','N','L','N','','','',NULL,'[\"[{\\\"type\\\":\\\"MD\\\",\\\"name\\\":\\\"\\uc0c1\\ub2f4\\uc6d0\\uc5d0\\uac8c \\ubb38\\uc758\\ud558\\uae30\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(81,2,'','761283e30af0bba98fb0c802a60f6b06619210ff','2017-11-30 09:24:36','ft','(광고)\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n수신거부 : 홈>친구차단','N','L','N','','(광고) (주)웹싱크\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n무료수신거부 : ','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"(주)웹싱크\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@webthink\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(82,2,'','761283e30af0bba98fb0c802a60f6b06619210ff','2017-11-30 09:25:03','ft','(광고)\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n수신거부 : 홈>친구차단','N','L','N','','(광고) (주)웹싱크\n\n정부가 1000만 원 이하 빚을 10년 넘게 갚지 못하고 있는 장기 소액 연체자 159만여 명의 빚을 탕감해 주기로 했다. 탕감 규모는 최대 6조2000억 원에 달할 것으로 전망된다. 정부가 채무 일부를 깎아주거나 만기를 연장해준 적은 있지만 빚을 아예 없던 걸로 해 주는 것은 이번이 처음이다. \n\n무료수신거부 : ','','','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"(주)웹싱크\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@webthink\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(83,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-30 10:13:48','ft','(광고)\n\n금일 오전 특가 행사 진행합니다.\n\n\n수신거부 : 홈>친구차단','N','L','N','','','','http://dhn.webthink.co.kr/pop/2017/11/b2878669535576b114b12d3bfbcf8ba3.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"친구톡test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,15.00,0,0,0,0,0,0,0),(84,1,'','0b796447e8f8613d3ade096a5c23120b069124a9','2017-11-30 13:07:19','ft','(광고)\n\n이미지 등록 테스트\n\n수신거부 : 홈>친구차단','N','L','N','','','','http://img.bizmsg.kr/bd7ff995ee.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"친구톡test\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@clsrnxhrxptmxm\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','0552389456','1',1,15.00,0,0,0,0,0,0,0),(85,1,'','2f637c7c03d0a88084ab866fd7407a7e6f46e8fd','2017-11-30 13:34:13','ft','(광고)\n\n이미지 테스트 합니다.\n\n수신거부 : 홈>친구차단','N','L','N','','','','http://img.bizmsg.kr/bd7ff995ee.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"에이치엠피\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@에이치엠피\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(86,1,'','2f637c7c03d0a88084ab866fd7407a7e6f46e8fd','2017-11-30 13:36:58','ft','(광고)\n\n이미지 테스트 합니다.\n\n수신거부 : 홈>친구차단','N','L','N','','','','http://img.bizmsg.kr/bd7ff995ee.jpg','[\"[{\\\"type\\\":\\\"WL\\\",\\\"name\\\":\\\"에이치엠피\\\",\\\"url_mobile\\\":\\\"http:\\/\\/plus-talk.kakao.com\\/plus\\/home\\/@에이치엠피\\\",\\\"url_pc\\\":\\\"\\\"}]\",\"[]\",\"[]\",\"[]\",\"[]\"]','01065748654','1',1,15.00,0,0,0,0,0,0,0),(87,1,'alimtalktest_004','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-30 15:59:43','at',NULL,'N',NULL,'N','',NULL,'',NULL,'[null,null,null,null,null]',NULL,'1',0,0.00,0,0,0,0,0,0,0),(88,1,'alimtalktest_004','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-30 16:25:43','at',NULL,'N',NULL,'N','',NULL,'',NULL,'[null,null,null,null,null]',NULL,'1',0,0.00,0,0,0,0,0,0,0),(89,1,'alimtalktest_004','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-30 17:06:55','at',NULL,'N',NULL,'N','',NULL,'',NULL,'[null,null,null,null,null]',NULL,'1',0,0.00,0,0,0,0,0,0,0),(90,1,'alimtalktest_004','89823b83f2182b1e229c2e95e21cf5e6301eed98','2017-11-30 17:08:08','at','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','N',NULL,'N','','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','','','[\"{\\\"linkType\\\":\\\"WL\\\",\\\"name\\\":\\\"주문내역 상세보기\\\",\\\"linkMo\\\":\\\"http:\\\\\\/\\\\\\/www.bizalimtalk.kr\\\"}\",\"\",\"\",\"\",\"\"]','01065748654','1',2,30.00,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `cb_wt_msg_sent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_refund`
--

DROP TABLE IF EXISTS `cb_wt_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_refund` (
  `ref_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `ref_datetime` datetime DEFAULT NULL COMMENT '신청일시',
  `ref_amount` decimal(14,2) DEFAULT '0.00' COMMENT '신청금액',
  `ref_appr_amount` decimal(14,2) DEFAULT '0.00' COMMENT '확정금액',
  `ref_bank_name` varchar(100) DEFAULT '' COMMENT '은행명',
  `ref_account` varchar(50) DEFAULT '' COMMENT '계좌번호',
  `ref_acc_master` varchar(100) DEFAULT '' COMMENT '예금주',
  `ref_sheet` varchar(100) DEFAULT '' COMMENT '통장사본',
  `ref_memo` varchar(1000) DEFAULT '' COMMENT '환불사유',
  `ref_tel` varchar(15) DEFAULT '' COMMENT '연락처',
  `ref_stat` char(1) DEFAULT '' COMMENT '처리상태',
  `ref_appr_id` varchar(100) DEFAULT '' COMMENT '승인자ID',
  `ref_appr_datetime` datetime DEFAULT NULL COMMENT '승인일시',
  `ref_end_datetime` datetime DEFAULT NULL COMMENT '완료일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_refund`
--

LOCK TABLES `cb_wt_refund` WRITE;
/*!40000 ALTER TABLE `cb_wt_refund` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_wt_refund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_send_cache`
--

DROP TABLE IF EXISTS `cb_wt_send_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_send_cache` (
  `sc_mem_id` bigint(20) unsigned NOT NULL COMMENT '회원식별번호',
  `sc_datetime` datetime DEFAULT NULL COMMENT '등록일시',
  `sc_name` varchar(100) DEFAULT '' COMMENT '고객명',
  `sc_tel` varchar(15) DEFAULT '' COMMENT '전화번호',
  `sc_content` varchar(5000) DEFAULT '' COMMENT '내용(친구톡/알림톡)',
  `sc_button1` varchar(600) DEFAULT '' COMMENT '버튼1',
  `sc_button2` varchar(600) DEFAULT '' COMMENT '버튼2',
  `sc_button3` varchar(600) DEFAULT '' COMMENT '버튼3',
  `sc_button4` varchar(600) DEFAULT '' COMMENT '버튼4',
  `sc_button5` varchar(600) DEFAULT '' COMMENT '버튼5',
  `sc_sms_yn` char(1) DEFAULT 'N' COMMENT 'SMS재발신여부',
  `sc_lms_content` varchar(5000) DEFAULT '' COMMENT '내용LMS',
  `sc_sms_callback` varchar(15) DEFAULT '' COMMENT 'SMS발신번호',
  `sc_img_url` varchar(1000) DEFAULT '' COMMENT '이미지URL',
  `sc_img_link` varchar(1000) DEFAULT '' COMMENT '이미지Link',
  `sc_template` varchar(30) DEFAULT '' COMMENT '템플릿코드',
  `sc_p_com` varchar(5) DEFAULT '' COMMENT '택배사코드',
  `sc_p_invoice` varchar(100) DEFAULT '' COMMENT '택배송장번호',
  `sc_s_code` varchar(5) DEFAULT '' COMMENT '쇼핑몰코드',
  `sc_reserve_dt` varchar(14) DEFAULT '' COMMENT '예약전송일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_send_cache`
--

LOCK TABLES `cb_wt_send_cache` WRITE;
/*!40000 ALTER TABLE `cb_wt_send_cache` DISABLE KEYS */;
INSERT INTO `cb_wt_send_cache` VALUES (1,'2017-11-30 17:08:02','','01027877110','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http:\\/\\/www.bizalimtalk.kr\"}','','','','','N','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290102\n□ 배송지 : 창원시 의창구\n□ 배송예정일 : 12월 01일\n□ 결제금액 :: 176,000원','01065748654','','','alimtalktest_004','','','',''),(1,'2017-11-30 17:08:02','','01093111339','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290101\n□ 배송지 : 창원시 진해구\n□ 배송예정일 : 11월 30일\n□ 결제금액 :: 13,600원','{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http:\\/\\/www.bizalimtalk.kr\"}','','','','','N','[카카오프렌즈샵] 주문완료 안내\n□ 주문번호: 1711290101\n□ 배송지 : 창원시 진해구\n□ 배송예정일 : 11월 30일\n□ 결제금액 :: 13,600원','01065748654','','','alimtalktest_004','','','','');
/*!40000 ALTER TABLE `cb_wt_send_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_send_profile`
--

DROP TABLE IF EXISTS `cb_wt_send_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_send_profile` (
  `spf_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `spf_friend` varchar(100) NOT NULL DEFAULT '' COMMENT '플러스친구아이디',
  `spf_company` varchar(100) DEFAULT '' COMMENT '업체명',
  `spf_manager_hp` varchar(15) DEFAULT '' COMMENT '관리자휴대폰번호',
  `spf_key` varchar(100) DEFAULT '' COMMENT '발신프로필키',
  `spf_key_type` varchar(20) DEFAULT 'None' COMMENT '발신프로필키종류',
  `spf_sms_callback` varchar(20) DEFAULT '' COMMENT 'SMS발신번호',
  `spf_biz_sheet` varchar(100) DEFAULT '' COMMENT '사업자등록증',
  `spf_datetime` datetime DEFAULT NULL COMMENT '등록일시',
  `spf_appr_id` varchar(100) DEFAULT '' COMMENT '승인자ID',
  `spf_appr_datetime` datetime DEFAULT NULL COMMENT '승인일시',
  `spf_use` char(1) DEFAULT 'Y' COMMENT '사용여부',
  PRIMARY KEY (`spf_mem_id`,`spf_friend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_send_profile`
--

LOCK TABLES `cb_wt_send_profile` WRITE;
/*!40000 ALTER TABLE `cb_wt_send_profile` DISABLE KEYS */;
INSERT INTO `cb_wt_send_profile` VALUES (1,'@dkfflaxhrxptmxm','알림톡test','01065748654','89823b83f2182b1e229c2e95e21cf5e6301eed98','None','01065748654','2017/11/4ef460caa10a4fae1ce782e818a7710e.jpg','2017-11-16 16:41:19','admin','2017-11-16 16:41:19','Y'),(1,'@clsrnxhrxptmxm','친구톡test','2222222','0b796447e8f8613d3ade096a5c23120b069124a9','None','0552389456','2017/11/edf1158d45ac2648993f359dedf865d9.jpg','2017-11-16 16:41:39','admin','2017-11-16 16:41:39','Y'),(5,'@webthink','(주)웹싱크','01065748654','761283e30af0bba98fb0c802a60f6b06619210ff','S','01065748654','2017/11/edf1158d45ac2648993f359dedf865d9.jpg','2017-11-29 15:44:50','admin','2017-11-29 15:44:50','Y'),(1,'@에이치엠피','에이치엠피','01065748654','2f637c7c03d0a88084ab866fd7407a7e6f46e8fd','S','01065748654','2017/11/edf1158d45ac2648993f359dedf865d9.jpg','2017-11-29 15:44:50','admin','2017-11-29 15:44:50','Y');
/*!40000 ALTER TABLE `cb_wt_send_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_send_profile_group`
--

DROP TABLE IF EXISTS `cb_wt_send_profile_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_send_profile_group` (
  `spg_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `spg_name` varchar(100) NOT NULL DEFAULT '' COMMENT '그룹명',
  `spg_datetime` datetime DEFAULT NULL COMMENT '등록일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_send_profile_group`
--

LOCK TABLES `cb_wt_send_profile_group` WRITE;
/*!40000 ALTER TABLE `cb_wt_send_profile_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_wt_send_profile_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_setting`
--

DROP TABLE IF EXISTS `cb_wt_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_setting` (
  `wst_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `wst_price_ft` decimal(6,2) DEFAULT '0.00' COMMENT '친구톡가격',
  `wst_price_ft_img` decimal(6,2) DEFAULT '0.00' COMMENT '친구톡이미지가격',
  `wst_price_at` decimal(6,2) DEFAULT '0.00' COMMENT '알림톡가격',
  `wst_price_sms` decimal(6,2) DEFAULT '0.00' COMMENT 'SMS가격',
  `wst_price_lms` decimal(6,2) DEFAULT '0.00' COMMENT 'LMS가격',
  `wst_price_mms` decimal(6,2) DEFAULT '0.00' COMMENT 'MMS가격',
  `wst_price_phn` decimal(6,2) DEFAULT '0.00' COMMENT '폰문자가격',
  `wst_weight1` decimal(6,2) DEFAULT '0.00' COMMENT '가중치1',
  `wst_weight2` decimal(6,2) DEFAULT '0.00' COMMENT '가중치2',
  `wst_weight3` decimal(6,2) DEFAULT '0.00' COMMENT '가중치3',
  `wst_weight4` decimal(6,2) DEFAULT '0.00' COMMENT '가중치4',
  `wst_weight5` decimal(6,2) DEFAULT '0.00' COMMENT '가중치5',
  `wst_weight6` decimal(6,2) DEFAULT '0.00' COMMENT '가중치6',
  `wst_weight7` decimal(6,2) DEFAULT '0.00' COMMENT '가중치7',
  `wst_weight8` decimal(6,2) DEFAULT '0.00' COMMENT '가중치8',
  `wst_weight9` decimal(6,2) DEFAULT '0.00' COMMENT '가중치9',
  `wst_weight10` decimal(6,2) DEFAULT '0.00' COMMENT '가중치10',
  PRIMARY KEY (`wst_mem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_setting`
--

LOCK TABLES `cb_wt_setting` WRITE;
/*!40000 ALTER TABLE `cb_wt_setting` DISABLE KEYS */;
INSERT INTO `cb_wt_setting` VALUES (1,15.00,30.00,15.00,17.00,29.00,90.00,25.20,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00);
/*!40000 ALTER TABLE `cb_wt_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_sms_cert`
--

DROP TABLE IF EXISTS `cb_wt_sms_cert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_sms_cert` (
  `sct_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `sct_hp` varchar(15) NOT NULL DEFAULT '' COMMENT '전화번호',
  `sct_cert_no` varchar(10) DEFAULT '' COMMENT '인증번호',
  `sct_result` char(1) DEFAULT 'N' COMMENT '인증결과',
  `sct_datetime` datetime DEFAULT NULL COMMENT '발송일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_sms_cert`
--

LOCK TABLES `cb_wt_sms_cert` WRITE;
/*!40000 ALTER TABLE `cb_wt_sms_cert` DISABLE KEYS */;
/*!40000 ALTER TABLE `cb_wt_sms_cert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cb_wt_template`
--

DROP TABLE IF EXISTS `cb_wt_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cb_wt_template` (
  `tpl_mem_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '회원ID',
  `tpl_id` bigint(20) NOT NULL COMMENT '식별번호',
  `tpl_company` varchar(100) DEFAULT '' COMMENT '업체명',
  `tpl_profile_key` varchar(100) DEFAULT '' COMMENT '프로필키',
  `tpl_code` varchar(100) DEFAULT '' COMMENT '템플릿코드',
  `tpl_name` varchar(100) DEFAULT '' COMMENT '템플릿명',
  `tpl_inspect_status` char(5) DEFAULT 'REG' COMMENT '검수상태',
  `tpl_status` char(5) DEFAULT 'R' COMMENT '템플릿상태(발송여부)',
  `tpl_comment_status` char(5) DEFAULT 'INQ' COMMENT '문의상태(문의,답변)',
  `tpl_use` char(1) DEFAULT 'Y' COMMENT '사용여부',
  `tpl_contents` varchar(5000) DEFAULT '' COMMENT '템플릿내용',
  `tpl_button` varchar(5000) DEFAULT '' COMMENT '버튼1',
  `tpl_datetime` datetime DEFAULT NULL COMMENT '등록일시',
  `tpl_check_datetime` datetime DEFAULT NULL COMMENT '검수신청일시',
  `tpl_appr_id` varchar(100) DEFAULT '' COMMENT '승인자ID',
  `tpl_appr_datetime` datetime DEFAULT NULL COMMENT '승인일시',
  PRIMARY KEY (`tpl_mem_id`,`tpl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cb_wt_template`
--

LOCK TABLES `cb_wt_template` WRITE;
/*!40000 ALTER TABLE `cb_wt_template` DISABLE KEYS */;
INSERT INTO `cb_wt_template` VALUES (1,1,'관리자','89823b83f2182b1e229c2e95e21cf5e6301eed98','alimtalktest_003','택배배송안내','APR','R','INQ','Y','#{고객명} 고객님! #{택배회사명}입니다.\r\n#{오늘 11시부터 13시 사이에} 택배를 배달할 예정입니다.\r\n등기번호(운송장번호) : #{1234123412345}\r\n','[{\"linkType\":\"DS\",\"name\":\"배송조회하기\"}]','2017-11-20 11:28:05','2017-11-17 17:07:00','admin','2017-11-17 17:07:00'),(1,2,'관리자','89823b83f2182b1e229c2e95e21cf5e6301eed98','alimtalktest_004','주문완료안내','APR','R','INQ','Y','[카카오프렌즈샵] 주문완료 안내\r\n□ 주문번호: #{12341234}\r\n□ 배송지 : #{구/면 } #{동/리}\r\n□ 배송예정일 : #{00}월 #{00}일\r\n□ 결제금액 :: #{결제금액}원','[{\"linkType\":\"WL\",\"name\":\"주문내역 상세보기\",\"linkMo\":\"http://www.kakao.com\"}]','2017-11-20 11:28:05','2017-11-24 21:34:49','admin','2017-11-17 17:07:00'),(1,3,'관리자','89823b83f2182b1e229c2e95e21cf5e6301eed98','alimtalktest_005','문의등록안내','APR','R','INQ','Y','[카카오고객센터] 1:1 상담 안내\r\n#{고객명} 고객님! 1:1문의 게시판을 통해 문의 주신 #{문의카테고리}에 대해 아래와 같이 안내 드립니다.\r\n□ 주문번호: #{12341234}\r\n고객님께서 문의하여 주신 #{문의카테고리}는 전화상담을 통해 #{처리내용} 안내해 드렸습니다.\r\n다른 문의사항이 있으시면 언제든지 고객서비스센터로 문의 주시면 최선을 다해 도와드리겠습니다.','[{\"linkType\":\"AL\",\"name\":\"문의내역 확인하기\",\"linkAnd\":\"daumapps://open\",\"linkIos\":\"daumapps://open\"}]','2017-11-20 11:28:05','2017-11-24 21:34:49','admin','2017-11-17 17:07:00'),(1,4,'관리자','89823b83f2182b1e229c2e95e21cf5e6301eed98','alimtalktest_006','카카오택배배송안내','APR','R','INQ','Y','#{고객명} 고객님 카카오택배입니다.\r\n오늘 택배를 배송할 예정입니다.\r\n운송장번호 : #{12341234}\r\n발송인 : #{발송처}\r\n\r\n#{대리점명} #{배송기사명}\r\n#{배송기사 연락처}\r\n\r\n상품을 수령하실 방법을 저희에게 살~짝! 알려주시\r\n겠어요? ^^','[{\"linkType\":\"BK\",\"name\":\"집으로 직접 받고싶어요!\"},{\"linkType\":\"BK\",\"name\":\"경비실에 부탁드릴께요~\"},{\"linkType\":\"BK\",\"name\":\"나만의 시크릿 장소?! (상세히 설명해 주세요)\"}]','2017-11-20 11:28:05','2017-11-24 21:34:49','admin','2017-11-17 17:07:00'),(1,5,'관리자','89823b83f2182b1e229c2e95e21cf5e6301eed98','alimtalktest_007','카카오투어','APR','R','INQ','Y','[카카오투어] 정상적으로 예약신청이 완료되었습니다.\r\n예약자 : #{고객명}\r\n예약번호 : #{예약번호}\r\n상품명 : #{상품명}\r\n여행기간 : #{여행기간}\r\n상세내역 : #{url}\r\n예약 내용에 대한 문의사항은 아래 버튼을 눌러 상담원에게 문의해주세요.','[{\"linkType\":\"MD\",\"name\":\"상담원에게 문의하기\"}]','2017-11-20 11:28:05','2017-11-24 21:34:49','admin','2017-11-17 17:07:00'),(1,6,'관리자','89823b83f2182b1e229c2e95e21cf5e6301eed98','alimtalktest_008','카카오쇼핑','APR','R','INQ','Y','[카카오쇼핑] 방송 알림 안내\r\n#{고객명} 고객님! 신청하신 상품의 방송 일정 안내드립니다.\r\n#{프로그램명} #{방송예정일시} #{상품명}\r\n※ 방송 예정 일시는 상품이 편성된 프로그램 시간 기준으로, 여러 상품을 방송하는 프로그램의 경우 실제방송과 시간 차이가 발생할 수 있습니다.','[{\"linkType\":\"WL\",\"name\":\"미리 주문하기\",\"linkMo\":\"http://www.kakao.com\"},{\"linkType\":\"MD\",\"name\":\"상담원 연결하기\"},{\"linkType\":\"AL\",\"name\":\"방송 알림 설정 보기\",\"linkAnd\":\"daumapps://open\",\"linkIos\":\"daumapps://open\"}]','2017-11-20 11:28:05','2017-11-24 21:34:49','admin','2017-11-17 17:07:00'),(5,7,'(주)웹싱크','761283e30af0bba98fb0c802a60f6b06619210ff','12345','3333','REG','R','INQ','N','3333','[]','2017-11-29 17:55:49',NULL,'',NULL),(5,8,'(주)웹싱크','761283e30af0bba98fb0c802a60f6b06619210ff','2222','4444','REG','R','INQ','N','4444','[]','2017-11-29 17:56:18',NULL,'',NULL),(5,9,'(주)웹싱크','761283e30af0bba98fb0c802a60f6b06619210ff','WT171129_001','배송알림','','R','INQ','Y','[녹차의달인] 배송알림\n#{고객}님 #{주문일자}일에 주문하신 #{상품}이 KGB택배로 배송되었습니다.\n송장번호 : #{송장번호}\n감사합니다.','[{\"ordering\":1,\"linkType\":\"DS\",\"name\":\"배송조회\"},{\"ordering\":2,\"linkType\":\"WL\",\"name\":\"주문내역확인\",\"linkMo\":\"http://dalinmall.kr\",\"linkPc\":\"\"}]','2017-11-29 18:02:39','2017-11-29 22:26:34','',NULL);
/*!40000 ALTER TABLE `cb_wt_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_auth`
--

DROP TABLE IF EXISTS `g5_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_auth` (
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `au_menu` varchar(20) NOT NULL DEFAULT '',
  `au_auth` set('r','w','d') NOT NULL DEFAULT '',
  PRIMARY KEY (`mb_id`,`au_menu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_auth`
--

LOCK TABLES `g5_auth` WRITE;
/*!40000 ALTER TABLE `g5_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_autosave`
--

DROP TABLE IF EXISTS `g5_autosave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_autosave` (
  `as_id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) NOT NULL,
  `as_uid` bigint(20) unsigned NOT NULL,
  `as_subject` varchar(255) NOT NULL,
  `as_content` text NOT NULL,
  `as_datetime` datetime NOT NULL,
  PRIMARY KEY (`as_id`),
  UNIQUE KEY `as_uid` (`as_uid`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_autosave`
--

LOCK TABLES `g5_autosave` WRITE;
/*!40000 ALTER TABLE `g5_autosave` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_autosave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_board`
--

DROP TABLE IF EXISTS `g5_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_board` (
  `bo_table` varchar(20) NOT NULL DEFAULT '',
  `gr_id` varchar(255) NOT NULL DEFAULT '',
  `bo_subject` varchar(255) NOT NULL DEFAULT '',
  `bo_mobile_subject` varchar(255) NOT NULL DEFAULT '',
  `bo_device` enum('both','pc','mobile') NOT NULL DEFAULT 'both',
  `bo_admin` varchar(255) NOT NULL DEFAULT '',
  `bo_list_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_read_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_write_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_reply_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_comment_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_upload_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_download_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_html_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_link_level` tinyint(4) NOT NULL DEFAULT '0',
  `bo_count_delete` tinyint(4) NOT NULL DEFAULT '0',
  `bo_count_modify` tinyint(4) NOT NULL DEFAULT '0',
  `bo_read_point` int(11) NOT NULL DEFAULT '0',
  `bo_write_point` int(11) NOT NULL DEFAULT '0',
  `bo_comment_point` int(11) NOT NULL DEFAULT '0',
  `bo_download_point` int(11) NOT NULL DEFAULT '0',
  `bo_use_category` tinyint(4) NOT NULL DEFAULT '0',
  `bo_category_list` text NOT NULL,
  `bo_use_sideview` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_file_content` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_secret` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_dhtml_editor` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_rss_view` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_good` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_nogood` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_name` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_signature` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_ip_view` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_list_view` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_list_file` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_list_content` tinyint(4) NOT NULL DEFAULT '0',
  `bo_table_width` int(11) NOT NULL DEFAULT '0',
  `bo_subject_len` int(11) NOT NULL DEFAULT '0',
  `bo_mobile_subject_len` int(11) NOT NULL DEFAULT '0',
  `bo_page_rows` int(11) NOT NULL DEFAULT '0',
  `bo_mobile_page_rows` int(11) NOT NULL DEFAULT '0',
  `bo_new` int(11) NOT NULL DEFAULT '0',
  `bo_hot` int(11) NOT NULL DEFAULT '0',
  `bo_image_width` int(11) NOT NULL DEFAULT '0',
  `bo_skin` varchar(255) NOT NULL DEFAULT '',
  `bo_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `bo_include_head` varchar(255) NOT NULL DEFAULT '',
  `bo_include_tail` varchar(255) NOT NULL DEFAULT '',
  `bo_content_head` text NOT NULL,
  `bo_mobile_content_head` text NOT NULL,
  `bo_content_tail` text NOT NULL,
  `bo_mobile_content_tail` text NOT NULL,
  `bo_insert_content` text NOT NULL,
  `bo_gallery_cols` int(11) NOT NULL DEFAULT '0',
  `bo_gallery_width` int(11) NOT NULL DEFAULT '0',
  `bo_gallery_height` int(11) NOT NULL DEFAULT '0',
  `bo_mobile_gallery_width` int(11) NOT NULL DEFAULT '0',
  `bo_mobile_gallery_height` int(11) NOT NULL DEFAULT '0',
  `bo_upload_size` int(11) NOT NULL DEFAULT '0',
  `bo_reply_order` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_search` tinyint(4) NOT NULL DEFAULT '0',
  `bo_order` int(11) NOT NULL DEFAULT '0',
  `bo_count_write` int(11) NOT NULL DEFAULT '0',
  `bo_count_comment` int(11) NOT NULL DEFAULT '0',
  `bo_write_min` int(11) NOT NULL DEFAULT '0',
  `bo_write_max` int(11) NOT NULL DEFAULT '0',
  `bo_comment_min` int(11) NOT NULL DEFAULT '0',
  `bo_comment_max` int(11) NOT NULL DEFAULT '0',
  `bo_notice` text NOT NULL,
  `bo_upload_count` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_email` tinyint(4) NOT NULL DEFAULT '0',
  `bo_use_cert` enum('','cert','adult','hp-cert','hp-adult') NOT NULL DEFAULT '',
  `bo_use_sns` tinyint(4) NOT NULL DEFAULT '0',
  `bo_sort_field` varchar(255) NOT NULL DEFAULT '',
  `bo_1_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_2_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_3_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_4_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_5_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_6_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_7_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_8_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_9_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_10_subj` varchar(255) NOT NULL DEFAULT '',
  `bo_1` varchar(255) NOT NULL DEFAULT '',
  `bo_2` varchar(255) NOT NULL DEFAULT '',
  `bo_3` varchar(255) NOT NULL DEFAULT '',
  `bo_4` varchar(255) NOT NULL DEFAULT '',
  `bo_5` varchar(255) NOT NULL DEFAULT '',
  `bo_6` varchar(255) NOT NULL DEFAULT '',
  `bo_7` varchar(255) NOT NULL DEFAULT '',
  `bo_8` varchar(255) NOT NULL DEFAULT '',
  `bo_9` varchar(255) NOT NULL DEFAULT '',
  `bo_10` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`bo_table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_board`
--

LOCK TABLES `g5_board` WRITE;
/*!40000 ALTER TABLE `g5_board` DISABLE KEYS */;
INSERT INTO `g5_board` VALUES ('qa','shop','질문답변','','both','',1,1,1,1,1,1,1,1,1,1,1,-1,5,1,-20,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,100,60,30,15,15,24,100,600,'basic','basic','_head.php','_tail.php','','','','','',4,174,124,125,100,1048576,1,0,0,0,0,0,0,0,0,'',2,0,'',0,'','','','','','','','','','','','','','','','','','','','',''),('free','shop','자유게시판','','both','',1,1,1,1,1,1,1,1,1,1,1,-1,5,1,-20,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,100,60,30,15,15,24,100,600,'basic','basic','_head.php','_tail.php','','','','','',4,174,124,125,100,1048576,1,0,0,0,0,0,0,0,0,'',2,0,'',0,'','','','','','','','','','','','','','','','','','','','',''),('notice','shop','공지사항','','both','',1,1,1,1,1,1,1,1,1,1,1,-1,5,1,-20,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,100,60,30,15,15,24,100,600,'basic','basic','_head.php','_tail.php','','','','','',4,174,124,125,100,1048576,1,0,0,0,0,0,0,0,0,'',2,0,'',0,'','','','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_board_file`
--

DROP TABLE IF EXISTS `g5_board_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_board_file` (
  `bo_table` varchar(20) NOT NULL DEFAULT '',
  `wr_id` int(11) NOT NULL DEFAULT '0',
  `bf_no` int(11) NOT NULL DEFAULT '0',
  `bf_source` varchar(255) NOT NULL DEFAULT '',
  `bf_file` varchar(255) NOT NULL DEFAULT '',
  `bf_download` int(11) NOT NULL,
  `bf_content` text NOT NULL,
  `bf_filesize` int(11) NOT NULL DEFAULT '0',
  `bf_width` int(11) NOT NULL DEFAULT '0',
  `bf_height` smallint(6) NOT NULL DEFAULT '0',
  `bf_type` tinyint(4) NOT NULL DEFAULT '0',
  `bf_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bo_table`,`wr_id`,`bf_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_board_file`
--

LOCK TABLES `g5_board_file` WRITE;
/*!40000 ALTER TABLE `g5_board_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_board_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_board_good`
--

DROP TABLE IF EXISTS `g5_board_good`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_board_good` (
  `bg_id` int(11) NOT NULL AUTO_INCREMENT,
  `bo_table` varchar(20) NOT NULL DEFAULT '',
  `wr_id` int(11) NOT NULL DEFAULT '0',
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `bg_flag` varchar(255) NOT NULL DEFAULT '',
  `bg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bg_id`),
  UNIQUE KEY `fkey1` (`bo_table`,`wr_id`,`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_board_good`
--

LOCK TABLES `g5_board_good` WRITE;
/*!40000 ALTER TABLE `g5_board_good` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_board_good` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_board_new`
--

DROP TABLE IF EXISTS `g5_board_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_board_new` (
  `bn_id` int(11) NOT NULL AUTO_INCREMENT,
  `bo_table` varchar(20) NOT NULL DEFAULT '',
  `wr_id` int(11) NOT NULL DEFAULT '0',
  `wr_parent` int(11) NOT NULL DEFAULT '0',
  `bn_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`bn_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_board_new`
--

LOCK TABLES `g5_board_new` WRITE;
/*!40000 ALTER TABLE `g5_board_new` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_board_new` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_cert_history`
--

DROP TABLE IF EXISTS `g5_cert_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_cert_history` (
  `cr_id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `cr_company` varchar(255) NOT NULL DEFAULT '',
  `cr_method` varchar(255) NOT NULL DEFAULT '',
  `cr_ip` varchar(255) NOT NULL DEFAULT '',
  `cr_date` date NOT NULL DEFAULT '0000-00-00',
  `cr_time` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`cr_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_cert_history`
--

LOCK TABLES `g5_cert_history` WRITE;
/*!40000 ALTER TABLE `g5_cert_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_cert_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_config`
--

DROP TABLE IF EXISTS `g5_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_config` (
  `cf_title` varchar(255) NOT NULL DEFAULT '',
  `cf_theme` varchar(255) NOT NULL DEFAULT '',
  `cf_admin` varchar(255) NOT NULL DEFAULT '',
  `cf_admin_email` varchar(255) NOT NULL DEFAULT '',
  `cf_admin_email_name` varchar(255) NOT NULL DEFAULT '',
  `cf_add_script` text NOT NULL,
  `cf_use_point` tinyint(4) NOT NULL DEFAULT '0',
  `cf_point_term` int(11) NOT NULL DEFAULT '0',
  `cf_use_copy_log` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_email_certify` tinyint(4) NOT NULL DEFAULT '0',
  `cf_login_point` int(11) NOT NULL DEFAULT '0',
  `cf_cut_name` tinyint(4) NOT NULL DEFAULT '0',
  `cf_nick_modify` int(11) NOT NULL DEFAULT '0',
  `cf_new_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_new_rows` int(11) NOT NULL DEFAULT '0',
  `cf_search_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_connect_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_faq_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_read_point` int(11) NOT NULL DEFAULT '0',
  `cf_write_point` int(11) NOT NULL DEFAULT '0',
  `cf_comment_point` int(11) NOT NULL DEFAULT '0',
  `cf_download_point` int(11) NOT NULL DEFAULT '0',
  `cf_write_pages` int(11) NOT NULL DEFAULT '0',
  `cf_mobile_pages` int(11) NOT NULL DEFAULT '0',
  `cf_link_target` varchar(255) NOT NULL DEFAULT '',
  `cf_delay_sec` int(11) NOT NULL DEFAULT '0',
  `cf_filter` text NOT NULL,
  `cf_possible_ip` text NOT NULL,
  `cf_intercept_ip` text NOT NULL,
  `cf_analytics` text NOT NULL,
  `cf_add_meta` text NOT NULL,
  `cf_syndi_token` varchar(255) NOT NULL,
  `cf_syndi_except` text NOT NULL,
  `cf_member_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_use_homepage` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_homepage` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_tel` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_tel` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_hp` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_hp` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_addr` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_addr` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_signature` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_signature` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_profile` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_profile` tinyint(4) NOT NULL DEFAULT '0',
  `cf_register_level` tinyint(4) NOT NULL DEFAULT '0',
  `cf_register_point` int(11) NOT NULL DEFAULT '0',
  `cf_icon_level` tinyint(4) NOT NULL DEFAULT '0',
  `cf_use_recommend` tinyint(4) NOT NULL DEFAULT '0',
  `cf_recommend_point` int(11) NOT NULL DEFAULT '0',
  `cf_leave_day` int(11) NOT NULL DEFAULT '0',
  `cf_search_part` int(11) NOT NULL DEFAULT '0',
  `cf_email_use` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_wr_super_admin` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_wr_group_admin` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_wr_board_admin` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_wr_write` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_wr_comment_all` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_mb_super_admin` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_mb_member` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_po_super_admin` tinyint(4) NOT NULL DEFAULT '0',
  `cf_prohibit_id` text NOT NULL,
  `cf_prohibit_email` text NOT NULL,
  `cf_new_del` int(11) NOT NULL DEFAULT '0',
  `cf_memo_del` int(11) NOT NULL DEFAULT '0',
  `cf_visit_del` int(11) NOT NULL DEFAULT '0',
  `cf_popular_del` int(11) NOT NULL DEFAULT '0',
  `cf_optimize_date` date NOT NULL DEFAULT '0000-00-00',
  `cf_use_member_icon` tinyint(4) NOT NULL DEFAULT '0',
  `cf_member_icon_size` int(11) NOT NULL DEFAULT '0',
  `cf_member_icon_width` int(11) NOT NULL DEFAULT '0',
  `cf_member_icon_height` int(11) NOT NULL DEFAULT '0',
  `cf_login_minutes` int(11) NOT NULL DEFAULT '0',
  `cf_image_extension` varchar(255) NOT NULL DEFAULT '',
  `cf_flash_extension` varchar(255) NOT NULL DEFAULT '',
  `cf_movie_extension` varchar(255) NOT NULL DEFAULT '',
  `cf_formmail_is_member` tinyint(4) NOT NULL DEFAULT '0',
  `cf_page_rows` int(11) NOT NULL DEFAULT '0',
  `cf_mobile_page_rows` int(11) NOT NULL DEFAULT '0',
  `cf_visit` varchar(255) NOT NULL DEFAULT '',
  `cf_max_po_id` int(11) NOT NULL DEFAULT '0',
  `cf_stipulation` text NOT NULL,
  `cf_privacy` text NOT NULL,
  `cf_open_modify` int(11) NOT NULL DEFAULT '0',
  `cf_memo_send_point` int(11) NOT NULL DEFAULT '0',
  `cf_mobile_new_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_mobile_search_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_mobile_connect_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_mobile_faq_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_mobile_member_skin` varchar(255) NOT NULL DEFAULT '',
  `cf_captcha_mp3` varchar(255) NOT NULL DEFAULT '',
  `cf_editor` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_use` tinyint(4) NOT NULL DEFAULT '0',
  `cf_cert_ipin` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_hp` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_kcb_cd` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_kcp_cd` varchar(255) NOT NULL DEFAULT '',
  `cf_lg_mid` varchar(255) NOT NULL DEFAULT '',
  `cf_lg_mert_key` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_limit` int(11) NOT NULL DEFAULT '0',
  `cf_cert_req` tinyint(4) NOT NULL DEFAULT '0',
  `cf_sms_use` varchar(255) NOT NULL DEFAULT '',
  `cf_sms_type` varchar(10) NOT NULL DEFAULT '',
  `cf_icode_id` varchar(255) NOT NULL DEFAULT '',
  `cf_icode_pw` varchar(255) NOT NULL DEFAULT '',
  `cf_icode_server_ip` varchar(255) NOT NULL DEFAULT '',
  `cf_icode_server_port` varchar(255) NOT NULL DEFAULT '',
  `cf_googl_shorturl_apikey` varchar(255) NOT NULL DEFAULT '',
  `cf_facebook_appid` varchar(255) NOT NULL,
  `cf_facebook_secret` varchar(255) NOT NULL,
  `cf_twitter_key` varchar(255) NOT NULL,
  `cf_twitter_secret` varchar(255) NOT NULL,
  `cf_kakao_js_apikey` varchar(255) NOT NULL,
  `cf_1_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_2_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_3_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_4_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_5_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_6_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_7_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_8_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_9_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_10_subj` varchar(255) NOT NULL DEFAULT '',
  `cf_1` varchar(255) NOT NULL DEFAULT '',
  `cf_2` varchar(255) NOT NULL DEFAULT '',
  `cf_3` varchar(255) NOT NULL DEFAULT '',
  `cf_4` varchar(255) NOT NULL DEFAULT '',
  `cf_5` varchar(255) NOT NULL DEFAULT '',
  `cf_6` varchar(255) NOT NULL DEFAULT '',
  `cf_7` varchar(255) NOT NULL DEFAULT '',
  `cf_8` varchar(255) NOT NULL DEFAULT '',
  `cf_9` varchar(255) NOT NULL DEFAULT '',
  `cf_10` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_config`
--

LOCK TABLES `g5_config` WRITE;
/*!40000 ALTER TABLE `g5_config` DISABLE KEYS */;
INSERT INTO `g5_config` VALUES ('대형네트웍스','basic','admin','pm@webthink.co.kr','대형네트웍스','',0,0,1,0,100,15,60,'theme/basic',15,'theme/basic','theme/basic','theme/basic',0,0,0,0,10,5,'_blank',30,'18아,18놈,18새끼,18뇬,18노,18것,18넘,개년,개놈,개뇬,개새,개색끼,개세끼,개세이,개쉐이,개쉑,개쉽,개시키,개자식,개좆,게색기,게색끼,광뇬,뇬,눈깔,뉘미럴,니귀미,니기미,니미,도촬,되질래,뒈져라,뒈진다,디져라,디진다,디질래,병쉰,병신,뻐큐,뻑큐,뽁큐,삐리넷,새꺄,쉬발,쉬밸,쉬팔,쉽알,스패킹,스팽,시벌,시부랄,시부럴,시부리,시불,시브랄,시팍,시팔,시펄,실밸,십8,십쌔,십창,싶알,쌉년,썅놈,쌔끼,쌩쑈,썅,써벌,썩을년,쎄꺄,쎄엑,쓰바,쓰발,쓰벌,쓰팔,씨8,씨댕,씨바,씨발,씨뱅,씨봉알,씨부랄,씨부럴,씨부렁,씨부리,씨불,씨브랄,씨빠,씨빨,씨뽀랄,씨팍,씨팔,씨펄,씹,아가리,아갈이,엄창,접년,잡놈,재랄,저주글,조까,조빠,조쟁이,조지냐,조진다,조질래,존나,존니,좀물,좁년,좃,좆,좇,쥐랄,쥐롤,쥬디,지랄,지럴,지롤,지미랄,쫍빱,凸,퍽큐,뻑큐,빠큐,ㅅㅂㄹㅁ','','','','','','','theme/basic',0,0,1,0,1,0,1,0,0,0,0,0,2,1000,2,0,0,30,10000,1,0,0,0,0,0,0,0,0,'admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객','',30,180,180,180,'2017-11-28',2,5000,22,22,10,'gif|jpg|jpeg|png','swf','asx|asf|wmv|wma|mpg|mpeg|mov|avi|mp3',1,15,15,'오늘:2,어제:2,최대:3,전체:12',0,'이용약관\r\n\r\n제1조(목적)\r\n\r\n이 약관은 대형네트웍스 (이하 ”회사”이라 한다)가 운영하는 대형네트웍스 사이버 몰(이하 “사이트”이라 한다)에서 제공하는 인터넷 관련 서비스(이하 “서비스”라 한다)를 이용함에 있어 사이버 몰과 이용자의 권리·의무 및 책임사항을 규정함을 목적으로 합니다.\r\n\r\n※「인터넷, 정보통신망, 모바일 및 스마트 장치 등을 이용하는 전자상거래에 대하여도 그 성질에 반하지 않는 한 이 약관을 준용합니다.」\r\n\r\n제2조(정의)\r\n\r\n① “사이트”이란 회사이 재화 또는 용역(이하 “재화 등”이라 함)을 이용자에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 재화 등을 거래할 수 있도록 설정한 가상의 영업장을 말하며, 아울러 사이버몰을 운영하는 사업자의 의미로도 사용합니다.\r\n\r\n② “이용자”란 “사이트”에 접속하여 이 약관에 따라 “사이트”이 제공하는 서비스를 받는 회원 및 비회원을 말합니다.\r\n\r\n③ ‘회원’이라 함은 “사이트”에 회원등록을 한 자로서, 계속적으로 “사이트”이 제공하는 서비스를 이용할 수 있는 자를 말합니다.\r\n\r\n④ ‘비회원’이라 함은 회원에 가입하지 않고 “사이트”이 제공하는 서비스를 이용하는 자를 말합니다.\r\n\r\n제3조 (약관 등의 명시와 설명 및 개정)\r\n\r\n① “사이트”는 이 약관의 내용과 상호 및 대표자 성명, 영업소 소재지 주소(소비자의 불만을 처리할 수 있는 곳의 주소를 포함), 전화번호·모사전송번호·전자우편주소, 사업자등록번호, 통신판매업 신고번호, 개인정보관리책임자 등을 이용자가 쉽게 알 수 있도록 대형네트웍스 사이버몰의 초기 서비스화면(전면)에 게시합니다. 다만, 약관의 내용은 이용자가 연결화면을 통하여 볼 수 있도록 할 수 있습니다.\r\n\r\n② “몰은 이용자가 약관에 동의하기에 앞서 약관에 정하여져 있는 내용 중 청약철회·배송책임·환불조건 등과 같은 중요한 내용을 이용자가 이해할 수 있도록 별도의 연결화면 또는 팝업화면 등을 제공하여 이용자의 확인을 구하여야 합니다.\r\n\r\n③ “사이트”는 「전자상거래 등에서의 소비자보호에 관한 법률」, 「약관의 규제에 관한 법률」, 「전자문서 및 전자거래기본법」, 「전자금융거래법」, 「전자서명법」, 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」, 「방문판매 등에 관한 법률」, 「소비자기본법」 등 관련 법을 위배하지 않는 범위에서 이 약관을 개정할 수 있습니다.\r\n\r\n④ “사이트”이 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행약관과 함께 몰의 초기화면에 그 적용일자 7일 이전부터 적용일자 전일까지 공지합니다. 다만, 이용자에게 불리하게 약관내용을 변경하는 경우에는 최소한 30일 이상의 사전 유예기간을 두고 공지합니다.  이 경우 \"몰“은 개정 전 내용과 개정 후 내용을 명확하게 비교하여 이용자가 알기 쉽도록 표시합니다.\r\n\r\n⑤ “사이트”이 약관을 개정할 경우에는 그 개정약관은 그 적용일자 이후에 체결되는 계약에만 적용되고 그 이전에 이미 체결된 계약에 대해서는 개정 전의 약관조항이 그대로 적용됩니다. 다만 이미 계약을 체결한 이용자가 개정약관 조항의 적용을 받기를 원하는 뜻을 제3항에 의한 개정약관의 공지기간 내에 “사이트”에 송신하여 “사이트”의 동의를 받은 경우에는 개정약관 조항이 적용됩니다.\r\n\r\n⑥ 이 약관에서 정하지 아니한 사항과 이 약관의 해석에 관하여는 전자상거래 등에서의 소비자보호에 관한 법률, 약관의 규제 등에 관한 법률, 공정거래위원회가 정하는 「전자상거래 등에서의 소비자 보호지침」 및 관계법령 또는 상관례에 따릅니다.\r\n\r\n제4조(서비스의 제공 및 변경)\r\n\r\n① “사이트”는 다음과 같은 업무를 수행합니다.\r\n\r\n1. 재화 또는 용역에 대한 정보 제공 및 구매계약의 체결\r\n\r\n2. 구매계약이 체결된 재화 또는 용역의 배송\r\n\r\n3. 기타 “사이트”이 정하는 업무\r\n\r\n② “사이트”는 재화 또는 용역의 품절 또는 기술적 사양의 변경 등의 경우에는 장차 체결되는 계약에 의해 제공할 재화 또는 용역의 내용을 변경할 수 있습니다. 이 경우에는 변경된 재화 또는 용역의 내용 및 제공일자를 명시하여 현재의 재화 또는 용역의 내용을 게시한 곳에 즉시 공지합니다.\r\n\r\n③ “사이트”이 제공하기로 이용자와 계약을 체결한 서비스의 내용을 재화 등의 품절 또는 기술적 사양의 변경 등의 사유로 변경할 경우에는 그 사유를 이용자에게 통지 가능한 주소로 즉시 통지합니다.\r\n\r\n④ 전항의 경우 “사이트”는 이로 인하여 이용자가 입은 손해를 배상합니다. 다만, “사이트”이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.\r\n\r\n제5조(서비스의 중단)\r\n\r\n① “사이트”는 컴퓨터 등 정보통신설비의 보수점검·교체 및 고장, 통신의 두절 등의 사유가 발생한 경우에는 서비스의 제공을 일시적으로 중단할 수 있습니다.\r\n\r\n② “사이트”는 제1항의 사유로 서비스의 제공이 일시적으로 중단됨으로 인하여 이용자 또는 제3자가 입은 손해에 대하여 배상합니다. 단, “사이트”이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.\r\n\r\n③ 사업종목의 전환, 사업의 포기, 업체 간의 통합 등의 이유로 서비스를 제공할 수 없게 되는 경우에는 “사이트”는 제8조에 정한 방법으로 이용자에게 통지하고 당초 “사이트”에서 제시한 조건에 따라 소비자에게 보상합니다. 다만, “사이트”이 보상기준 등을 고지하지 아니한 경우에는 이용자들의 마일리지 또는 적립금 등을 “사이트”에서 통용되는 통화가치에 상응하는 현물 또는 현금으로 이용자에게 지급합니다.\r\n\r\n제6조(회원가입)\r\n\r\n① 이용자는 “사이트”이 정한 가입 양식에 따라 회원정보를 기입한 후 이 약관에 동의한다는 의사표시를 함으로서 회원가입을 신청합니다.\r\n\r\n② “사이트”는 제1항과 같이 회원으로 가입할 것을 신청한 이용자 중 다음 각 호에 해당하지 않는 한 회원으로 등록합니다.\r\n\r\n1. 가입신청자가 이 약관 제7조제3항에 의하여 이전에 회원자격을 상실한 적이 있는 경우, 다만 제7조제3항에 의한 회원자격 상실 후 3년이 경과한 자로서 “사이트”의 회원재가입 승낙을 얻은 경우에는 예외로 한다.\r\n\r\n2. 등록 내용에 허위, 기재누락, 오기가 있는 경우\r\n\r\n3. 기타 회원으로 등록하는 것이 “사이트”의 기술상 현저히 지장이 있다고 판단되는 경우\r\n\r\n③ 회원가입계약의 성립 시기는 “사이트”의 승낙이 회원에게 도달한 시점으로 합니다.\r\n\r\n④ 회원은 회원가입 시 등록한 사항에 변경이 있는 경우, 상당한 기간 이내에 “사이트”에 대하여 회원정보 수정 등의 방법으로 그 변경사항을 알려야 합니다.\r\n\r\n제7조(회원 탈퇴 및 자격 상실 등)\r\n\r\n① 회원은 “사이트”에 언제든지 탈퇴를 요청할 수 있으며 “사이트”는 즉시 회원탈퇴를 처리합니다.\r\n\r\n② 회원이 다음 각 호의 사유에 해당하는 경우, “사이트”는 회원자격을 제한 및 정지시킬 수 있습니다.\r\n\r\n1. 가입 신청 시에 허위 내용을 등록한 경우\r\n\r\n2. “사이트”을 이용하여 구입한 재화 등의 대금, 기타 “사이트”이용에 관련하여 회원이 부담하는 채무를 기일에 지급하지 않는 경우\r\n\r\n3. 다른 사람의 “사이트” 이용을 방해하거나 그 정보를 도용하는 등 전자상거래 질서를 위협하는 경우\r\n\r\n4. “사이트”을 이용하여 법령 또는 이 약관이 금지하거나 공서양속에 반하는 행위를 하는 경우\r\n\r\n③ “사이트”이 회원 자격을 제한·정지 시킨 후, 동일한 행위가 2회 이상 반복되거나 30일 이내에 그 사유가 시정되지 아니하는 경우 “사이트”는 회원자격을 상실시킬 수 있습니다.\r\n\r\n④ “사이트”이 회원자격을 상실시키는 경우에는 회원등록을 말소합니다. 이 경우 회원에게 이를 통지하고, 회원등록 말소 전에 최소한 30일 이상의 기간을 정하여 소명할 기회를 부여합니다.\r\n\r\n제8조(회원에 대한 통지)\r\n\r\n① “사이트”이 회원에 대한 통지를 하는 경우, 회원이 “사이트”과 미리 약정하여 지정한 전자우편 주소로 할 수 있습니다.\r\n\r\n② “사이트”는 불특정다수 회원에 대한 통지의 경우 1주일이상 “사이트” 게시판에 게시함으로서 개별 통지에 갈음할 수 있습니다. 다만, 회원 본인의 거래와 관련하여 중대한 영향을 미치는 사항에 대하여는 개별통지를 합니다.\r\n\r\n제9조(구매신청)\r\n\r\n① “사이트”이용자는 “사이트”상에서 다음 또는 이와 유사한 방법에 의하여 구매를 신청하며, “사이트”는 이용자가 구매신청을 함에 있어서 다음의 각 내용을 알기 쉽게 제공하여야 합니다.\r\n\r\n1. 재화 등의 검색 및 선택\r\n\r\n2. 받는 사람의 성명, 주소, 전화번호, 전자우편주소(또는 이동전화번호) 등의 입력\r\n\r\n3. 약관내용, 청약철회권이 제한되는 서비스, 배송료·설치비 등의 비용부담과 관련한 내용에 대한 확인\r\n\r\n4. 이 약관에 동의하고 위 3.호의 사항을 확인하거나 거부하는 표시\r\n\r\n(예, 마우스 클릭)\r\n\r\n5. 재화 등의 구매신청 및 이에 관한 확인 또는 “사이트”의 확인에 대한 동의\r\n\r\n6. 결제방법의 선택\r\n\r\n② “사이트”이 제3자에게 구매자 개인정보를 제공·위탁할 필요가 있는 경우 실제 구매신청 시 구매자의 동의를 받아야 하며, 회원가입 시 미리 포괄적으로 동의를 받지 않습니다. 이 때 “사이트”는 제공되는 개인정보 항목, 제공받는 자, 제공받는 자의 개인정보 이용 목적 및 보유?이용 기간 등을 구매자에게 명시하여야 합니다. 다만 「정보통신망이용촉진 및 정보보호 등에 관한 법률」 제25조 제1항에 의한 개인정보 취급위탁의 경우 등 관련 법령에 달리 정함이 있는 경우에는 그에 따릅니다.\r\n\r\n제10조 (계약의 성립)\r\n\r\n①  “사이트”는 제9조와 같은 구매신청에 대하여 다음 각 호에 해당하면 승낙하지 않을 수 있습니다. 다만, 미성년자와 계약을 체결하는 경우에는 법정대리인의 동의를 얻지 못하면 미성년자 본인 또는 법정대리인이 계약을 취소할 수 있다는 내용을 고지하여야 합니다.\r\n\r\n1. 신청 내용에 허위, 기재누락, 오기가 있는 경우\r\n\r\n2. 미성년자가 담배, 주류 등 청소년보호법에서 금지하는 재화 및 용역을 구매하는 경우\r\n\r\n3. 기타 구매신청에 승낙하는 것이 “사이트” 기술상 현저히 지장이 있다고 판단하는 경우\r\n\r\n② “사이트”의 승낙이 제12조제1항의 수신확인통지형태로 이용자에게 도달한 시점에 계약이 성립한 것으로 봅니다.\r\n\r\n③ “사이트”의 승낙의 의사표시에는 이용자의 구매 신청에 대한 확인 및 판매가능 여부, 구매신청의 정정 취소 등에 관한 정보 등을 포함하여야 합니다.\r\n\r\n제11조(지급방법)\r\n\r\n“사이트”에서 구매한 재화 또는 용역에 대한 대금지급방법은 다음 각 호의 방법중 가용한 방법으로 할 수 있습니다. 단, “사이트”는 이용자의 지급방법에 대하여 재화 등의 대금에 어떠한 명목의 수수료도  추가하여 징수할 수 없습니다.\r\n\r\n1. 폰뱅킹, 인터넷뱅킹, 메일 뱅킹 등의 각종 계좌이체\r\n\r\n2. 선불카드, 직불카드, 신용카드 등의 각종 카드 결제\r\n\r\n3. 온라인무통장입금\r\n\r\n4. 전자화폐에 의한 결제\r\n\r\n5. 수령 시 대금지급\r\n\r\n6. 마일리지 등 “사이트”이 지급한 포인트에 의한 결제\r\n\r\n7. “사이트”과 계약을 맺었거나 “사이트”이 인정한 상품권에 의한 결제\r\n\r\n8. 기타 전자적 지급 방법에 의한 대금 지급 등\r\n\r\n제12조(수신확인통지·구매신청 변경 및 취소)\r\n\r\n① “사이트”는 이용자의 구매신청이 있는 경우 이용자에게 수신확인통지를 합니다.\r\n\r\n② 수신확인통지를 받은 이용자는 의사표시의 불일치 등이 있는 경우에는 수신확인통지를 받은 후 즉시 구매신청 변경 및 취소를 요청할 수 있고 “사이트”는 배송 전에 이용자의 요청이 있는 경우에는 지체 없이 그 요청에 따라 처리하여야 합니다. 다만 이미 대금을 지불한 경우에는 제15조의 청약철회 등에 관한 규정에 따릅니다.\r\n\r\n제13조(재화 등의 공급)\r\n\r\n① “사이트”는 이용자와 재화 등의 공급시기에 관하여 별도의 약정이 없는 이상, 이용자가 청약을 한 날부터 7일 이내에 재화 등을 배송할 수 있도록 주문제작, 포장 등 기타의 필요한 조치를 취합니다. 다만, “사이트”이 이미 재화 등의 대금의 전부 또는 일부를 받은 경우에는 대금의 전부 또는 일부를 받은 날부터 3영업일 이내에 조치를 취합니다.  이때 “사이트”는 이용자가 재화 등의 공급 절차 및 진행 사항을 확인할 수 있도록 적절한 조치를 합니다.\r\n\r\n② “사이트”는 이용자가 구매한 재화에 대해 배송수단, 수단별 배송비용 부담자, 수단별 배송기간 등을 명시합니다. 만약 “사이트”이 약정 배송기간을 초과한 경우에는 그로 인한 이용자의 손해를 배상하여야 합니다. 다만 “사이트”이 고의·과실이 없음을 입증한 경우에는 그러하지 아니합니다.\r\n\r\n제14조(환급)\r\n\r\n“사이트”는 이용자가 구매신청한 재화 등이 품절 등의 사유로 인도 또는 제공을 할 수 없을 때에는 지체 없이 그 사유를 이용자에게 통지하고 사전에 재화 등의 대금을 받은 경우에는 대금을 받은 날부터 3영업일 이내에 환급하거나 환급에 필요한 조치를 취합니다.\r\n\r\n제15조(청약철회 등)\r\n\r\n① “사이트”과 재화등의 구매에 관한 계약을 체결한 이용자는 「전자상거래 등에서의 소비자보호에 관한 법률」 제13조 제2항에 따른 계약내용에 관한 서면을 받은 날(그 서면을 받은 때보다 재화 등의 공급이 늦게 이루어진 경우에는 재화 등을 공급받거나 재화 등의 공급이 시작된 날을 말합니다)부터 7일 이내에는 청약의 철회를 할 수 있습니다. 다만, 청약철회에 관하여 「전자상거래 등에서의 소비자보호에 관한 법률」에 달리 정함이 있는 경우에는 동 법 규정에 따릅니다.\r\n\r\n② 이용자는 재화 등을 배송 받은 경우 다음 각 호의 1에 해당하는 경우에는 반품 및 교환을 할 수 없습니다.\r\n\r\n1. 이용자에게 책임 있는 사유로 재화 등이 멸실 또는 훼손된 경우(다만, 재화 등의 내용을 확인하기 위하여 포장 등을 훼손한 경우에는 청약철회를 할 수 있습니다)\r\n\r\n2. 이용자의 사용 또는 일부 소비에 의하여 재화 등의 가치가 현저히 감소한 경우\r\n\r\n3. 시간의 경과에 의하여 재판매가 곤란할 정도로 재화등의 가치가 현저히 감소한 경우\r\n\r\n4. 같은 성능을 지닌 재화 등으로 복제가 가능한 경우 그 원본인 재화 등의 포장을 훼손한 경우\r\n\r\n③ 제2항제2호 내지 제4호의 경우에 “사이트”이 사전에 청약철회 등이 제한되는 사실을 소비자가 쉽게 알 수 있는 곳에 명기하거나 시용상품을 제공하는 등의 조치를 하지 않았다면 이용자의 청약철회 등이 제한되지 않습니다.\r\n\r\n④ 이용자는 제1항 및 제2항의 규정에 불구하고 재화 등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행된 때에는 당해 재화 등을 공급받은 날부터 3월 이내, 그 사실을 안 날 또는 알 수 있었던 날부터 30일 이내에 청약철회 등을 할 수 있습니다.\r\n\r\n제16조(청약철회 등의 효과)\r\n\r\n① “사이트”는 이용자로부터 재화 등을 반환받은 경우 3영업일 이내에 이미 지급받은 재화 등의 대금을 환급합니다. 이 경우 “사이트”이 이용자에게 재화등의 환급을 지연한때에는 그 지연기간에 대하여 「전자상거래 등에서의 소비자보호에 관한 법률 시행령」제21조의2에서 정하는 지연이자율을 곱하여 산정한 지연이자를 지급합니다.\r\n\r\n② “사이트”는 위 대금을 환급함에 있어서 이용자가 신용카드 또는 전자화폐 등의 결제수단으로 재화 등의 대금을 지급한 때에는 지체 없이 당해 결제수단을 제공한 사업자로 하여금 재화 등의 대금의 청구를 정지 또는 취소하도록 요청합니다.\r\n\r\n③ 청약철회 등의 경우 공급받은 재화 등의 반환에 필요한 비용은 이용자가 부담합니다. “사이트”는 이용자에게 청약철회 등을 이유로 위약금 또는 손해배상을 청구하지 않습니다. 다만 재화 등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행되어 청약철회 등을 하는 경우 재화 등의 반환에 필요한 비용은 “사이트”이 부담합니다.\r\n\r\n④ 이용자가 재화 등을 제공받을 때 발송비를 부담한 경우에 “사이트”는 청약철회 시 그 비용을  누가 부담하는지를 이용자가 알기 쉽도록 명확하게 표시합니다.\r\n\r\n제17조(개인정보보호)\r\n\r\n① “사이트”는 이용자의 개인정보 수집시 서비스제공을 위하여 필요한 범위에서 최소한의 개인정보를 수집합니다.\r\n\r\n② “사이트”는 회원가입시 구매계약이행에 필요한 정보를 미리 수집하지 않습니다. 다만, 관련 법령상 의무이행을 위하여 구매계약 이전에 본인확인이 필요한 경우로서 최소한의 특정 개인정보를 수집하는 경우에는 그러하지 아니합니다.\r\n\r\n③ “사이트”는 이용자의 개인정보를 수집·이용하는 때에는 당해 이용자에게 그 목적을 고지하고 동의를 받습니다.\r\n\r\n④ “사이트”는 수집된 개인정보를 목적외의 용도로 이용할 수 없으며, 새로운 이용목적이 발생한 경우 또는 제3자에게 제공하는 경우에는 이용·제공단계에서 당해 이용자에게 그 목적을 고지하고 동의를 받습니다. 다만, 관련 법령에 달리 정함이 있는 경우에는 예외로 합니다.\r\n\r\n⑤ “사이트”이 제3항과 제4항에 의해 이용자의 동의를 받아야 하는 경우에는 개인정보관리 책임자의 신원(소속, 성명 및 전화번호, 기타 연락처), 정보의 수집목적 및 이용목적, 제3자에 대한 정보제공 관련사항(제공받은자, 제공목적 및 제공할 정보의 내용) 등 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」 제22조제2항이 규정한 사항을 미리 명시하거나 고지해야 하며 이용자는 언제든지 이 동의를 철회할 수 있습니다.\r\n\r\n⑥ 이용자는 언제든지 “사이트”이 가지고 있는 자신의 개인정보에 대해 열람 및 오류정정을 요구할 수 있으며 “사이트”는 이에 대해 지체 없이 필요한 조치를 취할 의무를 집니다. 이용자가 오류의 정정을 요구한 경우에는 “사이트”는 그 오류를 정정할 때까지 당해 개인정보를 이용하지 않습니다.\r\n\r\n⑦ “사이트”는 개인정보 보호를 위하여 이용자의 개인정보를 취급하는 자를  최소한으로 제한하여야 하며 신용카드, 은행계좌 등을 포함한 이용자의 개인정보의 분실, 도난, 유출, 동의 없는 제3자 제공, 변조 등으로 인한 이용자의 손해에 대하여 모든 책임을 집니다.\r\n\r\n⑧ “사이트” 또는 그로부터 개인정보를 제공받은 제3자는 개인정보의 수집목적 또는 제공받은 목적을 달성한 때에는 당해 개인정보를 지체 없이 파기합니다.\r\n\r\n⑨ “사이트”는 개인정보의 수집·이용·제공에 관한 동의란을 미리 선택한 것으로 설정해두지 않습니다. 또한 개인정보의 수집·이용·제공에 관한 이용자의 동의거절시 제한되는 서비스를 구체적으로 명시하고, 필수수집항목이 아닌 개인정보의 수집·이용·제공에 관한 이용자의 동의 거절을 이유로 회원가입 등 서비스 제공을 제한하거나 거절하지 않습니다.\r\n\r\n제18조(“사이트“의 의무)\r\n\r\n① “사이트”는 법령과 이 약관이 금지하거나 공서양속에 반하는 행위를 하지 않으며 이 약관이 정하는 바에 따라 지속적이고, 안정적으로 재화·용역을 제공하는데 최선을 다하여야 합니다.\r\n\r\n② “사이트”는 이용자가 안전하게 인터넷 서비스를 이용할 수 있도록 이용자의 개인정보(신용정보 포함)보호를 위한 보안 시스템을 갖추어야 합니다.\r\n\r\n③ “사이트”이 상품이나 용역에 대하여 「표시·광고의 공정화에 관한 법률」 제3조 소정의 부당한 표시·광고행위를 함으로써 이용자가 손해를 입은 때에는 이를 배상할 책임을 집니다.\r\n\r\n④ “사이트”는 이용자가 원하지 않는 영리목적의 광고성 전자우편을 발송하지 않습니다.\r\n\r\n제19조(회원의 ID 및 비밀번호에 대한 의무)\r\n\r\n① 제17조의 경우를 제외한 ID와 비밀번호에 관한 관리책임은 회원에게 있습니다.\r\n\r\n② 회원은 자신의 ID 및 비밀번호를 제3자에게 이용하게 해서는 안됩니다.\r\n\r\n③ 회원이 자신의 ID 및 비밀번호를 도난당하거나 제3자가 사용하고 있음을 인지한 경우에는 바로 “사이트”에 통보하고 “사이트”의 안내가 있는 경우에는 그에 따라야 합니다.\r\n\r\n제20조(이용자의 의무)\r\n\r\n이용자는 다음 행위를 하여서는 안 됩니다.\r\n\r\n1. 신청 또는 변경시 허위 내용의 등록\r\n\r\n2. 타인의 정보 도용\r\n\r\n3. “사이트”에 게시된 정보의 변경\r\n\r\n4. “사이트”이 정한 정보 이외의 정보(컴퓨터 프로그램 등) 등의 송신 또는 게시\r\n\r\n5. “사이트” 기타 제3자의 저작권 등 지적재산권에 대한 침해\r\n\r\n6. “사이트” 기타 제3자의 명예를 손상시키거나 업무를 방해하는 행위\r\n\r\n7. 외설 또는 폭력적인 메시지, 화상, 음성, 기타 공서양속에 반하는 정보를 사이트에 공개 또는 게시하는 행위\r\n\r\n제21조(연결“사이트”과 피연결“사이트” 간의 관계)\r\n\r\n① 상위 “사이트”과 하위 “사이트”이 하이퍼링크(예: 하이퍼링크의 대상에는 문자, 그림 및 동화상 등이 포함됨)방식 등으로 연결된 경우, 전자를 연결 “사이트”(웹 사이트)이라고 하고 후자를 피연결 “사이트”(웹사이트)이라고 합니다.\r\n\r\n② 연결“사이트”는 피연결“사이트”이 독자적으로 제공하는 재화 등에 의하여 이용자와 행하는 거래에 대해서 보증 책임을 지지 않는다는 뜻을 연결“사이트”의 초기화면 또는 연결되는 시점의 팝업화면으로 명시한 경우에는 그 거래에 대한 보증 책임을 지지 않습니다.\r\n\r\n제22조(저작권의 귀속 및 이용제한)\r\n\r\n① “사이트“이 작성한 저작물에 대한 저작권 기타 지적재산권은 ”사이트“에 귀속합니다.\r\n\r\n② 이용자는 “사이트”을 이용함으로써 얻은 정보 중 “사이트”에게 지적재산권이 귀속된 정보를 “사이트”의 사전 승낙 없이 복제, 송신, 출판, 배포, 방송 기타 방법에 의하여 영리목적으로 이용하거나 제3자에게 이용하게 하여서는 안됩니다.\r\n\r\n③ “사이트”는 약정에 따라 이용자에게 귀속된 저작권을 사용하는 경우 당해 이용자에게 통보하여야 합니다.\r\n\r\n제23조(분쟁해결)\r\n\r\n① “사이트”는 이용자가 제기하는 정당한 의견이나 불만을 반영하고 그 피해를 보상처리하기 위하여 피해보상처리기구를 설치·운영합니다.\r\n\r\n② “사이트”는 이용자로부터 제출되는 불만사항 및 의견은 우선적으로 그 사항을 처리합니다. 다만, 신속한 처리가 곤란한 경우에는 이용자에게 그 사유와 처리일정을 즉시 통보해 드립니다.\r\n\r\n③ “사이트”과 이용자 간에 발생한 전자상거래 분쟁과 관련하여 이용자의 피해구제신청이 있는 경우에는 공정거래위원회 또는 시·도지사가 의뢰하는 분쟁조정기관의 조정에 따를 수 있습니다.\r\n\r\n제24조(재판권 및 준거법)\r\n\r\n① “사이트”과 이용자 간에 발생한 전자상거래 분쟁에 관한 소송은 제소 당시의 이용자의 주소에 의하고, 주소가 없는 경우에는 거소를 관할하는 지방법원의 전속관할로 합니다. 다만, 제소 당시 이용자의 주소 또는 거소가 분명하지 않거나 외국 거주자의 경우에는 민사소송법상의 관할법원에 제기합니다.\r\n\r\n② “사이트”과 이용자 간에 제기된 전자상거래 소송에는 한국법을 적용합니다.','\'대형네트웍스\'는 (이하 \'회사\'는) 고객님의 개인정보를 중요시하며, \"정보통신망 이용촉진 및 정보보호\"에 관한 법률을 준수하고 있습니다.\r\n\r\n회사는 개인정보취급방침을 통하여 고객님께서 제공하시는 개인정보가 어떠한 용도와 방식으로 이용되고 있으며, 개인정보보호를 위해 어떠한 조치가 취해지고 있는지 알려드립니다.\r\n\r\n회사는 개인정보취급방침을 개정하는 경우 웹사이트 공지사항(또는 개별공지)을 통하여 공지할 것입니다.\r\n\r\nο 본 방침은 : 2017 년 11 월 25일 부터 시행됩니다.\r\n\r\n1. 개인정보의 처리 목적\r\n\r\n회사는 개인정보를 다음의 목적을 위해 처리합니다. 처리한 개인정보는 다음의 목적이외의 용도로는 사용되지 않으며 이용 목적이 변경될 시에는 사전동의를 구할 예정입니다.\r\n\r\nο 홈페이지 회원가입 및 관리\r\n\r\n회원 가입의사 확인, 회원제 서비스 제공에 따른 본인 식별·인증, 회원자격 유지·관리, 제한적 본인확인제 시행에 따른 본인확인, 서비스 부정이용 방지, 만14세 미만 아동 개인정보 수집 시 법정대리인 동의 여부 확인, 각종 고지·통지, 고충처리, 분쟁 조정을 위한 기록 보존 등을 목적으로 개인정보를 처리합니다.\r\n\r\nο 민원사무 처리\r\n\r\n민원인의 신원 확인, 민원사항 확인, 사실조사를 위한 연락·통지, 처리결과 통보 등을 목적으로 개인정보를 처리합니다.\r\n\r\nο 재화 또는 서비스 제공\r\n\r\n물품배송, 서비스 제공, 청구서 발송, 콘텐츠 제공, 맞춤 서비스 제공, 본인인증, 연령인증, 요금결제·정산 등을 목적으로 개인정보를 처리합니다.\r\n\r\nο 마케팅 및 광고에의 활용\r\n\r\n신규 서비스(제품) 개발 및 맞춤 서비스 제공, 이벤트 및 광고성 정보 제공 및 참여기회 제공 , 인구통계학적 특성에 따른 서비스 제공 및 광고 게재 , 서비스의 유효성 확인, 접속빈도 파악 또는 회원의 서비스 이용에 대한 통계 등을 목적으로 개인정보를 처리합니다.\r\n\r\n2. 수집하는 개인정보 (필수 안내사항)\r\n\r\n회사는 회원가입, 상담, 서비스 신청 등등을 위해 아래와 같은 개인정보를 수집하고 있습니다.\r\n\r\nο 수집항목 : 이름 , 로그인ID , 비밀번호 , 연락처 , 이메일, 주소, 서비스 이용기록 , 접속 로그 , 접속 IP 정보 , 결제기록\r\n\r\nο 개인정보 수집방법 : 홈페이지(회원가입, 공개 게시판, 배송요청 등) , 경품 행사 응모 , 배송 요청\r\n\r\n3.개인정보의 수집 및 이용목적\r\n\r\n회사는 수집한 개인정보를 다음의 목적을 위해 활용합니다. ο 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산 콘텐츠 제공 , 구매 및 요금 결제 , 물품배송 또는 청구지 등 발송\r\n\r\nο 회원 관리 개인 식별 , 불량회원의 부정 이용 방지와 비인가 사용 방지 , 가입 의사 확인 , 만14세 미만 아동 개인정보 수집 시 법정 대리인 동의여부 확인 , 불만처리 등 민원처리 , 고지사항 전달\r\n\r\nο 마케팅 및 광고에 활용 이벤트 등 광고성 정보 전달\r\n\r\n4.개인정보의 보유 및 이용기간\r\n\r\n회사는 개인정보 수집 및 이용목적이 달성된 후에는 예외 없이 해당 정보를 지체 없이 파기합니다.\r\n\r\n5. 개인정보의 파기절차 및 방법\r\n\r\n회사는 원칙적으로 개인정보 처리목적이 달성된 경우에는 지체없이 해당 개인정보를 파기합니다. 파기의 절차, 기한 및 방법은 다음과 같습니다.\r\n\r\n- 파기절차이용자가 입력한 정보는 목적 달성 후 별도의 DB에 옮겨져(종이의 경우 별도의 서류) 내부 방침 및 기타 관련 법령에 따라 일정기간 저장된 후 혹은 즉시 파기됩니다. 이 때, DB로 옮겨진 개인정보는 법률에 의한 경우가 아니고서는 다른 목적으로 이용되지 않습니다.-파기기한이용자의 개인정보는 개인정보의 보유기간이 경과된 경우에는 보유기간의 종료일로부터 5일 이내에, 개인정보의 처리 목적 달성, 해당 서비스의 폐지, 사업의 종료 등 그 개인정보가 불필요하게 되었을 때에는 개인정보의 처리가 불필요한 것으로 인정되는 날로부터 5일 이내에 그 개인정보를 파기합니다.\r\n\r\n- 파기방법\r\n\r\n전자적 파일 형태의 정보는 기록을 재생할 수 없는 기술적 방법을 사용합니다.\r\n\r\n종이에 출력된 개인정보는 분쇄기로 분쇄하거나 소각을 통하여 파기합니다.\r\n\r\n6. 개인정보 제공\r\n\r\n회사는 이용자의 개인정보를 원칙적으로 외부에 제공하지 않습니다. 다만, 아래의 경우에는 예외로 합니다.\r\n\r\n- 이용자들이 사전에 동의한 경우\r\n\r\n- 법령에서 정한 소관업무의 수행을 위하여 불가피한 경우.\r\n\r\n- 제3자의 급박한 생명, 신체, 재산의 이익을 위하여 필요하다고 인정되는 경우.\r\n\r\n7. 수집한 개인정보의 위탁\r\n\r\n회사는 이용자의 동의 없이 해당 개인정보를 타인에게 위탁하지 않습니다. 향후 개인정보처리 위탁 필요가 생길 경우, 위탁대상자, 위탁업무내용, 위탁기간, 위탁계약내용(개인정보보호 관련 법규의 준수, 개인정보에 관한 제3자 제공 금지 및 책임부담 등을 규정)을 개인정보처리방침을 통해 고지하겠습니다. 또한 필요한 경우 사전동의를 받도록 하겠습니다.\r\n\r\n8. 이용자 및 법정대리인의 권리와 그 행사방법\r\n\r\n이용자 및 법정 대리인은 언제든지 등록되어 있는 자신 혹은 당해 만 14세 미만 아동의 개인정보를 조회하거나 수정할 수 있으며 가입해지를 요청할 수도 있습니다.\r\n\r\n이용자 혹은 만 14세 미만 아동의 개인정보 조회·수정을 위해서는 ‘개인정보변경’(또는 ‘회원정보수정’ 등)을 가입해지(동의철회)를 위해서는 “회원탈퇴”를 클릭하여 본인 확인 절차를 거치신 후 직접 열람, 정정 또는 탈퇴가 가능합니다.\r\n\r\n혹은 개인정보관리책임자에게 서면, 전화 또는 이메일로 연락하시면 지체없이 조치하겠습니다.\r\n\r\n귀하가 개인정보의 오류에 대한 정정을 요청하신 경우에는 정정을 완료하기 전까지 당해 개인정보를 이용 또는 제공하지 않습니다. 또한 잘못된 개인정보를 제3자에게 이미 제공한 경우에는 정정 처리결과를 제3자에게 지체없이 통지하여 정정이 이루어지도록 하겠습니다.\r\n\r\n회사는 이용자 혹은 법정 대리인의 요청에 의해 해지 또는 삭제된 개인정보는 회사가 수집하는 개인정보의 보유 및 이용기간에 명시된 바에 따라 처리하고 그 외의 용도로 열람 또는 이용할 수 없도록 처리하고 있습니다.\r\n\r\n9. 개인정보 자동수집 장치의 설치, 운영 및 그 거부에 관한 사항\r\n\r\n회사는 귀하의 정보를 수시로 저장하고 찾아내는 ‘쿠키(cookie)’ 등을 운용합니다. 쿠키란 녹차의달인 쇼핑몰의 웹사이트를 운영하는데 이용되는 서버가 귀하의 브라우저에 보내는 아주 작은 텍스트 파일로서 귀하의 컴퓨터 하드디스크에 저장됩니다. 회사는(는) 다음과 같은 목적을 위해 쿠키를 사용합니다.\r\n\r\n- 쿠키 등 사용 목적 - 회원과 비회원의 접속 빈도나 방문 시간 등을 분석, 이용자의 취향과 관심분야를 파악 및 자취 추적, 각종 이벤트 참여 정도 및 방문 회수 파악 등을 통한 타겟 마케팅 및 개인 맞춤 서비스 제공 귀하는 쿠키 설치에 대한 선택권을 가지고 있습니다. 따라서, 귀하는 웹브라우저에서 옵션을 설정함으로써 모든 쿠키를 허용하거나, 쿠키가 저장될 때마다 확인을 거치거나, 아니면 모든 쿠키의 저장을 거부할 수도 있습니다.\r\n\r\n- 쿠키 설정 거부 방법 예: 쿠키 설정을 거부하는 방법으로는 회원님이 사용하시는 웹 브라우저의 옵션을 선택함으로써 모든 쿠키를 허용하거나 쿠키를 저장할 때마다 확인을 거치거나, 모든 쿠키의 저장을 거부할 수 있습니다. 설정방법 예(인터넷 익스플로어의 경우) : 웹 브라우저 상단의 도구 > 인터넷 옵션 > 개인정보 단, 귀하께서 쿠키 설치를 거부하였을 경우 서비스 제공에 어려움이 있을 수 있습니다.\r\n\r\n10. 개인정보에 관한 민원서비스\r\n\r\n회사는 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.\r\n\r\n- 고객서비스담당 부서: 고객센터\r\n전화번호 : 1800-7540\r\n이메일 : allimtalk@gmail.com\r\n\r\n- 개인정보관리책임자 성명 : 송종근\r\n전화번호 : 1800-7540\r\n이메일 : allimtalk@gmail.com\r\n\r\n정보주체께서는 회사의 서비스(또는 사업)을 이용하시면서 발생한 모든 개인정보 보호 관련 문의, 불만처리, 피해구제 등에 관한 사항을 개인정보 보호책임자 및 담당부서로 문의하실 수 있습니다. 회사는 정보주체의 문의에 대해 지체 없이 답변 및 처리해드릴 것입니다.\r\n\r\n기타 개인정보침해에 대한 신고나 상담이 필요하신 경우에는 아래 기관에 문의하시기 바랍니다.\r\n\r\n-개인정보침해신고센터 (privacy.kisa.or.kr / 국번없이 118)\r\n- 대검찰청 사이버수사과 (www.spo.go.kr / 국번없이 1301)\r\n- 경찰청 사이버안전국 (cyberbureau.police.go.kr / 국번없이 182)',0,500,'theme/basic','theme/basic','theme/basic','theme/basic','basic','basic','smarteditor2',0,'','','','','','',2,0,'','','','','211.172.232.124','7295','','','','','','','','','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_content`
--

DROP TABLE IF EXISTS `g5_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_content` (
  `co_id` varchar(20) NOT NULL DEFAULT '',
  `co_html` tinyint(4) NOT NULL DEFAULT '0',
  `co_subject` varchar(255) NOT NULL DEFAULT '',
  `co_content` longtext NOT NULL,
  `co_mobile_content` longtext NOT NULL,
  `co_skin` varchar(255) NOT NULL DEFAULT '',
  `co_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `co_tag_filter_use` tinyint(4) NOT NULL DEFAULT '0',
  `co_hit` int(11) NOT NULL DEFAULT '0',
  `co_include_head` varchar(255) NOT NULL,
  `co_include_tail` varchar(255) NOT NULL,
  PRIMARY KEY (`co_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_content`
--

LOCK TABLES `g5_content` WRITE;
/*!40000 ALTER TABLE `g5_content` DISABLE KEYS */;
INSERT INTO `g5_content` VALUES ('company',1,'회사소개','<div style=\"text-align: center;\"><img title=\"2ebec2fb1cf689d90a2ae007c8aae132_1511512681_6111.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/2ebec2fb1cf689d90a2ae007c8aae132_1511512681_6111.jpg\"></div>','','theme/basic','theme/basic',0,0,'',''),('privacy',1,'개인정보 처리방침','<h1>개인정보처리방침</h1>\r\n<p>\'대형네트웍스\'는 (이하 \'회사\'는) 고객님의 개인정보를 중요시하며, \"정보통신망 이용촉진 및 정보보호\"에 관한 법률을 준수하고 있습니다.</p>\r\n<p>회사는 개인정보취급방침을 통하여 고객님께서 제공하시는 개인정보가 어떠한 용도와 방식으로 이용되고 있으며, 개인정보보호를 위해 어떠한 조치가 취해지고 있는지 알려드립니다.</p>\r\n<p>회사는 개인정보취급방침을 개정하는 경우 웹사이트 공지사항(또는 개별공지)을 통하여 공지할 것입니다.</p>\r\n<p>ο 본 방침은 : 2017 년 11 월 25일 부터 시행됩니다.</p>\r\n<h2>1. 개인정보의 처리 목적</h2>\r\n<p>회사는 개인정보를 다음의 목적을 위해 처리합니다. 처리한 개인정보는 다음의 목적이외의 용도로는 사용되지 않으며 이용 목적이 변경될 시에는 사전동의를 구할 예정입니다.</p>\r\n<p>ο 홈페이지 회원가입 및 관리</p>\r\n<p class=\"margin-left-17\">회원 가입의사 확인, 회원제 서비스 제공에 따른 본인 식별·인증, 회원자격 유지·관리, 제한적 본인확인제 시행에 따른 본인확인, 서비스 부정이용 방지, 만14세 미만 아동 개인정보 수집 시 법정대리인 동의 여부 확인, 각종 고지·통지, 고충처리, 분쟁 조정을 위한 기록 보존 등을 목적으로 개인정보를 처리합니다.</p>\r\n<p>ο 민원사무 처리</p>\r\n<p class=\"margin-left-17\">민원인의 신원 확인, 민원사항 확인, 사실조사를 위한 연락·통지, 처리결과 통보 등을 목적으로 개인정보를 처리합니다.</p>\r\n<p>ο 재화 또는 서비스 제공</p>\r\n<p class=\"margin-left-17\">물품배송, 서비스 제공, 청구서 발송, 콘텐츠 제공, 맞춤 서비스 제공, 본인인증, 연령인증, 요금결제·정산 등을 목적으로 개인정보를 처리합니다.</p>\r\n<p>ο 마케팅 및 광고에의 활용</p>\r\n<p class=\"margin-left-17\">신규 서비스(제품) 개발 및 맞춤 서비스 제공, 이벤트 및 광고성 정보 제공 및 참여기회 제공 , 인구통계학적 특성에 따른 서비스 제공 및 광고 게재 , 서비스의 유효성 확인, 접속빈도 파악 또는 회원의 서비스 이용에 대한 통계 등을 목적으로 개인정보를 처리합니다.</p>\r\n<h2>2. 수집하는 개인정보 (필수 안내사항)</h2>\r\n<p>회사는 회원가입, 상담, 서비스 신청 등등을 위해 아래와 같은 개인정보를 수집하고 있습니다.</p>\r\n<p>ο 수집항목 : 이름 , 로그인ID , 비밀번호 , 연락처 , 이메일, 주소, 서비스 이용기록 , 접속 로그 , 접속 IP 정보 , 결제기록</p>\r\n<p>ο 개인정보 수집방법 : 홈페이지(회원가입, 공개 게시판, 배송요청 등) , 경품 행사 응모 , 배송 요청</p>\r\n<h2>3.개인정보의 수집 및 이용목적</h2>\r\n<p>회사는 수집한 개인정보를 다음의 목적을 위해 활용합니다. ο 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산 콘텐츠 제공 , 구매 및 요금 결제 , 물품배송 또는 청구지 등 발송</p>\r\n<p>ο 회원 관리 개인 식별 , 불량회원의 부정 이용 방지와 비인가 사용 방지 , 가입 의사 확인 , 만14세 미만 아동 개인정보 수집 시 법정 대리인 동의여부 확인 , 불만처리 등 민원처리 , 고지사항 전달</p>\r\n<p>ο 마케팅 및 광고에 활용 이벤트 등 광고성 정보 전달</p>\r\n<h2>4.개인정보의 보유 및 이용기간</h2>\r\n<p>회사는 개인정보 수집 및 이용목적이 달성된 후에는 예외 없이 해당 정보를 지체 없이 파기합니다.</p>\r\n<h2>5. 개인정보의 파기절차 및 방법</h2>\r\n<p>회사는 원칙적으로 개인정보 처리목적이 달성된 경우에는 지체없이 해당 개인정보를 파기합니다. 파기의 절차, 기한 및 방법은 다음과 같습니다.</p>\r\n<p class=\"margin-left-17\">- 파기절차이용자가 입력한 정보는 목적 달성 후 별도의 DB에 옮겨져(종이의 경우 별도의 서류) 내부 방침 및 기타 관련 법령에 따라 일정기간 저장된 후 혹은 즉시 파기됩니다. 이 때, DB로 옮겨진 개인정보는 법률에 의한 경우가 아니고서는 다른 목적으로 이용되지 않습니다.-파기기한이용자의 개인정보는 개인정보의 보유기간이 경과된 경우에는 보유기간의 종료일로부터 5일 이내에, 개인정보의 처리 목적 달성, 해당 서비스의 폐지, 사업의 종료 등 그 개인정보가 불필요하게 되었을 때에는 개인정보의 처리가 불필요한 것으로 인정되는 날로부터 5일 이내에 그 개인정보를 파기합니다.</p>\r\n<p class=\"margin-left-17\">- 파기방법</p>\r\n<p>전자적 파일 형태의 정보는 기록을 재생할 수 없는 기술적 방법을 사용합니다.</p>\r\n<p>종이에 출력된 개인정보는 분쇄기로 분쇄하거나 소각을 통하여 파기합니다.</p>\r\n<h2>6. 개인정보 제공</h2>\r\n<p>회사는 이용자의 개인정보를 원칙적으로 외부에 제공하지 않습니다. 다만, 아래의 경우에는 예외로 합니다.</p>\r\n<p class=\"margin-left-17\">- 이용자들이 사전에 동의한 경우</p>\r\n<p class=\"margin-left-17\">- 법령에서 정한 소관업무의 수행을 위하여 불가피한 경우.</p>\r\n<p class=\"margin-left-17\">- 제3자의 급박한 생명, 신체, 재산의 이익을 위하여 필요하다고 인정되는 경우.</p>\r\n<h2>7. 수집한 개인정보의 위탁</h2>\r\n<p>회사는 이용자의 동의 없이 해당 개인정보를 타인에게 위탁하지 않습니다. 향후 개인정보처리 위탁 필요가 생길 경우, 위탁대상자, 위탁업무내용, 위탁기간, 위탁계약내용(개인정보보호 관련 법규의 준수, 개인정보에 관한 제3자 제공 금지 및 책임부담 등을 규정)을 개인정보처리방침을 통해 고지하겠습니다. 또한 필요한 경우 사전동의를 받도록 하겠습니다.</p>\r\n<h2>8. 이용자 및 법정대리인의 권리와 그 행사방법</h2>\r\n<p>이용자 및 법정 대리인은 언제든지 등록되어 있는 자신 혹은 당해 만 14세 미만 아동의 개인정보를 조회하거나 수정할 수 있으며 가입해지를 요청할 수도 있습니다.</p>\r\n<p>이용자 혹은 만 14세 미만 아동의 개인정보 조회·수정을 위해서는 ‘개인정보변경’(또는 ‘회원정보수정’ 등)을 가입해지(동의철회)를 위해서는 “회원탈퇴”를 클릭하여 본인 확인 절차를 거치신 후 직접 열람, 정정 또는 탈퇴가 가능합니다.</p>\r\n<p>혹은 개인정보관리책임자에게 서면, 전화 또는 이메일로 연락하시면 지체없이 조치하겠습니다.</p>\r\n<p>귀하가 개인정보의 오류에 대한 정정을 요청하신 경우에는 정정을 완료하기 전까지 당해 개인정보를 이용 또는 제공하지 않습니다. 또한 잘못된 개인정보를 제3자에게 이미 제공한 경우에는 정정 처리결과를 제3자에게 지체없이 통지하여 정정이 이루어지도록 하겠습니다.</p>\r\n<p>회사는 이용자 혹은 법정 대리인의 요청에 의해 해지 또는 삭제된 개인정보는 회사가 수집하는 개인정보의 보유 및 이용기간에 명시된 바에 따라 처리하고 그 외의 용도로 열람 또는 이용할 수 없도록 처리하고 있습니다.</p>\r\n<h2>9. 개인정보 자동수집 장치의 설치, 운영 및 그 거부에 관한 사항</h2>\r\n<p>회사는 귀하의 정보를 수시로 저장하고 찾아내는 ‘쿠키(cookie)’ 등을 운용합니다. 쿠키란 녹차의달인 쇼핑몰의 웹사이트를 운영하는데 이용되는 서버가 귀하의 브라우저에 보내는 아주 작은 텍스트 파일로서 귀하의 컴퓨터 하드디스크에 저장됩니다. 회사는(는) 다음과 같은 목적을 위해 쿠키를 사용합니다.</p>\r\n<p class=\"margin-left-17\">- 쿠키 등 사용 목적 - 회원과 비회원의 접속 빈도나 방문 시간 등을 분석, 이용자의 취향과 관심분야를 파악 및 자취 추적, 각종 이벤트 참여 정도 및 방문 회수 파악 등을 통한 타겟 마케팅 및 개인 맞춤 서비스 제공 귀하는 쿠키 설치에 대한 선택권을 가지고 있습니다. 따라서, 귀하는 웹브라우저에서 옵션을 설정함으로써 모든 쿠키를 허용하거나, 쿠키가 저장될 때마다 확인을 거치거나, 아니면 모든 쿠키의 저장을 거부할 수도 있습니다.</p>\r\n<p class=\"margin-left-17\">- 쿠키 설정 거부 방법 예: 쿠키 설정을 거부하는 방법으로는 회원님이 사용하시는 웹 브라우저의 옵션을 선택함으로써 모든 쿠키를 허용하거나 쿠키를 저장할 때마다 확인을 거치거나, 모든 쿠키의 저장을 거부할 수 있습니다. 설정방법 예(인터넷 익스플로어의 경우) : 웹 브라우저 상단의 도구 &gt; 인터넷 옵션 &gt; 개인정보 단, 귀하께서 쿠키 설치를 거부하였을 경우 서비스 제공에 어려움이 있을 수 있습니다.</p>\r\n<h2>10. 개인정보에 관한 민원서비스</h2>\r\n<p>회사는 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.</p>\r\n<p class=\"margin-left-17\">- 고객서비스담당 부서: 고객센터</p>\r\n<p class=\"margin-left-32\">전화번호 : 1800-7540</p>\r\n<p class=\"margin-left-32\">이메일 : allimtalk@gmail.com</p>\r\n<p class=\"margin-left-17\">- 개인정보관리책임자 성명 : 송종근</p>\r\n<p class=\"margin-left-32\">전화번호 : 1800-7540</p>\r\n<p class=\"margin-left-32\">이메일 : allimtalk@gmail.com</p>\r\n<p>정보주체께서는 회사의 서비스(또는 사업)을 이용하시면서 발생한 모든 개인정보 보호 관련 문의, 불만처리, 피해구제 등에 관한 사항을 개인정보 보호책임자 및 담당부서로 문의하실 수 있습니다. 회사는 정보주체의 문의에 대해 지체 없이 답변 및 처리해드릴 것입니다.</p>\r\n<h2>기타 개인정보침해에 대한 신고나 상담이 필요하신 경우에는 아래 기관에 문의하시기 바랍니다.</h2>\r\n<p>-개인정보침해신고센터 (privacy.kisa.or.kr / 국번없이 118)</p>\r\n<p>- 대검찰청 사이버수사과 (www.spo.go.kr / 국번없이 1301)</p>\r\n<p>- 경찰청 사이버안전국 (cyberbureau.police.go.kr / 국번없이 182)</p>','','theme/basic','theme/basic',0,0,'',''),('provision',1,'이용약관','<h1>이용약관</h1>\r\n<h2>제1조(목적)</h2>\r\n<p>이 약관은 대형네트웍스 (이하 ”회사”이라 한다)가 운영하는 대형네트웍스 사이버 몰(이하 “사이트”이라 한다)에서 제공하는 인터넷 관련 서비스(이하 “서비스”라 한다)를 이용함에 있어 사이버 몰과 이용자의 권리·의무 및 책임사항을 규정함을 목적으로 합니다.</p>\r\n<p>※「인터넷, 정보통신망, 모바일 및 스마트 장치 등을 이용하는 전자상거래에 대하여도 그 성질에 반하지 않는 한 이 약관을 준용합니다.」</p>\r\n<h2>제2조(정의)</h2>\r\n<p>① “사이트”이란 회사이 재화 또는 용역(이하 “재화 등”이라 함)을 이용자에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 재화 등을 거래할 수 있도록 설정한 가상의 영업장을 말하며, 아울러 사이버몰을 운영하는 사업자의 의미로도 사용합니다.</p>\r\n<p>② “이용자”란 “사이트”에 접속하여 이 약관에 따라 “사이트”이 제공하는 서비스를 받는 회원 및 비회원을 말합니다.</p>\r\n<p>③ ‘회원’이라 함은 “사이트”에 회원등록을 한 자로서, 계속적으로 “사이트”이 제공하는 서비스를 이용할 수 있는 자를 말합니다.</p>\r\n<p>④ ‘비회원’이라 함은 회원에 가입하지 않고 “사이트”이 제공하는 서비스를 이용하는 자를 말합니다.</p>\r\n<h2>제3조 (약관 등의 명시와 설명 및 개정)</h2>\r\n<p>① “사이트”는 이 약관의 내용과 상호 및 대표자 성명, 영업소 소재지 주소(소비자의 불만을 처리할 수 있는 곳의 주소를 포함), 전화번호·모사전송번호·전자우편주소, 사업자등록번호, 통신판매업 신고번호, 개인정보관리책임자 등을 이용자가 쉽게 알 수 있도록 대형네트웍스 사이버몰의 초기 서비스화면(전면)에 게시합니다. 다만, 약관의 내용은 이용자가 연결화면을 통하여 볼 수 있도록 할 수 있습니다.</p>\r\n<p>② “몰은 이용자가 약관에 동의하기에 앞서 약관에 정하여져 있는 내용 중 청약철회·배송책임·환불조건 등과 같은 중요한 내용을 이용자가 이해할 수 있도록 별도의 연결화면 또는 팝업화면 등을 제공하여 이용자의 확인을 구하여야 합니다.</p>\r\n<p>③ “사이트”는 「전자상거래 등에서의 소비자보호에 관한 법률」, 「약관의 규제에 관한 법률」, 「전자문서 및 전자거래기본법」, 「전자금융거래법」, 「전자서명법」, 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」, 「방문판매 등에 관한 법률」, 「소비자기본법」 등 관련 법을 위배하지 않는 범위에서 이 약관을 개정할 수 있습니다.</p>\r\n<p>④ “사이트”이 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행약관과 함께 몰의 초기화면에 그 적용일자 7일 이전부터 적용일자 전일까지 공지합니다. 다만, 이용자에게 불리하게 약관내용을 변경하는 경우에는 최소한 30일 이상의 사전 유예기간을 두고 공지합니다. &nbsp;이 경우 \"몰“은 개정 전 내용과 개정 후 내용을 명확하게 비교하여 이용자가 알기 쉽도록 표시합니다.</p>\r\n<p>⑤ “사이트”이 약관을 개정할 경우에는 그 개정약관은 그 적용일자 이후에 체결되는 계약에만 적용되고 그 이전에 이미 체결된 계약에 대해서는 개정 전의 약관조항이 그대로 적용됩니다. 다만 이미 계약을 체결한 이용자가 개정약관 조항의 적용을 받기를 원하는 뜻을 제3항에 의한 개정약관의 공지기간 내에 “사이트”에 송신하여 “사이트”의 동의를 받은 경우에는 개정약관 조항이 적용됩니다.</p>\r\n<p>⑥ 이 약관에서 정하지 아니한 사항과 이 약관의 해석에 관하여는 전자상거래 등에서의 소비자보호에 관한 법률, 약관의 규제 등에 관한 법률, 공정거래위원회가 정하는 「전자상거래 등에서의 소비자 보호지침」 및 관계법령 또는 상관례에 따릅니다.</p>\r\n<h2>제4조(서비스의 제공 및 변경)</h2>\r\n<p>① “사이트”는 다음과 같은 업무를 수행합니다.</p>\r\n<p class=\"margin-left-17\">1. 재화 또는 용역에 대한 정보 제공 및 구매계약의 체결</p>\r\n<p class=\"margin-left-17\">2. 구매계약이 체결된 재화 또는 용역의 배송</p>\r\n<p class=\"margin-left-17\">3. 기타 “사이트”이 정하는 업무</p>\r\n<p>② “사이트”는 재화 또는 용역의 품절 또는 기술적 사양의 변경 등의 경우에는 장차 체결되는 계약에 의해 제공할 재화 또는 용역의 내용을 변경할 수 있습니다. 이 경우에는 변경된 재화 또는 용역의 내용 및 제공일자를 명시하여 현재의 재화 또는 용역의 내용을 게시한 곳에 즉시 공지합니다.</p>\r\n<p>③ “사이트”이 제공하기로 이용자와 계약을 체결한 서비스의 내용을 재화 등의 품절 또는 기술적 사양의 변경 등의 사유로 변경할 경우에는 그 사유를 이용자에게 통지 가능한 주소로 즉시 통지합니다.</p>\r\n<p>④ 전항의 경우 “사이트”는 이로 인하여 이용자가 입은 손해를 배상합니다. 다만, “사이트”이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.</p>\r\n<h2>제5조(서비스의 중단)</h2>\r\n<p>① “사이트”는 컴퓨터 등 정보통신설비의 보수점검·교체 및 고장, 통신의 두절 등의 사유가 발생한 경우에는 서비스의 제공을 일시적으로 중단할 수 있습니다.</p>\r\n<p>② “사이트”는 제1항의 사유로 서비스의 제공이 일시적으로 중단됨으로 인하여 이용자 또는 제3자가 입은 손해에 대하여 배상합니다. 단, “사이트”이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.</p>\r\n<p>③ 사업종목의 전환, 사업의 포기, 업체 간의 통합 등의 이유로 서비스를 제공할 수 없게 되는 경우에는 “사이트”는 제8조에 정한 방법으로 이용자에게 통지하고 당초 “사이트”에서 제시한 조건에 따라 소비자에게 보상합니다. 다만, “사이트”이 보상기준 등을 고지하지 아니한 경우에는 이용자들의 마일리지 또는 적립금 등을 “사이트”에서 통용되는 통화가치에 상응하는 현물 또는 현금으로 이용자에게 지급합니다.</p>\r\n<h2>제6조(회원가입)</h2>\r\n<p>① 이용자는 “사이트”이 정한 가입 양식에 따라 회원정보를 기입한 후 이 약관에 동의한다는 의사표시를 함으로서 회원가입을 신청합니다.</p>\r\n<p>② “사이트”는 제1항과 같이 회원으로 가입할 것을 신청한 이용자 중 다음 각 호에 해당하지 않는 한 회원으로 등록합니다.</p>\r\n<p class=\"margin-left-17\">1. 가입신청자가 이 약관 제7조제3항에 의하여 이전에 회원자격을 상실한 적이 있는 경우, 다만 제7조제3항에 의한 회원자격 상실 후 3년이 경과한 자로서 “사이트”의 회원재가입 승낙을 얻은 경우에는 예외로 한다.</p>\r\n<p class=\"margin-left-17\">2. 등록 내용에 허위, 기재누락, 오기가 있는 경우</p>\r\n<p class=\"margin-left-17\">3. 기타 회원으로 등록하는 것이 “사이트”의 기술상 현저히 지장이 있다고 판단되는 경우</p>\r\n<p>③ 회원가입계약의 성립 시기는 “사이트”의 승낙이 회원에게 도달한 시점으로 합니다.</p>\r\n<p>④ 회원은 회원가입 시 등록한 사항에 변경이 있는 경우, 상당한 기간 이내에 “사이트”에 대하여 회원정보 수정 등의 방법으로 그 변경사항을 알려야 합니다.</p>\r\n<h2>제7조(회원 탈퇴 및 자격 상실 등)</h2>\r\n<p>① 회원은 “사이트”에 언제든지 탈퇴를 요청할 수 있으며 “사이트”는 즉시 회원탈퇴를 처리합니다.</p>\r\n<p>② 회원이 다음 각 호의 사유에 해당하는 경우, “사이트”는 회원자격을 제한 및 정지시킬 수 있습니다.</p>\r\n<p class=\"margin-left-17\">1. 가입 신청 시에 허위 내용을 등록한 경우</p>\r\n<p class=\"margin-left-17\">2. “사이트”을 이용하여 구입한 재화 등의 대금, 기타 “사이트”이용에 관련하여 회원이 부담하는 채무를 기일에 지급하지 않는 경우</p>\r\n<p class=\"margin-left-17\">3. 다른 사람의 “사이트” 이용을 방해하거나 그 정보를 도용하는 등 전자상거래 질서를 위협하는 경우</p>\r\n<p class=\"margin-left-17\">4. “사이트”을 이용하여 법령 또는 이 약관이 금지하거나 공서양속에 반하는 행위를 하는 경우</p>\r\n<p>③ “사이트”이 회원 자격을 제한·정지 시킨 후, 동일한 행위가 2회 이상 반복되거나 30일 이내에 그 사유가 시정되지 아니하는 경우 “사이트”는 회원자격을 상실시킬 수 있습니다.</p>\r\n<p>④ “사이트”이 회원자격을 상실시키는 경우에는 회원등록을 말소합니다. 이 경우 회원에게 이를 통지하고, 회원등록 말소 전에 최소한 30일 이상의 기간을 정하여 소명할 기회를 부여합니다.</p>\r\n<h2>제8조(회원에 대한 통지)</h2>\r\n<p>① “사이트”이 회원에 대한 통지를 하는 경우, 회원이 “사이트”과 미리 약정하여 지정한 전자우편 주소로 할 수 있습니다.</p>\r\n<p>② “사이트”는 불특정다수 회원에 대한 통지의 경우 1주일이상 “사이트” 게시판에 게시함으로서 개별 통지에 갈음할 수 있습니다. 다만, 회원 본인의 거래와 관련하여 중대한 영향을 미치는 사항에 대하여는 개별통지를 합니다.</p>\r\n<h2>제9조(구매신청)</h2>\r\n<p>① “사이트”이용자는 “사이트”상에서 다음 또는 이와 유사한 방법에 의하여 구매를 신청하며, “사이트”는 이용자가 구매신청을 함에 있어서 다음의 각 내용을 알기 쉽게 제공하여야 합니다.</p>\r\n<p class=\"margin-left-17\">1. 재화 등의 검색 및 선택</p>\r\n<p class=\"margin-left-17\">2. 받는 사람의 성명, 주소, 전화번호, 전자우편주소(또는 이동전화번호) 등의 입력</p>\r\n<p class=\"margin-left-17\">3. 약관내용, 청약철회권이 제한되는 서비스, 배송료·설치비 등의 비용부담과 관련한 내용에 대한 확인</p>\r\n<p class=\"margin-left-17\">4. 이 약관에 동의하고 위 3.호의 사항을 확인하거나 거부하는 표시</p>\r\n<p class=\"margin-left-32\">(예, 마우스 클릭)</p>\r\n<p>5. 재화 등의 구매신청 및 이에 관한 확인 또는 “사이트”의 확인에 대한 동의</p>\r\n<p>6. 결제방법의 선택</p>\r\n<p>② “사이트”이 제3자에게 구매자 개인정보를 제공·위탁할 필요가 있는 경우 실제 구매신청 시 구매자의 동의를 받아야 하며, 회원가입 시 미리 포괄적으로 동의를 받지 않습니다. 이 때 “사이트”는 제공되는 개인정보 항목, 제공받는 자, 제공받는 자의 개인정보 이용 목적 및 보유?이용 기간 등을 구매자에게 명시하여야 합니다. 다만 「정보통신망이용촉진 및 정보보호 등에 관한 법률」 제25조 제1항에 의한 개인정보 취급위탁의 경우 등 관련 법령에 달리 정함이 있는 경우에는 그에 따릅니다.</p>\r\n<h2>제10조 (계약의 성립)</h2>\r\n<p>① &nbsp;“사이트”는 제9조와 같은 구매신청에 대하여 다음 각 호에 해당하면 승낙하지 않을 수 있습니다. 다만, 미성년자와 계약을 체결하는 경우에는 법정대리인의 동의를 얻지 못하면 미성년자 본인 또는 법정대리인이 계약을 취소할 수 있다는 내용을 고지하여야 합니다.</p>\r\n<p class=\"margin-left-17\">1. 신청 내용에 허위, 기재누락, 오기가 있는 경우</p>\r\n<p class=\"margin-left-17\">2. 미성년자가 담배, 주류 등 청소년보호법에서 금지하는 재화 및 용역을 구매하는 경우</p>\r\n<p class=\"margin-left-17\">3. 기타 구매신청에 승낙하는 것이 “사이트” 기술상 현저히 지장이 있다고 판단하는 경우</p>\r\n<p>② “사이트”의 승낙이 제12조제1항의 수신확인통지형태로 이용자에게 도달한 시점에 계약이 성립한 것으로 봅니다.</p>\r\n<p>③ “사이트”의 승낙의 의사표시에는 이용자의 구매 신청에 대한 확인 및 판매가능 여부, 구매신청의 정정 취소 등에 관한 정보 등을 포함하여야 합니다.</p>\r\n<h2>제11조(지급방법)</h2>\r\n<p>“사이트”에서 구매한 재화 또는 용역에 대한 대금지급방법은 다음 각 호의 방법중 가용한 방법으로 할 수 있습니다. 단, “사이트”는 이용자의 지급방법에 대하여 재화 등의 대금에 어떠한 명목의 수수료도 &nbsp;추가하여 징수할 수 없습니다.</p>\r\n<p class=\"margin-left-17\">1. 폰뱅킹, 인터넷뱅킹, 메일 뱅킹 등의 각종 계좌이체</p>\r\n<p class=\"margin-left-17\">2. 선불카드, 직불카드, 신용카드 등의 각종 카드 결제</p>\r\n<p class=\"margin-left-17\">3. 온라인무통장입금</p>\r\n<p class=\"margin-left-17\">4. 전자화폐에 의한 결제</p>\r\n<p class=\"margin-left-17\">5. 수령 시 대금지급</p>\r\n<p class=\"margin-left-17\">6. 마일리지 등 “사이트”이 지급한 포인트에 의한 결제</p>\r\n<p class=\"margin-left-17\">7. “사이트”과 계약을 맺었거나 “사이트”이 인정한 상품권에 의한 결제</p>\r\n<p class=\"margin-left-17\">8. 기타 전자적 지급 방법에 의한 대금 지급 등</p>\r\n<h2>제12조(수신확인통지·구매신청 변경 및 취소)</h2>\r\n<p>① “사이트”는 이용자의 구매신청이 있는 경우 이용자에게 수신확인통지를 합니다.</p>\r\n<p>② 수신확인통지를 받은 이용자는 의사표시의 불일치 등이 있는 경우에는 수신확인통지를 받은 후 즉시 구매신청 변경 및 취소를 요청할 수 있고 “사이트”는 배송 전에 이용자의 요청이 있는 경우에는 지체 없이 그 요청에 따라 처리하여야 합니다. 다만 이미 대금을 지불한 경우에는 제15조의 청약철회 등에 관한 규정에 따릅니다.</p>\r\n<h2>제13조(재화 등의 공급)</h2>\r\n<p>① “사이트”는 이용자와 재화 등의 공급시기에 관하여 별도의 약정이 없는 이상, 이용자가 청약을 한 날부터 7일 이내에 재화 등을 배송할 수 있도록 주문제작, 포장 등 기타의 필요한 조치를 취합니다. 다만, “사이트”이 이미 재화 등의 대금의 전부 또는 일부를 받은 경우에는 대금의 전부 또는 일부를 받은 날부터 3영업일 이내에 조치를 취합니다. &nbsp;이때 “사이트”는 이용자가 재화 등의 공급 절차 및 진행 사항을 확인할 수 있도록 적절한 조치를 합니다.</p>\r\n<p>② “사이트”는 이용자가 구매한 재화에 대해 배송수단, 수단별 배송비용 부담자, 수단별 배송기간 등을 명시합니다. 만약 “사이트”이 약정 배송기간을 초과한 경우에는 그로 인한 이용자의 손해를 배상하여야 합니다. 다만 “사이트”이 고의·과실이 없음을 입증한 경우에는 그러하지 아니합니다.</p>\r\n<h2>제14조(환급)</h2>\r\n<p>“사이트”는 이용자가 구매신청한 재화 등이 품절 등의 사유로 인도 또는 제공을 할 수 없을 때에는 지체 없이 그 사유를 이용자에게 통지하고 사전에 재화 등의 대금을 받은 경우에는 대금을 받은 날부터 3영업일 이내에 환급하거나 환급에 필요한 조치를 취합니다.</p>\r\n<h2>제15조(청약철회 등)</h2>\r\n<p>① “사이트”과 재화등의 구매에 관한 계약을 체결한 이용자는 「전자상거래 등에서의 소비자보호에 관한 법률」 제13조 제2항에 따른 계약내용에 관한 서면을 받은 날(그 서면을 받은 때보다 재화 등의 공급이 늦게 이루어진 경우에는 재화 등을 공급받거나 재화 등의 공급이 시작된 날을 말합니다)부터 7일 이내에는 청약의 철회를 할 수 있습니다. 다만, 청약철회에 관하여 「전자상거래 등에서의 소비자보호에 관한 법률」에 달리 정함이 있는 경우에는 동 법 규정에 따릅니다.</p>\r\n<p>② 이용자는 재화 등을 배송 받은 경우 다음 각 호의 1에 해당하는 경우에는 반품 및 교환을 할 수 없습니다.</p>\r\n<p class=\"margin-left-17\">1. 이용자에게 책임 있는 사유로 재화 등이 멸실 또는 훼손된 경우(다만, 재화 등의 내용을 확인하기 위하여 포장 등을 훼손한 경우에는 청약철회를 할 수 있습니다)</p>\r\n<p class=\"margin-left-17\">2. 이용자의 사용 또는 일부 소비에 의하여 재화 등의 가치가 현저히 감소한 경우</p>\r\n<p class=\"margin-left-17\">3. 시간의 경과에 의하여 재판매가 곤란할 정도로 재화등의 가치가 현저히 감소한 경우</p>\r\n<p class=\"margin-left-17\">4. 같은 성능을 지닌 재화 등으로 복제가 가능한 경우 그 원본인 재화 등의 포장을 훼손한 경우</p>\r\n<p>③ 제2항제2호 내지 제4호의 경우에 “사이트”이 사전에 청약철회 등이 제한되는 사실을 소비자가 쉽게 알 수 있는 곳에 명기하거나 시용상품을 제공하는 등의 조치를 하지 않았다면 이용자의 청약철회 등이 제한되지 않습니다.</p>\r\n<p>④ 이용자는 제1항 및 제2항의 규정에 불구하고 재화 등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행된 때에는 당해 재화 등을 공급받은 날부터 3월 이내, 그 사실을 안 날 또는 알 수 있었던 날부터 30일 이내에 청약철회 등을 할 수 있습니다.</p>\r\n<h2>제16조(청약철회 등의 효과)</h2>\r\n<p>① “사이트”는 이용자로부터 재화 등을 반환받은 경우 3영업일 이내에 이미 지급받은 재화 등의 대금을 환급합니다. 이 경우 “사이트”이 이용자에게 재화등의 환급을 지연한때에는 그 지연기간에 대하여 「전자상거래 등에서의 소비자보호에 관한 법률 시행령」제21조의2에서 정하는 지연이자율을 곱하여 산정한 지연이자를 지급합니다.</p>\r\n<p>② “사이트”는 위 대금을 환급함에 있어서 이용자가 신용카드 또는 전자화폐 등의 결제수단으로 재화 등의 대금을 지급한 때에는 지체 없이 당해 결제수단을 제공한 사업자로 하여금 재화 등의 대금의 청구를 정지 또는 취소하도록 요청합니다.</p>\r\n<p>③ 청약철회 등의 경우 공급받은 재화 등의 반환에 필요한 비용은 이용자가 부담합니다. “사이트”는 이용자에게 청약철회 등을 이유로 위약금 또는 손해배상을 청구하지 않습니다. 다만 재화 등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행되어 청약철회 등을 하는 경우 재화 등의 반환에 필요한 비용은 “사이트”이 부담합니다.</p>\r\n<p>④ 이용자가 재화 등을 제공받을 때 발송비를 부담한 경우에 “사이트”는 청약철회 시 그 비용을 &nbsp;누가 부담하는지를 이용자가 알기 쉽도록 명확하게 표시합니다.</p>\r\n<h2>제17조(개인정보보호)</h2>\r\n<p>① “사이트”는 이용자의 개인정보 수집시 서비스제공을 위하여 필요한 범위에서 최소한의 개인정보를 수집합니다.</p>\r\n<p>② “사이트”는 회원가입시 구매계약이행에 필요한 정보를 미리 수집하지 않습니다. 다만, 관련 법령상 의무이행을 위하여 구매계약 이전에 본인확인이 필요한 경우로서 최소한의 특정 개인정보를 수집하는 경우에는 그러하지 아니합니다.</p>\r\n<p>③ “사이트”는 이용자의 개인정보를 수집·이용하는 때에는 당해 이용자에게 그 목적을 고지하고 동의를 받습니다.</p>\r\n<p>④ “사이트”는 수집된 개인정보를 목적외의 용도로 이용할 수 없으며, 새로운 이용목적이 발생한 경우 또는 제3자에게 제공하는 경우에는 이용·제공단계에서 당해 이용자에게 그 목적을 고지하고 동의를 받습니다. 다만, 관련 법령에 달리 정함이 있는 경우에는 예외로 합니다.</p>\r\n<p>⑤ “사이트”이 제3항과 제4항에 의해 이용자의 동의를 받아야 하는 경우에는 개인정보관리 책임자의 신원(소속, 성명 및 전화번호, 기타 연락처), 정보의 수집목적 및 이용목적, 제3자에 대한 정보제공 관련사항(제공받은자, 제공목적 및 제공할 정보의 내용) 등 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」 제22조제2항이 규정한 사항을 미리 명시하거나 고지해야 하며 이용자는 언제든지 이 동의를 철회할 수 있습니다.</p>\r\n<p>⑥ 이용자는 언제든지 “사이트”이 가지고 있는 자신의 개인정보에 대해 열람 및 오류정정을 요구할 수 있으며 “사이트”는 이에 대해 지체 없이 필요한 조치를 취할 의무를 집니다. 이용자가 오류의 정정을 요구한 경우에는 “사이트”는 그 오류를 정정할 때까지 당해 개인정보를 이용하지 않습니다.</p>\r\n<p>⑦ “사이트”는 개인정보 보호를 위하여 이용자의 개인정보를 취급하는 자를 &nbsp;최소한으로 제한하여야 하며 신용카드, 은행계좌 등을 포함한 이용자의 개인정보의 분실, 도난, 유출, 동의 없는 제3자 제공, 변조 등으로 인한 이용자의 손해에 대하여 모든 책임을 집니다.</p>\r\n<p>⑧ “사이트” 또는 그로부터 개인정보를 제공받은 제3자는 개인정보의 수집목적 또는 제공받은 목적을 달성한 때에는 당해 개인정보를 지체 없이 파기합니다.</p>\r\n<p>⑨ “사이트”는 개인정보의 수집·이용·제공에 관한 동의란을 미리 선택한 것으로 설정해두지 않습니다. 또한 개인정보의 수집·이용·제공에 관한 이용자의 동의거절시 제한되는 서비스를 구체적으로 명시하고, 필수수집항목이 아닌 개인정보의 수집·이용·제공에 관한 이용자의 동의 거절을 이유로 회원가입 등 서비스 제공을 제한하거나 거절하지 않습니다.</p>\r\n<h2>제18조(“사이트“의 의무)</h2>\r\n<p>① “사이트”는 법령과 이 약관이 금지하거나 공서양속에 반하는 행위를 하지 않으며 이 약관이 정하는 바에 따라 지속적이고, 안정적으로 재화·용역을 제공하는데 최선을 다하여야 합니다.</p>\r\n<p>② “사이트”는 이용자가 안전하게 인터넷 서비스를 이용할 수 있도록 이용자의 개인정보(신용정보 포함)보호를 위한 보안 시스템을 갖추어야 합니다.</p>\r\n<p>③ “사이트”이 상품이나 용역에 대하여 「표시·광고의 공정화에 관한 법률」 제3조 소정의 부당한 표시·광고행위를 함으로써 이용자가 손해를 입은 때에는 이를 배상할 책임을 집니다.</p>\r\n<p>④ “사이트”는 이용자가 원하지 않는 영리목적의 광고성 전자우편을 발송하지 않습니다.</p>\r\n<h2>제19조(회원의 ID 및 비밀번호에 대한 의무)</h2>\r\n<p>① 제17조의 경우를 제외한 ID와 비밀번호에 관한 관리책임은 회원에게 있습니다.</p>\r\n<p>② 회원은 자신의 ID 및 비밀번호를 제3자에게 이용하게 해서는 안됩니다.</p>\r\n<p>③ 회원이 자신의 ID 및 비밀번호를 도난당하거나 제3자가 사용하고 있음을 인지한 경우에는 바로 “사이트”에 통보하고 “사이트”의 안내가 있는 경우에는 그에 따라야 합니다.</p>\r\n<h2>제20조(이용자의 의무)</h2>\r\n<p>이용자는 다음 행위를 하여서는 안 됩니다.</p>\r\n<p class=\"margin-left-17\">1. 신청 또는 변경시 허위 내용의 등록</p>\r\n<p class=\"margin-left-17\">2. 타인의 정보 도용</p>\r\n<p class=\"margin-left-17\">3. “사이트”에 게시된 정보의 변경</p>\r\n<p class=\"margin-left-17\">4. “사이트”이 정한 정보 이외의 정보(컴퓨터 프로그램 등) 등의 송신 또는 게시</p>\r\n<p class=\"margin-left-17\">5. “사이트” 기타 제3자의 저작권 등 지적재산권에 대한 침해</p>\r\n<p class=\"margin-left-17\">6. “사이트” 기타 제3자의 명예를 손상시키거나 업무를 방해하는 행위</p>\r\n<p class=\"margin-left-17\">7. 외설 또는 폭력적인 메시지, 화상, 음성, 기타 공서양속에 반하는 정보를 사이트에 공개 또는 게시하는 행위</p>\r\n<h2>제21조(연결“사이트”과 피연결“사이트” 간의 관계)</h2>\r\n<p>① 상위 “사이트”과 하위 “사이트”이 하이퍼링크(예: 하이퍼링크의 대상에는 문자, 그림 및 동화상 등이 포함됨)방식 등으로 연결된 경우, 전자를 연결 “사이트”(웹 사이트)이라고 하고 후자를 피연결 “사이트”(웹사이트)이라고 합니다.</p>\r\n<p>② 연결“사이트”는 피연결“사이트”이 독자적으로 제공하는 재화 등에 의하여 이용자와 행하는 거래에 대해서 보증 책임을 지지 않는다는 뜻을 연결“사이트”의 초기화면 또는 연결되는 시점의 팝업화면으로 명시한 경우에는 그 거래에 대한 보증 책임을 지지 않습니다.</p>\r\n<p>제22조(저작권의 귀속 및 이용제한)</p>\r\n<p>① “사이트“이 작성한 저작물에 대한 저작권 기타 지적재산권은 ”사이트“에 귀속합니다.</p>\r\n<p>② 이용자는 “사이트”을 이용함으로써 얻은 정보 중 “사이트”에게 지적재산권이 귀속된 정보를 “사이트”의 사전 승낙 없이 복제, 송신, 출판, 배포, 방송 기타 방법에 의하여 영리목적으로 이용하거나 제3자에게 이용하게 하여서는 안됩니다.</p>\r\n<p>③ “사이트”는 약정에 따라 이용자에게 귀속된 저작권을 사용하는 경우 당해 이용자에게 통보하여야 합니다.</p>\r\n<h2>제23조(분쟁해결)</h2>\r\n<p>① “사이트”는 이용자가 제기하는 정당한 의견이나 불만을 반영하고 그 피해를 보상처리하기 위하여 피해보상처리기구를 설치·운영합니다.</p>\r\n<p>② “사이트”는 이용자로부터 제출되는 불만사항 및 의견은 우선적으로 그 사항을 처리합니다. 다만, 신속한 처리가 곤란한 경우에는 이용자에게 그 사유와 처리일정을 즉시 통보해 드립니다.</p>\r\n<p>③ “사이트”과 이용자 간에 발생한 전자상거래 분쟁과 관련하여 이용자의 피해구제신청이 있는 경우에는 공정거래위원회 또는 시·도지사가 의뢰하는 분쟁조정기관의 조정에 따를 수 있습니다.</p>\r\n<h2>제24조(재판권 및 준거법)</h2>\r\n<p>① “사이트”과 이용자 간에 발생한 전자상거래 분쟁에 관한 소송은 제소 당시의 이용자의 주소에 의하고, 주소가 없는 경우에는 거소를 관할하는 지방법원의 전속관할로 합니다. 다만, 제소 당시 이용자의 주소 또는 거소가 분명하지 않거나 외국 거주자의 경우에는 민사소송법상의 관할법원에 제기합니다.</p>\r\n<p>② “사이트”과 이용자 간에 제기된 전자상거래 소송에는 한국법을 적용합니다.</p>','','theme/basic','theme/basic',0,0,'',''),('copyright',1,'저작권정책','<h1>저작권정책</h1>\r\n<p>대형네트웍스 홈페이지에서 대형네트웍스가 제공하는 모든 콘텐츠 즉, 웹문서·첨부파일·DB정보 등은 저작권법에 의하여 보호받는 저작물로, 별도의 저작권 표시 또는 다른 출처를 명시한 경우를 제외하고는 원칙적으로 대형네트웍스에 저작권이 있습니다.</p>\r\n<p>대형네트웍스 홈페이지의 자료들을 무단 복제·배포하는 경우에는 저작권법 제97 조의 5 에 의한 저작재산권침해죄에 해당하여 5 년 이하의 징역 또는 5 천만원 이하의 벌금에 처해질 수 있습니다.</p>\r\n<p>대형네트웍스 홈페이지에서 발간하는 콘텐츠로 수익을 얻거나 이에 상응하는 혜택을 누리고자 하는 경우에는 대형네트웍스의 사전에 별도의 협의를 하거나 허락을 얻어야 하며 협의 또는 허락을 얻어 자료의 내용을 게재하는 경우에도 출처가 대형네트웍스 홈페이지임을 밝혀야 합니다.</p>\r\n<p>다른 인터넷 사이트상의 화면에서 대형네트웍스 홈페이지의 메인화면으로 링크시키는 것은 허용되지만 세부화면(서브도메인)으로 링크시키는 것은 허용되지 않습니다. 또한 메인페이지로의 링크 시에도 링크 사실을 본 시스템 관리자에 통지하여야 합니다.</p>\r\n<p>대형네트웍스 홈페이지의 자료를 적법한 절차에 따라 다른 인터넷사이트에서 게재하는 경우에도 단순한 오류 정정 이외의 내용의 무단변경을 금지하며, 이를 위반할 때에는 형사처벌을 받을 수 있습니다.</p>\r\n<p>※ 문의 : 1800-7540, 담당자 : 송종근</p>\r\n','','theme/basic','theme/basic',0,0,'',''),('email',1,'이메일무단수집거부','<h1>이메일무단수집거부</h1>\r\n<p>대형네트웍스는 정보통신망법 제50조의 2, 제50조의 7 등에 의거하여,<br>\r\n대형네트웍스가 운영,관리하는 웹페이지 상에서 이메일 주소 수집 프로그램이나 그 밖의 기술적 장치 등을 이용하여<br>\r\n이메일 주소를 무단으로 수집하는 행위를 거부합니다.<br>\r\n이를 위반 시 정보통신망법에 의해 형사 처벌됨을 유념하시기 바랍니다.</p>','','theme/basic','theme/basic',0,0,'',''),('viewer',1,'뷰어다운로드','<h1>뷰어다운로드</h1>\r\n<div class=\"viewer1\">\r\n<dl>\r\n 	<dt>한글뷰어</dt>\r\n 	<dd class=\"pic\"><img alt=\"한글뷰어\" src=\"/shop/img/viewer_img1.gif\"></dd>\r\n 	<dd>한글뷰어는 한글문서(*.hwp)의 내용을 확인하거나 인쇄할 수 있습니다.</dd>\r\n 	<dd><a title=\"한글뷰어\" class=\"btn-normal downbtn\" href=\"http://www.hancom.com/downLoad.downPU.do?mcd=002\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>아크로벳리더</dt>\r\n 	<dd class=\"pic\"><img alt=\"아크로벳리더\" src=\"/shop/img/viewer_img2.gif\"></dd>\r\n 	<dd>아크로벳리더는 PDF문서(*.pdf)의 내용을 보고 인쇄할 수 있습니다.</dd>\r\n 	<dd><a title=\"아크로벳리더\" class=\"btn-normal downbtn\" href=\"http://get.adobe.com/kr/reader/\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>엑셀뷰어</dt>\r\n 	<dd class=\"pic\"><img alt=\"엑셀뷰어\" src=\"/shop/img/viewer_img3.gif\"></dd>\r\n 	<dd>엑셀뷰어는 MS-Excel의 내용을 보고 인쇄할 수 있습니다.</dd>\r\n 	<dd><a title=\"프리젠테이션\" class=\"btn-normal downbtn\" href=\"http://www.microsoft.com/ko-kr/download/details.aspx?id=10\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>프리젠테이션</dt>\r\n 	<dd class=\"pic\"><img alt=\"파워포인트뷰어\" src=\"/shop/img/viewer_img4.gif\"></dd>\r\n 	<dd>MS-PowerPoint 97/2000 문서를 보실수 있고 프리젠테이션을 할 수있습니다.</dd>\r\n 	<dd><a title=\"프리젠테이션\" class=\"btn-normal downbtn\" href=\"http://www.microsoft.com/ko-kr/download/details.aspx?id=13\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>워드뷰어</dt>\r\n 	<dd class=\"pic\"><img alt=\"워드뷰어\" src=\"/shop/img/viewer_img5.gif\"></dd>\r\n 	<dd>MS-Word 97/2000 문서를 보실수 있는 프로그램입니다.</dd>\r\n 	<dd><a title=\"워드뷰어\" class=\"btn-normal downbtn\" href=\"http://www.microsoft.com/ko-kr/download/details.aspx?id=4\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>플래시</dt>\r\n 	<dd class=\"pic\"><img alt=\"플래시\" src=\"/shop/img/viewer_img6.gif\"></dd>\r\n 	<dd>매크로미디어사의 플러그인 Flash Player의 프로그램 입니다.</dd>\r\n 	<dd><a title=\"플래시\" class=\"btn-normal downbtn\" href=\"http://get.adobe.com/kr/flashplayer\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>퀵타임</dt>\r\n 	<dd class=\"pic\"><img alt=\"퀵타임\" src=\"/shop/img/viewer_img7.gif\"></dd>\r\n 	<dd>Apple사의 QuickTime은 동영상 포맷으로 유명한 MOV 포맷을 재생해 주는 프로그램입니다.</dd>\r\n 	<dd><a title=\"퀵타임\" class=\"btn-normal downbtn\" href=\"http://www.apple.com/kr/quicktime/download/\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n<dl>\r\n 	<dt>윈도우미디어</dt>\r\n 	<dd class=\"pic\"><img alt=\"윈도우미디어\" src=\"/shop/img/viewer_img8.gif\"></dd>\r\n 	<dd>Windows Media Player 은 동영상 플레이어로 손쉬운 방법으로 PC에서 디지털 음악, 비디오, 사진 등을 즐기며 관리할 수 있게 해줍니다.</dd>\r\n 	<dd><a title=\"윈도우미디어\" class=\"btn-normal downbtn\" href=\"http://windows.microsoft.com/ko-KR/windows/products/windows-media\" target=\"_blank\">다운로드</a></dd>\r\n</dl>\r\n</div>','','theme/basic','theme/basic',0,0,'','');
/*!40000 ALTER TABLE `g5_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_faq`
--

DROP TABLE IF EXISTS `g5_faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_faq` (
  `fa_id` int(11) NOT NULL AUTO_INCREMENT,
  `fm_id` int(11) NOT NULL DEFAULT '0',
  `fa_subject` text NOT NULL,
  `fa_content` text NOT NULL,
  `fa_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fa_id`),
  KEY `fm_id` (`fm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_faq`
--

LOCK TABLES `g5_faq` WRITE;
/*!40000 ALTER TABLE `g5_faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_faq_master`
--

DROP TABLE IF EXISTS `g5_faq_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_faq_master` (
  `fm_id` int(11) NOT NULL AUTO_INCREMENT,
  `fm_subject` varchar(255) NOT NULL DEFAULT '',
  `fm_head_html` text NOT NULL,
  `fm_tail_html` text NOT NULL,
  `fm_mobile_head_html` text NOT NULL,
  `fm_mobile_tail_html` text NOT NULL,
  `fm_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_faq_master`
--

LOCK TABLES `g5_faq_master` WRITE;
/*!40000 ALTER TABLE `g5_faq_master` DISABLE KEYS */;
INSERT INTO `g5_faq_master` VALUES (1,'자주하시는 질문','<h1>FAQ</h1>','','','',0);
/*!40000 ALTER TABLE `g5_faq_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_group`
--

DROP TABLE IF EXISTS `g5_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_group` (
  `gr_id` varchar(10) NOT NULL DEFAULT '',
  `gr_subject` varchar(255) NOT NULL DEFAULT '',
  `gr_device` enum('both','pc','mobile') NOT NULL DEFAULT 'both',
  `gr_admin` varchar(255) NOT NULL DEFAULT '',
  `gr_use_access` tinyint(4) NOT NULL DEFAULT '0',
  `gr_order` int(11) NOT NULL DEFAULT '0',
  `gr_1_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_2_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_3_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_4_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_5_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_6_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_7_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_8_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_9_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_10_subj` varchar(255) NOT NULL DEFAULT '',
  `gr_1` varchar(255) NOT NULL DEFAULT '',
  `gr_2` varchar(255) NOT NULL DEFAULT '',
  `gr_3` varchar(255) NOT NULL DEFAULT '',
  `gr_4` varchar(255) NOT NULL DEFAULT '',
  `gr_5` varchar(255) NOT NULL DEFAULT '',
  `gr_6` varchar(255) NOT NULL DEFAULT '',
  `gr_7` varchar(255) NOT NULL DEFAULT '',
  `gr_8` varchar(255) NOT NULL DEFAULT '',
  `gr_9` varchar(255) NOT NULL DEFAULT '',
  `gr_10` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`gr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_group`
--

LOCK TABLES `g5_group` WRITE;
/*!40000 ALTER TABLE `g5_group` DISABLE KEYS */;
INSERT INTO `g5_group` VALUES ('shop','쇼핑몰','both','',0,0,'','','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_group_member`
--

DROP TABLE IF EXISTS `g5_group_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_group_member` (
  `gm_id` int(11) NOT NULL AUTO_INCREMENT,
  `gr_id` varchar(255) NOT NULL DEFAULT '',
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `gm_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gm_id`),
  KEY `gr_id` (`gr_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_group_member`
--

LOCK TABLES `g5_group_member` WRITE;
/*!40000 ALTER TABLE `g5_group_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_group_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_login`
--

DROP TABLE IF EXISTS `g5_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_login` (
  `lo_ip` varchar(255) NOT NULL DEFAULT '',
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `lo_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lo_location` text NOT NULL,
  `lo_url` text NOT NULL,
  PRIMARY KEY (`lo_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_login`
--

LOCK TABLES `g5_login` WRITE;
/*!40000 ALTER TABLE `g5_login` DISABLE KEYS */;
INSERT INTO `g5_login` VALUES ('112.163.89.66','','2017-11-29 10:05:46','대형네트웍스','/shop/');
/*!40000 ALTER TABLE `g5_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_mail`
--

DROP TABLE IF EXISTS `g5_mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_mail` (
  `ma_id` int(11) NOT NULL AUTO_INCREMENT,
  `ma_subject` varchar(255) NOT NULL DEFAULT '',
  `ma_content` mediumtext NOT NULL,
  `ma_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ma_ip` varchar(255) NOT NULL DEFAULT '',
  `ma_last_option` text NOT NULL,
  PRIMARY KEY (`ma_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_mail`
--

LOCK TABLES `g5_mail` WRITE;
/*!40000 ALTER TABLE `g5_mail` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_member`
--

DROP TABLE IF EXISTS `g5_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_member` (
  `mb_no` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `mb_password` varchar(255) NOT NULL DEFAULT '',
  `mb_name` varchar(255) NOT NULL DEFAULT '',
  `mb_nick` varchar(255) NOT NULL DEFAULT '',
  `mb_nick_date` date NOT NULL DEFAULT '0000-00-00',
  `mb_email` varchar(255) NOT NULL DEFAULT '',
  `mb_homepage` varchar(255) NOT NULL DEFAULT '',
  `mb_level` tinyint(4) NOT NULL DEFAULT '0',
  `mb_sex` char(1) NOT NULL DEFAULT '',
  `mb_birth` varchar(255) NOT NULL DEFAULT '',
  `mb_tel` varchar(255) NOT NULL DEFAULT '',
  `mb_hp` varchar(255) NOT NULL DEFAULT '',
  `mb_certify` varchar(20) NOT NULL DEFAULT '',
  `mb_adult` tinyint(4) NOT NULL DEFAULT '0',
  `mb_dupinfo` varchar(255) NOT NULL DEFAULT '',
  `mb_zip1` char(3) NOT NULL DEFAULT '',
  `mb_zip2` char(3) NOT NULL DEFAULT '',
  `mb_addr1` varchar(255) NOT NULL DEFAULT '',
  `mb_addr2` varchar(255) NOT NULL DEFAULT '',
  `mb_addr3` varchar(255) NOT NULL DEFAULT '',
  `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '',
  `mb_signature` text NOT NULL,
  `mb_recommend` varchar(255) NOT NULL DEFAULT '',
  `mb_point` int(11) NOT NULL DEFAULT '0',
  `mb_today_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mb_login_ip` varchar(255) NOT NULL DEFAULT '',
  `mb_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mb_ip` varchar(255) NOT NULL DEFAULT '',
  `mb_leave_date` varchar(8) NOT NULL DEFAULT '',
  `mb_intercept_date` varchar(8) NOT NULL DEFAULT '',
  `mb_email_certify` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mb_email_certify2` varchar(255) NOT NULL DEFAULT '',
  `mb_memo` text NOT NULL,
  `mb_lost_certify` varchar(255) NOT NULL,
  `mb_mailling` tinyint(4) NOT NULL DEFAULT '0',
  `mb_sms` tinyint(4) NOT NULL DEFAULT '0',
  `mb_open` tinyint(4) NOT NULL DEFAULT '0',
  `mb_open_date` date NOT NULL DEFAULT '0000-00-00',
  `mb_profile` text NOT NULL,
  `mb_memo_call` varchar(255) NOT NULL DEFAULT '',
  `mb_1` varchar(255) NOT NULL DEFAULT '',
  `mb_2` varchar(255) NOT NULL DEFAULT '',
  `mb_3` varchar(255) NOT NULL DEFAULT '',
  `mb_4` varchar(255) NOT NULL DEFAULT '',
  `mb_5` varchar(255) NOT NULL DEFAULT '',
  `mb_6` varchar(255) NOT NULL DEFAULT '',
  `mb_7` varchar(255) NOT NULL DEFAULT '',
  `mb_8` varchar(255) NOT NULL DEFAULT '',
  `mb_9` varchar(255) NOT NULL DEFAULT '',
  `mb_10` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`mb_no`),
  UNIQUE KEY `mb_id` (`mb_id`),
  KEY `mb_today_login` (`mb_today_login`),
  KEY `mb_datetime` (`mb_datetime`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_member`
--

LOCK TABLES `g5_member` WRITE;
/*!40000 ALTER TABLE `g5_member` DISABLE KEYS */;
INSERT INTO `g5_member` VALUES (1,'admin','*9E01B4E7EDADE9787F746F6C490278ED4C3BAEB2','최고관리자','최고관리자','0000-00-00','pm@webthink.co.kr','',10,'','','','','',0,'','','','','','','','','',100,'2017-11-28 14:56:46','112.163.89.66','2017-11-20 09:35:04','112.163.89.66','','','2017-11-20 09:35:04','','','',1,0,1,'0000-00-00','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_memo`
--

DROP TABLE IF EXISTS `g5_memo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_memo` (
  `me_id` int(11) NOT NULL DEFAULT '0',
  `me_recv_mb_id` varchar(20) NOT NULL DEFAULT '',
  `me_send_mb_id` varchar(20) NOT NULL DEFAULT '',
  `me_send_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `me_read_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `me_memo` text NOT NULL,
  PRIMARY KEY (`me_id`),
  KEY `me_recv_mb_id` (`me_recv_mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_memo`
--

LOCK TABLES `g5_memo` WRITE;
/*!40000 ALTER TABLE `g5_memo` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_memo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_menu`
--

DROP TABLE IF EXISTS `g5_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_menu` (
  `me_id` int(11) NOT NULL AUTO_INCREMENT,
  `me_code` varchar(255) NOT NULL DEFAULT '',
  `me_name` varchar(255) NOT NULL DEFAULT '',
  `me_link` varchar(255) NOT NULL DEFAULT '',
  `me_target` varchar(255) NOT NULL DEFAULT '',
  `me_order` int(11) NOT NULL DEFAULT '0',
  `me_use` tinyint(4) NOT NULL DEFAULT '0',
  `me_mobile_use` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`me_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_menu`
--

LOCK TABLES `g5_menu` WRITE;
/*!40000 ALTER TABLE `g5_menu` DISABLE KEYS */;
INSERT INTO `g5_menu` VALUES (9,'10','알림톡','/','self',2,1,1),(10,'20','친구톡','/','self',3,1,1),(11,'30','상담톡','/','self',4,1,1),(12,'40','SMS/LMS/MMS','/','self',5,1,1),(13,'50','상담신청','/','self',6,1,1),(14,'60','이용절차','/','self',7,1,1),(15,'70','고객센터','/','self',8,1,1),(16,'80','SHOP','/shop','self',1,1,1);
/*!40000 ALTER TABLE `g5_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_new_win`
--

DROP TABLE IF EXISTS `g5_new_win`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_new_win` (
  `nw_id` int(11) NOT NULL AUTO_INCREMENT,
  `nw_division` varchar(10) NOT NULL DEFAULT 'both',
  `nw_device` varchar(10) NOT NULL DEFAULT 'both',
  `nw_begin_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nw_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nw_disable_hours` int(11) NOT NULL DEFAULT '0',
  `nw_left` int(11) NOT NULL DEFAULT '0',
  `nw_top` int(11) NOT NULL DEFAULT '0',
  `nw_height` int(11) NOT NULL DEFAULT '0',
  `nw_width` int(11) NOT NULL DEFAULT '0',
  `nw_subject` text NOT NULL,
  `nw_content` text NOT NULL,
  `nw_content_html` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nw_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_new_win`
--

LOCK TABLES `g5_new_win` WRITE;
/*!40000 ALTER TABLE `g5_new_win` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_new_win` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_point`
--

DROP TABLE IF EXISTS `g5_point`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_point` (
  `po_id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `po_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `po_content` varchar(255) NOT NULL DEFAULT '',
  `po_point` int(11) NOT NULL DEFAULT '0',
  `po_use_point` int(11) NOT NULL DEFAULT '0',
  `po_expired` tinyint(4) NOT NULL DEFAULT '0',
  `po_expire_date` date NOT NULL DEFAULT '0000-00-00',
  `po_mb_point` int(11) NOT NULL DEFAULT '0',
  `po_rel_table` varchar(20) NOT NULL DEFAULT '',
  `po_rel_id` varchar(20) NOT NULL DEFAULT '',
  `po_rel_action` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`po_id`),
  KEY `index1` (`mb_id`,`po_rel_table`,`po_rel_id`,`po_rel_action`),
  KEY `index2` (`po_expire_date`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_point`
--

LOCK TABLES `g5_point` WRITE;
/*!40000 ALTER TABLE `g5_point` DISABLE KEYS */;
INSERT INTO `g5_point` VALUES (1,'admin','2017-11-20 09:35:15','2017-11-20 첫로그인',100,0,0,'9999-12-31',100,'@login','admin','2017-11-20');
/*!40000 ALTER TABLE `g5_point` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_poll`
--

DROP TABLE IF EXISTS `g5_poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_poll` (
  `po_id` int(11) NOT NULL AUTO_INCREMENT,
  `po_subject` varchar(255) NOT NULL DEFAULT '',
  `po_poll1` varchar(255) NOT NULL DEFAULT '',
  `po_poll2` varchar(255) NOT NULL DEFAULT '',
  `po_poll3` varchar(255) NOT NULL DEFAULT '',
  `po_poll4` varchar(255) NOT NULL DEFAULT '',
  `po_poll5` varchar(255) NOT NULL DEFAULT '',
  `po_poll6` varchar(255) NOT NULL DEFAULT '',
  `po_poll7` varchar(255) NOT NULL DEFAULT '',
  `po_poll8` varchar(255) NOT NULL DEFAULT '',
  `po_poll9` varchar(255) NOT NULL DEFAULT '',
  `po_cnt1` int(11) NOT NULL DEFAULT '0',
  `po_cnt2` int(11) NOT NULL DEFAULT '0',
  `po_cnt3` int(11) NOT NULL DEFAULT '0',
  `po_cnt4` int(11) NOT NULL DEFAULT '0',
  `po_cnt5` int(11) NOT NULL DEFAULT '0',
  `po_cnt6` int(11) NOT NULL DEFAULT '0',
  `po_cnt7` int(11) NOT NULL DEFAULT '0',
  `po_cnt8` int(11) NOT NULL DEFAULT '0',
  `po_cnt9` int(11) NOT NULL DEFAULT '0',
  `po_etc` varchar(255) NOT NULL DEFAULT '',
  `po_level` tinyint(4) NOT NULL DEFAULT '0',
  `po_point` int(11) NOT NULL DEFAULT '0',
  `po_date` date NOT NULL DEFAULT '0000-00-00',
  `po_ips` mediumtext NOT NULL,
  `mb_ids` text NOT NULL,
  PRIMARY KEY (`po_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_poll`
--

LOCK TABLES `g5_poll` WRITE;
/*!40000 ALTER TABLE `g5_poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_poll_etc`
--

DROP TABLE IF EXISTS `g5_poll_etc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_poll_etc` (
  `pc_id` int(11) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `pc_name` varchar(255) NOT NULL DEFAULT '',
  `pc_idea` varchar(255) NOT NULL DEFAULT '',
  `pc_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_poll_etc`
--

LOCK TABLES `g5_poll_etc` WRITE;
/*!40000 ALTER TABLE `g5_poll_etc` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_poll_etc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_popular`
--

DROP TABLE IF EXISTS `g5_popular`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_popular` (
  `pp_id` int(11) NOT NULL AUTO_INCREMENT,
  `pp_word` varchar(50) NOT NULL DEFAULT '',
  `pp_date` date NOT NULL DEFAULT '0000-00-00',
  `pp_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pp_id`),
  UNIQUE KEY `index1` (`pp_date`,`pp_word`,`pp_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_popular`
--

LOCK TABLES `g5_popular` WRITE;
/*!40000 ALTER TABLE `g5_popular` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_popular` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_qa_config`
--

DROP TABLE IF EXISTS `g5_qa_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_qa_config` (
  `qa_title` varchar(255) NOT NULL DEFAULT '',
  `qa_category` varchar(255) NOT NULL DEFAULT '',
  `qa_skin` varchar(255) NOT NULL DEFAULT '',
  `qa_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `qa_use_email` tinyint(4) NOT NULL DEFAULT '0',
  `qa_req_email` tinyint(4) NOT NULL DEFAULT '0',
  `qa_use_hp` tinyint(4) NOT NULL DEFAULT '0',
  `qa_req_hp` tinyint(4) NOT NULL DEFAULT '0',
  `qa_use_sms` tinyint(4) NOT NULL DEFAULT '0',
  `qa_send_number` varchar(255) NOT NULL DEFAULT '0',
  `qa_admin_hp` varchar(255) NOT NULL DEFAULT '',
  `qa_admin_email` varchar(255) NOT NULL DEFAULT '',
  `qa_use_editor` tinyint(4) NOT NULL DEFAULT '0',
  `qa_subject_len` int(11) NOT NULL DEFAULT '0',
  `qa_mobile_subject_len` int(11) NOT NULL DEFAULT '0',
  `qa_page_rows` int(11) NOT NULL DEFAULT '0',
  `qa_mobile_page_rows` int(11) NOT NULL DEFAULT '0',
  `qa_image_width` int(11) NOT NULL DEFAULT '0',
  `qa_upload_size` int(11) NOT NULL DEFAULT '0',
  `qa_insert_content` text NOT NULL,
  `qa_include_head` varchar(255) NOT NULL DEFAULT '',
  `qa_include_tail` varchar(255) NOT NULL DEFAULT '',
  `qa_content_head` text NOT NULL,
  `qa_content_tail` text NOT NULL,
  `qa_mobile_content_head` text NOT NULL,
  `qa_mobile_content_tail` text NOT NULL,
  `qa_1_subj` varchar(255) NOT NULL DEFAULT '',
  `qa_2_subj` varchar(255) NOT NULL DEFAULT '',
  `qa_3_subj` varchar(255) NOT NULL DEFAULT '',
  `qa_4_subj` varchar(255) NOT NULL DEFAULT '',
  `qa_5_subj` varchar(255) NOT NULL DEFAULT '',
  `qa_1` varchar(255) NOT NULL DEFAULT '',
  `qa_2` varchar(255) NOT NULL DEFAULT '',
  `qa_3` varchar(255) NOT NULL DEFAULT '',
  `qa_4` varchar(255) NOT NULL DEFAULT '',
  `qa_5` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_qa_config`
--

LOCK TABLES `g5_qa_config` WRITE;
/*!40000 ALTER TABLE `g5_qa_config` DISABLE KEYS */;
INSERT INTO `g5_qa_config` VALUES ('1:1문의','회원|포인트','theme/basic','theme/basic',1,0,1,0,0,'0','','',1,60,30,15,15,600,1048576,'','','','<h1>1:1문의</h1>','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_qa_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_qa_content`
--

DROP TABLE IF EXISTS `g5_qa_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_qa_content` (
  `qa_id` int(11) NOT NULL AUTO_INCREMENT,
  `qa_num` int(11) NOT NULL DEFAULT '0',
  `qa_parent` int(11) NOT NULL DEFAULT '0',
  `qa_related` int(11) NOT NULL DEFAULT '0',
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `qa_name` varchar(255) NOT NULL DEFAULT '',
  `qa_email` varchar(255) NOT NULL DEFAULT '',
  `qa_hp` varchar(255) NOT NULL DEFAULT '',
  `qa_type` tinyint(4) NOT NULL DEFAULT '0',
  `qa_category` varchar(255) NOT NULL DEFAULT '',
  `qa_email_recv` tinyint(4) NOT NULL DEFAULT '0',
  `qa_sms_recv` tinyint(4) NOT NULL DEFAULT '0',
  `qa_html` tinyint(4) NOT NULL DEFAULT '0',
  `qa_subject` varchar(255) NOT NULL DEFAULT '',
  `qa_content` text NOT NULL,
  `qa_status` tinyint(4) NOT NULL DEFAULT '0',
  `qa_file1` varchar(255) NOT NULL DEFAULT '',
  `qa_source1` varchar(255) NOT NULL DEFAULT '',
  `qa_file2` varchar(255) NOT NULL DEFAULT '',
  `qa_source2` varchar(255) NOT NULL DEFAULT '',
  `qa_ip` varchar(255) NOT NULL DEFAULT '',
  `qa_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `qa_1` varchar(255) NOT NULL DEFAULT '',
  `qa_2` varchar(255) NOT NULL DEFAULT '',
  `qa_3` varchar(255) NOT NULL DEFAULT '',
  `qa_4` varchar(255) NOT NULL DEFAULT '',
  `qa_5` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`qa_id`),
  KEY `qa_num_parent` (`qa_num`,`qa_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_qa_content`
--

LOCK TABLES `g5_qa_content` WRITE;
/*!40000 ALTER TABLE `g5_qa_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_qa_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_scrap`
--

DROP TABLE IF EXISTS `g5_scrap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_scrap` (
  `ms_id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `bo_table` varchar(20) NOT NULL DEFAULT '',
  `wr_id` varchar(15) NOT NULL DEFAULT '',
  `ms_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ms_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_scrap`
--

LOCK TABLES `g5_scrap` WRITE;
/*!40000 ALTER TABLE `g5_scrap` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_scrap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_banner`
--

DROP TABLE IF EXISTS `g5_shop_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_banner` (
  `bn_id` int(11) NOT NULL AUTO_INCREMENT,
  `bn_alt` varchar(255) NOT NULL DEFAULT '',
  `bn_url` varchar(255) NOT NULL DEFAULT '',
  `bn_device` varchar(10) NOT NULL DEFAULT '',
  `bn_position` varchar(255) NOT NULL DEFAULT '',
  `bn_border` tinyint(4) NOT NULL DEFAULT '0',
  `bn_new_win` tinyint(4) NOT NULL DEFAULT '0',
  `bn_begin_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bn_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bn_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bn_hit` int(11) NOT NULL DEFAULT '0',
  `bn_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_banner`
--

LOCK TABLES `g5_shop_banner` WRITE;
/*!40000 ALTER TABLE `g5_shop_banner` DISABLE KEYS */;
INSERT INTO `g5_shop_banner` VALUES (1,'','/','both','메인',0,0,'2017-11-21 00:00:00','2027-12-22 00:00:00','0000-00-00 00:00:00',1,1),(2,'','/','both','메인',0,0,'2017-11-21 00:00:00','2027-12-22 00:00:00','0000-00-00 00:00:00',0,2);
/*!40000 ALTER TABLE `g5_shop_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_cart`
--

DROP TABLE IF EXISTS `g5_shop_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_cart` (
  `ct_id` int(11) NOT NULL AUTO_INCREMENT,
  `od_id` bigint(20) unsigned NOT NULL,
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `it_id` varchar(20) NOT NULL DEFAULT '',
  `it_name` varchar(255) NOT NULL DEFAULT '',
  `it_sc_type` tinyint(4) NOT NULL DEFAULT '0',
  `it_sc_method` tinyint(4) NOT NULL DEFAULT '0',
  `it_sc_price` int(11) NOT NULL DEFAULT '0',
  `it_sc_minimum` int(11) NOT NULL DEFAULT '0',
  `it_sc_qty` int(11) NOT NULL DEFAULT '0',
  `ct_status` varchar(255) NOT NULL DEFAULT '',
  `ct_history` text NOT NULL,
  `ct_price` int(11) NOT NULL DEFAULT '0',
  `ct_point` int(11) NOT NULL DEFAULT '0',
  `cp_price` int(11) NOT NULL DEFAULT '0',
  `ct_point_use` tinyint(4) NOT NULL DEFAULT '0',
  `ct_stock_use` tinyint(4) NOT NULL DEFAULT '0',
  `ct_option` varchar(255) NOT NULL DEFAULT '',
  `ct_qty` int(11) NOT NULL DEFAULT '0',
  `ct_notax` tinyint(4) NOT NULL DEFAULT '0',
  `io_id` varchar(255) NOT NULL DEFAULT '',
  `io_type` tinyint(4) NOT NULL DEFAULT '0',
  `io_price` int(11) NOT NULL DEFAULT '0',
  `ct_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ct_ip` varchar(25) NOT NULL DEFAULT '',
  `ct_send_cost` tinyint(4) NOT NULL DEFAULT '0',
  `ct_direct` tinyint(4) NOT NULL DEFAULT '0',
  `ct_select` tinyint(4) NOT NULL DEFAULT '0',
  `ct_select_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ct_id`),
  KEY `od_id` (`od_id`),
  KEY `it_id` (`it_id`),
  KEY `ct_status` (`ct_status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_cart`
--

LOCK TABLES `g5_shop_cart` WRITE;
/*!40000 ALTER TABLE `g5_shop_cart` DISABLE KEYS */;
INSERT INTO `g5_shop_cart` VALUES (1,2017112017482399,'admin','1511160936','삼성전자 SL-X4220RX',1,0,50000,0,0,'주문','',1979180,0,0,0,0,'삼성전자 SL-X4220RX',1,0,'',0,0,'2017-11-20 17:48:23','112.163.89.66',2,1,1,'2017-11-20 17:48:23'),(2,2017112415301869,'','1511254288','HP OfficeJet 8620',0,0,0,0,0,'쇼핑','',364800,0,0,0,0,'HP OfficeJet 8620',1,0,'',0,0,'2017-11-24 15:30:18','211.197.42.37',0,1,0,'2017-11-24 15:30:18');
/*!40000 ALTER TABLE `g5_shop_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_category`
--

DROP TABLE IF EXISTS `g5_shop_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_category` (
  `ca_id` varchar(10) NOT NULL DEFAULT '0',
  `ca_name` varchar(255) NOT NULL DEFAULT '',
  `ca_order` int(11) NOT NULL DEFAULT '0',
  `ca_skin_dir` varchar(255) NOT NULL DEFAULT '',
  `ca_mobile_skin_dir` varchar(255) NOT NULL DEFAULT '',
  `ca_skin` varchar(255) NOT NULL DEFAULT '',
  `ca_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `ca_img_width` int(11) NOT NULL DEFAULT '0',
  `ca_img_height` int(11) NOT NULL DEFAULT '0',
  `ca_mobile_img_width` int(11) NOT NULL DEFAULT '0',
  `ca_mobile_img_height` int(11) NOT NULL DEFAULT '0',
  `ca_sell_email` varchar(255) NOT NULL DEFAULT '',
  `ca_use` tinyint(4) NOT NULL DEFAULT '0',
  `ca_stock_qty` int(11) NOT NULL DEFAULT '0',
  `ca_explan_html` tinyint(4) NOT NULL DEFAULT '0',
  `ca_head_html` text NOT NULL,
  `ca_tail_html` text NOT NULL,
  `ca_mobile_head_html` text NOT NULL,
  `ca_mobile_tail_html` text NOT NULL,
  `ca_list_mod` int(11) NOT NULL DEFAULT '0',
  `ca_list_row` int(11) NOT NULL DEFAULT '0',
  `ca_mobile_list_mod` int(11) NOT NULL DEFAULT '0',
  `ca_mobile_list_row` int(11) NOT NULL DEFAULT '0',
  `ca_include_head` varchar(255) NOT NULL DEFAULT '',
  `ca_include_tail` varchar(255) NOT NULL DEFAULT '',
  `ca_mb_id` varchar(255) NOT NULL DEFAULT '',
  `ca_cert_use` tinyint(4) NOT NULL DEFAULT '0',
  `ca_adult_use` tinyint(4) NOT NULL DEFAULT '0',
  `ca_nocoupon` tinyint(4) NOT NULL DEFAULT '0',
  `ca_1_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_2_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_3_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_4_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_5_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_6_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_7_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_8_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_9_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_10_subj` varchar(255) NOT NULL DEFAULT '',
  `ca_1` varchar(255) NOT NULL DEFAULT '',
  `ca_2` varchar(255) NOT NULL DEFAULT '',
  `ca_3` varchar(255) NOT NULL DEFAULT '',
  `ca_4` varchar(255) NOT NULL DEFAULT '',
  `ca_5` varchar(255) NOT NULL DEFAULT '',
  `ca_6` varchar(255) NOT NULL DEFAULT '',
  `ca_7` varchar(255) NOT NULL DEFAULT '',
  `ca_8` varchar(255) NOT NULL DEFAULT '',
  `ca_9` varchar(255) NOT NULL DEFAULT '',
  `ca_10` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ca_id`),
  KEY `ca_order` (`ca_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_category`
--

LOCK TABLES `g5_shop_category` WRITE;
/*!40000 ALTER TABLE `g5_shop_category` DISABLE KEYS */;
INSERT INTO `g5_shop_category` VALUES ('10','소형복합기',0,'','','list.10.skin.php','list.10.skin.php',340,340,340,340,'',1,99999,1,'','','','',3,5,2,5,'','','',0,0,0,'','','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_shop_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_coupon`
--

DROP TABLE IF EXISTS `g5_shop_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_coupon` (
  `cp_no` int(11) NOT NULL AUTO_INCREMENT,
  `cp_id` varchar(255) NOT NULL DEFAULT '',
  `cp_subject` varchar(255) NOT NULL DEFAULT '',
  `cp_method` tinyint(4) NOT NULL DEFAULT '0',
  `cp_target` varchar(255) NOT NULL DEFAULT '',
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `cz_id` int(11) NOT NULL DEFAULT '0',
  `cp_start` date NOT NULL DEFAULT '0000-00-00',
  `cp_end` date NOT NULL DEFAULT '0000-00-00',
  `cp_price` int(11) NOT NULL DEFAULT '0',
  `cp_type` tinyint(4) NOT NULL DEFAULT '0',
  `cp_trunc` int(11) NOT NULL DEFAULT '0',
  `cp_minimum` int(11) NOT NULL DEFAULT '0',
  `cp_maximum` int(11) NOT NULL DEFAULT '0',
  `od_id` bigint(20) unsigned NOT NULL,
  `cp_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cp_no`),
  UNIQUE KEY `cp_id` (`cp_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_coupon`
--

LOCK TABLES `g5_shop_coupon` WRITE;
/*!40000 ALTER TABLE `g5_shop_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_coupon_log`
--

DROP TABLE IF EXISTS `g5_shop_coupon_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_coupon_log` (
  `cl_id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_id` varchar(255) NOT NULL DEFAULT '',
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `od_id` bigint(20) NOT NULL,
  `cp_price` int(11) NOT NULL DEFAULT '0',
  `cl_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cl_id`),
  KEY `mb_id` (`mb_id`),
  KEY `od_id` (`od_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_coupon_log`
--

LOCK TABLES `g5_shop_coupon_log` WRITE;
/*!40000 ALTER TABLE `g5_shop_coupon_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_coupon_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_coupon_zone`
--

DROP TABLE IF EXISTS `g5_shop_coupon_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_coupon_zone` (
  `cz_id` int(11) NOT NULL AUTO_INCREMENT,
  `cz_type` tinyint(4) NOT NULL DEFAULT '0',
  `cz_subject` varchar(255) NOT NULL DEFAULT '',
  `cz_start` date NOT NULL DEFAULT '0000-00-00',
  `cz_end` date NOT NULL DEFAULT '0000-00-00',
  `cz_file` varchar(255) NOT NULL DEFAULT '',
  `cz_period` int(11) NOT NULL DEFAULT '0',
  `cz_point` int(11) NOT NULL DEFAULT '0',
  `cp_method` tinyint(4) NOT NULL DEFAULT '0',
  `cp_target` varchar(255) NOT NULL DEFAULT '',
  `cp_price` int(11) NOT NULL DEFAULT '0',
  `cp_type` tinyint(4) NOT NULL DEFAULT '0',
  `cp_trunc` int(11) NOT NULL DEFAULT '0',
  `cp_minimum` int(11) NOT NULL DEFAULT '0',
  `cp_maximum` int(11) NOT NULL DEFAULT '0',
  `cz_download` int(11) NOT NULL DEFAULT '0',
  `cz_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cz_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_coupon_zone`
--

LOCK TABLES `g5_shop_coupon_zone` WRITE;
/*!40000 ALTER TABLE `g5_shop_coupon_zone` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_coupon_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_default`
--

DROP TABLE IF EXISTS `g5_shop_default`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_default` (
  `de_admin_company_owner` varchar(255) NOT NULL DEFAULT '',
  `de_admin_company_name` varchar(255) NOT NULL DEFAULT '',
  `de_admin_company_saupja_no` varchar(255) NOT NULL DEFAULT '',
  `de_admin_company_tel` varchar(255) NOT NULL DEFAULT '',
  `de_admin_company_fax` varchar(255) NOT NULL DEFAULT '',
  `de_admin_tongsin_no` varchar(255) NOT NULL DEFAULT '',
  `de_admin_company_zip` varchar(255) NOT NULL DEFAULT '',
  `de_admin_company_addr` varchar(255) NOT NULL DEFAULT '',
  `de_admin_info_name` varchar(255) NOT NULL DEFAULT '',
  `de_admin_info_email` varchar(255) NOT NULL DEFAULT '',
  `de_shop_skin` varchar(255) NOT NULL DEFAULT '',
  `de_shop_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `de_type1_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_type1_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_type1_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_type1_list_row` int(11) NOT NULL DEFAULT '0',
  `de_type1_img_width` int(11) NOT NULL DEFAULT '0',
  `de_type1_img_height` int(11) NOT NULL DEFAULT '0',
  `de_type2_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_type2_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_type2_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_type2_list_row` int(11) NOT NULL DEFAULT '0',
  `de_type2_img_width` int(11) NOT NULL DEFAULT '0',
  `de_type2_img_height` int(11) NOT NULL DEFAULT '0',
  `de_type3_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_type3_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_type3_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_type3_list_row` int(11) NOT NULL DEFAULT '0',
  `de_type3_img_width` int(11) NOT NULL DEFAULT '0',
  `de_type3_img_height` int(11) NOT NULL DEFAULT '0',
  `de_type4_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_type4_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_type4_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_type4_list_row` int(11) NOT NULL DEFAULT '0',
  `de_type4_img_width` int(11) NOT NULL DEFAULT '0',
  `de_type4_img_height` int(11) NOT NULL DEFAULT '0',
  `de_type5_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_type5_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_type5_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_type5_list_row` int(11) NOT NULL DEFAULT '0',
  `de_type5_img_width` int(11) NOT NULL DEFAULT '0',
  `de_type5_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type1_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_mobile_type1_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_type1_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type1_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type1_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type1_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type2_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_mobile_type2_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_type2_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type2_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type2_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type2_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type3_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_mobile_type3_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_type3_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type3_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type3_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type3_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type4_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_mobile_type4_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_type4_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type4_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type4_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type4_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type5_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_mobile_type5_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_type5_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type5_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type5_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_type5_img_height` int(11) NOT NULL DEFAULT '0',
  `de_rel_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_rel_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_rel_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_rel_img_width` int(11) NOT NULL DEFAULT '0',
  `de_rel_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_rel_list_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_mobile_rel_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_rel_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_rel_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_rel_img_height` int(11) NOT NULL DEFAULT '0',
  `de_search_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_search_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_search_list_row` int(11) NOT NULL DEFAULT '0',
  `de_search_img_width` int(11) NOT NULL DEFAULT '0',
  `de_search_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_search_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_search_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_search_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_search_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_search_img_height` int(11) NOT NULL DEFAULT '0',
  `de_listtype_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_listtype_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_listtype_list_row` int(11) NOT NULL DEFAULT '0',
  `de_listtype_img_width` int(11) NOT NULL DEFAULT '0',
  `de_listtype_img_height` int(11) NOT NULL DEFAULT '0',
  `de_mobile_listtype_list_skin` varchar(255) NOT NULL DEFAULT '',
  `de_mobile_listtype_list_mod` int(11) NOT NULL DEFAULT '0',
  `de_mobile_listtype_list_row` int(11) NOT NULL DEFAULT '0',
  `de_mobile_listtype_img_width` int(11) NOT NULL DEFAULT '0',
  `de_mobile_listtype_img_height` int(11) NOT NULL DEFAULT '0',
  `de_bank_use` int(11) NOT NULL DEFAULT '0',
  `de_bank_account` text NOT NULL,
  `de_card_test` int(11) NOT NULL DEFAULT '0',
  `de_card_use` int(11) NOT NULL DEFAULT '0',
  `de_card_noint_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_card_point` int(11) NOT NULL DEFAULT '0',
  `de_settle_min_point` int(11) NOT NULL DEFAULT '0',
  `de_settle_max_point` int(11) NOT NULL DEFAULT '0',
  `de_settle_point_unit` int(11) NOT NULL DEFAULT '0',
  `de_level_sell` int(11) NOT NULL DEFAULT '0',
  `de_delivery_company` varchar(255) NOT NULL DEFAULT '',
  `de_send_cost_case` varchar(255) NOT NULL DEFAULT '',
  `de_send_cost_limit` varchar(255) NOT NULL DEFAULT '',
  `de_send_cost_list` varchar(255) NOT NULL DEFAULT '',
  `de_hope_date_use` int(11) NOT NULL DEFAULT '0',
  `de_hope_date_after` int(11) NOT NULL DEFAULT '0',
  `de_baesong_content` text NOT NULL,
  `de_change_content` text NOT NULL,
  `de_point_days` int(11) NOT NULL DEFAULT '0',
  `de_simg_width` int(11) NOT NULL DEFAULT '0',
  `de_simg_height` int(11) NOT NULL DEFAULT '0',
  `de_mimg_width` int(11) NOT NULL DEFAULT '0',
  `de_mimg_height` int(11) NOT NULL DEFAULT '0',
  `de_sms_cont1` varchar(255) NOT NULL DEFAULT '',
  `de_sms_cont2` varchar(255) NOT NULL DEFAULT '',
  `de_sms_cont3` varchar(255) NOT NULL DEFAULT '',
  `de_sms_cont4` varchar(255) NOT NULL DEFAULT '',
  `de_sms_cont5` varchar(255) NOT NULL DEFAULT '',
  `de_sms_use1` tinyint(4) NOT NULL DEFAULT '0',
  `de_sms_use2` tinyint(4) NOT NULL DEFAULT '0',
  `de_sms_use3` tinyint(4) NOT NULL DEFAULT '0',
  `de_sms_use4` tinyint(4) NOT NULL DEFAULT '0',
  `de_sms_use5` tinyint(4) NOT NULL DEFAULT '0',
  `de_sms_hp` varchar(255) NOT NULL DEFAULT '',
  `de_pg_service` varchar(255) NOT NULL DEFAULT '',
  `de_kcp_mid` varchar(255) NOT NULL DEFAULT '',
  `de_kcp_site_key` varchar(255) NOT NULL DEFAULT '',
  `de_inicis_mid` varchar(255) NOT NULL DEFAULT '',
  `de_inicis_admin_key` varchar(255) NOT NULL DEFAULT '',
  `de_inicis_sign_key` varchar(255) NOT NULL DEFAULT '',
  `de_iche_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_easy_pay_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_samsung_pay_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_inicis_cartpoint_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_item_use_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_item_use_write` tinyint(4) NOT NULL DEFAULT '0',
  `de_code_dup_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_cart_keep_term` int(11) NOT NULL DEFAULT '0',
  `de_guest_cart_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_admin_buga_no` varchar(255) NOT NULL DEFAULT '',
  `de_vbank_use` varchar(255) NOT NULL DEFAULT '',
  `de_taxsave_use` tinyint(4) NOT NULL,
  `de_guest_privacy` text NOT NULL,
  `de_hp_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_escrow_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_tax_flag_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_kakaopay_mid` varchar(255) NOT NULL DEFAULT '',
  `de_kakaopay_key` varchar(255) NOT NULL DEFAULT '',
  `de_kakaopay_enckey` varchar(255) NOT NULL DEFAULT '',
  `de_kakaopay_hashkey` varchar(255) NOT NULL DEFAULT '',
  `de_kakaopay_cancelpwd` varchar(255) NOT NULL DEFAULT '',
  `de_naverpay_mid` varchar(255) NOT NULL DEFAULT '',
  `de_naverpay_cert_key` varchar(255) NOT NULL DEFAULT '',
  `de_naverpay_button_key` varchar(255) NOT NULL DEFAULT '',
  `de_naverpay_test` tinyint(4) NOT NULL DEFAULT '0',
  `de_naverpay_mb_id` varchar(255) NOT NULL DEFAULT '',
  `de_naverpay_sendcost` varchar(255) NOT NULL DEFAULT '',
  `de_member_reg_coupon_use` tinyint(4) NOT NULL DEFAULT '0',
  `de_member_reg_coupon_term` int(11) NOT NULL DEFAULT '0',
  `de_member_reg_coupon_price` int(11) NOT NULL DEFAULT '0',
  `de_member_reg_coupon_minimum` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_default`
--

LOCK TABLES `g5_shop_default` WRITE;
/*!40000 ALTER TABLE `g5_shop_default` DISABLE KEYS */;
INSERT INTO `g5_shop_default` VALUES ('송종근','대형네트웍스','608-14-76994','1800-7540','0505-299-0001','제 2016-창원의창-00189호','51386','경상남도 창원시 의창구 원이대로240번길 31 KT 빌딩','송종근','allimtalk@gmail.com','theme/basic','theme/basic',1,'main.10.skin.php',3,10,340,340,1,'main.10.skin.php',3,1,340,340,1,'main.10.skin.php',3,1,340,340,1,'main.10.skin.php',3,1,340,340,1,'main.10.skin.php',3,1,340,340,1,'main.10.skin.php',2,10,340,340,1,'main.10.skin.php',2,2,340,340,1,'main.10.skin.php',2,2,340,340,1,'main.10.skin.php',2,2,340,340,1,'main.10.skin.php',2,2,340,340,0,'relation.10.skin.php',3,230,230,0,'relation.10.skin.php',3,230,230,'list.10.skin.php',3,5,230,230,'list.10.skin.php',3,5,230,230,'list.10.skin.php',3,5,230,230,'list.10.skin.php',3,5,230,230,1,'OO은행 12345-67-89012 예금주명',1,0,0,0,5000,50000,100,1,'KGB택배','차등','50000','3000',0,3,'배송 안내 입력전입니다.','<p>※ 상품 설명에 배송/반품/교환 관련한 안내가 있는 경우 그 내용을 우선으로 합니다.</p>\r\n<table class=\"__se_tbl_ext tbl_head03\" summary=\"교환/반품/품절안내 상세테이블로 목록으로 반품/교환방법, 반품/교환가능 기간, 반품/교환비용, 반품/교환 불가 사유, 상품 품절, 소비자 피해보상 환불지연에 따른 배상\">\r\n	<caption>상품 배송/반품/교환 상세정보</caption>\r\n	<colgroup>\r\n		<col width=\"20%\">\r\n		<col width=\"auto\">\r\n	</colgroup>\r\n	<tbody>\r\n		<tr>\r\n			<th>\r\n				<strong>배송안내</strong>\r\n				<p>지역, 날씨 상황에 따라<br>달라질 수 있습니다.</p>\r\n			</th>\r\n			<td>\r\n				<p>상품 배송비는 조건부 무료(5만원이상 구매시 무료)입니다. </p>\r\n				<p>묶음배송가능</p>\r\n				<p>판매자의 사정으로 배송이 지연될 수 있습니다.</p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<th>\r\n				<strong>반품/교환 안내</strong>\r\n				<p>반품/교환에 관한 일반적인<br>사항은 판매자 제시사항보다<br>관계법령이 우선합니다.<a href=\"#\" target=\"_blank\">자세히 보기</a></p>\r\n			</th>\r\n			<td>\r\n				<ul>\r\n					<li>판매자 반품 택배사 : KG로지스택배</li>\r\n					<li>반품/교환 배송비(편도) : 3,000원</li>\r\n					<li>보내실 곳 : 주소.&nbsp;경상남도 창원시 의창구 원이대로240번길 31 KT 빌딩 , Tel. 1800-7540</li>\r\n					<li>연락처 : 1800-7540</li>\r\n				</ul>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<th rowspan=\"2\">\r\n				<strong>반품/교환 가능기간</strong>\r\n				<p>먼저 판매자와 연락하여<br>반품사유, 택배사, 배송비,<br>반품주소 등을 협의 후에<br>상품을 발송해 주십시오.</p>\r\n			</th>\r\n			<td>\r\n				<ul>\r\n					<li>구매자 단순 변심 : 상품 수령일로부터 7일 이내 (배송비 6,000원 : 구매자 부담)</li>\r\n					<li>표시/광고와 상이, 상품 하자 : 상품 수령 후 3개월 이내 및 표시/광고와 다른 사실을 안 날 또는 알 수 있었던 날부터 30일 이내<br>(배송비 : 판매자 부담)</li>\r\n				</ul>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<p>소비자가 전자상거래 등에서의 소비자 보호에 관한 법률 (이하 전상법) 제 17조 제 1항 또는 제 3항에 따라 청약철회를 한 후<br>그 상품을 판매자에게 반환하였음에도 불구하고 정당한 사유 없이 현금 결제대금의 환급이 3영업일을 넘게 지연된 경우, 소비자는<br>전상법 제 18조 제 2항 및 동법 시행령 제 21조의 2에 따라 지연일수에 대하여 연 20% (일할 계산)의 지연배상금을 신청할 수 있습니다.</p>\r\n			</td>					\r\n		</tr>\r\n		<tr>\r\n			<th>\r\n				<strong>반품/교환 불가사유</strong>\r\n				<p>이 경우에는 반품/교환이 불가능합니다.</p>\r\n			</th>\r\n			<td>\r\n				<ul>\r\n					<li>반품 요청 가능 기간이 지난 경우</li>\r\n					<li>구매자 책임 사유로 상품 등이 멸실 또는 훼손된 경우 (단, 상품 내용 확인을 위해 포장 등을 훼손한 경우는 제외)</li>\r\n					<li>포장을 개봉하였으나, 포장이 훼손되어 상품가치가 현저히 상실된 경우</li>\r\n					<li>구매자의 사용 또는 일부 소비에 의하여 상품가치가 현저히 감소한 경우</li>\r\n					<li>시간의 경과에 의해 재판매가 곤란할 정도로 상품 등의 가치가 현저히 감소한 경우</li>\r\n					<li>복제가 가능한 상품 등의 포장을 훼손한 경우(도서의 경우 포장 개봉 시)</li>\r\n				</ul>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>',7,230,230,500,500,'{이름}님의 회원가입을 축하드립니다.\r\nID:{회원아이디}\r\n{회사명}','{이름}님 주문해주셔서 고맙습니다.\r\n{주문번호}\r\n{주문금액}원\r\n{회사명}','{이름}님께서 주문하셨습니다.\r\n{주문번호}\r\n{주문금액}원\r\n{회사명}','{이름}님 입금 감사합니다.\r\n{입금액}원\r\n주문번호:\r\n{주문번호}\r\n{회사명}','{이름}님 배송합니다.\r\n택배:{택배회사}\r\n운송장번호:\r\n{운송장번호}\r\n{회사명}',0,0,0,0,0,'','kcp','','','','','',0,0,0,0,1,0,1,15,0,'','0',0,'',0,0,0,'','','','','','','','',0,'','',0,0,0,0);
/*!40000 ALTER TABLE `g5_shop_default` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_event`
--

DROP TABLE IF EXISTS `g5_shop_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_event` (
  `ev_id` int(11) NOT NULL AUTO_INCREMENT,
  `ev_skin` varchar(255) NOT NULL DEFAULT '',
  `ev_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `ev_img_width` int(11) NOT NULL DEFAULT '0',
  `ev_img_height` int(11) NOT NULL DEFAULT '0',
  `ev_list_mod` int(11) NOT NULL DEFAULT '0',
  `ev_list_row` int(11) NOT NULL DEFAULT '0',
  `ev_mobile_img_width` int(11) NOT NULL DEFAULT '0',
  `ev_mobile_img_height` int(11) NOT NULL DEFAULT '0',
  `ev_mobile_list_mod` int(11) NOT NULL DEFAULT '0',
  `ev_mobile_list_row` int(11) NOT NULL DEFAULT '0',
  `ev_subject` varchar(255) NOT NULL DEFAULT '',
  `ev_subject_strong` tinyint(4) NOT NULL DEFAULT '0',
  `ev_head_html` text NOT NULL,
  `ev_tail_html` text NOT NULL,
  `ev_use` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ev_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_event`
--

LOCK TABLES `g5_shop_event` WRITE;
/*!40000 ALTER TABLE `g5_shop_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_event_item`
--

DROP TABLE IF EXISTS `g5_shop_event_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_event_item` (
  `ev_id` int(11) NOT NULL DEFAULT '0',
  `it_id` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ev_id`,`it_id`),
  KEY `it_id` (`it_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_event_item`
--

LOCK TABLES `g5_shop_event_item` WRITE;
/*!40000 ALTER TABLE `g5_shop_event_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_event_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_inicis_log`
--

DROP TABLE IF EXISTS `g5_shop_inicis_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_inicis_log` (
  `oid` bigint(20) unsigned NOT NULL,
  `P_TID` varchar(255) NOT NULL DEFAULT '',
  `P_MID` varchar(255) NOT NULL DEFAULT '',
  `P_AUTH_DT` varchar(255) NOT NULL DEFAULT '',
  `P_STATUS` varchar(255) NOT NULL DEFAULT '',
  `P_TYPE` varchar(255) NOT NULL DEFAULT '',
  `P_OID` varchar(255) NOT NULL DEFAULT '',
  `P_FN_NM` varchar(255) NOT NULL DEFAULT '',
  `P_AUTH_NO` varchar(255) NOT NULL DEFAULT '',
  `P_AMT` int(11) NOT NULL DEFAULT '0',
  `P_RMESG1` varchar(255) NOT NULL DEFAULT '',
  `post_data` text NOT NULL,
  `is_mail_send` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_inicis_log`
--

LOCK TABLES `g5_shop_inicis_log` WRITE;
/*!40000 ALTER TABLE `g5_shop_inicis_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_inicis_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_item`
--

DROP TABLE IF EXISTS `g5_shop_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_item` (
  `it_id` varchar(20) NOT NULL DEFAULT '',
  `ca_id` varchar(10) NOT NULL DEFAULT '0',
  `ca_id2` varchar(255) NOT NULL DEFAULT '',
  `ca_id3` varchar(255) NOT NULL DEFAULT '',
  `it_skin` varchar(255) NOT NULL DEFAULT '',
  `it_mobile_skin` varchar(255) NOT NULL DEFAULT '',
  `it_name` varchar(255) NOT NULL DEFAULT '',
  `it_maker` varchar(255) NOT NULL DEFAULT '',
  `it_origin` varchar(255) NOT NULL DEFAULT '',
  `it_brand` varchar(255) NOT NULL DEFAULT '',
  `it_model` varchar(255) NOT NULL DEFAULT '',
  `it_option_subject` varchar(255) NOT NULL DEFAULT '',
  `it_supply_subject` varchar(255) NOT NULL DEFAULT '',
  `it_type1` tinyint(4) NOT NULL DEFAULT '0',
  `it_type2` tinyint(4) NOT NULL DEFAULT '0',
  `it_type3` tinyint(4) NOT NULL DEFAULT '0',
  `it_type4` tinyint(4) NOT NULL DEFAULT '0',
  `it_type5` tinyint(4) NOT NULL DEFAULT '0',
  `it_basic` text NOT NULL,
  `it_explan` mediumtext NOT NULL,
  `it_explan2` mediumtext NOT NULL,
  `it_mobile_explan` mediumtext NOT NULL,
  `it_cust_price` int(11) NOT NULL DEFAULT '0',
  `it_price` int(11) NOT NULL DEFAULT '0',
  `it_point` int(11) NOT NULL DEFAULT '0',
  `it_point_type` tinyint(4) NOT NULL DEFAULT '0',
  `it_supply_point` int(11) NOT NULL DEFAULT '0',
  `it_notax` tinyint(4) NOT NULL DEFAULT '0',
  `it_sell_email` varchar(255) NOT NULL DEFAULT '',
  `it_use` tinyint(4) NOT NULL DEFAULT '0',
  `it_nocoupon` tinyint(4) NOT NULL DEFAULT '0',
  `it_soldout` tinyint(4) NOT NULL DEFAULT '0',
  `it_stock_qty` int(11) NOT NULL DEFAULT '0',
  `it_stock_sms` tinyint(4) NOT NULL DEFAULT '0',
  `it_noti_qty` int(11) NOT NULL DEFAULT '0',
  `it_sc_type` tinyint(4) NOT NULL DEFAULT '0',
  `it_sc_method` tinyint(4) NOT NULL DEFAULT '0',
  `it_sc_price` int(11) NOT NULL DEFAULT '0',
  `it_sc_minimum` int(11) NOT NULL DEFAULT '0',
  `it_sc_qty` int(11) NOT NULL DEFAULT '0',
  `it_buy_min_qty` int(11) NOT NULL DEFAULT '0',
  `it_buy_max_qty` int(11) NOT NULL DEFAULT '0',
  `it_head_html` text NOT NULL,
  `it_tail_html` text NOT NULL,
  `it_mobile_head_html` text NOT NULL,
  `it_mobile_tail_html` text NOT NULL,
  `it_hit` int(11) NOT NULL DEFAULT '0',
  `it_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `it_update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `it_ip` varchar(25) NOT NULL DEFAULT '',
  `it_order` int(11) NOT NULL DEFAULT '0',
  `it_tel_inq` tinyint(4) NOT NULL DEFAULT '0',
  `it_info_gubun` varchar(50) NOT NULL DEFAULT '',
  `it_info_value` text NOT NULL,
  `it_sum_qty` int(11) NOT NULL DEFAULT '0',
  `it_use_cnt` int(11) NOT NULL DEFAULT '0',
  `it_use_avg` decimal(2,1) NOT NULL,
  `it_shop_memo` text NOT NULL,
  `ec_mall_pid` varchar(255) NOT NULL DEFAULT '',
  `it_img1` varchar(255) NOT NULL DEFAULT '',
  `it_img2` varchar(255) NOT NULL DEFAULT '',
  `it_img3` varchar(255) NOT NULL DEFAULT '',
  `it_img4` varchar(255) NOT NULL DEFAULT '',
  `it_img5` varchar(255) NOT NULL DEFAULT '',
  `it_img6` varchar(255) NOT NULL DEFAULT '',
  `it_img7` varchar(255) NOT NULL DEFAULT '',
  `it_img8` varchar(255) NOT NULL DEFAULT '',
  `it_img9` varchar(255) NOT NULL DEFAULT '',
  `it_img10` varchar(255) NOT NULL DEFAULT '',
  `it_1_subj` varchar(255) NOT NULL DEFAULT '',
  `it_2_subj` varchar(255) NOT NULL DEFAULT '',
  `it_3_subj` varchar(255) NOT NULL DEFAULT '',
  `it_4_subj` varchar(255) NOT NULL DEFAULT '',
  `it_5_subj` varchar(255) NOT NULL DEFAULT '',
  `it_6_subj` varchar(255) NOT NULL DEFAULT '',
  `it_7_subj` varchar(255) NOT NULL DEFAULT '',
  `it_8_subj` varchar(255) NOT NULL DEFAULT '',
  `it_9_subj` varchar(255) NOT NULL DEFAULT '',
  `it_10_subj` varchar(255) NOT NULL DEFAULT '',
  `it_1` varchar(255) NOT NULL DEFAULT '',
  `it_2` varchar(255) NOT NULL DEFAULT '',
  `it_3` varchar(255) NOT NULL DEFAULT '',
  `it_4` varchar(255) NOT NULL DEFAULT '',
  `it_5` varchar(255) NOT NULL DEFAULT '',
  `it_6` varchar(255) NOT NULL DEFAULT '',
  `it_7` varchar(255) NOT NULL DEFAULT '',
  `it_8` varchar(255) NOT NULL DEFAULT '',
  `it_9` varchar(255) NOT NULL DEFAULT '',
  `it_10` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`it_id`),
  KEY `ca_id` (`ca_id`),
  KEY `it_name` (`it_name`),
  KEY `it_order` (`it_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_item`
--

LOCK TABLES `g5_shop_item` WRITE;
/*!40000 ALTER TABLE `g5_shop_item` DISABLE KEYS */;
INSERT INTO `g5_shop_item` VALUES ('1511160936','10','','','theme/basic','theme/basic','삼성전자 SL-X4220RX','삼성전자','중국산(삼성전자)','삼성전자','SL-X4220RX','','',1,0,0,0,0,'복사/스캔/프린터/양면인쇄/네트워크/컬러/A3/22매/빠른설치','<div class=\"product-w-inner\">\r\n<h2 class=\"sec-title\">\r\n<span class=\"tit-feature\">특장점</span>\r\n</h2><div class=\"featurebox\"><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511162913_7897.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511162913_7897.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">안드로이드 기반의 직관적인 UI</div>\r\n<div class=\"des\">25.6cm 컬러 터치 패널(960 × 600 해상도)을 통해 원하는 메뉴를 터치하면 손쉽게 기능이 실행됩니다.<br>스마트폰 UI 그대로 페이지를 옆으로 넘기거나 아래로 내려보는 스크롤이 가능하여 사용이 편리합니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511162960_943.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511162960_943.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">다양한 기업용 솔루션 선택 적용</div>\r\n<div class=\"des\">BCPS는 비즈니스 코어 프린팅 솔루션(Business Core Printing Solution)의 약자이자 기업용 프린팅 솔루션으로 보안, 클라우드, 문서관리, 스캔, 모니터링 등 5가지 솔루션으로 구성되어 있습니다.</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163003_6316.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163003_6316.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">능동형 NFC 보안성 및 관리</div>\r\n<div class=\"des\">스마트기기를 활용하여 모바일 ID 인증, 모바일 폰 등록으로 보안성을 향상시킵니다.<br>탭을 이용한 장비관리로 NFC 장비관리, 무선설정을 할 수 있습니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163028_9968.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163028_9968.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">1 GHz 듀얼 CPU 탑재로 1.5배 빠른 출력속도 제공</div>\r\n<div class=\"des\">1GHz 듀얼코어 프로세서 탑재로 인쇄 및 스캔 속도가 향상되어 더욱 빠르고 효율적인 업무 활동이 가능합니다.<br>대용량의 출력/복사/스캔 작업 속도가 보다 빨라집니다.</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163041_5842.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163041_5842.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">개방형 플랫폼 커스터마이징(XOA)</div>\r\n<div class=\"des\">맞춤형 응용 프로그램만 수정이나 개발이 허용되었던 기존 제품들의 제한된 개발형 구조를 뛰어넘어 XOA는 기본 응용 프로그램의 구성과 형식까지 편의에 따라 수정과 개발이 가능한 개방된 환경을 제공합니다.<br>Job accounting과 같은 문서관리 솔루션 외 모바일 프린팅, 보안, Form 프린팅 및 UI 커스터마이징 등의 구현 환경을 제공합니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163054_9316.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163054_9316.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">다양한 옵션 장착</div>\r\n<div class=\"des\">다양한 옵션 장착을 제공합니다.</div>\r\n</div>\r\n</div>\r\n</div></div></div><div class=\"more-features\"><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">쉽고 빠른 설정 및 활용</div>\r\n<div class=\"desc active black m-black\">사용자별 UI 개인 설정을 통해 사용자 프로파일 이미지, 언어, 키보드, 배경화면, 홈 스크린 기본 앱 등 쉽고 편리하고 빠른 사용이 가능합니다.<br>개인 모바일 디바이스와 같은 스마트폰을 활용한 컨트롤도 가능합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163068_2475.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163068_2475.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">향상된 듀얼 스캔</div>\r\n<div class=\"desc active black m-black\">MX4 시리즈는 듀얼 스캔으로 빠른 스캔 속도(80/120ipm)을 구현하였으며, 새로운 디자인의 DSDF &amp; RADF를 통해 빠르고 고장 없는 스캔을 위한 기구 구조물을 보강하였습니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163082_7693.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163082_7693.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">깔끔하고 선명한 문서작업 (ReCP 기술)</div>\r\n<div class=\"desc active black m-black\">삼성의 독자적인 ReCP(Rendering Engine for Clean Page) 기술로 출력 품질과 선명도가 탁월하게 향상되었습니다.<br>이미지와 텍스트 모두 자동으로 포커스를 맞춰서 빈틈이 남지 않도록 인쇄합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163097_5262.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163097_5262.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">선명한 화질 제공</div>\r\n<div class=\"desc active black m-black\">광학 1200dpi의 선명한 화질을 제공합니다.<br><br><span style=\"font-size: 12px;\">* \'dpi\' = \'dots per inch\' 고해상도 (숫자 ↑) = inch 별 많은 dot 구현 = 보다 작은 dot 크기 = 섬세한 표현 가능</span></div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163492_4473.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163492_4473.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">공용 드럼을 활용한 편리한 관리</div>\r\n<div class=\"desc active black m-black\">당사 신형 복합기는 분리형, 공용 소모품(Y/M/CK 공용 드럼)를 활용하여 유지보수/창고관리 등을 통한 비용 절감이 가능합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163508_397.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163508_397.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">토너 활용으로 더욱 오래도록 사용 가능</div>\r\n<div class=\"desc active black m-black\">당사 신형 복합기는 현상기 관리 필요 없이 이미지 품질 유지를 통해 토너 활용이 더욱 오래도록 사용하여 비용 절감에 탁월합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163522_7081.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163522_7081.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">더욱 효율적인 업무 공간</div>\r\n<div class=\"desc active black m-black\">슬림하고 컴팩트한 디자인으로 협소한 공간에서도 최적의 업무 공간 창조를 실현합니다. (공간을 절약하는 내장형 피니셔, 내장형 카드리더)</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163536_9268.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163536_9268.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">소모품 절약을 위한 에코모드</div>\r\n<div class=\"desc active black m-black\">에코 드라이버는 토너 사용량을 절약할 수 있는 다양한 프린팅 옵션과 시뮬레이션을 제공합니다.<br>문자/이미지 삭제, 색상 변경, 비트맵을 스케치로 전환하는 등의 작업이 모두 가능합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163551_636.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163551_636.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div></div>\r\n</div>\r\n<div class=\"spec-section\">\r\n<div class=\"product-w-inner\">\r\n<h2 class=\"sec-title\">스펙</h2>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">기본 사양</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">기본 기능</h4>\r\n<div class=\"desc\">인쇄, 복사, 스캔, 네트워크, 양면 인쇄</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">특수 기능</h4>\r\n<div class=\"desc\">팩스, 네트워크 PC 팩스</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">프로세서</h4>\r\n<div class=\"desc\">1GHz (듀얼 코어)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">디스플레이</h4>\r\n<div class=\"desc\">25.6 cm 컬러 터치 LCD, 960 x 600</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리 (기본)</h4>\r\n<div class=\"desc\">4 GB (안드로이드 OS 용 2GB 포함  * 안드로이드 OS용 사용자 가용 공간: 1 GB)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리 (최대)</h4>\r\n<div class=\"desc\">4 GB (안드로이드 OS 용 2GB 포함  * 안드로이드 OS용 사용자 가용 공간: 1 GB)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">하드 디스크</h4>\r\n<div class=\"desc\">320 GB  * 사용자 가용 공간: 279 GB</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인터페이스 (기본)</h4>\r\n<div class=\"desc\">USB 2.0, Ethernet 10/100/1G BASE TX</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인터페이스 (옵션)</h4>\r\n<div class=\"desc\">IEEE 802.11 b/g/n + NFC Active Type, IEEE 802.11 b/g/n/ac + BLE + NFC Active Type</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">소비전력</h4>\r\n<div class=\"desc\">1.2 kWh (최대), 250 W (대기), 1.5 W (절전모드)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">최대 소음</h4>\r\n<div class=\"desc\">53 dBA (복사), 48 dBA (인쇄), 30 dBA (대기)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">제품크기 (가로x세로x높이)</h4>\r\n<div class=\"desc\">566 x 640 x 879 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">최대 제품크기 (가로x세로x높이)</h4>\r\n<div class=\"desc\">1105 x 647 x 1139 mm (2단 급지장치, 내장형 피니셔, 작업 테이블 포함)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">무게</h4>\r\n<div class=\"desc\">79 kg</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">월 최대 출력매수</h4>\r\n<div class=\"desc\">100000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">월 권장 출력매수</h4>\r\n<div class=\"desc\">5000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">보안</h4>\r\n<div class=\"desc\">SSL/TLS, IP Sec, SNMPv3, Protocol&amp;Port Management, IPv6, IP/MAC Filtering, IEEE 802.1x support</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">제조 국가</h4>\r\n<div class=\"desc\">중국</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">출시일</h4>\r\n<div class=\"desc\">2014년 7월</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">품질 보증 기간</h4>\r\n<div class=\"desc\">1년</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">정격 전압</h4>\r\n<div class=\"desc\">AC220-240V~, 50/60Hz, 4.0A</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">지원운영체제</h4>\r\n<div class=\"desc\">Window: XP (32 / 64 bit) / 2003 Server (32 / 64 bit) / Vista (32 / 64 bit) / 2008 server (32 / 64 bit) / 7 (32 / 64 bit) / 2008 Server R2 / Windows 8 (32 / 64 bit) / Windows 8.1 (32 / 64 bit) / Windows Server 2012 (32 / 64 bit) / Windows Server 2012 R2, Mac OS 10.5 - 10.9, Red Hat Enterprise Linux 5, 6 / Fedora 11, 12, 13, 14, 15, 16, 17, 18, 19 / openSUSE 11.0, 11.1, 11.2, 11.3, 11.4, 12.1, 12.2, 12.3 / Ubuntu 10.04, 10.10, 11.04, 11.10, 12.04, 12.10, 13.04 / SUSE Linux Enterprise Desktop 10, 11 / Debian 5.0, 6.0, 7.0, 7.1 / Mint 13, 14, 15 / Sun Solaris 9, 10, 11 (x86, SPARC) /  HP-UX 11.0, 11i v1, 11i v2, 11i v3 (PA-RISC, Itanium) / IBM AIX 5.1, 5.2, 5.3, 5.4, 6.1, 7.1 (PowerPC)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">네트워크 프로토콜</h4>\r\n<div class=\"desc\">IP Management(DHCP, BOOTP, AutoIP, SetIP, Static) / Discovery Protocol (SLP, UPnP, Bonjour, DNS, WINS) / Printing Protocol(TCP/IP, LPR, IPP, WSD) / Management Protocol(SNMPv1.2, SNMP3, SMTP, Talnet) / Scan Protocol(SMTP, FTP, SMB, WSD) / Security Protocol(SMB, Kerberos, LDAP, IPsec, EAP)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">인쇄</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장출력시간 (흑백)</h4>\r\n<div class=\"desc\">10초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장출력시간 (컬러)</h4>\r\n<div class=\"desc\">12초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">1,200x1,200 dpi (속도 감소)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">에뮬레이션</h4>\r\n<div class=\"desc\">PCL5Ce, PCL6, Postscript 3, PDF</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">양면인쇄</h4>\r\n<div class=\"desc\">자동 지원</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">다이렉트 프린트 지원</h4>\r\n<div class=\"desc\">PRN,PDF,TIFF,JPEG,XPS</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인쇄 특수기능</h4>\r\n<div class=\"desc\">WSD 인쇄, 보안 인쇄, 저장후 인쇄, 책 형식,  모아 인쇄, 표지 인쇄, 페이지 삽입, 바코드, 에코, 포스터, 광택, 워트마크, 트레이 우선순위 선정, 트레이 자동 설정, USB 인쇄, Secure PDF 인쇄, Google Gloud Print</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-section more-spec-section\">\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">복사</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장복사시간 (흑백)</h4>\r\n<div class=\"desc\">7.2초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장복사시간 (컬러)</h4>\r\n<div class=\"desc\">9초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">600 x 600 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">축소 / 확대 배율</h4>\r\n<div class=\"desc\">25% - 400%</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">여러장 복사</h4>\r\n<div class=\"desc\">9999 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">복사 특수기능</h4>\r\n<div class=\"desc\">신분증 복사, 모아 찍기,  소책자, 이미지 반복, 자동 맞춤, 책 복사, 포스터 복사, 워터마트, 이미지 오버레이, 스탬프, 표지, 작업 빌드, 미리보기</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">스캔</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 45 매 (300 dpi, A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 45 매 (300 dpi, A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">호환</h4>\r\n<div class=\"desc\">Network TWAIN, Network SANE</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도 (광학)</h4>\r\n<div class=\"desc\">600x600 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도 (학장)</h4>\r\n<div class=\"desc\">4,800x4,800 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">전송방식</h4>\r\n<div class=\"desc\">Email, FTP, SMB, HDD, USB, WSD, PC</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">스캔용지 무게</h4>\r\n<div class=\"desc\">42-163 gsm (단면), 50-128 gsm (양면)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">팩스</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">호환</h4>\r\n<div class=\"desc\">ITU-T G3, Super G3</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">모뎀속도</h4>\r\n<div class=\"desc\">33.6 kbps</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">최고 600 x 600 dpi (흑백)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리</h4>\r\n<div class=\"desc\">HDD 백업</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동다이얼</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">팩스 특수기능</h4>\r\n<div class=\"desc\">자동 재호출, Caller ID, 보안 수신, Fax/Email/SMB/Box 재전송, 작업빌드, 추가 라인(옵션) 외</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">용지 취급</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (표준 용지함)</h4>\r\n<div class=\"desc\">1040 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (다목적 용지함)</h4>\r\n<div class=\"desc\">100 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (옵션 용지함)</h4>\r\n<div class=\"desc\">520 매 옵션 용지함 x 2</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (최대)</h4>\r\n<div class=\"desc\">2,180 매 ( 1,140 매 기본 + 1,040 매 옵션)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (표준 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (다목적 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 라벨 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지 / 봉투</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (옵션 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (표준 용지함)</h4>\r\n<div class=\"desc\">카세트1: 148 x 210 mm ~ 297 x 354 mm / 카세트2: 148 x 210 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (다목적 용지함)</h4>\r\n<div class=\"desc\">98 x 148 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (옵션 용지함)</h4>\r\n<div class=\"desc\">148 x 210 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (표준 용지함)</h4>\r\n<div class=\"desc\">일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (다목적 용지함)</h4>\r\n<div class=\"desc\">일반: 60~176g/㎡ (단면, 양면) /  봉투: 75~90g/㎡(단면) / 라벨: 120~150 g/㎡ (단면)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (옵션 용지함)</h4>\r\n<div class=\"desc\">일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동원고급지 종류</h4>\r\n<div class=\"desc\">RADF</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동원고급지 용량</h4>\r\n<div class=\"desc\">100 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">배지 용량</h4>\r\n<div class=\"desc\">500 매 (기본)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">소모품</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">토너 카트리지 (블랙)</h4>\r\n<div class=\"desc\">23,000 매 (5% 챠트)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">토너 카트리지 (컬러)</h4>\r\n<div class=\"desc\">20,000 매 (5% 챠트)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">이미징 유니트/드럼 (블랙)</h4>\r\n<div class=\"desc\">100,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">이미징 유니트/드럼 (컬러)</h4>\r\n<div class=\"desc\">100,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">현상기</h4>\r\n<div class=\"desc\">300,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">폐토너통</h4>\r\n<div class=\"desc\">약 33,700 매 (5% 챠트)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">옵션</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">옵션</h4>\r\n<div class=\"desc\">캐비닛 스탠드, 2단 급지 장치, 작업 분류기, 내장형 피니셔, 펀치 키트, 팩스 키트, 이중 팩스 키트, 외부 장치 인터페이스 키트, 추가 네트워크 키트, 작업 테이블, 무선네트워크/NFC, 무선네트워크/BLE/NFC</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">솔루션</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">기기 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">출력 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">문서 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">보안</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">모바일</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">KCC인증정보</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증번호</h4>\r\n<div class=\"desc\">MSIP-CMM-SEC-SLX4300LX</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증기관</h4>\r\n<div class=\"desc\">국립전파연구원</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증일자</h4>\r\n<div class=\"desc\">2014년 6월 2일</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>','\r\n\r\n특장점\r\n\r\n\r\n\r\n\r\n\r\n안드로이드 기반의 직관적인 UI\r\n25.6cm 컬러 터치 패널(960 × 600 해상도)을 통해 원하는 메뉴를 터치하면 손쉽게 기능이 실행됩니다.스마트폰 UI 그대로 페이지를 옆으로 넘기거나 아래로 내려보는 스크롤이 가능하여 사용이 편리합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n다양한 기업용 솔루션 선택 적용\r\nBCPS는 비즈니스 코어 프린팅 솔루션(Business Core Printing Solution)의 약자이자 기업용 프린팅 솔루션으로 보안, 클라우드, 문서관리, 스캔, 모니터링 등 5가지 솔루션으로 구성되어 있습니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n능동형 NFC 보안성 및 관리\r\n스마트기기를 활용하여 모바일 ID 인증, 모바일 폰 등록으로 보안성을 향상시킵니다.탭을 이용한 장비관리로 NFC 장비관리, 무선설정을 할 수 있습니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n1 GHz 듀얼 CPU 탑재로 1.5배 빠른 출력속도 제공\r\n1GHz 듀얼코어 프로세서 탑재로 인쇄 및 스캔 속도가 향상되어 더욱 빠르고 효율적인 업무 활동이 가능합니다.대용량의 출력/복사/스캔 작업 속도가 보다 빨라집니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n개방형 플랫폼 커스터마이징(XOA)\r\n맞춤형 응용 프로그램만 수정이나 개발이 허용되었던 기존 제품들의 제한된 개발형 구조를 뛰어넘어 XOA는 기본 응용 프로그램의 구성과 형식까지 편의에 따라 수정과 개발이 가능한 개방된 환경을 제공합니다.Job accounting과 같은 문서관리 솔루션 외 모바일 프린팅, 보안, Form 프린팅 및 UI 커스터마이징 등의 구현 환경을 제공합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n다양한 옵션 장착\r\n다양한 옵션 장착을 제공합니다.\r\n\r\n\r\n\r\n\r\n쉽고 빠른 설정 및 활용\r\n사용자별 UI 개인 설정을 통해 사용자 프로파일 이미지, 언어, 키보드, 배경화면, 홈 스크린 기본 앱 등 쉽고 편리하고 빠른 사용이 가능합니다.개인 모바일 디바이스와 같은 스마트폰을 활용한 컨트롤도 가능합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n향상된 듀얼 스캔\r\nMX4 시리즈는 듀얼 스캔으로 빠른 스캔 속도(80/120ipm)을 구현하였으며, 새로운 디자인의 DSDF &amp; RADF를 통해 빠르고 고장 없는 스캔을 위한 기구 구조물을 보강하였습니다.\r\n\r\n\r\n\r\n\r\n\r\n깔끔하고 선명한 문서작업 (ReCP 기술)\r\n삼성의 독자적인 ReCP(Rendering Engine for Clean Page) 기술로 출력 품질과 선명도가 탁월하게 향상되었습니다.이미지와 텍스트 모두 자동으로 포커스를 맞춰서 빈틈이 남지 않도록 인쇄합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n선명한 화질 제공\r\n광학 1200dpi의 선명한 화질을 제공합니다.* \'dpi\' = \'dots per inch\' 고해상도 (숫자 ↑) = inch 별 많은 dot 구현 = 보다 작은 dot 크기 = 섬세한 표현 가능\r\n\r\n\r\n\r\n\r\n\r\n공용 드럼을 활용한 편리한 관리\r\n당사 신형 복합기는 분리형, 공용 소모품(Y/M/CK 공용 드럼)를 활용하여 유지보수/창고관리 등을 통한 비용 절감이 가능합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n토너 활용으로 더욱 오래도록 사용 가능\r\n당사 신형 복합기는 현상기 관리 필요 없이 이미지 품질 유지를 통해 토너 활용이 더욱 오래도록 사용하여 비용 절감에 탁월합니다.\r\n\r\n\r\n\r\n\r\n\r\n더욱 효율적인 업무 공간\r\n슬림하고 컴팩트한 디자인으로 협소한 공간에서도 최적의 업무 공간 창조를 실현합니다. (공간을 절약하는 내장형 피니셔, 내장형 카드리더)\r\n\r\n\r\n\r\n\r\n\r\n\r\n소모품 절약을 위한 에코모드\r\n에코 드라이버는 토너 사용량을 절약할 수 있는 다양한 프린팅 옵션과 시뮬레이션을 제공합니다.문자/이미지 삭제, 색상 변경, 비트맵을 스케치로 전환하는 등의 작업이 모두 가능합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n스펙\r\n\r\n기본 사양\r\n\r\n\r\n\r\n기본 기능\r\n인쇄, 복사, 스캔, 네트워크, 양면 인쇄\r\n\r\n\r\n\r\n\r\n특수 기능\r\n팩스, 네트워크 PC 팩스\r\n\r\n\r\n\r\n\r\n프로세서\r\n1GHz (듀얼 코어)\r\n\r\n\r\n\r\n\r\n디스플레이\r\n25.6 cm 컬러 터치 LCD, 960 x 600\r\n\r\n\r\n\r\n\r\n메모리 (기본)\r\n4 GB (안드로이드 OS 용 2GB 포함  * 안드로이드 OS용 사용자 가용 공간: 1 GB)\r\n\r\n\r\n\r\n\r\n메모리 (최대)\r\n4 GB (안드로이드 OS 용 2GB 포함  * 안드로이드 OS용 사용자 가용 공간: 1 GB)\r\n\r\n\r\n\r\n\r\n하드 디스크\r\n320 GB  * 사용자 가용 공간: 279 GB\r\n\r\n\r\n\r\n\r\n인터페이스 (기본)\r\nUSB 2.0, Ethernet 10/100/1G BASE TX\r\n\r\n\r\n\r\n\r\n인터페이스 (옵션)\r\nIEEE 802.11 b/g/n + NFC Active Type, IEEE 802.11 b/g/n/ac + BLE + NFC Active Type\r\n\r\n\r\n\r\n\r\n소비전력\r\n1.2 kWh (최대), 250 W (대기), 1.5 W (절전모드)\r\n\r\n\r\n\r\n\r\n최대 소음\r\n53 dBA (복사), 48 dBA (인쇄), 30 dBA (대기)\r\n\r\n\r\n\r\n\r\n제품크기 (가로x세로x높이)\r\n566 x 640 x 879 mm\r\n\r\n\r\n\r\n\r\n최대 제품크기 (가로x세로x높이)\r\n1105 x 647 x 1139 mm (2단 급지장치, 내장형 피니셔, 작업 테이블 포함)\r\n\r\n\r\n\r\n\r\n무게\r\n79 kg\r\n\r\n\r\n\r\n\r\n월 최대 출력매수\r\n100000 매\r\n\r\n\r\n\r\n\r\n월 권장 출력매수\r\n5000 매\r\n\r\n\r\n\r\n\r\n보안\r\nSSL/TLS, IP Sec, SNMPv3, Protocol&amp;Port Management, IPv6, IP/MAC Filtering, IEEE 802.1x support\r\n\r\n\r\n\r\n\r\n제조 국가\r\n중국\r\n\r\n\r\n\r\n\r\n출시일\r\n2014년 7월\r\n\r\n\r\n\r\n\r\n품질 보증 기간\r\n1년\r\n\r\n\r\n\r\n\r\n정격 전압\r\nAC220-240V~, 50/60Hz, 4.0A\r\n\r\n\r\n\r\n\r\n지원운영체제\r\nWindow: XP (32 / 64 bit) / 2003 Server (32 / 64 bit) / Vista (32 / 64 bit) / 2008 server (32 / 64 bit) / 7 (32 / 64 bit) / 2008 Server R2 / Windows 8 (32 / 64 bit) / Windows 8.1 (32 / 64 bit) / Windows Server 2012 (32 / 64 bit) / Windows Server 2012 R2, Mac OS 10.5 - 10.9, Red Hat Enterprise Linux 5, 6 / Fedora 11, 12, 13, 14, 15, 16, 17, 18, 19 / openSUSE 11.0, 11.1, 11.2, 11.3, 11.4, 12.1, 12.2, 12.3 / Ubuntu 10.04, 10.10, 11.04, 11.10, 12.04, 12.10, 13.04 / SUSE Linux Enterprise Desktop 10, 11 / Debian 5.0, 6.0, 7.0, 7.1 / Mint 13, 14, 15 / Sun Solaris 9, 10, 11 (x86, SPARC) /  HP-UX 11.0, 11i v1, 11i v2, 11i v3 (PA-RISC, Itanium) / IBM AIX 5.1, 5.2, 5.3, 5.4, 6.1, 7.1 (PowerPC)\r\n\r\n\r\n\r\n\r\n네트워크 프로토콜\r\nIP Management(DHCP, BOOTP, AutoIP, SetIP, Static) / Discovery Protocol (SLP, UPnP, Bonjour, DNS, WINS) / Printing Protocol(TCP/IP, LPR, IPP, WSD) / Management Protocol(SNMPv1.2, SNMP3, SMTP, Talnet) / Scan Protocol(SMTP, FTP, SMB, WSD) / Security Protocol(SMB, Kerberos, LDAP, IPsec, EAP)\r\n\r\n\r\n\r\n\r\n\r\n인쇄\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n첫장출력시간 (흑백)\r\n10초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n첫장출력시간 (컬러)\r\n12초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n해상도\r\n1,200x1,200 dpi (속도 감소)\r\n\r\n\r\n\r\n\r\n에뮬레이션\r\nPCL5Ce, PCL6, Postscript 3, PDF\r\n\r\n\r\n\r\n\r\n양면인쇄\r\n자동 지원\r\n\r\n\r\n\r\n\r\n다이렉트 프린트 지원\r\nPRN,PDF,TIFF,JPEG,XPS\r\n\r\n\r\n\r\n\r\n인쇄 특수기능\r\nWSD 인쇄, 보안 인쇄, 저장후 인쇄, 책 형식,  모아 인쇄, 표지 인쇄, 페이지 삽입, 바코드, 에코, 포스터, 광택, 워트마크, 트레이 우선순위 선정, 트레이 자동 설정, USB 인쇄, Secure PDF 인쇄, Google Gloud Print\r\n\r\n\r\n\r\n\r\n\r\n\r\n복사\r\n\r\n\r\n\r\n첫장복사시간 (흑백)\r\n7.2초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n첫장복사시간 (컬러)\r\n9초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n해상도\r\n600 x 600 dpi\r\n\r\n\r\n\r\n\r\n축소 / 확대 배율\r\n25% - 400%\r\n\r\n\r\n\r\n\r\n여러장 복사\r\n9999 매\r\n\r\n\r\n\r\n\r\n복사 특수기능\r\n신분증 복사, 모아 찍기,  소책자, 이미지 반복, 자동 맞춤, 책 복사, 포스터 복사, 워터마트, 이미지 오버레이, 스탬프, 표지, 작업 빌드, 미리보기\r\n\r\n\r\n\r\n\r\n\r\n스캔\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 45 매 (300 dpi, A4 기준)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 45 매 (300 dpi, A4 기준)\r\n\r\n\r\n\r\n\r\n호환\r\nNetwork TWAIN, Network SANE\r\n\r\n\r\n\r\n\r\n해상도 (광학)\r\n600x600 dpi\r\n\r\n\r\n\r\n\r\n해상도 (학장)\r\n4,800x4,800 dpi\r\n\r\n\r\n\r\n\r\n전송방식\r\nEmail, FTP, SMB, HDD, USB, WSD, PC\r\n\r\n\r\n\r\n\r\n스캔용지 무게\r\n42-163 gsm (단면), 50-128 gsm (양면)\r\n\r\n\r\n\r\n\r\n\r\n팩스\r\n\r\n\r\n\r\n호환\r\nITU-T G3, Super G3\r\n\r\n\r\n\r\n\r\n모뎀속도\r\n33.6 kbps\r\n\r\n\r\n\r\n\r\n해상도\r\n최고 600 x 600 dpi (흑백)\r\n\r\n\r\n\r\n\r\n메모리\r\nHDD 백업\r\n\r\n\r\n\r\n\r\n자동다이얼\r\n있음\r\n\r\n\r\n\r\n\r\n팩스 특수기능\r\n자동 재호출, Caller ID, 보안 수신, Fax/Email/SMB/Box 재전송, 작업빌드, 추가 라인(옵션) 외\r\n\r\n\r\n\r\n\r\n\r\n용지 취급\r\n\r\n\r\n\r\n급지용량 (표준 용지함)\r\n1040 매\r\n\r\n\r\n\r\n\r\n급지용량 (다목적 용지함)\r\n100 매\r\n\r\n\r\n\r\n\r\n급지용량 (옵션 용지함)\r\n520 매 옵션 용지함 x 2\r\n\r\n\r\n\r\n\r\n급지용량 (최대)\r\n2,180 매 ( 1,140 매 기본 + 1,040 매 옵션)\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (표준 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (다목적 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 라벨 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지 / 봉투\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (옵션 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (표준 용지함)\r\n카세트1: 148 x 210 mm ~ 297 x 354 mm / 카세트2: 148 x 210 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (다목적 용지함)\r\n98 x 148 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (옵션 용지함)\r\n148 x 210 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (표준 용지함)\r\n일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (다목적 용지함)\r\n일반: 60~176g/㎡ (단면, 양면) /  봉투: 75~90g/㎡(단면) / 라벨: 120~150 g/㎡ (단면)\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (옵션 용지함)\r\n일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡\r\n\r\n\r\n\r\n\r\n자동원고급지 종류\r\nRADF\r\n\r\n\r\n\r\n\r\n자동원고급지 용량\r\n100 매\r\n\r\n\r\n\r\n\r\n배지 용량\r\n500 매 (기본)\r\n\r\n\r\n\r\n\r\n\r\n소모품\r\n\r\n\r\n\r\n토너 카트리지 (블랙)\r\n23,000 매 (5% 챠트)\r\n\r\n\r\n\r\n\r\n토너 카트리지 (컬러)\r\n20,000 매 (5% 챠트)\r\n\r\n\r\n\r\n\r\n이미징 유니트/드럼 (블랙)\r\n100,000 매\r\n\r\n\r\n\r\n\r\n이미징 유니트/드럼 (컬러)\r\n100,000 매\r\n\r\n\r\n\r\n\r\n현상기\r\n300,000 매\r\n\r\n\r\n\r\n\r\n폐토너통\r\n약 33,700 매 (5% 챠트)\r\n\r\n\r\n\r\n\r\n\r\n옵션\r\n\r\n\r\n\r\n옵션\r\n캐비닛 스탠드, 2단 급지 장치, 작업 분류기, 내장형 피니셔, 펀치 키트, 팩스 키트, 이중 팩스 키트, 외부 장치 인터페이스 키트, 추가 네트워크 키트, 작업 테이블, 무선네트워크/NFC, 무선네트워크/BLE/NFC\r\n\r\n\r\n\r\n\r\n\r\n솔루션\r\n\r\n\r\n\r\n기기 관리\r\n있음\r\n\r\n\r\n\r\n\r\n출력 관리\r\n있음\r\n\r\n\r\n\r\n\r\n문서 관리\r\n있음\r\n\r\n\r\n\r\n\r\n보안\r\n있음\r\n\r\n\r\n\r\n\r\n모바일\r\n있음\r\n\r\n\r\n\r\n\r\n\r\nKCC인증정보\r\n\r\n\r\n\r\n인증번호\r\nMSIP-CMM-SEC-SLX4300LX\r\n\r\n\r\n\r\n\r\n인증기관\r\n국립전파연구원\r\n\r\n\r\n\r\n\r\n인증일자\r\n2014년 6월 2일\r\n\r\n\r\n\r\n\r\n\r\n\r\n','',0,1979180,0,0,0,0,'',1,0,0,99999,0,10,1,0,50000,0,0,0,0,'','','','',10,'2017-11-20 15:55:54','2017-11-21 09:49:53','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:19:\"디지털 복합기\";s:10:\"model_name\";s:10:\"SL-X4220RX\";s:13:\"certification\";s:22:\"MSIP-CMM-SEC-SLX4300LX\";s:13:\"rated_voltage\";s:26:\"AC220-240V~, 50/60Hz, 4.0A\";s:17:\"power_consumption\";s:54:\"1.2 kWh (최대), 250 W (대기), 1.5 W (절전모드)\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:12:\"2014년 7월\";s:5:\"maker\";s:12:\"삼성전자\";s:6:\"madein\";s:6:\"중국\";s:4:\"size\";s:18:\"566 x 640 x 879 mm\";s:6:\"weight\";s:5:\"79 kg\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:4:\"1년\";s:2:\"as\";s:9:\"1800-7540\";}',0,0,0.0,'','','1511160936/img1.jpg','1511160936/img2.jpg','1511160936/img3.jpg','1511160936/img4.jpg','1511160936/img5.jpg','1511160936/img6.jpg','1511160936/img7.jpg','1511160936/img8.jpg','1511160936/img9.jpg','1511160936/img10.jpg','','','','','','','','','','','','','','','','','','','',''),('1511223468','10','','','theme/basic','theme/basic','삼성전자 SL-K4250RX','삼성전자','중국산(삼성전자)','삼성전자','SL-K4250RX','','',1,0,0,0,0,'A3 흑백 디지털복합기','<div class=\"product-w-inner\">\r\n<h2 class=\"sec-title\">\r\n<span class=\"tit-feature\">특장점</span>\r\n</h2><div class=\"featurebox\"><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511162913_7897.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511162913_7897_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">안드로이드 기반의 직관적인 UI</div>\r\n<div class=\"des\">25.6cm 컬러 터치 패널(960 × 600 해상도)을 통해 원하는 메뉴를 터치하면 손쉽게 기능이 실행됩니다.<br>스마트폰 UI 그대로 페이지를 옆으로 넘기거나 아래로 내려보는 스크롤이 가능하여 사용이 편리합니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511162960_943.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511162960_943_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">다양한 기업용 솔루션 선택 적용</div>\r\n<div class=\"des\">BCPS는 비즈니스 코어 프린팅 솔루션(Business Core Printing Solution)의 약자이자 기업용 프린팅 솔루션으로 보안, 클라우드, 문서관리, 스캔, 모니터링 등 5가지 솔루션으로 구성되어 있습니다.</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163003_6316.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163003_6316_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">능동형 NFC 보안성 및 관리</div>\r\n<div class=\"des\">스마트기기를 활용하여 모바일 ID 인증, 모바일 폰 등록으로 보안성을 향상시킵니다.<br>탭을 이용한 장비관리로 NFC 장비관리, 무선설정을 할 수 있습니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163028_9968.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163028_9968_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">1 GHz 듀얼 CPU 탑재로 1.5배 빠른 출력속도 제공</div>\r\n<div class=\"des\">1GHz 듀얼코어 프로세서 탑재로 인쇄 및 스캔 속도가 향상되어 더욱 빠르고 효율적인 업무 활동이 가능합니다.<br>대용량의 출력/복사/스캔 작업 속도가 보다 빨라집니다.</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163041_5842.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163041_5842_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">개방형 플랫폼 커스터마이징(XOA)</div>\r\n<div class=\"des\">맞춤형 응용 프로그램만 수정이나 개발이 허용되었던 기존 제품들의 제한된 개발형 구조를 뛰어넘어 XOA는 기본 응용 프로그램의 구성과 형식까지 편의에 따라 수정과 개발이 가능한 개방된 환경을 제공합니다.<br>Job accounting과 같은 문서관리 솔루션 외 모바일 프린팅, 보안, Form 프린팅 및 UI 커스터마이징 등의 구현 환경을 제공합니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163054_9316.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163054_9316_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">다양한 옵션 장착</div>\r\n<div class=\"des\">다양한 옵션 장착을 제공합니다.</div>\r\n</div>\r\n</div>\r\n</div></div></div><div class=\"more-features\"><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">쉽고 빠른 설정 및 활용</div>\r\n<div class=\"desc active black m-black\">사용자별 UI 개인 설정을 통해 사용자 프로파일 이미지, 언어, 키보드, 배경화면, 홈 스크린 기본 앱 등 쉽고 편리하고 빠른 사용이 가능합니다.<br>개인 모바일 디바이스와 같은 스마트폰을 활용한 컨트롤도 가능합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163068_2475.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163068_2475_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">향상된 듀얼 스캔</div>\r\n<div class=\"desc active black m-black\">MX4 시리즈는 듀얼 스캔으로 빠른 스캔 속도(80/120ipm)을 구현하였으며, 새로운 디자인의 DSDF &amp; RADF를 통해 빠르고 고장 없는 스캔을 위한 기구 구조물을 보강하였습니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163082_7693.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163082_7693_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">깔끔하고 선명한 문서작업 (ReCP 기술)</div>\r\n<div class=\"desc active black m-black\">삼성의 독자적인 ReCP(Rendering Engine for Clean Page) 기술로 출력 품질과 선명도가 탁월하게 향상되었습니다.<br>이미지와 텍스트 모두 자동으로 포커스를 맞춰서 빈틈이 남지 않도록 인쇄합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163097_5262.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163097_5262_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">선명한 화질 제공</div>\r\n<div class=\"desc active black m-black\">광학 1200dpi의 선명한 화질을 제공합니다.<br><br><span style=\"font-size: 12px;\">* \'dpi\' = \'dots per inch\' 고해상도 (숫자 ↑) = inch 별 많은 dot 구현 = 보다 작은 dot 크기 = 섬세한 표현 가능</span></div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163492_4473.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163492_4473_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">뛰어난 경제성 - 공용 드럼을 활용한 편리한 관리</div>\r\n<div class=\"desc active black m-black\">당사 신형 복합기는 분리형, 공용 소모품(Y/M/CK 공용 드럼)를 활용하여 유지보수/창고관리 등을 통한 비용 절감이 가능합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163508_397.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163508_397_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">뛰어난 경제성 - 토너 활용으로 더욱 오래도록 사용 가능</div>\r\n<div class=\"desc active black m-black\">당사 신형 복합기는 현상기 관리 필요 없이 이미지 품질 유지를 통해 토너 활용이 더욱 오래도록 사용하여 비용 절감에 탁월합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163522_7081.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163522_7081_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div><div class=\"feature-inner\"><div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">뛰어난 경제성 - 더욱 효율적인 업무 공간</div>\r\n<div class=\"desc active black m-black\">슬림하고 컴팩트한 디자인으로 협소한 공간에서도 최적의 업무 공간 창조를 실현합니다. (공간을 절약하는 내장형 피니셔, 내장형 카드리더)</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163536_9268.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163536_9268_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"feature dark\">\r\n<div class=\"wrap inner-x left\">\r\n<div class=\"title active black m-black\">뛰어난 경제성 - 소모품 절약을 위한 에코모드</div>\r\n<div class=\"desc active black m-black\">에코 드라이버는 토너 사용량을 절약할 수 있는 다양한 프린팅 옵션과 시뮬레이션을 제공합니다.<br>문자/이미지 삭제, 색상 변경, 비트맵을 스케치로 전환하는 등의 작업이 모두 가능합니다.</div>\r\n</div>\r\n<div class=\"image\">\r\n<img title=\"ac3d0d4995ef2b0a41a3c458bced110b_1511163551_636.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/ac3d0d4995ef2b0a41a3c458bced110b_1511163551_636_1511223468.jpg\"><br style=\"clear: both;\"></div>\r\n</div></div></div>\r\n</div><div class=\"spec-section more-spec-section\">\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">복사</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장복사시간 (흑백)</h4>\r\n<div class=\"desc\">6.2초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 25매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">600 x 600 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">축소 / 확대 배율</h4>\r\n<div class=\"desc\">25% - 400%</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">여러장 복사</h4>\r\n<div class=\"desc\">9999 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">복사 특수기능</h4>\r\n<div class=\"desc\">신분증 복사, 모아 찍기, 소책자, 이미지 반복, 자동 맞춤, 책 복사, 포스터 복사, 워터마트, 이미지 오버레이, 스탬프, 표지, 작업 빌드, 미리보기</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">스캔</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 45 매 (단면), 분당 최대 18매 (양면)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 45 매 (단면), 분당 최대 18매 (양면)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">호환</h4>\r\n<div class=\"desc\">Network TWAIN, Network SANE</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도 (광학)</h4>\r\n<div class=\"desc\">600 x 600 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도 (학장)</h4>\r\n<div class=\"desc\">4,800 x 4,800 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">전송방식</h4>\r\n<div class=\"desc\">Email, FTP, SMB, HDD, USB, WSD, PC</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">스캔용지 무게</h4>\r\n<div class=\"desc\">42-163 gsm (단면), 50-128 gsm (양면)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">팩스</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">호환</h4>\r\n<div class=\"desc\">ITU-T G3, Super G3</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">모뎀속도</h4>\r\n<div class=\"desc\">33.6 kbps</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">최고 600 x 600 dpi (흑백)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리</h4>\r\n<div class=\"desc\">HDD 백업</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동다이얼</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">팩스 특수기능</h4>\r\n<div class=\"desc\">자동 재호출, Caller ID, 보안 수신, Fax/Email/SMB/Box 재전송, 작업빌드, 추가 라인(옵션) 외</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">용지 취급</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (표준 용지함)</h4>\r\n<div class=\"desc\">1040 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (다목적 용지함)</h4>\r\n<div class=\"desc\">100 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (옵션 용지함)</h4>\r\n<div class=\"desc\">520 매 옵션 용지함 x 2</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (최대)</h4>\r\n<div class=\"desc\">2,180 매 ( 1,140 매 기본 + 1,040 매 옵션)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (표준 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (다목적 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 라벨 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지 / 봉투</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (옵션 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (표준 용지함)</h4>\r\n<div class=\"desc\">카세트1: 148 x 210 mm ~ 297 x 354 mm / 카세트2: 148 x 210 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (다목적 용지함)</h4>\r\n<div class=\"desc\">98 x 148 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (옵션 용지함)</h4>\r\n<div class=\"desc\">148 x 210 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (표준 용지함)</h4>\r\n<div class=\"desc\">일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (다목적 용지함)</h4>\r\n<div class=\"desc\">일반: 60~176g/㎡ (단면, 양면) / 봉투: 75~90g/㎡(단면) / 라벨: 120~150 g/㎡ (단면)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (옵션 용지함)</h4>\r\n<div class=\"desc\">일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동원고급지 종류</h4>\r\n<div class=\"desc\">RADF</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동원고급지 용량</h4>\r\n<div class=\"desc\">100 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">배지 용량</h4>\r\n<div class=\"desc\">500 매 (기본)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">소모품</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">토너 카트리지 (블랙)</h4>\r\n<div class=\"desc\">기본: 25,000 매 (6% 챠트), 대용량: 35,000 매 (6% 챠트)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">이미징 유니트/드럼 (블랙)</h4>\r\n<div class=\"desc\">200,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">현상기</h4>\r\n<div class=\"desc\">400,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">폐토너통</h4>\r\n<div class=\"desc\">100,000 매</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">옵션</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">옵션</h4>\r\n<div class=\"desc\">캐비닛 스탠드, 2단 급지 장치, 작업 분류기, 내장형 피니셔, 펀치 키트, 팩스 키트, 이중 팩스 키트, 외부 장치 인터페이스 키트, 추가 네트워크 키트, 작업 테이블, 무선네트워크/NFC, 무선네트워크/BLE/NFC</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">솔루션</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">기기 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">출력 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">문서 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">보안</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">모바일</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">KCC인증정보</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증번호</h4>\r\n<div class=\"desc\">MSIP-CMM-SEC-SLK4350LX</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증기관</h4>\r\n<div class=\"desc\">국립전파연구원</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증일자</h4>\r\n<div class=\"desc\">2014년 06월 02일</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n</div>','\r\n\r\n특장점\r\n\r\n\r\n\r\n\r\n\r\n안드로이드 기반의 직관적인 UI\r\n25.6cm 컬러 터치 패널(960 × 600 해상도)을 통해 원하는 메뉴를 터치하면 손쉽게 기능이 실행됩니다.스마트폰 UI 그대로 페이지를 옆으로 넘기거나 아래로 내려보는 스크롤이 가능하여 사용이 편리합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n다양한 기업용 솔루션 선택 적용\r\nBCPS는 비즈니스 코어 프린팅 솔루션(Business Core Printing Solution)의 약자이자 기업용 프린팅 솔루션으로 보안, 클라우드, 문서관리, 스캔, 모니터링 등 5가지 솔루션으로 구성되어 있습니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n능동형 NFC 보안성 및 관리\r\n스마트기기를 활용하여 모바일 ID 인증, 모바일 폰 등록으로 보안성을 향상시킵니다.탭을 이용한 장비관리로 NFC 장비관리, 무선설정을 할 수 있습니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n1 GHz 듀얼 CPU 탑재로 1.5배 빠른 출력속도 제공\r\n1GHz 듀얼코어 프로세서 탑재로 인쇄 및 스캔 속도가 향상되어 더욱 빠르고 효율적인 업무 활동이 가능합니다.대용량의 출력/복사/스캔 작업 속도가 보다 빨라집니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n개방형 플랫폼 커스터마이징(XOA)\r\n맞춤형 응용 프로그램만 수정이나 개발이 허용되었던 기존 제품들의 제한된 개발형 구조를 뛰어넘어 XOA는 기본 응용 프로그램의 구성과 형식까지 편의에 따라 수정과 개발이 가능한 개방된 환경을 제공합니다.Job accounting과 같은 문서관리 솔루션 외 모바일 프린팅, 보안, Form 프린팅 및 UI 커스터마이징 등의 구현 환경을 제공합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n다양한 옵션 장착\r\n다양한 옵션 장착을 제공합니다.\r\n\r\n\r\n\r\n\r\n쉽고 빠른 설정 및 활용\r\n사용자별 UI 개인 설정을 통해 사용자 프로파일 이미지, 언어, 키보드, 배경화면, 홈 스크린 기본 앱 등 쉽고 편리하고 빠른 사용이 가능합니다.개인 모바일 디바이스와 같은 스마트폰을 활용한 컨트롤도 가능합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n향상된 듀얼 스캔\r\nMX4 시리즈는 듀얼 스캔으로 빠른 스캔 속도(80/120ipm)을 구현하였으며, 새로운 디자인의 DSDF &amp; RADF를 통해 빠르고 고장 없는 스캔을 위한 기구 구조물을 보강하였습니다.\r\n\r\n\r\n\r\n\r\n\r\n깔끔하고 선명한 문서작업 (ReCP 기술)\r\n삼성의 독자적인 ReCP(Rendering Engine for Clean Page) 기술로 출력 품질과 선명도가 탁월하게 향상되었습니다.이미지와 텍스트 모두 자동으로 포커스를 맞춰서 빈틈이 남지 않도록 인쇄합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n선명한 화질 제공\r\n광학 1200dpi의 선명한 화질을 제공합니다.* \'dpi\' = \'dots per inch\' 고해상도 (숫자 ↑) = inch 별 많은 dot 구현 = 보다 작은 dot 크기 = 섬세한 표현 가능\r\n\r\n\r\n\r\n\r\n\r\n뛰어난 경제성 - 공용 드럼을 활용한 편리한 관리\r\n당사 신형 복합기는 분리형, 공용 소모품(Y/M/CK 공용 드럼)를 활용하여 유지보수/창고관리 등을 통한 비용 절감이 가능합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n뛰어난 경제성 - 토너 활용으로 더욱 오래도록 사용 가능\r\n당사 신형 복합기는 현상기 관리 필요 없이 이미지 품질 유지를 통해 토너 활용이 더욱 오래도록 사용하여 비용 절감에 탁월합니다.\r\n\r\n\r\n\r\n\r\n\r\n뛰어난 경제성 - 더욱 효율적인 업무 공간\r\n슬림하고 컴팩트한 디자인으로 협소한 공간에서도 최적의 업무 공간 창조를 실현합니다. (공간을 절약하는 내장형 피니셔, 내장형 카드리더)\r\n\r\n\r\n\r\n\r\n\r\n\r\n뛰어난 경제성 - 소모품 절약을 위한 에코모드\r\n에코 드라이버는 토너 사용량을 절약할 수 있는 다양한 프린팅 옵션과 시뮬레이션을 제공합니다.문자/이미지 삭제, 색상 변경, 비트맵을 스케치로 전환하는 등의 작업이 모두 가능합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n복사\r\n\r\n\r\n\r\n첫장복사시간 (흑백)\r\n6.2초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 25매 (A4 기준)\r\n\r\n\r\n\r\n\r\n해상도\r\n600 x 600 dpi\r\n\r\n\r\n\r\n\r\n축소 / 확대 배율\r\n25% - 400%\r\n\r\n\r\n\r\n\r\n여러장 복사\r\n9999 매\r\n\r\n\r\n\r\n\r\n복사 특수기능\r\n신분증 복사, 모아 찍기, 소책자, 이미지 반복, 자동 맞춤, 책 복사, 포스터 복사, 워터마트, 이미지 오버레이, 스탬프, 표지, 작업 빌드, 미리보기\r\n\r\n\r\n\r\n\r\n\r\n스캔\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 45 매 (단면), 분당 최대 18매 (양면)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 45 매 (단면), 분당 최대 18매 (양면)\r\n\r\n\r\n\r\n\r\n호환\r\nNetwork TWAIN, Network SANE\r\n\r\n\r\n\r\n\r\n해상도 (광학)\r\n600 x 600 dpi\r\n\r\n\r\n\r\n\r\n해상도 (학장)\r\n4,800 x 4,800 dpi\r\n\r\n\r\n\r\n\r\n전송방식\r\nEmail, FTP, SMB, HDD, USB, WSD, PC\r\n\r\n\r\n\r\n\r\n스캔용지 무게\r\n42-163 gsm (단면), 50-128 gsm (양면)\r\n\r\n\r\n\r\n\r\n\r\n팩스\r\n\r\n\r\n\r\n호환\r\nITU-T G3, Super G3\r\n\r\n\r\n\r\n\r\n모뎀속도\r\n33.6 kbps\r\n\r\n\r\n\r\n\r\n해상도\r\n최고 600 x 600 dpi (흑백)\r\n\r\n\r\n\r\n\r\n메모리\r\nHDD 백업\r\n\r\n\r\n\r\n\r\n자동다이얼\r\n있음\r\n\r\n\r\n\r\n\r\n팩스 특수기능\r\n자동 재호출, Caller ID, 보안 수신, Fax/Email/SMB/Box 재전송, 작업빌드, 추가 라인(옵션) 외\r\n\r\n\r\n\r\n\r\n\r\n용지 취급\r\n\r\n\r\n\r\n급지용량 (표준 용지함)\r\n1040 매\r\n\r\n\r\n\r\n\r\n급지용량 (다목적 용지함)\r\n100 매\r\n\r\n\r\n\r\n\r\n급지용량 (옵션 용지함)\r\n520 매 옵션 용지함 x 2\r\n\r\n\r\n\r\n\r\n급지용량 (최대)\r\n2,180 매 ( 1,140 매 기본 + 1,040 매 옵션)\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (표준 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (다목적 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 라벨 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지 / 봉투\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (옵션 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (표준 용지함)\r\n카세트1: 148 x 210 mm ~ 297 x 354 mm / 카세트2: 148 x 210 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (다목적 용지함)\r\n98 x 148 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (옵션 용지함)\r\n148 x 210 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (표준 용지함)\r\n일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (다목적 용지함)\r\n일반: 60~176g/㎡ (단면, 양면) / 봉투: 75~90g/㎡(단면) / 라벨: 120~150 g/㎡ (단면)\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (옵션 용지함)\r\n일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡\r\n\r\n\r\n\r\n\r\n자동원고급지 종류\r\nRADF\r\n\r\n\r\n\r\n\r\n자동원고급지 용량\r\n100 매\r\n\r\n\r\n\r\n\r\n배지 용량\r\n500 매 (기본)\r\n\r\n\r\n\r\n\r\n\r\n소모품\r\n\r\n\r\n\r\n토너 카트리지 (블랙)\r\n기본: 25,000 매 (6% 챠트), 대용량: 35,000 매 (6% 챠트)\r\n\r\n\r\n\r\n\r\n이미징 유니트/드럼 (블랙)\r\n200,000 매\r\n\r\n\r\n\r\n\r\n현상기\r\n400,000 매\r\n\r\n\r\n\r\n\r\n폐토너통\r\n100,000 매\r\n\r\n\r\n\r\n\r\n\r\n옵션\r\n\r\n\r\n\r\n옵션\r\n캐비닛 스탠드, 2단 급지 장치, 작업 분류기, 내장형 피니셔, 펀치 키트, 팩스 키트, 이중 팩스 키트, 외부 장치 인터페이스 키트, 추가 네트워크 키트, 작업 테이블, 무선네트워크/NFC, 무선네트워크/BLE/NFC\r\n\r\n\r\n\r\n\r\n\r\n솔루션\r\n\r\n\r\n\r\n기기 관리\r\n있음\r\n\r\n\r\n\r\n\r\n출력 관리\r\n있음\r\n\r\n\r\n\r\n\r\n문서 관리\r\n있음\r\n\r\n\r\n\r\n\r\n보안\r\n있음\r\n\r\n\r\n\r\n\r\n모바일\r\n있음\r\n\r\n\r\n\r\n\r\n\r\nKCC인증정보\r\n\r\n\r\n\r\n인증번호\r\nMSIP-CMM-SEC-SLK4350LX\r\n\r\n\r\n\r\n\r\n인증기관\r\n국립전파연구원\r\n\r\n\r\n\r\n\r\n인증일자\r\n2014년 06월 02일\r\n\r\n\r\n\r\n\r\n','',0,1497160,0,0,0,0,'',1,0,0,99999,0,10,1,0,50000,0,0,0,0,'','','','',12,'2017-11-20 15:55:54','2017-11-21 09:33:11','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:19:\"디지털 복합기\";s:10:\"model_name\";s:10:\"SL-K4250RX\";s:13:\"certification\";s:22:\"MSIP-CMM-SEC-SLK4350LX\";s:13:\"rated_voltage\";s:26:\"AC220-240V~, 50/60Hz, 4.0A\";s:17:\"power_consumption\";s:55:\"1.3 kWh (최대), 200 Wh (대기), 1.5 W (절전모드)\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:12:\"2014년 7월\";s:5:\"maker\";s:12:\"삼성전자\";s:6:\"madein\";s:6:\"중국\";s:4:\"size\";s:18:\"566 x 640 x 829 mm\";s:6:\"weight\";s:7:\"61.7 kg\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:4:\"1년\";s:2:\"as\";s:9:\"1800-7540\";}',0,0,0.0,'','','1511223468/img1.jpg','1511223468/img2.jpg','1511223468/img3.jpg','1511223468/img4.jpg','1511223468/img5.jpg','1511223468/img6.jpg','1511223468/img7.jpg','1511223468/img8.jpg','1511223468/img9.jpg','1511223468/img10.jpg','','','','','','','','','','','','','','','','','','','',''),('1511225688','10','','','theme/basic','theme/basic','삼성전자 SL-X3220NR','삼성전자','중국산(삼성전자)','삼성전자','SL-X3220NR','','',1,0,0,0,0,'인쇄/복사/스캔/네트워크/양면 인쇄/컬러/A3/22매/빠른설치','<div class=\"product-w-inner\">\r\n<h2 class=\"sec-title\">\r\n<span class=\"tit-feature\">특장점</span>\r\n</h2><div class=\"featurebox\"><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226210_8099.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226210_8099.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">직관적인 UI 및 터치패널</div>\r\n<div class=\"des\">직관적인 하드웨어 조작부의 UI를 바탕으로 17.8cm 컬러 터치 패널(800 x 480 해상도)을 통해 원하는 메뉴를 터치하면 손쉽게 기능이 실행됩니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226222_692.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226222_692.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">손쉬운 화면설정 및 출력작업</div>\r\n<div class=\"des\">최대 40개의 바로가기 지원(스캔/인쇄), 복합기 설정 검색 기능 제공 과 컬러 터치 스크린 패널로 스마트폰 UI 그대로 페이지를 옆으로 넘기거나 아래로 내려보는 스크롤이 가능하여 사용이 편리합니다.</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226233_9694.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226233_9694.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">다양한 기업용 솔루션 선택 적용</div>\r\n<div class=\"des\">BCPS란? 비즈니스 코어 프린팅 솔루션(Business Core Printing Solution)의 약자로 기업용 프린팅 솔루션으로 보안, 클라우드, 문서관리, 스캔, 모니터링 등 5가지 솔루션으로 구성되어 있습니다.</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226244_6322.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226244_6322.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">다양한 옵션 장착</div>\r\n<div class=\"des\">다양한 옵션 장착을 제공합니다.</div>\r\n<div class=\"disclaimer\">\r\n<div class=\"disclaimer_inner\">\r\n<ul>\r\n<li>* HDD는 모노기에만 장착 가능 (컬러기는 기본 장착)</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226255_3747.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226255_3747.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">더욱 효율적인 업무 공간</div>\r\n<div class=\"des\">슬림하고 컴팩트한 디자인으로 협소한 공간에서도 최적의 업무 공간 창조를 실현합니다. (공간을 절약하는 내장형 피니셔, 내장형 카드리더)</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"rightarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226266_4339.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226266_4339.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">강력한 보안</div>\r\n<div class=\"des\">최근 부각되는 인쇄물 보안 이슈로 인해 중요 용지를 용지 함 잠금 기능을 통해 사용하거나 PC에서 조차 남기지 않는 자료를 문서서버로 활용하여 보안 기능을 한층 강화한 활용성을 갖추어 강력한 보안과 편의성을 동시에 만족시킬 수 있습니다.</div>\r\n</div>\r\n</div>\r\n</div></div><div class=\"feature-inner\"><div class=\"leftarea\">\r\n<div class=\"insection\">\r\n<div class=\"img-area\">\r\n<img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511226278_0503.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511226278_0503.jpg\"><br style=\"clear: both;\"></div>\r\n<div class=\"info-txt white m-white\">\r\n<div class=\"title\">모바일 프린팅 사용성 강화</div>\r\n<div class=\"des\">사용자별 UI 개인 설정을 통해 사용자 프로파일 이미지, 언어, 키보드, 배경화면, 홈 스크린 기본 앱 등 쉽고 빠른 사용이 편리합니다. 개인 모바일 디바이스와 같은 스마트폰을 활용한 컨트롤도 가능합니다.(옵션)</div>\r\n</div>\r\n</div>\r\n</div></div></div></div>\r\n<div class=\"grid-row\">\r\n<div class=\"grid-col\">\r\n<h2 class=\"sec-title\">스펙</h2>\r\n</div>\r\n</div>\r\n<div class=\"spec-section\">\r\n<div class=\"product-w-inner\">\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">기본 사양</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">기본 기능</h4>\r\n<div class=\"desc\">인쇄, 복사, 스캔, 네트워크, 양면 인쇄</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">특수 기능</h4>\r\n<div class=\"desc\">팩스, 네트워크 PC 팩스</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">프로세서</h4>\r\n<div class=\"desc\">1GHz (듀얼 코어)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">디스플레이</h4>\r\n<div class=\"desc\">17.8cm 컬러 터치 LCD, 800 x 480 (WVGA)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리 (기본)</h4>\r\n<div class=\"desc\">2 GB</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리 (최대)</h4>\r\n<div class=\"desc\">2 GB</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">하드 디스크</h4>\r\n<div class=\"desc\">320 GB 혹은 320 GB이상 (사용자 가용 공간: 300 GB)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인터페이스 (기본)</h4>\r\n<div class=\"desc\">USB 2.0, Ethernet 10/100/1G BASE TX</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인터페이스 (옵션)</h4>\r\n<div class=\"desc\">IEEE 802.11b/g/n + NFC Active Type</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">예열시간 (From Power Off)</h4>\r\n<div class=\"desc\">25 초</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">예열시간 (From Sleep)</h4>\r\n<div class=\"desc\">21 초</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">소비전력</h4>\r\n<div class=\"desc\">1.75 kWh (최대), 60 Wh (대기), 1.5 W (절전모드)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">최대 소음</h4>\r\n<div class=\"desc\">53 dBA (복사), 48 dBA (인쇄), 30 dBA (대기)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">제품크기 (가로x세로x높이)</h4>\r\n<div class=\"desc\">566 x 620 x 870 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">최대 제품크기 (가로x세로x높이)</h4>\r\n<div class=\"desc\">566 x 620 x 1,130 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">무게</h4>\r\n<div class=\"desc\">78.20 kg (세트, RADF, 드럼, 폐토너통)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">월 최대 출력매수</h4>\r\n<div class=\"desc\">60,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">월 권장 출력매수</h4>\r\n<div class=\"desc\">3,500 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">보안</h4>\r\n<div class=\"desc\">SSL/TLS, IP Sec, SNMPv3, Protocol&amp;Port Management, IPv6, IP/MAC Filtering, IEEE 802.1x</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">제조 국가</h4>\r\n<div class=\"desc\">중국</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">제조사</h4>\r\n<div class=\"desc\">2015년 10월</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">품질 보증 기간</h4>\r\n<div class=\"desc\">1년</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">정격 전압</h4>\r\n<div class=\"desc\">AC220-240V~, 50/60Hz, 4.0A</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">지원운영체제</h4>\r\n<div class=\"desc\">Windows: Window XP(32/64bit), Vista(32/64bit), 2003 Server(32/64bit), 2008 Server(32/64bit), Win7(32/64bit), 2008 Server R2(64bit), Win8(32/64bit), Win8.1(32bit/64bit), 2012 Server(64bit), 2012 Server R2(64bit) / Mac OS 10.6 ~ 10.10 /  Linux: Red Hat Enterprise Linux 5, 6, 7 / Fedora 13, 14, 15, 16, 17, 18, 19, 20, 21 / openSUSE 11.3, 11.4, 12.1, 12.2, 12.3, 13.1 ,13.2 / Ubuntu 10.04, 10.10, 11.04, 11.10, 12.04, 12.10, 13.04, 13.10, 14.04, 14.10 / SUSE Linux Enterprise Desktop 11, 12 /  Debian 6 ,7 / Mint 13, 14, 15, 16, 17 / Unix: Sun Solaris 9, 10, 11 (x86, SPARC) / HP-UX 11.0, 11i v1, 11i v2, 11i v3 (PA-RISC, Itanium) / IBM AIX 5.1, 5.2, 5.3, 5.4, 6.1, 7.1 (PowerPC)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">네트워크 프로토콜</h4>\r\n<div class=\"desc\">IP Management(DHCP, BOOTP, AutoIP, SetIP, Static) / Discovery Protocol (SLP, UPnP, Bonjour, DNS, WINS) / Printing Protocol(TCP/IP, LPR, IPP, WSD) / Management Protocol(SNMPv1.2, SNMP3, POP3, SMTP, Telnet) / Scan Protocol(SMTP, FTP, SMB, WSD) / Security Protocol(SMB, Kerberos, LDAP, IPsec, EAP)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">인쇄</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장출력시간 (흑백)</h4>\r\n<div class=\"desc\">10초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장출력시간 (컬러)</h4>\r\n<div class=\"desc\">12초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">1,200 x 1,200 dpi (속도 감소)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">에뮬레이션</h4>\r\n<div class=\"desc\">PCL5e, PCL6,  PostScript3, PDF V1.7</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">양면인쇄</h4>\r\n<div class=\"desc\">자동 지원</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">다이렉트 프린트 지원</h4>\r\n<div class=\"desc\">PRN,PDF,TIFF,JPEG,XPS</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인쇄 특수기능</h4>\r\n<div class=\"desc\">WSD 인쇄, 보안 인쇄, 저장후 인쇄, 책 형식, 모아 인쇄, 표지 인쇄, 페이지 삽입, 페이지 삭제, 바코드, 에코, 포스터, 광택, 워트마크, 트레이 우선 순위 선정, 트레이 자동 설정, USB 인쇄, Secure PDF 인쇄, Google Gloud Print</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-section more-spec-section\">\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">복사</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장복사시간 (흑백)</h4>\r\n<div class=\"desc\">7.2초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">첫장복사시간 (컬러)</h4>\r\n<div class=\"desc\">9.0초 (대기모드 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 22매 (A4 기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">600 x 600 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">축소 / 확대 배율</h4>\r\n<div class=\"desc\">25% - 400%</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">여러장 복사</h4>\r\n<div class=\"desc\">9999 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">복사 특수기능</h4>\r\n<div class=\"desc\">신분증 복사, 모아 찍기, 소책자, 자동 맞춤, 책 복사, 포스터 복사, 워터마트, 이미지 오버레이, 스탬프, 표지, 작업 빌드</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">스캔</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (흑백)</h4>\r\n<div class=\"desc\">분당 최대 45매 (300 dpi, A4기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">속도 (컬러)</h4>\r\n<div class=\"desc\">분당 최대 45매 (300 dpi, A4기준)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">호환</h4>\r\n<div class=\"desc\">Network TWAIN, Network SANE</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도 (광학)</h4>\r\n<div class=\"desc\">600 x 600 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도 (학장)</h4>\r\n<div class=\"desc\">4,800 x 4,800 dpi</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">전송방식</h4>\r\n<div class=\"desc\">Email, FTP, SMB, HDD, DFS, USB, WSD, PC</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">팩스</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">호환</h4>\r\n<div class=\"desc\">ITU-T G3, Super G3</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">모뎀속도</h4>\r\n<div class=\"desc\">33.6 kbps</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">해상도</h4>\r\n<div class=\"desc\">최고 600 x 600 dpi (흑백)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">메모리</h4>\r\n<div class=\"desc\">HDD 백업</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동다이얼</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">팩스 특수기능</h4>\r\n<div class=\"desc\">고속 호출, 그룹 호출, On-hook Dial, 자동 재호출, Caller ID, 보안 수신, 팩스 재전송 외</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">용지 취급</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (표준 용지함)</h4>\r\n<div class=\"desc\">1,040 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (다목적 용지함)</h4>\r\n<div class=\"desc\">100 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (옵션 용지함)</h4>\r\n<div class=\"desc\">520 매 옵션 용지함 x 2</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지용량 (최대)</h4>\r\n<div class=\"desc\">2,180 매 ( 1,140 매 기본 + 1,040 매 옵션)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (표준 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (다목적 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 라벨 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지 / 봉투</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 종류 (옵션 용지함)</h4>\r\n<div class=\"desc\">일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (표준 용지함)</h4>\r\n<div class=\"desc\">카세트1: 148 x 210 mm ~ 297 x 354 mm / 카세트2: 148 x 210 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (다목적 용지함)</h4>\r\n<div class=\"desc\">98 x 148 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 규격 (옵션 용지함)</h4>\r\n<div class=\"desc\">148 x 210 mm ~ 297 x 432 mm</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (표준 용지함)</h4>\r\n<div class=\"desc\">일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (다목적 용지함)</h4>\r\n<div class=\"desc\">일반: 60~176g/㎡ (단면, 양면) / 봉투: 75~90g/㎡(단면) / 라벨: 120~150 g/㎡ (단면)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">급지 지원용지 무게 (옵션 용지함)</h4>\r\n<div class=\"desc\">일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동원고급지 종류</h4>\r\n<div class=\"desc\">RADF</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">자동원고급지 용량</h4>\r\n<div class=\"desc\">100 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">배지 용량</h4>\r\n<div class=\"desc\">500매 (기본)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">소모품</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">토너 카트리지 (블랙)</h4>\r\n<div class=\"desc\">20,000 매 (5% 챠트)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">토너 카트리지 (컬러)</h4>\r\n<div class=\"desc\">15,000 매 (5% 챠트)</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">이미징 유니트/드럼 (블랙)</h4>\r\n<div class=\"desc\">50,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">이미징 유니트/드럼 (컬러)</h4>\r\n<div class=\"desc\">50,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">현상기</h4>\r\n<div class=\"desc\">300,000 매</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">폐토너통</h4>\r\n<div class=\"desc\">약 33,700 매 (5% 챠트)</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">옵션</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">옵션</h4>\r\n<div class=\"desc\">캐비닛 스탠드, 2단 급지 장치, 작업 분류기, 팩스 키트, 외부 장치 인터페이스 키트, 무선네트워크/NFC</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">솔루션</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">기기 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">출력 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">문서 관리</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">보안</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">모바일</h4>\r\n<div class=\"desc\">있음</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class=\"spec-list\">\r\n<h3 class=\"tit\">KCC인증정보</h3>\r\n<ul>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증번호</h4>\r\n<div class=\"desc\">MSIP-RMM-SEC-SLX3280NR</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증기관</h4>\r\n<div class=\"desc\">국립전파연구원</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class=\"spec\">\r\n<h4 class=\"sub-tit\">인증일자</h4>\r\n<div class=\"desc\">2015년 09월 08일</div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>','\r\n\r\n특장점\r\n\r\n\r\n\r\n\r\n\r\n직관적인 UI 및 터치패널\r\n직관적인 하드웨어 조작부의 UI를 바탕으로 17.8cm 컬러 터치 패널(800 x 480 해상도)을 통해 원하는 메뉴를 터치하면 손쉽게 기능이 실행됩니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n손쉬운 화면설정 및 출력작업\r\n최대 40개의 바로가기 지원(스캔/인쇄), 복합기 설정 검색 기능 제공 과 컬러 터치 스크린 패널로 스마트폰 UI 그대로 페이지를 옆으로 넘기거나 아래로 내려보는 스크롤이 가능하여 사용이 편리합니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n다양한 기업용 솔루션 선택 적용\r\nBCPS란? 비즈니스 코어 프린팅 솔루션(Business Core Printing Solution)의 약자로 기업용 프린팅 솔루션으로 보안, 클라우드, 문서관리, 스캔, 모니터링 등 5가지 솔루션으로 구성되어 있습니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n다양한 옵션 장착\r\n다양한 옵션 장착을 제공합니다.\r\n\r\n\r\n\r\n* HDD는 모노기에만 장착 가능 (컬러기는 기본 장착)\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n더욱 효율적인 업무 공간\r\n슬림하고 컴팩트한 디자인으로 협소한 공간에서도 최적의 업무 공간 창조를 실현합니다. (공간을 절약하는 내장형 피니셔, 내장형 카드리더)\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n강력한 보안\r\n최근 부각되는 인쇄물 보안 이슈로 인해 중요 용지를 용지 함 잠금 기능을 통해 사용하거나 PC에서 조차 남기지 않는 자료를 문서서버로 활용하여 보안 기능을 한층 강화한 활용성을 갖추어 강력한 보안과 편의성을 동시에 만족시킬 수 있습니다.\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n모바일 프린팅 사용성 강화\r\n사용자별 UI 개인 설정을 통해 사용자 프로파일 이미지, 언어, 키보드, 배경화면, 홈 스크린 기본 앱 등 쉽고 빠른 사용이 편리합니다. 개인 모바일 디바이스와 같은 스마트폰을 활용한 컨트롤도 가능합니다.(옵션)\r\n\r\n\r\n\r\n\r\n\r\n스펙\r\n\r\n\r\n\r\n\r\n\r\n기본 사양\r\n\r\n\r\n\r\n기본 기능\r\n인쇄, 복사, 스캔, 네트워크, 양면 인쇄\r\n\r\n\r\n\r\n\r\n특수 기능\r\n팩스, 네트워크 PC 팩스\r\n\r\n\r\n\r\n\r\n프로세서\r\n1GHz (듀얼 코어)\r\n\r\n\r\n\r\n\r\n디스플레이\r\n17.8cm 컬러 터치 LCD, 800 x 480 (WVGA)\r\n\r\n\r\n\r\n\r\n메모리 (기본)\r\n2 GB\r\n\r\n\r\n\r\n\r\n메모리 (최대)\r\n2 GB\r\n\r\n\r\n\r\n\r\n하드 디스크\r\n320 GB 혹은 320 GB이상 (사용자 가용 공간: 300 GB)\r\n\r\n\r\n\r\n\r\n인터페이스 (기본)\r\nUSB 2.0, Ethernet 10/100/1G BASE TX\r\n\r\n\r\n\r\n\r\n인터페이스 (옵션)\r\nIEEE 802.11b/g/n + NFC Active Type\r\n\r\n\r\n\r\n\r\n예열시간 (From Power Off)\r\n25 초\r\n\r\n\r\n\r\n\r\n예열시간 (From Sleep)\r\n21 초\r\n\r\n\r\n\r\n\r\n소비전력\r\n1.75 kWh (최대), 60 Wh (대기), 1.5 W (절전모드)\r\n\r\n\r\n\r\n\r\n최대 소음\r\n53 dBA (복사), 48 dBA (인쇄), 30 dBA (대기)\r\n\r\n\r\n\r\n\r\n제품크기 (가로x세로x높이)\r\n566 x 620 x 870 mm\r\n\r\n\r\n\r\n\r\n최대 제품크기 (가로x세로x높이)\r\n566 x 620 x 1,130 mm\r\n\r\n\r\n\r\n\r\n무게\r\n78.20 kg (세트, RADF, 드럼, 폐토너통)\r\n\r\n\r\n\r\n\r\n월 최대 출력매수\r\n60,000 매\r\n\r\n\r\n\r\n\r\n월 권장 출력매수\r\n3,500 매\r\n\r\n\r\n\r\n\r\n보안\r\nSSL/TLS, IP Sec, SNMPv3, Protocol&amp;Port Management, IPv6, IP/MAC Filtering, IEEE 802.1x\r\n\r\n\r\n\r\n\r\n제조 국가\r\n중국\r\n\r\n\r\n\r\n\r\n제조사\r\n2015년 10월\r\n\r\n\r\n\r\n\r\n품질 보증 기간\r\n1년\r\n\r\n\r\n\r\n\r\n정격 전압\r\nAC220-240V~, 50/60Hz, 4.0A\r\n\r\n\r\n\r\n\r\n지원운영체제\r\nWindows: Window XP(32/64bit), Vista(32/64bit), 2003 Server(32/64bit), 2008 Server(32/64bit), Win7(32/64bit), 2008 Server R2(64bit), Win8(32/64bit), Win8.1(32bit/64bit), 2012 Server(64bit), 2012 Server R2(64bit) / Mac OS 10.6 ~ 10.10 /  Linux: Red Hat Enterprise Linux 5, 6, 7 / Fedora 13, 14, 15, 16, 17, 18, 19, 20, 21 / openSUSE 11.3, 11.4, 12.1, 12.2, 12.3, 13.1 ,13.2 / Ubuntu 10.04, 10.10, 11.04, 11.10, 12.04, 12.10, 13.04, 13.10, 14.04, 14.10 / SUSE Linux Enterprise Desktop 11, 12 /  Debian 6 ,7 / Mint 13, 14, 15, 16, 17 / Unix: Sun Solaris 9, 10, 11 (x86, SPARC) / HP-UX 11.0, 11i v1, 11i v2, 11i v3 (PA-RISC, Itanium) / IBM AIX 5.1, 5.2, 5.3, 5.4, 6.1, 7.1 (PowerPC)\r\n\r\n\r\n\r\n\r\n네트워크 프로토콜\r\nIP Management(DHCP, BOOTP, AutoIP, SetIP, Static) / Discovery Protocol (SLP, UPnP, Bonjour, DNS, WINS) / Printing Protocol(TCP/IP, LPR, IPP, WSD) / Management Protocol(SNMPv1.2, SNMP3, POP3, SMTP, Telnet) / Scan Protocol(SMTP, FTP, SMB, WSD) / Security Protocol(SMB, Kerberos, LDAP, IPsec, EAP)\r\n\r\n\r\n\r\n\r\n\r\n인쇄\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n첫장출력시간 (흑백)\r\n10초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n첫장출력시간 (컬러)\r\n12초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n해상도\r\n1,200 x 1,200 dpi (속도 감소)\r\n\r\n\r\n\r\n\r\n에뮬레이션\r\nPCL5e, PCL6,  PostScript3, PDF V1.7\r\n\r\n\r\n\r\n\r\n양면인쇄\r\n자동 지원\r\n\r\n\r\n\r\n\r\n다이렉트 프린트 지원\r\nPRN,PDF,TIFF,JPEG,XPS\r\n\r\n\r\n\r\n\r\n인쇄 특수기능\r\nWSD 인쇄, 보안 인쇄, 저장후 인쇄, 책 형식, 모아 인쇄, 표지 인쇄, 페이지 삽입, 페이지 삭제, 바코드, 에코, 포스터, 광택, 워트마크, 트레이 우선 순위 선정, 트레이 자동 설정, USB 인쇄, Secure PDF 인쇄, Google Gloud Print\r\n\r\n\r\n\r\n\r\n\r\n\r\n복사\r\n\r\n\r\n\r\n첫장복사시간 (흑백)\r\n7.2초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n첫장복사시간 (컬러)\r\n9.0초 (대기모드 기준)\r\n\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 22매 (A4 기준)\r\n\r\n\r\n\r\n\r\n해상도\r\n600 x 600 dpi\r\n\r\n\r\n\r\n\r\n축소 / 확대 배율\r\n25% - 400%\r\n\r\n\r\n\r\n\r\n여러장 복사\r\n9999 매\r\n\r\n\r\n\r\n\r\n복사 특수기능\r\n신분증 복사, 모아 찍기, 소책자, 자동 맞춤, 책 복사, 포스터 복사, 워터마트, 이미지 오버레이, 스탬프, 표지, 작업 빌드\r\n\r\n\r\n\r\n\r\n\r\n스캔\r\n\r\n\r\n\r\n속도 (흑백)\r\n분당 최대 45매 (300 dpi, A4기준)\r\n\r\n\r\n\r\n\r\n속도 (컬러)\r\n분당 최대 45매 (300 dpi, A4기준)\r\n\r\n\r\n\r\n\r\n호환\r\nNetwork TWAIN, Network SANE\r\n\r\n\r\n\r\n\r\n해상도 (광학)\r\n600 x 600 dpi\r\n\r\n\r\n\r\n\r\n해상도 (학장)\r\n4,800 x 4,800 dpi\r\n\r\n\r\n\r\n\r\n전송방식\r\nEmail, FTP, SMB, HDD, DFS, USB, WSD, PC\r\n\r\n\r\n\r\n\r\n\r\n팩스\r\n\r\n\r\n\r\n호환\r\nITU-T G3, Super G3\r\n\r\n\r\n\r\n\r\n모뎀속도\r\n33.6 kbps\r\n\r\n\r\n\r\n\r\n해상도\r\n최고 600 x 600 dpi (흑백)\r\n\r\n\r\n\r\n\r\n메모리\r\nHDD 백업\r\n\r\n\r\n\r\n\r\n자동다이얼\r\n있음\r\n\r\n\r\n\r\n\r\n팩스 특수기능\r\n고속 호출, 그룹 호출, On-hook Dial, 자동 재호출, Caller ID, 보안 수신, 팩스 재전송 외\r\n\r\n\r\n\r\n\r\n\r\n용지 취급\r\n\r\n\r\n\r\n급지용량 (표준 용지함)\r\n1,040 매\r\n\r\n\r\n\r\n\r\n급지용량 (다목적 용지함)\r\n100 매\r\n\r\n\r\n\r\n\r\n급지용량 (옵션 용지함)\r\n520 매 옵션 용지함 x 2\r\n\r\n\r\n\r\n\r\n급지용량 (최대)\r\n2,180 매 ( 1,140 매 기본 + 1,040 매 옵션)\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (표준 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (다목적 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 라벨 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지 / 봉투\r\n\r\n\r\n\r\n\r\n급지 지원용지 종류 (옵션 용지함)\r\n일반 용지 / 얇은 용지 / 본드 / 펀치 용지 / 인쇄된 용지 / 재생 용지 / 카드 / 편지 / 두꺼운 용지 / 코튼 용지 / 색지 / 아카이브 / 광택 용지\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (표준 용지함)\r\n카세트1: 148 x 210 mm ~ 297 x 354 mm / 카세트2: 148 x 210 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (다목적 용지함)\r\n98 x 148 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 규격 (옵션 용지함)\r\n148 x 210 mm ~ 297 x 432 mm\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (표준 용지함)\r\n일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (다목적 용지함)\r\n일반: 60~176g/㎡ (단면, 양면) / 봉투: 75~90g/㎡(단면) / 라벨: 120~150 g/㎡ (단면)\r\n\r\n\r\n\r\n\r\n급지 지원용지 무게 (옵션 용지함)\r\n일반 용지: 70~90g/㎡ / 두꺼운 용지: 91~105g/㎡ / 무거운 용지: 106~176g/㎡ / 더 무거운 용지: 177 ~ 220g/㎡ / 얇은 용지: 60~69g/㎡ / 본드: 105~120g/㎡ / 펀치 용지: 75~90g/㎡ / 인쇄된 용지 : 75~90g/㎡ / 재생 용지: 60~90g/㎡ / 카드: 106~163g/㎡ / 편지: 75~90g/㎡ / 코튼 : 75~90g/㎡ / 광택지: 106~163g/㎡\r\n\r\n\r\n\r\n\r\n자동원고급지 종류\r\nRADF\r\n\r\n\r\n\r\n\r\n자동원고급지 용량\r\n100 매\r\n\r\n\r\n\r\n\r\n배지 용량\r\n500매 (기본)\r\n\r\n\r\n\r\n\r\n\r\n소모품\r\n\r\n\r\n\r\n토너 카트리지 (블랙)\r\n20,000 매 (5% 챠트)\r\n\r\n\r\n\r\n\r\n토너 카트리지 (컬러)\r\n15,000 매 (5% 챠트)\r\n\r\n\r\n\r\n\r\n이미징 유니트/드럼 (블랙)\r\n50,000 매\r\n\r\n\r\n\r\n\r\n이미징 유니트/드럼 (컬러)\r\n50,000 매\r\n\r\n\r\n\r\n\r\n현상기\r\n300,000 매\r\n\r\n\r\n\r\n\r\n폐토너통\r\n약 33,700 매 (5% 챠트)\r\n\r\n\r\n\r\n\r\n\r\n옵션\r\n\r\n\r\n\r\n옵션\r\n캐비닛 스탠드, 2단 급지 장치, 작업 분류기, 팩스 키트, 외부 장치 인터페이스 키트, 무선네트워크/NFC\r\n\r\n\r\n\r\n\r\n\r\n솔루션\r\n\r\n\r\n\r\n기기 관리\r\n있음\r\n\r\n\r\n\r\n\r\n출력 관리\r\n있음\r\n\r\n\r\n\r\n\r\n문서 관리\r\n있음\r\n\r\n\r\n\r\n\r\n보안\r\n있음\r\n\r\n\r\n\r\n\r\n모바일\r\n있음\r\n\r\n\r\n\r\n\r\n\r\nKCC인증정보\r\n\r\n\r\n\r\n인증번호\r\nMSIP-RMM-SEC-SLX3280NR\r\n\r\n\r\n\r\n\r\n인증기관\r\n국립전파연구원\r\n\r\n\r\n\r\n\r\n인증일자\r\n2015년 09월 08일\r\n\r\n\r\n\r\n\r\n\r\n\r\n','',0,1327810,0,0,0,0,'',1,0,0,99999,0,10,1,0,50000,0,0,0,0,'','','','',13,'2017-11-20 15:55:54','2017-11-21 10:07:44','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:19:\"디지털 복합기\";s:10:\"model_name\";s:10:\"SL-X3220NR\";s:13:\"certification\";s:22:\"MSIP-RMM-SEC-SLX3280NR\";s:13:\"rated_voltage\";s:26:\"AC220-240V~, 50/60Hz, 4.0A\";s:17:\"power_consumption\";s:55:\"1.75 kWh (최대), 60 Wh (대기), 1.5 W (절전모드)\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:13:\"2015년 10월\";s:5:\"maker\";s:12:\"삼성전자\";s:6:\"madein\";s:6:\"중국\";s:4:\"size\";s:20:\"566 x 620 x 1,130 mm\";s:6:\"weight\";s:45:\"78.20 kg (세트, RADF, 드럼, 폐토너통)\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:4:\"1년\";s:2:\"as\";s:9:\"1800-7540\";}',0,0,0.0,'','','1511225688/img1.jpg','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''),('1511250825','10','','','theme/basic','theme/basic','HP OfficeJet 7610','HP','중국산','HP','HP OfficeJet 7610','','',1,0,0,0,0,'HP 정품 오피스젯7610 와이드포맷 e-복합기(정품/자동양면인쇄장치포함)/인쇄+복사+스캔+팩스','<p align=\"center\" style=\"text-align: center;\"><img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511251053_4844.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511251053_4844.jpg\"><div align=\"center\" style=\"text-align: center;\"></div><p><br style=\"clear: both;\">&nbsp;</p>','&nbsp;','',0,249990,0,0,0,0,'',1,0,0,99999,0,10,0,0,0,0,0,0,0,'','','','',2,'2017-11-21 17:07:45','2017-11-21 17:09:17','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:12:\"HP OfficeJet\";s:10:\"model_name\";s:17:\"HP OfficeJet 7610\";s:13:\"certification\";s:22:\"상품페이지 참고\";s:13:\"rated_voltage\";s:22:\"상품페이지 참고\";s:17:\"power_consumption\";s:45:\"인쇄시 27.7W / 슬립1.9W / 대기중 4.6W\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:13:\"2013년 08월\";s:5:\"maker\";s:2:\"HP\";s:6:\"madein\";s:6:\"중국\";s:4:\"size\";s:22:\" 61.7 x 29.7 x 48.7 cm\";s:6:\"weight\";s:7:\"16.2 kg\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:22:\"상품페이지 참고\";s:2:\"as\";s:9:\"1800-7540\";}',0,0,0.0,'','','1511250825/img1.jpg','1511250825/img2.jpg','1511250825/img3.jpg','1511250825/img4.jpg','','','','','','','','','','','','','','','','','','','','','','','','','',''),('1511252236','10','','','theme/basic','theme/basic','HP OfficeJet Pro 7610 e-복합기','HP','중국산','HP','HP OfficeJet Pro 7610','','',1,0,0,0,0,'e복합기/무선/FAX/양면','<p align=\"center\" style=\"text-align: center;\"><img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511252362_3342.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511252362_3342.jpg\"><div align=\"center\" style=\"text-align: center;\"></div><p><br style=\"clear: both;\">&nbsp;</p>','&nbsp;','',0,165450,0,0,0,0,'',1,0,0,99999,0,10,0,0,0,0,0,0,0,'','','','',3,'2017-11-21 17:19:58','2017-11-21 17:19:58','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:22:\"상품페이지 참고\";s:10:\"model_name\";s:22:\"상품페이지 참고\";s:13:\"certification\";s:22:\"상품페이지 참고\";s:13:\"rated_voltage\";s:22:\"상품페이지 참고\";s:17:\"power_consumption\";s:22:\"상품페이지 참고\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:22:\"상품페이지 참고\";s:5:\"maker\";s:22:\"상품페이지 참고\";s:6:\"madein\";s:22:\"상품페이지 참고\";s:4:\"size\";s:22:\"상품페이지 참고\";s:6:\"weight\";s:22:\"상품페이지 참고\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:22:\"상품페이지 참고\";s:2:\"as\";s:22:\"상품페이지 참고\";}',0,0,0.0,'','','1511252236/img1.jpg','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''),('1511252400','10','','','theme/basic','theme/basic','HP OfficeJet 8610','HP','중국산','','','','',1,0,0,0,0,'팩스복합기 무한잉크프린터 + 무한잉크공급기','<p align=\"center\" style=\"text-align: center;\"><img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511254250_7858.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511254250_7858.jpg\"><br style=\"clear: both;\">&nbsp;</p>','&nbsp;','',0,345600,0,0,0,0,'',1,0,0,99999,0,10,0,0,0,0,0,0,0,'','','','',1,'2017-11-21 17:45:35','2017-11-21 17:50:55','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:22:\"상품페이지 참고\";s:10:\"model_name\";s:22:\"상품페이지 참고\";s:13:\"certification\";s:22:\"상품페이지 참고\";s:13:\"rated_voltage\";s:22:\"상품페이지 참고\";s:17:\"power_consumption\";s:22:\"상품페이지 참고\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:22:\"상품페이지 참고\";s:5:\"maker\";s:22:\"상품페이지 참고\";s:6:\"madein\";s:22:\"상품페이지 참고\";s:4:\"size\";s:22:\"상품페이지 참고\";s:6:\"weight\";s:22:\"상품페이지 참고\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:22:\"상품페이지 참고\";s:2:\"as\";s:22:\"상품페이지 참고\";}',0,0,0.0,'','','1511252400/img1.jpg','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''),('1511254288','10','','','theme/basic','theme/basic','HP OfficeJet 8620','HP','중국산','HP','HP OfficeJet 8620','','',1,0,0,0,0,'팩스복합기 무한잉크프린터 + 무한잉크공급기','<p align=\"center\" style=\"text-align: center;\"><img title=\"e09173fb69a7c9a3941a2aea5f6decbb_1511254370_9581.jpg\" src=\"http://dhn.webthink.co.kr/shop/data/editor/1711/e09173fb69a7c9a3941a2aea5f6decbb_1511254370_9581.jpg\"><br style=\"clear: both;\">&nbsp;</p>','&nbsp;','',0,364800,0,0,0,0,'',1,0,0,99999,0,10,0,0,0,0,0,0,0,'','','','',4,'2017-11-21 17:45:35','2017-11-21 17:53:10','112.163.89.66',0,0,'office_appliances','a:14:{s:12:\"product_name\";s:22:\"상품페이지 참고\";s:10:\"model_name\";s:22:\"상품페이지 참고\";s:13:\"certification\";s:22:\"상품페이지 참고\";s:13:\"rated_voltage\";s:22:\"상품페이지 참고\";s:17:\"power_consumption\";s:22:\"상품페이지 참고\";s:17:\"energy_efficiency\";s:22:\"상품페이지 참고\";s:13:\"released_date\";s:22:\"상품페이지 참고\";s:5:\"maker\";s:22:\"상품페이지 참고\";s:6:\"madein\";s:22:\"상품페이지 참고\";s:4:\"size\";s:22:\"상품페이지 참고\";s:6:\"weight\";s:22:\"상품페이지 참고\";s:13:\"specification\";s:22:\"상품페이지 참고\";s:8:\"warranty\";s:22:\"상품페이지 참고\";s:2:\"as\";s:22:\"상품페이지 참고\";}',0,0,0.0,'','','1511254288/img1.jpg','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `g5_shop_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_item_option`
--

DROP TABLE IF EXISTS `g5_shop_item_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_item_option` (
  `io_no` int(11) NOT NULL AUTO_INCREMENT,
  `io_id` varchar(255) NOT NULL DEFAULT '0',
  `io_type` tinyint(4) NOT NULL DEFAULT '0',
  `it_id` varchar(20) NOT NULL DEFAULT '',
  `io_price` int(11) NOT NULL DEFAULT '0',
  `io_stock_qty` int(11) NOT NULL DEFAULT '0',
  `io_noti_qty` int(11) NOT NULL DEFAULT '0',
  `io_use` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`io_no`),
  KEY `io_id` (`io_id`),
  KEY `it_id` (`it_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_item_option`
--

LOCK TABLES `g5_shop_item_option` WRITE;
/*!40000 ALTER TABLE `g5_shop_item_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_item_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_item_qa`
--

DROP TABLE IF EXISTS `g5_shop_item_qa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_item_qa` (
  `iq_id` int(11) NOT NULL AUTO_INCREMENT,
  `it_id` varchar(20) NOT NULL DEFAULT '',
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `iq_secret` tinyint(4) NOT NULL DEFAULT '0',
  `iq_name` varchar(255) NOT NULL DEFAULT '',
  `iq_email` varchar(255) NOT NULL DEFAULT '',
  `iq_hp` varchar(255) NOT NULL DEFAULT '',
  `iq_password` varchar(255) NOT NULL DEFAULT '',
  `iq_subject` varchar(255) NOT NULL DEFAULT '',
  `iq_question` text NOT NULL,
  `iq_answer` text NOT NULL,
  `iq_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `iq_ip` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`iq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_item_qa`
--

LOCK TABLES `g5_shop_item_qa` WRITE;
/*!40000 ALTER TABLE `g5_shop_item_qa` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_item_qa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_item_relation`
--

DROP TABLE IF EXISTS `g5_shop_item_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_item_relation` (
  `it_id` varchar(20) NOT NULL DEFAULT '',
  `it_id2` varchar(20) NOT NULL DEFAULT '',
  `ir_no` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`it_id`,`it_id2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_item_relation`
--

LOCK TABLES `g5_shop_item_relation` WRITE;
/*!40000 ALTER TABLE `g5_shop_item_relation` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_item_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_item_stocksms`
--

DROP TABLE IF EXISTS `g5_shop_item_stocksms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_item_stocksms` (
  `ss_id` int(11) NOT NULL AUTO_INCREMENT,
  `it_id` varchar(20) NOT NULL DEFAULT '',
  `ss_hp` varchar(255) NOT NULL DEFAULT '',
  `ss_send` tinyint(4) NOT NULL DEFAULT '0',
  `ss_send_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ss_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ss_ip` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`ss_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_item_stocksms`
--

LOCK TABLES `g5_shop_item_stocksms` WRITE;
/*!40000 ALTER TABLE `g5_shop_item_stocksms` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_item_stocksms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_item_use`
--

DROP TABLE IF EXISTS `g5_shop_item_use`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_item_use` (
  `is_id` int(11) NOT NULL AUTO_INCREMENT,
  `it_id` varchar(20) NOT NULL DEFAULT '0',
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `is_name` varchar(255) NOT NULL DEFAULT '',
  `is_password` varchar(255) NOT NULL DEFAULT '',
  `is_score` tinyint(4) NOT NULL DEFAULT '0',
  `is_subject` varchar(255) NOT NULL DEFAULT '',
  `is_content` text NOT NULL,
  `is_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_ip` varchar(25) NOT NULL DEFAULT '',
  `is_confirm` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`is_id`),
  KEY `index1` (`it_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_item_use`
--

LOCK TABLES `g5_shop_item_use` WRITE;
/*!40000 ALTER TABLE `g5_shop_item_use` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_item_use` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_order`
--

DROP TABLE IF EXISTS `g5_shop_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_order` (
  `od_id` bigint(20) unsigned NOT NULL,
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `od_name` varchar(20) NOT NULL DEFAULT '',
  `od_email` varchar(100) NOT NULL DEFAULT '',
  `od_tel` varchar(20) NOT NULL DEFAULT '',
  `od_hp` varchar(20) NOT NULL DEFAULT '',
  `od_zip1` char(3) NOT NULL DEFAULT '',
  `od_zip2` char(3) NOT NULL DEFAULT '',
  `od_addr1` varchar(100) NOT NULL DEFAULT '',
  `od_addr2` varchar(100) NOT NULL DEFAULT '',
  `od_addr3` varchar(255) NOT NULL DEFAULT '',
  `od_addr_jibeon` varchar(255) NOT NULL DEFAULT '',
  `od_deposit_name` varchar(20) NOT NULL DEFAULT '',
  `od_b_name` varchar(20) NOT NULL DEFAULT '',
  `od_b_tel` varchar(20) NOT NULL DEFAULT '',
  `od_b_hp` varchar(20) NOT NULL DEFAULT '',
  `od_b_zip1` char(3) NOT NULL DEFAULT '',
  `od_b_zip2` char(3) NOT NULL DEFAULT '',
  `od_b_addr1` varchar(100) NOT NULL DEFAULT '',
  `od_b_addr2` varchar(100) NOT NULL DEFAULT '',
  `od_b_addr3` varchar(255) NOT NULL DEFAULT '',
  `od_b_addr_jibeon` varchar(255) NOT NULL DEFAULT '',
  `od_memo` text NOT NULL,
  `od_cart_count` int(11) NOT NULL DEFAULT '0',
  `od_cart_price` int(11) NOT NULL DEFAULT '0',
  `od_cart_coupon` int(11) NOT NULL DEFAULT '0',
  `od_send_cost` int(11) NOT NULL DEFAULT '0',
  `od_send_cost2` int(11) NOT NULL DEFAULT '0',
  `od_send_coupon` int(11) NOT NULL DEFAULT '0',
  `od_receipt_price` int(11) NOT NULL DEFAULT '0',
  `od_cancel_price` int(11) NOT NULL DEFAULT '0',
  `od_receipt_point` int(11) NOT NULL DEFAULT '0',
  `od_refund_price` int(11) NOT NULL DEFAULT '0',
  `od_bank_account` varchar(255) NOT NULL DEFAULT '',
  `od_receipt_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `od_coupon` int(11) NOT NULL DEFAULT '0',
  `od_misu` int(11) NOT NULL DEFAULT '0',
  `od_shop_memo` text NOT NULL,
  `od_mod_history` text NOT NULL,
  `od_status` varchar(255) NOT NULL DEFAULT '',
  `od_hope_date` date NOT NULL DEFAULT '0000-00-00',
  `od_settle_case` varchar(255) NOT NULL DEFAULT '',
  `od_test` tinyint(4) NOT NULL DEFAULT '0',
  `od_mobile` tinyint(4) NOT NULL DEFAULT '0',
  `od_pg` varchar(255) NOT NULL DEFAULT '',
  `od_tno` varchar(255) NOT NULL DEFAULT '',
  `od_app_no` varchar(20) NOT NULL DEFAULT '',
  `od_escrow` tinyint(4) NOT NULL DEFAULT '0',
  `od_casseqno` varchar(255) NOT NULL DEFAULT '',
  `od_tax_flag` tinyint(4) NOT NULL DEFAULT '0',
  `od_tax_mny` int(11) NOT NULL DEFAULT '0',
  `od_vat_mny` int(11) NOT NULL DEFAULT '0',
  `od_free_mny` int(11) NOT NULL DEFAULT '0',
  `od_delivery_company` varchar(255) NOT NULL DEFAULT '0',
  `od_invoice` varchar(255) NOT NULL DEFAULT '',
  `od_invoice_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `od_cash` tinyint(4) NOT NULL,
  `od_cash_no` varchar(255) NOT NULL,
  `od_cash_info` text NOT NULL,
  `od_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `od_pwd` varchar(255) NOT NULL DEFAULT '',
  `od_ip` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`od_id`),
  KEY `index2` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_order`
--

LOCK TABLES `g5_shop_order` WRITE;
/*!40000 ALTER TABLE `g5_shop_order` DISABLE KEYS */;
INSERT INTO `g5_shop_order` VALUES (2017112017482399,'admin','최고관리자','pm@webthink.co.kr','055-238-9456','055-238-9456','511','81','경남 창원시 의창구 창이대로205번길 5','2층 202호',' (봉곡동, 봉곡프라자)','R','최고관리자','최고관리자','055-238-9456','055-238-9456','511','81','경남 창원시 의창구 창이대로205번길 5','2층 202호',' (봉곡동, 봉곡프라자)','R','',1,1979180,0,0,0,0,0,0,0,0,'OO은행 12345-67-89012 예금주명','0000-00-00 00:00:00',0,1979180,'','','주문','0000-00-00','무통장',1,0,'kcp','','',0,'',0,1799255,179925,0,'0','','0000-00-00 00:00:00',0,'','','2017-11-20 17:50:18','*9E01B4E7EDADE9787F746F6C490278ED4C3BAEB2','112.163.89.66');
/*!40000 ALTER TABLE `g5_shop_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_order_address`
--

DROP TABLE IF EXISTS `g5_shop_order_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_order_address` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `ad_subject` varchar(255) NOT NULL DEFAULT '',
  `ad_default` tinyint(4) NOT NULL DEFAULT '0',
  `ad_name` varchar(255) NOT NULL DEFAULT '',
  `ad_tel` varchar(255) NOT NULL DEFAULT '',
  `ad_hp` varchar(255) NOT NULL DEFAULT '',
  `ad_zip1` char(3) NOT NULL DEFAULT '',
  `ad_zip2` char(3) NOT NULL DEFAULT '',
  `ad_addr1` varchar(255) NOT NULL DEFAULT '',
  `ad_addr2` varchar(255) NOT NULL DEFAULT '',
  `ad_addr3` varchar(255) NOT NULL DEFAULT '',
  `ad_jibeon` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ad_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_order_address`
--

LOCK TABLES `g5_shop_order_address` WRITE;
/*!40000 ALTER TABLE `g5_shop_order_address` DISABLE KEYS */;
INSERT INTO `g5_shop_order_address` VALUES (1,'admin','',0,'최고관리자','055-238-9456','055-238-9456','511','81','경남 창원시 의창구 창이대로205번길 5','2층 202호',' (봉곡동, 봉곡프라자)','R');
/*!40000 ALTER TABLE `g5_shop_order_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_order_data`
--

DROP TABLE IF EXISTS `g5_shop_order_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_order_data` (
  `od_id` bigint(20) unsigned NOT NULL,
  `cart_id` bigint(20) unsigned NOT NULL,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `dt_pg` varchar(255) NOT NULL DEFAULT '',
  `dt_data` text NOT NULL,
  `dt_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `od_id` (`od_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_order_data`
--

LOCK TABLES `g5_shop_order_data` WRITE;
/*!40000 ALTER TABLE `g5_shop_order_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_order_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_order_delete`
--

DROP TABLE IF EXISTS `g5_shop_order_delete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_order_delete` (
  `de_id` int(11) NOT NULL AUTO_INCREMENT,
  `de_key` varchar(255) NOT NULL DEFAULT '',
  `de_data` longtext NOT NULL,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `de_ip` varchar(255) NOT NULL DEFAULT '',
  `de_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`de_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_order_delete`
--

LOCK TABLES `g5_shop_order_delete` WRITE;
/*!40000 ALTER TABLE `g5_shop_order_delete` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_order_delete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_personalpay`
--

DROP TABLE IF EXISTS `g5_shop_personalpay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_personalpay` (
  `pp_id` bigint(20) unsigned NOT NULL,
  `od_id` bigint(20) unsigned NOT NULL,
  `pp_name` varchar(255) NOT NULL DEFAULT '',
  `pp_email` varchar(255) NOT NULL DEFAULT '',
  `pp_hp` varchar(255) NOT NULL DEFAULT '',
  `pp_content` text NOT NULL,
  `pp_use` tinyint(4) NOT NULL DEFAULT '0',
  `pp_price` int(11) NOT NULL DEFAULT '0',
  `pp_pg` varchar(255) NOT NULL DEFAULT '',
  `pp_tno` varchar(255) NOT NULL DEFAULT '',
  `pp_app_no` varchar(20) NOT NULL DEFAULT '',
  `pp_casseqno` varchar(255) NOT NULL DEFAULT '',
  `pp_receipt_price` int(11) NOT NULL DEFAULT '0',
  `pp_settle_case` varchar(255) NOT NULL DEFAULT '',
  `pp_bank_account` varchar(255) NOT NULL DEFAULT '',
  `pp_deposit_name` varchar(255) NOT NULL DEFAULT '',
  `pp_receipt_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pp_receipt_ip` varchar(255) NOT NULL DEFAULT '',
  `pp_shop_memo` text NOT NULL,
  `pp_cash` tinyint(4) NOT NULL DEFAULT '0',
  `pp_cash_no` varchar(255) NOT NULL DEFAULT '',
  `pp_cash_info` text NOT NULL,
  `pp_ip` varchar(255) NOT NULL DEFAULT '',
  `pp_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pp_id`),
  KEY `od_id` (`od_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_personalpay`
--

LOCK TABLES `g5_shop_personalpay` WRITE;
/*!40000 ALTER TABLE `g5_shop_personalpay` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_personalpay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_sendcost`
--

DROP TABLE IF EXISTS `g5_shop_sendcost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_sendcost` (
  `sc_id` int(11) NOT NULL AUTO_INCREMENT,
  `sc_name` varchar(255) NOT NULL DEFAULT '',
  `sc_zip1` varchar(10) NOT NULL DEFAULT '',
  `sc_zip2` varchar(10) NOT NULL DEFAULT '',
  `sc_price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sc_id`),
  KEY `sc_zip1` (`sc_zip1`),
  KEY `sc_zip2` (`sc_zip2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_sendcost`
--

LOCK TABLES `g5_shop_sendcost` WRITE;
/*!40000 ALTER TABLE `g5_shop_sendcost` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_sendcost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_shop_wish`
--

DROP TABLE IF EXISTS `g5_shop_wish`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_shop_wish` (
  `wi_id` int(11) NOT NULL AUTO_INCREMENT,
  `mb_id` varchar(255) NOT NULL DEFAULT '',
  `it_id` varchar(20) NOT NULL DEFAULT '0',
  `wi_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wi_ip` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`wi_id`),
  KEY `index1` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_shop_wish`
--

LOCK TABLES `g5_shop_wish` WRITE;
/*!40000 ALTER TABLE `g5_shop_wish` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_shop_wish` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_uniqid`
--

DROP TABLE IF EXISTS `g5_uniqid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_uniqid` (
  `uq_id` bigint(20) unsigned NOT NULL,
  `uq_ip` varchar(255) NOT NULL,
  PRIMARY KEY (`uq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_uniqid`
--

LOCK TABLES `g5_uniqid` WRITE;
/*!40000 ALTER TABLE `g5_uniqid` DISABLE KEYS */;
INSERT INTO `g5_uniqid` VALUES (2017112011314080,'112.163.89.66'),(2017112015040285,'112.163.89.66'),(2017112015052830,'112.163.89.66'),(2017112015064033,'112.163.89.66'),(2017112017482394,'112.163.89.66'),(2017112017482399,'112.163.89.66'),(2017112118125283,'112.163.89.66'),(2017112410462316,'112.163.89.66'),(2017112411245043,'112.163.89.66'),(2017112412564964,'211.197.42.37'),(2017112415301869,'211.197.42.37'),(2017112417084211,'112.163.89.66'),(2017112710185201,'211.197.42.37'),(2017112713200307,'112.163.89.66');
/*!40000 ALTER TABLE `g5_uniqid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_visit`
--

DROP TABLE IF EXISTS `g5_visit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_visit` (
  `vi_id` int(11) NOT NULL DEFAULT '0',
  `vi_ip` varchar(255) NOT NULL DEFAULT '',
  `vi_date` date NOT NULL DEFAULT '0000-00-00',
  `vi_time` time NOT NULL DEFAULT '00:00:00',
  `vi_referer` text NOT NULL,
  `vi_agent` varchar(255) NOT NULL DEFAULT '',
  `vi_browser` varchar(255) NOT NULL DEFAULT '',
  `vi_os` varchar(255) NOT NULL DEFAULT '',
  `vi_device` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`vi_id`),
  UNIQUE KEY `index1` (`vi_ip`,`vi_date`),
  KEY `index2` (`vi_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_visit`
--

LOCK TABLES `g5_visit` WRITE;
/*!40000 ALTER TABLE `g5_visit` DISABLE KEYS */;
INSERT INTO `g5_visit` VALUES (1,'112.163.89.66','2017-11-20','14:49:28','','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','','',''),(2,'112.163.89.66','2017-11-21','09:36:20','http://dhn.webthink.co.kr/shop/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','','',''),(3,'112.163.89.66','2017-11-22','09:30:28','','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','','',''),(4,'112.163.89.66','2017-11-23','16:41:04','','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','','',''),(5,'211.197.42.37','2017-11-24','12:55:29','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','','',''),(6,'112.163.89.66','2017-11-24','15:34:07','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','','',''),(7,'116.45.151.116','2017-11-24','23:51:13','http://dhn.webthink.co.kr/','Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-N920L Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/6.2 Chrome/56.0.2924.87 Mobile Safari/537.36','','',''),(8,'1.226.241.15','2017-11-26','02:33:17','http://210.114.225.53/','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','','',''),(9,'211.197.42.37','2017-11-27','10:17:07','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','','',''),(10,'112.163.89.66','2017-11-27','13:17:58','http://dhn.webthink.co.kr/','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36','','',''),(11,'211.197.42.37','2017-11-28','14:25:05','http://210.114.225.53/','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','','',''),(12,'112.163.89.66','2017-11-28','14:48:39','','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko','','','');
/*!40000 ALTER TABLE `g5_visit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_visit_sum`
--

DROP TABLE IF EXISTS `g5_visit_sum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_visit_sum` (
  `vs_date` date NOT NULL DEFAULT '0000-00-00',
  `vs_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vs_date`),
  KEY `index1` (`vs_count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_visit_sum`
--

LOCK TABLES `g5_visit_sum` WRITE;
/*!40000 ALTER TABLE `g5_visit_sum` DISABLE KEYS */;
INSERT INTO `g5_visit_sum` VALUES ('2017-11-20',1),('2017-11-21',1),('2017-11-22',1),('2017-11-23',1),('2017-11-24',3),('2017-11-26',1),('2017-11-27',2),('2017-11-28',2);
/*!40000 ALTER TABLE `g5_visit_sum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_write_free`
--

DROP TABLE IF EXISTS `g5_write_free`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_write_free` (
  `wr_id` int(11) NOT NULL AUTO_INCREMENT,
  `wr_num` int(11) NOT NULL DEFAULT '0',
  `wr_reply` varchar(10) NOT NULL,
  `wr_parent` int(11) NOT NULL DEFAULT '0',
  `wr_is_comment` tinyint(4) NOT NULL DEFAULT '0',
  `wr_comment` int(11) NOT NULL DEFAULT '0',
  `wr_comment_reply` varchar(5) NOT NULL,
  `ca_name` varchar(255) NOT NULL,
  `wr_option` set('html1','html2','secret','mail') NOT NULL,
  `wr_subject` varchar(255) NOT NULL,
  `wr_content` text NOT NULL,
  `wr_link1` text NOT NULL,
  `wr_link2` text NOT NULL,
  `wr_link1_hit` int(11) NOT NULL DEFAULT '0',
  `wr_link2_hit` int(11) NOT NULL DEFAULT '0',
  `wr_hit` int(11) NOT NULL DEFAULT '0',
  `wr_good` int(11) NOT NULL DEFAULT '0',
  `wr_nogood` int(11) NOT NULL DEFAULT '0',
  `mb_id` varchar(20) NOT NULL,
  `wr_password` varchar(255) NOT NULL,
  `wr_name` varchar(255) NOT NULL,
  `wr_email` varchar(255) NOT NULL,
  `wr_homepage` varchar(255) NOT NULL,
  `wr_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wr_file` tinyint(4) NOT NULL DEFAULT '0',
  `wr_last` varchar(19) NOT NULL,
  `wr_ip` varchar(255) NOT NULL,
  `wr_facebook_user` varchar(255) NOT NULL,
  `wr_twitter_user` varchar(255) NOT NULL,
  `wr_1` varchar(255) NOT NULL,
  `wr_2` varchar(255) NOT NULL,
  `wr_3` varchar(255) NOT NULL,
  `wr_4` varchar(255) NOT NULL,
  `wr_5` varchar(255) NOT NULL,
  `wr_6` varchar(255) NOT NULL,
  `wr_7` varchar(255) NOT NULL,
  `wr_8` varchar(255) NOT NULL,
  `wr_9` varchar(255) NOT NULL,
  `wr_10` varchar(255) NOT NULL,
  PRIMARY KEY (`wr_id`),
  KEY `wr_num_reply_parent` (`wr_num`,`wr_reply`,`wr_parent`),
  KEY `wr_is_comment` (`wr_is_comment`,`wr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_write_free`
--

LOCK TABLES `g5_write_free` WRITE;
/*!40000 ALTER TABLE `g5_write_free` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_write_free` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_write_notice`
--

DROP TABLE IF EXISTS `g5_write_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_write_notice` (
  `wr_id` int(11) NOT NULL AUTO_INCREMENT,
  `wr_num` int(11) NOT NULL DEFAULT '0',
  `wr_reply` varchar(10) NOT NULL,
  `wr_parent` int(11) NOT NULL DEFAULT '0',
  `wr_is_comment` tinyint(4) NOT NULL DEFAULT '0',
  `wr_comment` int(11) NOT NULL DEFAULT '0',
  `wr_comment_reply` varchar(5) NOT NULL,
  `ca_name` varchar(255) NOT NULL,
  `wr_option` set('html1','html2','secret','mail') NOT NULL,
  `wr_subject` varchar(255) NOT NULL,
  `wr_content` text NOT NULL,
  `wr_link1` text NOT NULL,
  `wr_link2` text NOT NULL,
  `wr_link1_hit` int(11) NOT NULL DEFAULT '0',
  `wr_link2_hit` int(11) NOT NULL DEFAULT '0',
  `wr_hit` int(11) NOT NULL DEFAULT '0',
  `wr_good` int(11) NOT NULL DEFAULT '0',
  `wr_nogood` int(11) NOT NULL DEFAULT '0',
  `mb_id` varchar(20) NOT NULL,
  `wr_password` varchar(255) NOT NULL,
  `wr_name` varchar(255) NOT NULL,
  `wr_email` varchar(255) NOT NULL,
  `wr_homepage` varchar(255) NOT NULL,
  `wr_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wr_file` tinyint(4) NOT NULL DEFAULT '0',
  `wr_last` varchar(19) NOT NULL,
  `wr_ip` varchar(255) NOT NULL,
  `wr_facebook_user` varchar(255) NOT NULL,
  `wr_twitter_user` varchar(255) NOT NULL,
  `wr_1` varchar(255) NOT NULL,
  `wr_2` varchar(255) NOT NULL,
  `wr_3` varchar(255) NOT NULL,
  `wr_4` varchar(255) NOT NULL,
  `wr_5` varchar(255) NOT NULL,
  `wr_6` varchar(255) NOT NULL,
  `wr_7` varchar(255) NOT NULL,
  `wr_8` varchar(255) NOT NULL,
  `wr_9` varchar(255) NOT NULL,
  `wr_10` varchar(255) NOT NULL,
  PRIMARY KEY (`wr_id`),
  KEY `wr_num_reply_parent` (`wr_num`,`wr_reply`,`wr_parent`),
  KEY `wr_is_comment` (`wr_is_comment`,`wr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_write_notice`
--

LOCK TABLES `g5_write_notice` WRITE;
/*!40000 ALTER TABLE `g5_write_notice` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_write_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `g5_write_qa`
--

DROP TABLE IF EXISTS `g5_write_qa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `g5_write_qa` (
  `wr_id` int(11) NOT NULL AUTO_INCREMENT,
  `wr_num` int(11) NOT NULL DEFAULT '0',
  `wr_reply` varchar(10) NOT NULL,
  `wr_parent` int(11) NOT NULL DEFAULT '0',
  `wr_is_comment` tinyint(4) NOT NULL DEFAULT '0',
  `wr_comment` int(11) NOT NULL DEFAULT '0',
  `wr_comment_reply` varchar(5) NOT NULL,
  `ca_name` varchar(255) NOT NULL,
  `wr_option` set('html1','html2','secret','mail') NOT NULL,
  `wr_subject` varchar(255) NOT NULL,
  `wr_content` text NOT NULL,
  `wr_link1` text NOT NULL,
  `wr_link2` text NOT NULL,
  `wr_link1_hit` int(11) NOT NULL DEFAULT '0',
  `wr_link2_hit` int(11) NOT NULL DEFAULT '0',
  `wr_hit` int(11) NOT NULL DEFAULT '0',
  `wr_good` int(11) NOT NULL DEFAULT '0',
  `wr_nogood` int(11) NOT NULL DEFAULT '0',
  `mb_id` varchar(20) NOT NULL,
  `wr_password` varchar(255) NOT NULL,
  `wr_name` varchar(255) NOT NULL,
  `wr_email` varchar(255) NOT NULL,
  `wr_homepage` varchar(255) NOT NULL,
  `wr_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wr_file` tinyint(4) NOT NULL DEFAULT '0',
  `wr_last` varchar(19) NOT NULL,
  `wr_ip` varchar(255) NOT NULL,
  `wr_facebook_user` varchar(255) NOT NULL,
  `wr_twitter_user` varchar(255) NOT NULL,
  `wr_1` varchar(255) NOT NULL,
  `wr_2` varchar(255) NOT NULL,
  `wr_3` varchar(255) NOT NULL,
  `wr_4` varchar(255) NOT NULL,
  `wr_5` varchar(255) NOT NULL,
  `wr_6` varchar(255) NOT NULL,
  `wr_7` varchar(255) NOT NULL,
  `wr_8` varchar(255) NOT NULL,
  `wr_9` varchar(255) NOT NULL,
  `wr_10` varchar(255) NOT NULL,
  PRIMARY KEY (`wr_id`),
  KEY `wr_num_reply_parent` (`wr_num`,`wr_reply`,`wr_parent`),
  KEY `wr_is_comment` (`wr_is_comment`,`wr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `g5_write_qa`
--

LOCK TABLES `g5_write_qa` WRITE;
/*!40000 ALTER TABLE `g5_write_qa` DISABLE KEYS */;
/*!40000 ALTER TABLE `g5_write_qa` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-30 17:23:51
