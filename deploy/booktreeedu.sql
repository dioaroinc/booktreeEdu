
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `icon` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 0xF09F9398,
  `color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#136a43',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (4,'일반교육','교재·프로그램 운영 기본 교육','책나무 교재와 프로그램 운영에 필요한 기본 교육 영상입니다.','📘','#136a43',1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(5,'역량강화','원장님·선생님 역량강화 교육','지사장·본사 연구팀이 진행한 역량강화 교육 영상입니다. ★ 표시는 신규 원장님 필수 시청 자료입니다.','🎯','#0f88ca',2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(6,'교육자료','수업·설명회·시스템 교육자료','수업 과정, 학부모 설명회, 프로그램 업데이트 등 교육자료 영상입니다.','📚','#b5651d',3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(7,'스터디','지도법 스터디','글쓰기 등 지도법 스터디 영상입니다.','✏️','#7b3fa0',4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lessons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int unsigned NOT NULL,
  `section_id` int unsigned DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `ref_no` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '본사 자료실(bbs) 글 번호',
  `lecturer` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lecture_date` date DEFAULT NULL,
  `is_required` tinyint DEFAULT '0' COMMENT '1=신규 원장 필수 시청(★)',
  `video_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'youtube' COMMENT 'youtube | file | url',
  `video_url` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `video_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `duration_min` int NOT NULL DEFAULT '0',
  `pass_score` int NOT NULL DEFAULT '80' COMMENT '합격 기준(%)',
  `require_video` tinyint NOT NULL DEFAULT '1' COMMENT '영상 시청 완료 후 시험 응시 가능',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES (8,4,NULL,'책나무 방학특강 가이드 (1/3)','','526','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(9,4,NULL,'책나무 방학특강 가이드 (2/3)','','527','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(10,4,NULL,'책나무 방학특강 가이드 (3/3)','','528','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(11,4,NULL,'중등부 비문학 교재 런칭 이후 교육 자료','','577','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(12,4,NULL,'비문학 교재 활용 방안','','455','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,5,1,'2026-11-02 13:59:44','2026-11-02 14:00:20'),(13,5,NULL,'누리과정과 연계된 책나무 프로그램 교육','','610','김선희 부산지사장','2023-03-09',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(14,5,NULL,'책나무 활동지와 첨삭 방법','','617','정미경 경북서부지사장','2023-04-13',0,'youtube','',NULL,0,80,1,2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(15,5,NULL,'북코칭','','632','정선경 경남서부지사장','2023-05-11',0,'youtube','',NULL,0,80,1,3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(16,5,NULL,'슬기로운 스마트폰 생활','','651','김태호 울산지사장','2023-06-08',0,'youtube','',NULL,0,80,1,4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(17,5,NULL,'책나무 홍보 및 이벤트 전략','','675','노송희 경북동부지사장','2023-07-13',0,'youtube','',NULL,0,80,1,5,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(18,4,NULL,'신문 읽기 지도법','','692','AI 자체제작','2023-08-18',0,'youtube','',NULL,0,80,1,6,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(19,5,NULL,'학부모 상담 및 관리','','700','서지원 충북세종지사장','2023-09-14',0,'youtube','',NULL,0,80,1,6,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(20,5,NULL,'중등 국어 문법 : 음운, 형태, 문장 (1/2)','','709','오수지 전북지사장','2023-10-12',0,'youtube','',NULL,0,80,1,7,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(21,5,NULL,'중등 국어 문법 : 음운, 형태, 문장 (2/2)','','712','오수지 전북지사장','2023-10-19',0,'youtube','',NULL,0,80,1,8,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(22,5,NULL,'고교학점제','','719','김승연 충남지사장','2023-11-09',0,'youtube','',NULL,0,80,1,9,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(23,5,NULL,'책나무가 분석한 2024 수능국어 출제 경향 및 전망','','725','박지선 교육연구팀장','2023-11-28',0,'youtube','',NULL,0,80,1,10,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(24,5,NULL,'고학년을 위한 긴글쓰기','','729','박소현 제주지사장','2023-12-14',0,'youtube','',NULL,0,80,1,11,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(25,5,NULL,'책나무 활동지 활용 및 첨삭 (1/2)','','747','박지선 교육연구팀장','2024-01-25',0,'youtube','',NULL,0,80,1,12,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(26,5,NULL,'책나무 활동지 활용 및 첨삭 (2/2)','','746','박지선 교육연구팀장','2024-01-26',0,'youtube','',NULL,0,80,1,13,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(27,5,NULL,'KBS 학습 문해력 시험 결과 상담전략','','751','하윤정 기획연구개발실장','2024-02-22',0,'youtube','',NULL,0,80,1,14,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(28,5,NULL,'교육업을 위한 인스타그램 활용 전략','','760','이호정 대표(열정의 빅마마)','2024-03-21',0,'youtube','',NULL,0,80,1,15,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(29,5,NULL,'꼼꼼한 문학 첨삭을 위한 개념용어 정리','','764','박지선 교육연구팀장','2024-04-18',0,'youtube','',NULL,0,80,1,16,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(30,5,NULL,'눈에 띄는 성공적인 블로그 관리 전략과 노하우 교육','','767','박경탁 마케팅실장','2024-04-23',0,'youtube','',NULL,0,80,1,17,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(31,5,NULL,'책나무 기초 프로그램 활용을 위한 홈페이지 이용 교육','','772','하윤정 기획연구개발실장','2024-05-16',1,'youtube','',NULL,0,80,1,18,1,'2026-11-02 13:59:44','2026-11-02 14:00:20'),(32,5,NULL,'책나무 선생님 대상 역량강화교육 1부','','784','하윤정 기획연구개발실장','2024-06-20',1,'youtube','',NULL,0,80,1,19,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(33,5,NULL,'책나무 선생님 대상 역량강화교육 2부','','797','하윤정 기획연구개발실장','2024-07-18',1,'youtube','',NULL,0,80,1,20,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(34,5,NULL,'문해력 진단평가 상담 사례 교육','','831','하윤정 기획연구개발실장','2024-11-12',0,'youtube','',NULL,0,80,1,21,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(35,5,NULL,'책나무가 분석한 2025 수능국어 출제 경향 및 전망','','835','권시연 교육연구팀장','2024-11-21',0,'youtube','',NULL,0,80,1,22,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(36,5,NULL,'QR사용교육','','842','권시연 교육연구팀장','2024-12-18',1,'youtube','',NULL,0,80,1,23,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(37,5,NULL,'타브랜드와 고민하는 학부모님께 이렇게 상담하세요','','843','신은겸 김해삼계점 원장','2024-12-19',0,'youtube','',NULL,0,80,1,24,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(38,6,NULL,'책나무 수업과정 교육 영상','','858','자체 제작','2025-02-14',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(39,5,NULL,'한국사 마라톤 및 문학 리뉴얼','','866','한혜경 교육본부장','2025-03-25',1,'youtube','',NULL,0,80,1,25,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(40,5,NULL,'중등생을 위한 상담 전략','','906','김희정 수원지사장','2025-09-16',0,'youtube','',NULL,0,80,1,26,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(41,6,NULL,'학부모 설명회 교육 1부','','911','박수진 과장','2025-11-11',1,'youtube','',NULL,0,80,1,2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(42,6,NULL,'학부모 설명회 교육 2부','','912','박수진 과장','2025-11-11',1,'youtube','',NULL,0,80,1,3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(43,5,NULL,'2026 수능 분석','','917','주민지 연구원','2025-11-25',0,'youtube','',NULL,0,80,1,27,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(44,5,NULL,'초, 중등 입시제도의 방향성과 대응방안','','927','박흥순 강서동작지사장','2025-12-16',0,'youtube','',NULL,0,80,1,28,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(45,6,NULL,'프로그램 업데이트 관련 교육','','897','박수진 과장','2026-01-27',0,'youtube','',NULL,0,80,1,4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(46,7,NULL,'갈래별 글쓰기 지도 전략','','909','하윤정 기획연구개발실장','2026-01-27',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(47,5,NULL,'학군지에서 검증된 책나무 운영 전략과 성장 모델','','951','강남서초지사장','2026-03-20',0,'youtube','',NULL,0,80,1,29,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(48,5,NULL,'미리캔버스를 활용한 학원 콘텐츠 기획','','958','박수진 과장','2026-05-21',0,'youtube','',NULL,0,80,1,30,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(49,6,NULL,'세무 보수 교육','','970','김종현 회계사','2026-06-16',0,'youtube','',NULL,0,80,1,5,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(50,5,NULL,'AI 시대, 독서는 왜 여전히 핵심 역량인가?','','971','이수진 과천안양지사장','2026-06-18',0,'youtube','',NULL,0,80,1,31,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(51,6,NULL,'책나무 AI 시스템 도입 및 활용 교육','','982','한혜경 교육본부장','2026-09-15',1,'youtube','',NULL,0,80,1,6,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(52,5,NULL,'블로그 및 인스타그램 기초 교육','','994','박경탁 마케팅실장','2026-10-29',0,'youtube','',NULL,0,80,1,32,1,'2026-11-02 13:59:44','2026-11-02 13:59:44');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-10-26-000001','App\\Database\\Migrations\\CreateEducationTables','default','App',1793001070,1),(2,'2026-11-02-000001','App\\Database\\Migrations\\AddLessonMeta','default','App',1793595584,2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progress` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `lesson_id` int unsigned NOT NULL,
  `video_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=미시청 1=시청중 2=시청완료',
  `video_seconds` int NOT NULL DEFAULT '0' COMMENT '마지막 재생 위치(초)',
  `video_done_at` datetime DEFAULT NULL,
  `quiz_attempts` int NOT NULL DEFAULT '0',
  `quiz_best` int NOT NULL DEFAULT '0' COMMENT '최고 점수(%)',
  `quiz_passed` tinyint NOT NULL DEFAULT '0',
  `quiz_passed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_lesson_id` (`user_id`,`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `progress` WRITE;
/*!40000 ALTER TABLE `progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `progress` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int unsigned NOT NULL,
  `question` text COLLATE utf8mb4_general_ci NOT NULL,
  `choice1` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `choice2` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `choice3` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `choice4` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `answer` tinyint NOT NULL COMMENT '정답 번호 1~4',
  `explanation` text COLLATE utf8mb4_general_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `quiz_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `lesson_id` int unsigned NOT NULL,
  `score` int NOT NULL COMMENT '점수(%)',
  `correct` int NOT NULL,
  `total` int NOT NULL,
  `passed` tinyint NOT NULL DEFAULT '0',
  `answers` text COLLATE utf8mb4_general_ci COMMENT 'JSON {question_id: choice}',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_lesson_id` (`user_id`,`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `quiz_attempts` WRITE;
/*!40000 ALTER TABLE `quiz_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiz_attempts` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int unsigned NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `login_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `branch_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1=수강생, 100=관리자',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1=사용, 0=정지',
  `source` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'local' COMMENT 'local | booktree',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_id` (`login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$d4hhRCkwiydNzzzwO3IlxuIkuctTzfITS5xr.mERxhcxD2kRF78I2','본사 관리자','책나무 본사',NULL,NULL,100,1,'local','2026-11-12 10:04:22','2026-10-26 17:07:20','2026-11-12 10:04:22'),(2,'demo','$2y$10$dmM.LLhvylSlyKie3bhlWeMiomK1VEMZD0rVGq7DogtjsIGA38RmW','홍길동','책나무 서울송파잠실점',NULL,NULL,1,1,'local','2026-11-11 14:41:42','2026-10-26 17:07:20','2026-11-11 14:41:42'),(3,'demo2','$2y$10$mvmUuXc9kk011tFle8cszuFib50omx09078Ij.NpxsEaqAIet09am','김영희','책나무 분당정자점',NULL,NULL,1,1,'local',NULL,'2026-10-26 17:07:20','2026-10-26 17:07:20');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

