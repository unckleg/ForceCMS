-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 30, 2017 at 12:30 
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_acl`
--

CREATE TABLE `cms_acl` (
  `id` int(10) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `cms_acl`
--

INSERT INTO `cms_acl` (`id`, `controller`, `action`) VALUES
(32, 'admin_backup', 'index'),
(6, 'admin_blog', 'category'),
(8, 'admin_blog', 'comment'),
(9, 'admin_blog', 'create'),
(38, 'admin_blog', 'edit'),
(5, 'admin_blog', 'index'),
(39, 'admin_blog', 'status'),
(7, 'admin_blog', 'tag'),
(1, 'admin_dashboard', 'index'),
(22, 'admin_editor', 'index'),
(27, 'admin_filemanager', 'connector'),
(25, 'admin_filemanager', 'index'),
(26, 'admin_filemanager', 'popup'),
(28, 'admin_marketing', 'index'),
(11, 'admin_multimedia', 'create'),
(10, 'admin_multimedia', 'index'),
(41, 'admin_multimedia', 'order'),
(42, 'admin_multimedia', 'upload'),
(17, 'admin_navigation', 'index'),
(4, 'admin_page', 'create'),
(33, 'admin_page', 'edit'),
(3, 'admin_page', 'index'),
(34, 'admin_page', 'status'),
(15, 'admin_partner', 'create'),
(14, 'admin_partner', 'index'),
(13, 'admin_portfolio', 'create'),
(12, 'admin_portfolio', 'index'),
(31, 'admin_privilege', 'index'),
(36, 'admin_session', 'index'),
(35, 'admin_session', 'login'),
(40, 'admin_session', 'logout'),
(29, 'admin_settings', 'index'),
(30, 'admin_sidebar', 'index'),
(21, 'admin_slider', 'create'),
(20, 'admin_slider', 'index'),
(16, 'admin_theme', 'index'),
(24, 'admin_user', 'create'),
(23, 'admin_user', 'index'),
(19, 'admin_widget', 'create'),
(18, 'admin_widget', 'index'),
(2, 'error', 'error'),
(37, 'index', 'index');

-- --------------------------------------------------------

--
-- Table structure for table `cms_acl_to_roles`
--

CREATE TABLE `cms_acl_to_roles` (
  `id` int(10) NOT NULL,
  `acl_id` int(10) NOT NULL,
  `role_id` tinyint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_acl_to_roles`
--

INSERT INTO `cms_acl_to_roles` (`id`, `acl_id`, `role_id`) VALUES
(1, 35, 1),
(2, 36, 1),
(3, 37, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_author`
--

CREATE TABLE `cms_blog_author` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_blog_author`
--

INSERT INTO `cms_blog_author` (`id`, `first_name`, `last_name`) VALUES
(1, 'Developer', 'George');

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_category`
--

CREATE TABLE `cms_blog_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_blog_category`
--

INSERT INTO `cms_blog_category` (`id`, `name`, `status`, `date_created`, `is_deleted`) VALUES
(1, 'Opšte', 1, '2016-12-16 22:43:30', 0),
(2, 'Novosti', 1, '2016-12-16 22:43:30', 0),
(3, 'Akcije', 1, '2016-12-16 22:43:41', 0),
(4, 'Srbija', 1, '2016-12-17 00:46:59', 0),
(5, 'Novak Đoković', 1, '2016-12-17 00:52:11', 0),
(6, 'Blics', 1, '2016-12-17 15:52:05', 0),
(7, 'Nemaa', 1, '2017-01-22 14:04:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_comment`
--

CREATE TABLE `cms_blog_comment` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name_surname` varchar(255) NOT NULL DEFAULT 'anonymouse',
  `mark_read` tinyint(1) NOT NULL,
  `mark_approved` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_blog_comment`
--

INSERT INTO `cms_blog_comment` (`id`, `post_id`, `comment`, `user_id`, `name_surname`, `mark_read`, `mark_approved`, `date_created`) VALUES
(1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', NULL, 'Michael Baker', 0, 0, '2016-12-16 22:45:45'),
(2, 1, 'It is a long established fact that a reader will be distracted.', NULL, 'Larisa Maskalyova', 1, 0, '2016-12-16 22:45:45'),
(3, 1, 'The generated Lorem or non-characteristic Ipsum is therefore or non-characteristic.', NULL, 'Natasha Kim', 0, 1, '2016-12-16 22:45:45'),
(4, 1, '\nThe standard chunk of Lorem or non-characteristic Ipsum used since the 1500s or non-characteristic.', NULL, 'Sebastian Davidson', 0, 0, '2016-12-16 22:45:45'),
(5, 1, 'Lorem ipsum is simpy dummyy', NULL, 'Test name', 1, 1, '2016-12-16 22:45:45'),
(6, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy', NULL, 'Test name 2', 1, 1, '2016-12-16 22:45:45'),
(7, 1, 'It is a long established fact that a reader will be distracted by.', NULL, 'Test name 3', 1, 1, '2016-12-16 22:45:45'),
(8, 1, 'The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', NULL, 'Test name 4', 1, 1, '2016-12-16 22:45:45'),
(9, 1, 'The standard chunk of Lorem Ipsum used since the 1500', NULL, 'Test name 5', 1, 1, '2016-12-16 22:45:45'),
(10, 1, 'Lorem ipsum comment komentar.', NULL, 'Test name 6', 1, 1, '2016-12-16 22:45:45');

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_post`
--

CREATE TABLE `cms_blog_post` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `author_id` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `date_published` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `featured_image` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `comments_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_blog_post`
--

INSERT INTO `cms_blog_post` (`id`, `title`, `author_id`, `text`, `date_published`, `featured_image`, `status`, `comments_enabled`, `views`, `is_deleted`) VALUES
(1, 'G-CMS Blog Post', 1, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy\r\n                        nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. ', '2016-12-16 22:42:12', 'http://keenthemes.com/preview/metronic/theme/assets/pages/img/page_general_search/5.jpg', 1, 0, 0, 0),
(4, 'Nova objavas', 0, '<p>Neki tekst za novu objavu, moze da bude i lorem ipsum sa fotografijom. Testiramo funkcionalnost.</p>\r\n', '2016-12-31 22:55:00', '/uploads/posts/2017-01-28-15-12-20-.jpg', 1, 2, 0, 0),
(5, 'Još jedna objava idemo.', 0, '<p>Neki tekst uz ovu objavu. Ponovo može da bude lorem ipsum ali nije. Samo radi testiranja ovo proveravamo, moramo tako i nikako drugačije.</p>\r\n', '2016-12-13 13:50:00', '/uploads/posts/2016-12-22-00-18-28-ultimate-cms.jpg', 2, 2, 0, 0),
(6, 'GCMS Test', 0, '<p class="blog-post-desc" style="box-sizing: border-box; margin: 15px 0px 30px; color: rgb(160, 169, 180); font-size: 14px; font-family: &quot;Open Sans&quot;, sans-serif; border-radius: 0px !important;">Neki tekst uz ovu objavu. Ponovo može da bude lorem ipsum ali nije. Samo radi testiranja ovo provera..</p>\r\n', '2016-12-13 05:50:00', '/uploads/posts/2016-12-22-00-53-20-ultimate-cms.jpg', 1, 1, 0, 0),
(7, 'Dozvoljeno komentarisanje.', 0, '<p>Dozvoljeno komentarisanje.</p>\r\n', '2016-12-08 10:50:00', '/uploads/posts/2016-12-23-08-56-46-ultimate-cms.jpg', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_post_to_category`
--

CREATE TABLE `cms_blog_post_to_category` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_blog_post_to_category`
--

INSERT INTO `cms_blog_post_to_category` (`id`, `category_id`, `post_id`) VALUES
(7, 1, 5),
(8, 2, 5),
(9, 4, 5),
(10, 3, 6),
(11, 5, 6),
(12, 1, 7),
(13, 2, 7),
(14, 4, 7),
(15, 5, 7),
(16, 6, 7),
(51, 1, 4),
(52, 2, 4),
(53, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cms_blog_tag`
--

CREATE TABLE `cms_blog_tag` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_blog_tag`
--

INSERT INTO `cms_blog_tag` (`id`, `post_id`, `title`) VALUES
(1, 1, 'test'),
(2, 1, 'development'),
(9, 5, 'idemo'),
(10, 5, 'šala'),
(11, 5, 'kako je bre'),
(12, 5, 'novak test'),
(13, 6, 'test'),
(14, 7, 'tawd'),
(15, 7, 'wadawdawd'),
(16, 7, 'awdawddwd'),
(17, 7, 'dd'),
(18, 7, 'd'),
(19, 7, 'ds'),
(20, 7, 'dawdwad'),
(30, 4, 'idemo 3'),
(31, 4, '     malo 4');

-- --------------------------------------------------------

--
-- Table structure for table `cms_multimedia`
--

CREATE TABLE `cms_multimedia` (
  `id` int(10) UNSIGNED NOT NULL,
  `album_name` varchar(50) NOT NULL,
  `album_photo` varchar(500) NOT NULL,
  `url_name` varchar(50) NOT NULL,
  `album_order` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_multimedia`
--

INSERT INTO `cms_multimedia` (`id`, `album_name`, `album_photo`, `url_name`, `album_order`) VALUES
(1, 'Album 1', '/uploads/pages/2016-12-15-01-49-14-ultimate-cms.jpg', '/', 5),
(2, 'Album 2', '/uploads/posts/2016-12-22-00-06-46-ultimate-cms.jpg', '/', 2),
(3, 'Album 3', '/uploads/posts/2016-12-22-00-18-28-ultimate-cms.jpg', '/', 4),
(4, 'Album 4', '/uploads/pages/2016-12-15-01-49-14-ultimate-cms.jpg', '/', 6),
(5, 'Album 5', '/uploads/posts/2016-12-22-00-53-20-ultimate-cms.jpg', '/', 3),
(6, 'Album 6', '/uploads/pages/2016-12-15-01-49-14-ultimate-cms.jpg', '/', 8),
(7, 'Album 7', '/uploads/posts/2016-12-22-00-06-46-ultimate-cms.jpg', '/', 7),
(8, 'Album 8', '/uploads/pages/2016-12-15-01-49-14-ultimate-cms.jpg', '/', 9),
(9, 'Album 9', '/uploads/posts/2016-12-22-00-53-20-ultimate-cms.jpg', '/', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_multimedia_photos`
--

CREATE TABLE `cms_multimedia_photos` (
  `id` int(10) UNSIGNED NOT NULL,
  `photo_name` varchar(50) NOT NULL,
  `photo_path` varchar(1000) DEFAULT NULL,
  `photo_order` tinyint(4) NOT NULL,
  `album_id` int(10) UNSIGNED NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_page`
--

CREATE TABLE `cms_page` (
  `id` int(11) NOT NULL,
  `page_layout` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-active, 1-deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_page`
--

INSERT INTO `cms_page` (`id`, `page_layout`, `status`, `deleted`) VALUES
(1, 3, 1, 0),
(2, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_page_content`
--

CREATE TABLE `cms_page_content` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_description` varchar(500) NOT NULL,
  `seo_keywords` varchar(500) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `text` longtext NOT NULL,
  `page_photo` varchar(500) DEFAULT NULL,
  `status` int(4) NOT NULL DEFAULT '1' COMMENT '1-visible, 0-hidden',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_page_content`
--

INSERT INTO `cms_page_content` (`id`, `page_id`, `language_id`, `seo_title`, `seo_description`, `seo_keywords`, `title`, `description`, `text`, `page_photo`, `status`, `date_created`) VALUES
(1, 1, 1, 'Test page on EN', 'Test page on EN', 'Test page on EN', 'Test English #2', NULL, '<p>Test page on EN</p>\r\n', '/uploads/pages/2016-12-15-22-54-39-ultimate-cms.jpg', 1, '2016-11-27 01:08:22'),
(4, 1, 2, 'Serbizan', 'Serbizan', 'Serbizan', 'Serbizan', NULL, '<p>Serbizan</p>\r\n', NULL, 1, '2016-11-27 13:46:13'),
(5, 2, 1, 'awdawdawd', 'awdawdawd', 'awdawd', 'awdawdawd', NULL, '<p>awdawdawd</p>\r\n', '/uploads/pages/2016-12-15-01-49-14-ultimate-cms.jpg', 1, '2016-12-15 01:18:58'),
(6, 2, 2, 'Seo srpski', 'Seo srpski', 'Seo srpski', 'Na srpskom jeziku', NULL, '<p>Test</p>\r\n', NULL, 1, '2016-12-15 22:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `cms_roles`
--

CREATE TABLE `cms_roles` (
  `id` tinyint(1) NOT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_roles`
--

INSERT INTO `cms_roles` (`id`, `role`) VALUES
(1, 'Guest'),
(2, 'Moderator'),
(3, 'Admin'),
(4, 'Superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `cms_sidebar`
--

CREATE TABLE `cms_sidebar` (
  `id` int(11) NOT NULL,
  `acl_id` int(11) DEFAULT NULL,
  `label_id` int(11) NOT NULL,
  `separator_id` int(11) DEFAULT NULL,
  `order_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_sidebar`
--

INSERT INTO `cms_sidebar` (`id`, `acl_id`, `label_id`, `separator_id`, `order_number`) VALUES
(2, 3, 1, 1, 1),
(3, 4, 1, NULL, 2),
(4, 5, 2, NULL, 1),
(5, 6, 2, NULL, 2),
(6, 7, 2, NULL, 3),
(7, 8, 2, NULL, 4),
(8, 9, 2, NULL, 5),
(9, 10, 3, NULL, 1),
(10, 11, 3, NULL, 2),
(11, 12, 4, NULL, 1),
(12, 13, 4, NULL, 2),
(13, 14, 5, NULL, 1),
(14, 15, 5, NULL, 2),
(15, 16, 6, NULL, 1),
(16, 17, 6, NULL, 2),
(17, 18, 6, NULL, 3),
(18, 20, 6, NULL, 4),
(19, 22, 6, NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cms_sidebar_content`
--

CREATE TABLE `cms_sidebar_content` (
  `id` int(11) NOT NULL,
  `sidebar_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_sidebar_content`
--

INSERT INTO `cms_sidebar_content` (`id`, `sidebar_id`, `language_id`, `title`) VALUES
(1, 2, 1, 'Lista stranica'),
(2, 3, 1, 'Nova stranica'),
(3, 4, 1, 'Lista objava'),
(4, 5, 1, 'Kategorije'),
(5, 6, 1, 'Oznake'),
(6, 7, 1, 'Komentari'),
(7, 8, 1, 'Nova objava'),
(8, 9, 1, 'Lista galerija'),
(9, 10, 1, 'Nova galerija'),
(10, 11, 1, 'Lista '),
(11, 12, 1, 'Nova portfolio'),
(12, 13, 1, 'Svi partneri'),
(13, 14, 1, 'Novi partner'),
(14, 15, 1, 'Teme'),
(15, 16, 1, 'Meniji'),
(16, 17, 1, 'Dodaci'),
(17, 18, 1, 'Slajderi'),
(18, 19, 1, 'Editor');

-- --------------------------------------------------------

--
-- Table structure for table `cms_sidebar_label`
--

CREATE TABLE `cms_sidebar_label` (
  `id` int(11) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_sidebar_label`
--

INSERT INTO `cms_sidebar_label` (`id`, `icon`, `order_number`) VALUES
(1, 'icon-layers', 1),
(2, 'icon-pin', 2),
(3, 'icon-list', 3),
(4, 'icon-puzzle', 4),
(5, 'icon-briefcase', 5),
(6, 'icon-grid', 6);

-- --------------------------------------------------------

--
-- Table structure for table `cms_sidebar_label_content`
--

CREATE TABLE `cms_sidebar_label_content` (
  `id` int(11) NOT NULL,
  `sidebar_label_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_sidebar_label_content`
--

INSERT INTO `cms_sidebar_label_content` (`id`, `sidebar_label_id`, `language_id`, `title`) VALUES
(1, 1, 1, 'Stranice'),
(2, 2, 1, 'Blog'),
(3, 3, 1, 'Multimedija'),
(4, 4, 1, 'Portfolio'),
(5, 5, 1, 'Partneri'),
(6, 6, 1, 'Izgled');

-- --------------------------------------------------------

--
-- Table structure for table `cms_sidebar_separator`
--

CREATE TABLE `cms_sidebar_separator` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_sidebar_separator`
--

INSERT INTO `cms_sidebar_separator` (`id`) VALUES
(1),
(2),
(3),
(4);

-- --------------------------------------------------------

--
-- Table structure for table `cms_sidebar_separator_content`
--

CREATE TABLE `cms_sidebar_separator_content` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cms_sidebar_separator_content`
--

INSERT INTO `cms_sidebar_separator_content` (`id`, `parent_id`, `language_id`, `title`) VALUES
(1, 1, 1, 'MENADŽMENT SAJTA'),
(2, 2, 1, 'DIZAJN SAJTA'),
(3, 3, 1, 'OPŠTE'),
(4, 4, 1, 'PROGRAMER');

-- --------------------------------------------------------

--
-- Table structure for table `cms_users`
--

CREATE TABLE `cms_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `notes` text NOT NULL,
  `role` varchar(20) NOT NULL,
  `role_id` int(11) NOT NULL,
  `ban` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_users`
--

INSERT INTO `cms_users` (`id`, `username`, `password`, `name`, `surname`, `phone`, `notes`, `role`, `role_id`, `ban`) VALUES
(1, 'djordjeadmin', '089c1a6c4906d9c73e272ab6e39dc842', 'Djordje', 'Stojiljković', '+381 60 528-9528', '', 'Superadmin', 4, 0),
(2, 'mdrago', '8f178b383653f304d22ae40d08b6e0cf', 'Milomir', 'Dragović', '', '', 'Superadmin', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `short` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `short`, `name`, `status`, `priority`) VALUES
(1, 'en', 'English', 1, 1),
(2, 'sr', 'Serbian', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `theme_name` varchar(255) NOT NULL,
  `theme_description` longtext NOT NULL,
  `theme_folder` varchar(255) NOT NULL,
  `theme_status` tinyint(4) NOT NULL DEFAULT '0',
  `theme_screenshot` varchar(500) NOT NULL DEFAULT 'no-img.png',
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `who_created` int(11) NOT NULL,
  `date_created` timestamp NOT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `theme_name`, `theme_description`, `theme_folder`, `theme_status`, `theme_screenshot`, `lastchange`, `who_created`, `date_created`) VALUES
(1, 'Cardio - Bootstrap Theme', 'Cardio is a clean and modern looking, responsive one page website template built with Bootstrap. It has a gym related theme but it can be easily adjusted to fit well with any kind of topic. The template comes with a smooth page navigation and some subtle transition effects. The design is very clean and spacious with a fresh color theme and solid typography.', 'cardio', 1, '/themes/cardio/screenshot.png', '2016-11-17 14:13:50', 1, '2016-11-17 00:00:00'),
(2, 'Solid - Bootstrap Theme', 'Solid is a 7 pages theme ideal for web agencies and freelancers. Uses Font Awesome, Masonry Javascript, PrettyPhoto lightbox and nice hover effects thanks Codrops. Theme includes the Retina.js to work nice with retina display devices.', 'solid', 0, '/themes/solid/screenshot.png', '2016-11-17 14:13:50', 1, '2016-11-17 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_acl`
--
ALTER TABLE `cms_acl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `controller` (`controller`,`action`);

--
-- Indexes for table `cms_acl_to_roles`
--
ALTER TABLE `cms_acl_to_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_author`
--
ALTER TABLE `cms_blog_author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_category`
--
ALTER TABLE `cms_blog_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_comment`
--
ALTER TABLE `cms_blog_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_post`
--
ALTER TABLE `cms_blog_post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_post_to_category`
--
ALTER TABLE `cms_blog_post_to_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_blog_tag`
--
ALTER TABLE `cms_blog_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_multimedia`
--
ALTER TABLE `cms_multimedia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`album_name`),
  ADD KEY `url_name` (`url_name`);

--
-- Indexes for table `cms_multimedia_photos`
--
ALTER TABLE `cms_multimedia_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `cms_page`
--
ALTER TABLE `cms_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_page_content`
--
ALTER TABLE `cms_page_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_roles`
--
ALTER TABLE `cms_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sidebar`
--
ALTER TABLE `cms_sidebar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sidebar_content`
--
ALTER TABLE `cms_sidebar_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sidebar_label`
--
ALTER TABLE `cms_sidebar_label`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sidebar_label_content`
--
ALTER TABLE `cms_sidebar_label_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sidebar_separator`
--
ALTER TABLE `cms_sidebar_separator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sidebar_separator_content`
--
ALTER TABLE `cms_sidebar_separator_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_users`
--
ALTER TABLE `cms_users`
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `short` (`short`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_acl`
--
ALTER TABLE `cms_acl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `cms_acl_to_roles`
--
ALTER TABLE `cms_acl_to_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cms_blog_author`
--
ALTER TABLE `cms_blog_author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cms_blog_category`
--
ALTER TABLE `cms_blog_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `cms_blog_comment`
--
ALTER TABLE `cms_blog_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `cms_blog_post`
--
ALTER TABLE `cms_blog_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `cms_blog_post_to_category`
--
ALTER TABLE `cms_blog_post_to_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `cms_blog_tag`
--
ALTER TABLE `cms_blog_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `cms_multimedia`
--
ALTER TABLE `cms_multimedia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `cms_multimedia_photos`
--
ALTER TABLE `cms_multimedia_photos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cms_page`
--
ALTER TABLE `cms_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `cms_page_content`
--
ALTER TABLE `cms_page_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cms_roles`
--
ALTER TABLE `cms_roles`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_sidebar`
--
ALTER TABLE `cms_sidebar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `cms_sidebar_content`
--
ALTER TABLE `cms_sidebar_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `cms_sidebar_label`
--
ALTER TABLE `cms_sidebar_label`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cms_sidebar_label_content`
--
ALTER TABLE `cms_sidebar_label_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cms_sidebar_separator`
--
ALTER TABLE `cms_sidebar_separator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_sidebar_separator_content`
--
ALTER TABLE `cms_sidebar_separator_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_users`
--
ALTER TABLE `cms_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
