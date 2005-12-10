#
# ELGG database schema
#
# This must be installed into your chosen Elgg database before you can run Elgg
#

# --------------------------------------------------------


-- 
-- Table structure for table `content_flags`
-- 

CREATE TABLE `content_flags` (
  `ident` int(11) NOT NULL auto_increment,
  `url` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `url` (`url`)
) ;


-- --------------------------------------------------------

-- 
-- Table structure for table `file_folders`
-- 

CREATE TABLE `file_folders` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `files_owner` int(11) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `access` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`parent`,`name`,`access`)
) ;


-- --------------------------------------------------------

-- 
-- Table structure for table `file_metadata`
-- 

CREATE TABLE `file_metadata` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `file_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`file_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `files`
-- 

CREATE TABLE `files` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `files_owner` int(11) NOT NULL default '0',
  `folder` int(11) NOT NULL default '-1',
  `community` int(11) NOT NULL default '-1',
  `title` varchar(255) NOT NULL default '',
  `originalname` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `access` varchar(255) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  `time_uploaded` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`folder`,`access`),
  KEY `size` (`size`),
  KEY `time_uploaded` (`time_uploaded`),
  KEY `originalname` (`originalname`),
  KEY `community` (`community`),
  KEY `files_owner` (`files_owner`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `friends`
-- 

CREATE TABLE `friends` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `friend` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`friend`)
) ;


-- --------------------------------------------------------

-- 
-- Table structure for table `group_membership`
-- 

CREATE TABLE `group_membership` (
  `ident` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `user_id` (`user_id`,`group_id`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `groups`
-- 

CREATE TABLE `groups` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `access` varchar(255) NOT NULL default 'PUBLIC',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`name`),
  KEY `access` (`access`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `icons`
-- 

CREATE TABLE `icons` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `filename` varchar(128) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`filename`,`description`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `invitations`
-- 

CREATE TABLE `invitations` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `code` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`email`,`code`,`owner`,`added`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `password_requests`
-- 

CREATE TABLE `password_requests` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `code` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`code`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `profile_data`
-- 

CREATE TABLE `profile_data` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL default '0',
  `access` varchar(16) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`access`,`name`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 

CREATE TABLE `tags` (
  `ident` int(11) NOT NULL auto_increment,
  `tag` varchar(128) NOT NULL default '',
  `tagtype` varchar(128) NOT NULL default '',
  `ref` int(11) NOT NULL default '0',
  `access` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `tag` (`tag`,`tagtype`,`ref`,`access`),
  KEY `owner` (`owner`),
  FULLTEXT KEY `tag_2` (`tag`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `template_elements`
-- 

CREATE TABLE `template_elements` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `content` text NOT NULL,
  `template_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`template_id`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `templates`
-- 

CREATE TABLE `templates` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  `public` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`owner`,`public`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user_flags`
-- 

CREATE TABLE `user_flags` (
  `ident` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `flag` varchar(64) NOT NULL default '',
  `value` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `user_id` (`user_id`,`flag`,`value`)
) ;

INSERT INTO `user_flags` VALUES (0,1,'admin','1');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `name` varchar(128) NOT NULL default '',
  `icon` int(11) NOT NULL default '-1',
  `active` enum('yes','no') NOT NULL default 'yes',
  `alias` varchar(128) NOT NULL default '',
  `code` varchar(32) NOT NULL default '',
  `icon_quota` int(11) NOT NULL default '10',
  `file_quota` int(11) NOT NULL default '10000000',
  `template_id` int(11) NOT NULL default '-1',
  `owner` int(11) NOT NULL default '-1',
  `user_type` varchar(128) NOT NULL default 'person',
  `last_action` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `username` (`username`,`password`,`name`,`active`),
  KEY `code` (`code`),
  KEY `icon` (`icon`),
  KEY `icon_quota` (`icon_quota`),
  KEY `file_quota` (`file_quota`),
  KEY `email` (`email`),
  KEY `template_id` (`template_id`),
  KEY `community` (`owner`),
  KEY `user_type` (`user_type`),
  KEY `last_action` (`last_action`),
  FULLTEXT KEY `name` (`name`)
) ;

INSERT INTO `users` VALUES (1, 'news', '5f4dcc3b5aa765d61d8327deb882cf99', '', 'News', -1, 'yes', '', '', 10, 10000000, -1, -1, 'person', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `weblog_comments`
-- 

CREATE TABLE `weblog_comments` (
  `ident` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL default '0',
  `postedname` varchar(128) NOT NULL default '',
  `body` text NOT NULL,
  `posted` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`),
  KEY `posted` (`posted`),
  KEY `post_id` (`post_id`),
  KEY `postedname` (`postedname`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `weblog_posts`
--

CREATE TABLE `weblog_posts` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `weblog` int(11) NOT NULL default '-1',
  `access` varchar(255) NOT NULL default '',
  `posted` int(11) NOT NULL default '0',
  `title` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`access`,`posted`),
  KEY `community` (`weblog`)
) ;


INSERT INTO `weblog_posts` VALUES (15, 1, 1, 'PUBLIC', 1119422380, 'Hello', 'Welcome to this Elgg installation.');
