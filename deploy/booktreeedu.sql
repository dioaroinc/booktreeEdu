
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
INSERT INTO `courses` VALUES (1,'신규원장교육','책나무 가맹 원장님을 위한 필수 기본 과정','책나무 독서교육 프로그램의 철학과 운영 방법, 홈페이지 메인 프로그램 사용법, 수강생 관리와 주문·결제 절차까지 신규 원장님이 반드시 알아야 할 내용을 단계별로 안내합니다.','🌱','#136a43',1,0,'2026-10-26 17:07:20','2026-11-02 13:59:44'),(2,'지점SW 사용교육','지점 지원 소프트웨어 완전 정복','수강생 등록부터 진단테스트, BToS(독서 퀴즈 온라인 시스템), 북히스토리 관리까지 지점 소프트웨어의 모든 기능을 화면과 함께 배웁니다.','💻','#0f88ca',2,0,'2026-10-26 17:07:20','2026-11-02 13:59:44'),(3,'보수교육','운영 중인 원장님을 위한 심화·업데이트 교육','매년 업데이트되는 교재와 프로그램 변경 사항, 학부모 상담 노하우, 지점 운영 사례를 공유하는 심화 과정입니다.','📚','#b5651d',3,0,'2026-10-26 17:07:20','2026-11-02 13:59:44'),(4,'일반교육','교재·프로그램 운영 기본 교육','책나무 교재와 프로그램 운영에 필요한 기본 교육 영상입니다.','📘','#136a43',1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(5,'역량강화','원장님·선생님 역량강화 교육','지사장·본사 연구팀이 진행한 역량강화 교육 영상입니다. ★ 표시는 신규 원장님 필수 시청 자료입니다.','🎯','#0f88ca',2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(6,'교육자료','수업·설명회·시스템 교육자료','수업 과정, 학부모 설명회, 프로그램 업데이트 등 교육자료 영상입니다.','📚','#b5651d',3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(7,'스터디','지도법 스터디','글쓰기 등 지도법 스터디 영상입니다.','✏️','#7b3fa0',4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44');
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
INSERT INTO `lessons` VALUES (1,1,1,'접속방법, 메인프로그램 소개, 진단평가','[데모 영상] 책나무 홈페이지 접속 방법과 메인 프로그램의 구성, 학년별 진단 TEST 의 의미를 소개합니다.\n\n이 강의는 유튜브 영상 임베드 방식의 데모입니다. 영상을 끝까지 시청하면 자동으로 시청완료 처리되고 시험을 볼 수 있습니다.',NULL,NULL,NULL,0,'youtube','https://www.youtube.com/watch?v=5cJ-tHkI4MA',NULL,10,80,1,1,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(2,1,1,'수강생 관리, 활동지 및 북히스토리','[데모 영상] 수강생 등록·반 편성, 활동지 출력, 북히스토리 기록 방법을 안내합니다.\n\n이 강의는 MP4 파일 재생 방식의 데모입니다 (직접 업로드한 파일과 동일하게 동작). 재생 위치가 자동 저장되어 다음에 이어서 볼 수 있습니다.',NULL,NULL,NULL,0,'url','https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4',NULL,1,80,1,2,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(3,1,1,'독서 프로그램 운영과 학부모 상담','개별 맞춤 독서 프로그램의 운영 원리와 학부모 상담 시 활용하는 진단 결과 해석법을 다룹니다.',NULL,NULL,NULL,0,'url','https://interactive-examples.mdn.mozilla.net/media/cc0-videos/friday.mp4',NULL,1,80,1,3,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(4,1,2,'교재 주문과 결제 절차','교재·교구 주문 방법, 결제 수단, 배송 일정과 반품 규정을 안내합니다.',NULL,NULL,NULL,0,'url','https://mdn.github.io/learning-area/html/multimedia-and-embedding/video-and-audio-content/rabbit320.mp4',NULL,1,80,1,4,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(5,2,3,'지점SW 로그인과 기본 화면 구성','지점 지원 소프트웨어 로그인, 메뉴 구조, 자주 쓰는 기능의 위치를 익힙니다.',NULL,NULL,NULL,0,'url','https://test-videos.co.uk/vids/bigbuckbunny/mp4/h264/360/Big_Buck_Bunny_360_10s_1MB.mp4',NULL,1,80,1,1,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(6,2,3,'BToS 독서 퀴즈 온라인 시스템 활용','BToS(Booktree Test online System) 에서 독서 퀴즈를 배정하고 결과를 확인하는 방법을 배웁니다.',NULL,NULL,NULL,0,'url','https://samplelib.com/lib/preview/mp4/sample-10s.mp4',NULL,1,80,1,2,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(7,3,4,'2026년 교재 개정 사항 안내','개정 교재의 변경 포인트와 수업 적용 방법을 안내합니다.',NULL,NULL,NULL,0,'url','https://download.samplelib.com/mp4/sample-15s.mp4',NULL,1,80,1,1,1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(8,4,NULL,'책나무 방학특강 가이드 (1/3)','','526','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(9,4,NULL,'책나무 방학특강 가이드 (2/3)','','527','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(10,4,NULL,'책나무 방학특강 가이드 (3/3)','','528','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(11,4,NULL,'중등부 비문학 교재 런칭 이후 교육 자료','','577','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(12,4,NULL,'비문학 교재 활용 방안','','455','한혜경 교육본부장','2023-02-14',0,'youtube','',NULL,0,80,1,5,1,'2026-11-02 13:59:44','2026-11-02 14:00:20'),(13,5,NULL,'누리과정과 연계된 책나무 프로그램 교육','','610','김선희 부산지사장','2023-03-09',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(14,5,NULL,'책나무 활동지와 첨삭 방법','','617','정미경 경북서부지사장','2023-04-13',0,'youtube','',NULL,0,80,1,2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(15,5,NULL,'북코칭','','632','정선경 경남서부지사장','2023-05-11',0,'youtube','',NULL,0,80,1,3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(16,5,NULL,'슬기로운 스마트폰 생활','','651','김태호 울산지사장','2023-06-08',0,'youtube','',NULL,0,80,1,4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(17,5,NULL,'책나무 홍보 및 이벤트 전략','','675','노송희 경북동부지사장','2023-07-13',0,'youtube','',NULL,0,80,1,5,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(18,4,NULL,'신문 읽기 지도법','','692','AI 자체제작','2023-08-18',0,'youtube','',NULL,0,80,1,6,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(19,5,NULL,'학부모 상담 및 관리','','700','서지원 충북세종지사장','2023-09-14',0,'youtube','',NULL,0,80,1,6,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(20,5,NULL,'중등 국어 문법 : 음운, 형태, 문장 (1/2)','','709','오수지 전북지사장','2023-10-12',0,'youtube','',NULL,0,80,1,7,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(21,5,NULL,'중등 국어 문법 : 음운, 형태, 문장 (2/2)','','712','오수지 전북지사장','2023-10-19',0,'youtube','',NULL,0,80,1,8,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(22,5,NULL,'고교학점제','','719','김승연 충남지사장','2023-11-09',0,'youtube','',NULL,0,80,1,9,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(23,5,NULL,'책나무가 분석한 2024 수능국어 출제 경향 및 전망','','725','박지선 교육연구팀장','2023-11-28',0,'youtube','',NULL,0,80,1,10,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(24,5,NULL,'고학년을 위한 긴글쓰기','','729','박소현 제주지사장','2023-12-14',0,'youtube','',NULL,0,80,1,11,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(25,5,NULL,'책나무 활동지 활용 및 첨삭 (1/2)','','747','박지선 교육연구팀장','2024-01-25',0,'youtube','',NULL,0,80,1,12,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(26,5,NULL,'책나무 활동지 활용 및 첨삭 (2/2)','','746','박지선 교육연구팀장','2024-01-26',0,'youtube','',NULL,0,80,1,13,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(27,5,NULL,'KBS 학습 문해력 시험 결과 상담전략','','751','하윤정 기획연구개발실장','2024-02-22',0,'youtube','',NULL,0,80,1,14,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(28,5,NULL,'교육업을 위한 인스타그램 활용 전략','','760','이호정 대표(열정의 빅마마)','2024-03-21',0,'youtube','',NULL,0,80,1,15,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(29,5,NULL,'꼼꼼한 문학 첨삭을 위한 개념용어 정리','','764','박지선 교육연구팀장','2024-04-18',0,'youtube','',NULL,0,80,1,16,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(30,5,NULL,'눈에 띄는 성공적인 블로그 관리 전략과 노하우 교육','','767','박경탁 마케팅실장','2024-04-23',0,'youtube','',NULL,0,80,1,17,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(31,5,NULL,'책나무 기초 프로그램 활용을 위한 홈페이지 이용 교육','','772','하윤정 기획연구개발실장','2024-05-16',1,'youtube','',NULL,0,80,1,18,1,'2026-11-02 13:59:44','2026-11-02 14:00:20'),(32,5,NULL,'책나무 선생님 대상 역량강화교육 1부','','784','하윤정 기획연구개발실장','2024-06-20',1,'youtube','',NULL,0,80,1,19,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(33,5,NULL,'책나무 선생님 대상 역량강화교육 2부','','797','하윤정 기획연구개발실장','2024-07-18',1,'youtube','',NULL,0,80,1,20,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(34,5,NULL,'문해력 진단평가 상담 사례 교육','','831','하윤정 기획연구개발실장','2024-11-12',0,'youtube','',NULL,0,80,1,21,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(35,5,NULL,'책나무가 분석한 2025 수능국어 출제 경향 및 전망','','835','권시연 교육연구팀장','2024-11-21',0,'youtube','',NULL,0,80,1,22,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(36,5,NULL,'QR사용교육','','842','권시연 교육연구팀장','2024-12-18',1,'youtube','',NULL,0,80,1,23,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(37,5,NULL,'타브랜드와 고민하는 학부모님께 이렇게 상담하세요','','843','신은겸 김해삼계점 원장','2024-12-19',0,'youtube','',NULL,0,80,1,24,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(38,6,NULL,'책나무 수업과정 교육 영상','','858','자체 제작','2025-02-14',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(39,5,NULL,'한국사 마라톤 및 문학 리뉴얼','','866','한혜경 교육본부장','2025-03-25',1,'youtube','',NULL,0,80,1,25,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(40,5,NULL,'중등생을 위한 상담 전략','','906','김희정 수원지사장','2025-09-16',0,'youtube','',NULL,0,80,1,26,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(41,6,NULL,'학부모 설명회 교육 1부','','911','박수진 과장','2025-11-11',1,'youtube','',NULL,0,80,1,2,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(42,6,NULL,'학부모 설명회 교육 2부','','912','박수진 과장','2025-11-11',1,'youtube','',NULL,0,80,1,3,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(43,5,NULL,'2026 수능 분석','','917','주민지 연구원','2025-11-25',0,'youtube','',NULL,0,80,1,27,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(44,5,NULL,'초, 중등 입시제도의 방향성과 대응방안','','927','박흥순 강서동작지사장','2025-12-16',0,'youtube','',NULL,0,80,1,28,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(45,6,NULL,'프로그램 업데이트 관련 교육','','897','박수진 과장','2026-01-27',0,'youtube','',NULL,0,80,1,4,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(46,7,NULL,'갈래별 글쓰기 지도 전략','','909','하윤정 기획연구개발실장','2026-01-27',0,'youtube','',NULL,0,80,1,1,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(47,5,NULL,'학군지에서 검증된 책나무 운영 전략과 성장 모델','','951','강남서초지사장','2026-03-20',0,'youtube','',NULL,0,80,1,29,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(48,5,NULL,'미리캔버스를 활용한 학원 콘텐츠 기획','','958','박수진 과장','2026-05-21',0,'youtube','',NULL,0,80,1,30,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(49,6,NULL,'세무 보수 교육','','970','김종현 회계사','2026-06-16',0,'youtube','',NULL,0,80,1,5,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(50,5,NULL,'AI 시대, 독서는 왜 여전히 핵심 역량인가?','','971','이수진 과천안양지사장','2026-06-18',0,'youtube','',NULL,0,80,1,31,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(51,6,NULL,'책나무 AI 시스템 도입 및 활용 교육','','982','한혜경 교육본부장','2026-09-15',1,'youtube','',NULL,0,80,1,6,1,'2026-11-02 13:59:44','2026-11-02 13:59:44'),(52,5,NULL,'블로그 및 인스타그램 기초 교육','','994','박경탁 마케팅실장','2026-10-29',0,'youtube','',NULL,0,80,1,32,1,'2026-11-02 13:59:44','2026-11-02 13:59:44');
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
INSERT INTO `progress` VALUES (1,2,1,2,600,'2026-10-26 17:07:20',1,100,1,'2026-10-26 17:07:20','2026-10-26 17:07:20','2026-10-26 17:07:20'),(2,2,2,1,8,NULL,0,0,0,NULL,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(3,1,9,1,0,NULL,0,0,0,NULL,'2026-11-02 14:00:22','2026-11-02 14:00:22'),(4,1,31,1,0,NULL,0,0,0,NULL,'2026-11-02 14:00:22','2026-11-02 14:00:22');
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
INSERT INTO `questions` VALUES (1,1,'책나무 독서교육에서 \"독서\"의 의미로 가장 알맞은 것은?','책을 빠르게 많이 읽는 것','읽고, 생각하고, 쓰는 모든 과정','줄거리를 외우는 것','독후감을 제출하는 것',2,'책나무는 읽기만이 아니라 읽고·생각하고·쓰는 전 과정을 독서로 봅니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(2,1,'학년별 진단 TEST 의 주된 목적은 무엇인가요?','학생의 등수를 매기기 위해','개별 맞춤 독서 프로그램을 설계하기 위해','교재 판매량을 늘리기 위해','수업 시간을 줄이기 위해',2,'진단 결과를 바탕으로 학생별 수준에 맞는 프로그램을 구성합니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(3,1,'책나무 홈페이지 메인 프로그램에 접속할 때 사용하는 계정은?','개인 이메일 계정','지점(가맹점) 계정','학생 계정','학부모 계정',2,'지점SW 와 동일한 가맹점 계정으로 로그인합니다.',3,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(4,1,'BToS 는 무엇의 약자인가요?','Booktree Teacher online School','Booktree Test online System','Best Textbook of Study','Book Trading online Service',2,'BToS = Booktree Test online System (독서 퀴즈 온라인 프로그램)',4,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(5,1,'진단 TEST 결과지에 포함되지 않는 항목은?','영역별 점수','학년 수준 비교','학생의 혈액형','추천 독서 단계',3,'결과지는 영역별 점수·수준 비교·추천 단계를 제공합니다.',5,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(6,2,'수강생을 새로 등록할 때 가장 먼저 해야 할 일은?','교재를 먼저 주문한다','진단 TEST 를 실시한다','학부모 상담을 생략한다','활동지를 먼저 출력한다',2,'등록 → 진단 TEST → 프로그램 배정 순서로 진행합니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(7,2,'북히스토리에 기록되는 내용으로 알맞은 것은?','학생이 읽은 도서와 활동 기록','지점의 매출 정보','교사의 출퇴근 기록','학부모의 연락처',1,'북히스토리는 학생의 독서·활동 이력을 누적 관리합니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(8,2,'활동지는 어디에서 출력하나요?','지점SW 의 활동지 메뉴','학생의 개인 이메일','본사에 전화 요청','출력할 수 없다',1,'지점SW 활동지 메뉴에서 학생별로 출력합니다.',3,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(9,2,'다양한 글쓰기 활동의 목적으로 가장 알맞은 것은?','글씨체 교정','읽은 내용을 생각하고 표현하는 힘 기르기','시험 점수 올리기','시간 채우기',2,'글쓰기는 독서의 완성 단계로, 사고력과 표현력을 기릅니다.',4,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(10,2,'도서관식 몰입독서 환경의 특징은?','모든 학생이 같은 책을 읽는다','학생 개별 수준의 책을 스스로 선택해 몰입한다','교사가 책을 대신 읽어준다','독서 시간이 없다',2,'개별 맞춤 + 몰입독서 환경이 책나무 프로그램의 핵심입니다.',5,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(11,3,'학부모 상담에서 진단 결과를 설명할 때 가장 중요한 태도는?','점수만 강조한다','다른 학생과 비교한다','강점과 보완점을 함께 설명한다','결과를 보여주지 않는다',3,'강점·보완점을 균형 있게 전달해야 신뢰를 얻습니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(12,3,'개별 맞춤 독서 프로그램 설계의 기준은?','학생의 진단 결과와 독서 수준','교재 재고','학부모의 희망 학년','교사의 취향',1,'진단 결과에 따른 수준별 설계가 원칙입니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(13,3,'정기 상담의 적절한 주기로 권장되는 것은?','매일','학기별 또는 분기별','졸업할 때 한 번','상담하지 않는다',2,'학기·분기 단위 정기 상담을 권장합니다.',3,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(14,4,'교재 주문은 어디에서 진행하나요?','지점SW 주문 메뉴','개인 온라인 서점','학부모가 직접','본사 방문만 가능',1,'지점SW 주문 메뉴에서 교재·교구를 주문합니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(15,4,'주문 후 결제 확인은 어떻게 하나요?','주문 내역의 결제 상태 확인','확인할 수 없다','학생에게 묻는다','본사에 매번 전화',1,'주문 내역 화면에서 결제·배송 상태를 확인합니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(16,4,'반품이 가능한 경우는?','사용한 교재','파손·오배송된 미사용 교재','한 학기 지난 교재','모든 경우 불가',2,'미사용 상태의 파손·오배송 교재는 반품 규정에 따라 처리됩니다.',3,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(17,5,'지점SW 로그인에 사용하는 계정은?','가맹점 계정','학생 계정','학부모 계정','본사 공용 계정',1,'가맹점(지점) 계정으로 로그인합니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(18,5,'지점SW 에서 수강생을 관리하는 메뉴는?','수강생 관리','매출 관리','교재 주문','공지사항',1,'수강생 관리 메뉴에서 등록·반 편성·이력을 관리합니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(19,5,'비밀번호를 잊었을 때는?','본사(1522-9320)에 문의','새 계정을 만든다','다른 지점 계정을 쓴다','포기한다',1,'로그인 관련 문의는 본사로 연락합니다.',3,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(20,6,'BToS 에서 독서 퀴즈를 배정하는 단위는?','학생별 도서','지점 전체','학부모','교사',1,'학생이 읽은 도서별로 퀴즈를 배정합니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(21,6,'퀴즈 결과는 어디에 누적되나요?','북히스토리','매출 장부','교재 주문서','누적되지 않는다',1,'퀴즈 결과는 학생의 북히스토리에 기록됩니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(22,6,'BToS 의 장점으로 알맞은 것은?','독서 확인과 결과 관리를 온라인으로 간편하게','종이 시험지만 사용','교사가 채점을 직접 해야 함','결과를 볼 수 없음',1,'온라인 퀴즈로 독서 확인·채점·결과 관리가 자동화됩니다.',3,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(23,7,'개정 교재를 수업에 적용할 때 먼저 확인할 것은?','변경된 단원 구성','교재의 표지 색','가격표','아무것도 확인하지 않는다',1,'변경 단원과 활동 구성을 먼저 파악합니다.',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(24,7,'보수교육의 목적은?','운영 중인 원장님의 지식 업데이트','신규 가맹 안내','학생 평가','교재 판매',1,'운영 노하우와 변경 사항을 공유하는 심화 과정입니다.',2,'2026-10-26 17:07:20','2026-10-26 17:07:20');
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
INSERT INTO `quiz_attempts` VALUES (1,2,1,100,5,5,1,'[]','2026-10-26 17:07:20');
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
INSERT INTO `sections` VALUES (1,1,'1. 홈페이지 메인 프로그램 Part 1',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(2,1,'2. 주문 및 결제 안내',2,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(3,2,'1. 지점SW 시작하기',1,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(4,3,'1. 2026년 프로그램 업데이트',1,'2026-10-26 17:07:20','2026-10-26 17:07:20');
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
INSERT INTO `users` VALUES (1,'admin','$2y$10$d4hhRCkwiydNzzzwO3IlxuIkuctTzfITS5xr.mERxhcxD2kRF78I2','본사 관리자','책나무 본사',NULL,NULL,100,1,'local','2026-11-02 14:00:20','2026-10-26 17:07:20','2026-11-02 14:00:20'),(2,'demo','$2y$10$dmM.LLhvylSlyKie3bhlWeMiomK1VEMZD0rVGq7DogtjsIGA38RmW','홍길동','책나무 서울송파잠실점',NULL,NULL,1,1,'local',NULL,'2026-10-26 17:07:20','2026-10-26 17:07:20'),(3,'demo2','$2y$10$mvmUuXc9kk011tFle8cszuFib50omx09078Ij.NpxsEaqAIet09am','김영희','책나무 분당정자점',NULL,NULL,1,1,'local',NULL,'2026-10-26 17:07:20','2026-10-26 17:07:20');
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

