/*
SQLyog Ultimate v12.5.0 (64 bit)
MySQL - 10.4.27-MariaDB : Database - 23598_ahsan_ali_online_blogging_application
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`23598_ahsan_ali_online_blogging_application` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `23598_ahsan_ali_online_blogging_application`;

/*Table structure for table `blog` */

DROP TABLE IF EXISTS `blog`;

CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `blog_title` varchar(200) DEFAULT NULL,
  `post_per_page` int(11) DEFAULT NULL,
  `blog_background_image` text DEFAULT NULL,
  `blog_status` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `blog` */

insert  into `blog`(`blog_id`,`user_id`,`blog_title`,`post_per_page`,`blog_background_image`,`blog_status`,`created_at`,`updated_at`) values 
(20,1,'Tech Trends',5,'blog_68337143614315.79231560.jpeg','Active','2025-05-26 00:41:08','2025-05-26 12:41:08'),
(21,1,'Foodie\'s Paradise',8,'blog_68337380e03d64.38107344.jpg','Active','2025-05-26 00:46:08','2025-05-25 09:46:08'),
(22,1,'Travel Chronicles',6,'blog_683379951bd1a4.34608317.jpeg','Active','2025-05-26 01:19:45','2025-05-26 01:19:45'),
(23,2,'Dummy Blog',10,'blog_6833ffd12e2be6.12158118.jpg','Active','2025-05-26 10:53:56','2025-05-26 10:53:56');

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(100) DEFAULT NULL,
  `category_description` text DEFAULT NULL,
  `category_status` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `category` */

insert  into `category`(`category_id`,`category_title`,`category_description`,`category_status`,`created_at`,`updated_at`) values 
(15,'Technology','Posts about the latest in tech and innovation.','Active','2025-05-25 21:51:52','0000-00-00 00:00:00'),
(16,'Food','Recipes, reviews, and food adventures.','Active','2025-05-25 21:52:27','0000-00-00 00:00:00'),
(17,'Travel','Guides and experiences from around the world.','Active','2025-05-25 21:52:59','0000-00-00 00:00:00'),
(18,'Fashion','Trends, tips, and outfit inspiration.','Active','2025-05-25 21:53:26','0000-00-00 00:00:00'),
(19,'Health &amp; Wellness','Tips for a healthy body and mind.','Active','2025-05-25 21:54:01','0000-00-00 00:00:00'),
(20,'Motivation','Quotes, habits, and stories to inspire','Active','2025-05-25 21:54:30','0000-00-00 00:00:00'),
(21,'Art','Creative works, painting guides, and galleries.','Active','2025-05-25 21:55:02','0000-00-00 00:00:00'),
(22,'History','Historical events, figures, and timelines','Active','2025-05-25 21:55:31','0000-00-00 00:00:00'),
(23,'Business &amp; Startups','Tips, trends, and stories from entrepreneurs.','Active','2025-05-26 02:42:43','2025-05-25 11:42:43'),
(24,'Lifestyle','Daily life hacks, productivity, and routines','Active','2025-05-26 02:42:50','2025-05-25 11:42:50'),
(25,'Education','Learning resources, study guides, and school tips','Active','2025-05-26 02:42:57','2025-05-25 11:42:57'),
(26,'Gaming','Game reviews, news, and walkthroughs','Active','2025-05-25 21:57:24','0000-00-00 00:00:00'),
(27,'Photography','Tips, techniques, and inspiration for photographers','Active','2025-05-25 22:10:33','0000-00-00 00:00:00');

/*Table structure for table `following_blog` */

DROP TABLE IF EXISTS `following_blog`;

CREATE TABLE `following_blog` (
  `follow_id` int(11) NOT NULL AUTO_INCREMENT,
  `follower_id` int(11) DEFAULT NULL,
  `blog_following_id` int(11) DEFAULT NULL,
  `status` enum('Followed','Unfollowed') DEFAULT 'Followed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`follow_id`),
  KEY `blog_following_id` (`blog_following_id`),
  KEY `follower_id` (`follower_id`),
  CONSTRAINT `following_blog_ibfk_1` FOREIGN KEY (`blog_following_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `following_blog_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `following_blog` */

insert  into `following_blog`(`follow_id`,`follower_id`,`blog_following_id`,`status`,`created_at`,`updated_at`) values 
(1,33,22,'Followed','2025-05-26 10:31:02','0000-00-00 00:00:00'),
(2,33,21,'Followed','2025-05-26 10:31:04','0000-00-00 00:00:00'),
(3,33,20,'Followed','2025-05-26 10:31:06','0000-00-00 00:00:00'),
(4,34,23,'Followed','2025-05-26 10:57:02','0000-00-00 00:00:00'),
(5,34,22,'Followed','2025-05-26 10:57:04','0000-00-00 00:00:00'),
(6,34,21,'Followed','2025-05-26 10:57:07','0000-00-00 00:00:00');

/*Table structure for table `post` */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `post_title` varchar(200) NOT NULL,
  `post_summary` text NOT NULL,
  `post_description` longtext NOT NULL,
  `featured_image` text DEFAULT NULL,
  `post_status` enum('Active','InActive') DEFAULT NULL,
  `is_comment_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `blog_id` (`blog_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post` */

insert  into `post`(`post_id`,`blog_id`,`post_title`,`post_summary`,`post_description`,`featured_image`,`post_status`,`is_comment_allowed`,`created_at`,`updated_at`) values 
(31,20,'The Future of AI','Exploring trends in artificial intelligence','A deep dive into the rapid advancement of AI technologies, their applications, and ethical implications.','1748203143_ai.jpg','Active',1,'2025-05-26 01:01:03','2025-05-25 10:01:03'),
(33,21,'Top 10 Street Foods in Lahore','Must-try local delicacies','A mouth-watering guide to the best street food stalls in Lahore, from spicy chaat to creamy kulfi','1748203521_lahore_food.jpg','Active',1,'2025-05-26 01:05:47','2025-05-25 10:05:47'),
(34,20,'AI in Everyday Life','Exploring AI applications in daily routines','From voice assistants to personalized shopping, AI is reshaping our world.','1748204292_ai.jpg','Active',1,'2025-05-25 10:18:12',NULL),
(35,21,'Mastering the Art of Pasta','Homemade pasta tips & tricks','Learn to make fresh pasta from scratch with tips on sauces and fillings.','1748206288_pasta.jpeg','Active',1,'2025-05-26 01:51:28','2025-05-25 22:51:28'),
(36,22,'Backpacking Through Europe','Budget travel across iconic cities','A detailed guide to planning your Europe backpacking trip, including hostels, food, and transportation tips.','1748204828_europe.jpeg','Active',1,'2025-05-25 10:27:08',NULL),
(37,22,'Top 10 Hidden Gems in Asia','Discover the unbeaten path','Explore lesser-known destinations in Asia that are rich in culture and untouched beauty.','1748206223_asia.jpg','Active',1,'2025-05-26 10:33:21','2025-05-26 07:33:21'),
(38,23,'Dummy Post 1','Lorem ipsum dolor sit amet consectetur adipisicing elit.','Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime blanditiis error assumenda totam reiciendis animi, alias consequuntur! Officia veniam itaque harum voluptatibus deleniti possimus eaque animi? Deserunt unde aut et!','1748238369_blog_cover.jpg','Active',1,'2025-05-26 12:04:37','2025-05-26 09:04:37'),
(39,23,'Dummy Post 2','Lorem ipsum dolor sit amet consectetur adipisicing elit.','Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime blanditiis error assumenda totam reiciendis animi, alias consequuntur! Officia veniam itaque harum voluptatibus deleniti possimus eaque animi? Deserunt unde aut et!','1748238605_Blog-intro.jpg','Active',1,'2025-05-26 12:04:34','2025-05-26 09:04:34');

/*Table structure for table `post_atachment` */

DROP TABLE IF EXISTS `post_atachment`;

CREATE TABLE `post_atachment` (
  `post_atachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `post_attachment_title` varchar(200) DEFAULT NULL,
  `post_attachment_path` text DEFAULT NULL,
  `is_active` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_atachment_id`),
  KEY `fk1` (`post_id`),
  CONSTRAINT `fk1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_atachment` */

insert  into `post_atachment`(`post_atachment_id`,`post_id`,`post_attachment_title`,`post_attachment_path`,`is_active`,`created_at`,`updated_at`) values 
(2,37,'Asia.jpg','../uploads/attachments/Asia.jpg','Active','2025-05-26 02:08:28','2025-05-25 23:08:28'),
(3,38,'1748238530_multi-select-tag.css','../uploads/attachments/1748238530_multi-select-tag.css','Active','2025-05-26 10:48:57','2025-05-26 07:48:57');

/*Table structure for table `post_category` */

DROP TABLE IF EXISTS `post_category`;

CREATE TABLE `post_category` (
  `post_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_category_id`),
  KEY `post_id` (`post_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `post_category_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_category` */

insert  into `post_category`(`post_category_id`,`post_id`,`category_id`,`created_at`,`updated_at`) values 
(1,31,15,'2025-05-26 00:59:03',NULL),
(2,31,25,'2025-05-26 00:59:03',NULL),
(5,33,16,'2025-05-26 01:05:21',NULL),
(6,33,17,'2025-05-26 01:05:21',NULL),
(7,34,15,'2025-05-26 01:18:12',NULL),
(8,34,24,'2025-05-26 01:18:12',NULL),
(9,34,25,'2025-05-26 01:18:12',NULL),
(14,36,17,'2025-05-26 01:27:08',NULL),
(15,36,25,'2025-05-26 01:27:08',NULL),
(26,35,16,'2025-05-26 01:51:28','2025-05-25 22:51:28'),
(27,35,25,'2025-05-26 01:51:28','2025-05-25 22:51:28'),
(33,37,17,'2025-05-26 02:08:28','2025-05-25 23:08:28'),
(52,38,17,'2025-05-26 10:58:50',NULL),
(53,39,25,'2025-05-26 10:59:01',NULL),
(54,39,25,'2025-05-26 10:59:12',NULL);

/*Table structure for table `post_comment` */

DROP TABLE IF EXISTS `post_comment`;

CREATE TABLE `post_comment` (
  `post_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `is_active` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`post_comment_id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `post_comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_comment` */

insert  into `post_comment`(`post_comment_id`,`post_id`,`user_id`,`comment`,`is_active`,`created_at`) values 
(1,34,31,'AI has totally changed how I use my phone. Voice assistants are a blessing!','Active','2025-05-26 02:13:35'),
(2,34,2,'I love how Netflix recommends shows based on my taste. AI is powerful!','Active','2025-05-26 02:42:29'),
(3,38,34,'like your post','Active','2025-05-26 11:39:15'),
(4,38,34,'this is Comment 2','Active','2025-05-26 11:39:18'),
(5,37,34,'Dummy Comment 1','Active','2025-05-26 11:39:24'),
(6,37,34,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime blanditiis error assumenda totam reiciendis animi, alias consequuntur! Officia veniam itaque harum voluptatibus deleniti possimus eaque animi? Deserunt unde aut et!','Active','2025-05-26 11:39:28');

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_type` varchar(50) NOT NULL,
  `is_active` enum('Active','InActive') DEFAULT 'Active',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `role` */

insert  into `role`(`role_id`,`role_type`,`is_active`) values 
(1,'Admin','InActive'),
(2,'User','Active');

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` varchar(100) DEFAULT NULL,
  `setting_status` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`setting_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `setting` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` text NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `user_image` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_approved` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `is_active` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user` */

insert  into `user`(`user_id`,`role_id`,`first_name`,`last_name`,`email`,`password`,`gender`,`date_of_birth`,`user_image`,`address`,`is_approved`,`is_active`,`created_at`,`updated_at`) values 
(1,1,'Admin','User','admin@example.com','1234','Male','1990-01-01','1748209325_user.jpg','Admin Street, City','Approved','InActive','2025-05-26 09:40:24','2025-05-25 23:42:05'),
(2,1,'Ahsan','Ali','ahsanalime3@gmail.com','Qwerty123','Male','2003-01-02',NULL,'Jamshoro, Sindh','Approved','Active','2025-05-26 10:43:58',NULL),
(31,2,'Ali','Khan','alikhan@example.com','Pass@123','Male','1995-06-15','1748200817_user3.jpeg','123 Main St, Lahore','Approved','Active','2025-05-26 02:41:13','2025-05-25 21:20:17'),
(32,2,'Sara','Ahmed','saraahmed@example.com','Sara#456','Female','1997-03-22','1748210175_user4.jpg','45 Garden Town, Karachi','Approved','Active','2025-05-26 02:59:06',NULL),
(33,2,'Usman','Tariq','usmant@example.com','Usm@n789','Male','1990-12-05','1748210618_user1 (1).jpeg','88 Gulberg III, Lahore','Approved','Active','2025-05-26 10:30:33','2025-05-26 10:30:33'),
(34,2,'Ayesha','Noor','ayeshan@example.com','Ay#100101','Female','2000-10-01','1748210730_user6.jpeg','12 Clifton Block 9, Karachi','Approved','Active','2025-05-26 10:34:51',NULL),
(35,2,'Tony','Stark','tonystark@gmail.com','tonystark123','Male','1986-07-04','','Los Angelas, California','Approved','Active','2025-05-26 11:38:15',NULL);

/*Table structure for table `user_feedback` */

DROP TABLE IF EXISTS `user_feedback`;

CREATE TABLE `user_feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`feedback_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user_feedback` */

insert  into `user_feedback`(`feedback_id`,`user_id`,`user_name`,`user_email`,`feedback`,`created_at`) values 
(1,33,'Usman Tariq','usmant@example.com','Like Your Blog Website.','2025-05-26 07:32:13'),
(2,NULL,'John Doe','john@gmail.com','This John Feedback...!','2025-05-26 07:34:10'),
(3,34,'Ayesha Noor','ayeshan@example.com','BlogBeat is great platform for Blogging...!','2025-05-26 08:40:26');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
