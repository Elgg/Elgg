#
# ELGG database schema
#
# This must be installed into your chosen Elgg database before you can run Elgg
#

# --------------------------------------------------------

/*!40101 ALTER DATABASE DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

-- 
-- Table structure for table `content_flags`
-- 

CREATE TABLE `prefix_content_flags` (
  `ident` int(11) NOT NULL auto_increment,
  `url` varchar(128) NOT NULL default '',
  PRIMARY KEY (`ident`),
  KEY `url` (`url`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `file_folders`
-- 

CREATE TABLE `prefix_file_folders` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, folder creator',
  `files_owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, folder owner (community)',
  `parent` int(11) NOT NULL default '0' COMMENT '-> file_folders.ident, parent folder',
  `name` varchar(128) NOT NULL default '',
  `access` varchar(20) NOT NULL default 'PUBLIC',
  `handler` varchar(32) NOT NULL default 'elgg',
  PRIMARY KEY (`ident`),
  KEY `files_owner` (`files_owner`),
  KEY `owner` (`owner`),
  KEY `access` (`access`),
  KEY `name` (`name`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `file_metadata`
-- 

CREATE TABLE `prefix_file_metadata` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `file_id` int(11) NOT NULL default '0' COMMENT '-> files.ident',
  PRIMARY KEY (`ident`),
  KEY `name` (`name`,`file_id`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `files`
-- 

CREATE TABLE `prefix_files` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, file uploader',
  `files_owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, file owner (community)',
  `folder` int(11) NOT NULL default '-1' COMMENT '-> file_folders.ident, parent folder',
  `community` int(11) NOT NULL default '-1' COMMENT 'not used?',
  `title` varchar(255) NOT NULL default '',
  `originalname` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '' COMMENT 'file location in dataroot',
  `access` varchar(20) NOT NULL default 'PUBLIC',
  `size` int(11) NOT NULL default '0' COMMENT 'bytes',
  `time_uploaded` int(11) NOT NULL default '0' COMMENT 'unix timestamp',
  `handler` varchar(32) NOT NULL default 'elgg',
  PRIMARY KEY (`ident`),
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

CREATE TABLE `prefix_friends` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, doing the friending',
  `friend` int(11) NOT NULL default '0' COMMENT '-> users.ident, being friended',
  `status` varchar(4) NOT NULL default 'perm' COMMENT 'not used?',
  PRIMARY KEY (`ident`),
  UNIQUE KEY `owner` (`owner`, `friend`),
  KEY `friend` (`friend`),
  KEY `status` (`status`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `friends_requests`
-- 

CREATE TABLE `prefix_friends_requests` (
  `ident` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `owner` INT NOT NULL COMMENT '-> users.ident, doing the friending',
  `friend` INT NOT NULL COMMENT '-> users.ident, being friended',
  PRIMARY KEY (`ident`) ,
  UNIQUE KEY (`owner`,`friend`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `group_membership`
-- 

CREATE TABLE `prefix_group_membership` (
  `ident` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0' COMMENT '-> users.ident',
  `group_id` int(11) NOT NULL default '0' COMMENT '-> groups.ident',
  PRIMARY KEY (`ident`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `groups`
-- 

CREATE TABLE `prefix_groups` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident',
  `name` varchar(128) NOT NULL default '',
  `access` varchar(20) NOT NULL default 'PUBLIC',
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`,`name`),
  KEY `access` (`access`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `icons`
-- 

CREATE TABLE `prefix_icons` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident',
  `filename` varchar(128) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `invitations`
-- 

CREATE TABLE `prefix_invitations` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `code` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, sender of invitation',
  `added` int(11) NOT NULL default '0' COMMENT 'unix timestamp',
  PRIMARY KEY (`ident`),
  KEY `code` (`code`),
  KEY `email` (`email`),
  KEY `added` (`added`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `password_requests`
-- 

CREATE TABLE `prefix_password_requests` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident',
  `code` varchar(128) NOT NULL default '',
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`,`code`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `profile_data`
-- 

CREATE TABLE `prefix_profile_data` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL default '0' COMMENT '-> users.ident',
  `access` varchar(20) NOT NULL default 'PUBLIC',
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`,`access`,`name`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 

CREATE TABLE `prefix_tags` (
  `ident` int(11) NOT NULL auto_increment,
  `tag` varchar(128) NOT NULL default '',
  `tagtype` varchar(20) NOT NULL default '' COMMENT 'type of object the tag links to',
  `ref` int(11) NOT NULL default '0' COMMENT 'ident of object the tag links to',
  `access` varchar(20) NOT NULL default 'PUBLIC',
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident',
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`),
  KEY `tagtype_ref` (`tagtype`,`ref`),
  FULLTEXT KEY `tag` (`tag`),
  KEY `tagliteral` (`tag`(20)),
  KEY `access` (`access`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `template_elements`
-- 

CREATE TABLE `prefix_template_elements` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `content` text NOT NULL,
  `template_id` int(11) NOT NULL default '0' COMMENT '-> templates.ident',
  PRIMARY KEY (`ident`),
  KEY `name` (`name`,`template_id`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `templates`
-- 

CREATE TABLE `prefix_templates` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, template creator',
  `public` enum('yes','no') NOT NULL default 'yes',
  `shortname` varchar(128) NOT NULL,
  PRIMARY KEY (`ident`),
  KEY `name` (`name`,`owner`,`public`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user_flags`
-- 

CREATE TABLE `prefix_user_flags` (
  `ident` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0' COMMENT '-> users.ident, user the flag refers to',
  `flag` varchar(64) NOT NULL default '',
  `value` varchar(64) NOT NULL default '',
  PRIMARY KEY (`ident`),
  KEY `user_id` (`user_id`,`flag`,`value`)
) ;

INSERT INTO `prefix_user_flags` VALUES (0,1,'admin','1');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `prefix_users` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(128) NOT NULL default '' COMMENT 'login name',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `name` varchar(128) NOT NULL default '' COMMENT 'descriptive name',
  `icon` int(11) NOT NULL default '-1' COMMENT '-> icons.ident',
  `active` enum('yes','no') NOT NULL default 'yes',
  `alias` varchar(128) NOT NULL default '',
  `code` varchar(32) NOT NULL default '' COMMENT 'auth value for cookied login',
  `icon_quota` int(11) NOT NULL default '10' COMMENT 'number of icons',
  `file_quota` int(11) NOT NULL default '1000000000' COMMENT 'bytes',
  `template_id` int(11) NOT NULL default '-1' COMMENT '-> templates.ident',
  `owner` int(11) NOT NULL default '-1' COMMENT '-> users.ident, community owner',
  `user_type` varchar(128) NOT NULL default 'person' COMMENT 'person, community, etc',
  `moderation` varchar(4) NOT NULL default 'no' COMMENT 'friendship moderation setting',
  `last_action` int(10) unsigned NOT NULL default '0' COMMENT 'unix timestamp',
  `template_name` varchar(128) NOT NULL default 'Default_Template' COMMENT '-> templates.shortname',
  PRIMARY KEY (`ident`),
  KEY `username` (`username`,`password`,`name`,`active`),
  KEY `code` (`code`),
  KEY `icon` (`icon`),
  KEY `icon_quota` (`icon_quota`),
  KEY `file_quota` (`file_quota`),
  KEY `email` (`email`),
  KEY `template_id` (`template_id`),
  KEY `community` (`owner`),
  KEY `user_type` (`user_type`),
  KEY `moderation` (`moderation`),
  KEY `last_action` (`last_action`),
  FULLTEXT KEY `name` (`name`)
) TYPE=MyISAM;

INSERT INTO `prefix_users` VALUES (0, 'news', '5f4dcc3b5aa765d61d8327deb882cf99', '', 'News', -1, 'yes', '', '', 10, 10000000, -1, -1, 'person', 'no', 0, 'Default_Template');

-- --------------------------------------------------------

-- 
-- Table structure for table `weblog_comments`
-- 

CREATE TABLE `prefix_weblog_comments` (
  `ident` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0' COMMENT '-> weblog_posts.ident',
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, commenter',
  `postedname` varchar(128) NOT NULL default '' COMMENT 'displayed name of commenter',
  `body` text NOT NULL,
  `posted` int(11) NOT NULL default '0' COMMENT 'unix timestamp',
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`),
  KEY `posted` (`posted`),
  KEY `post_id` (`post_id`),
  KEY `postedname` (`postedname`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `weblog_posts`
-- 

CREATE TABLE `prefix_weblog_posts` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, poster',
  `weblog` int(11) NOT NULL default '-1' COMMENT '-> users.ident, blog being posted into',
  `icon` int(11) NOT NULL default '-1',
  `access` varchar(20) NOT NULL default 'PUBLIC',
  `posted` int(11) NOT NULL default '0' COMMENT 'unix timestamp',
  `title` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`),
  KEY `access` (`access`),
  KEY `posted` (`posted`),
  KEY `community` (`weblog`)
) ;

INSERT INTO `prefix_weblog_posts` VALUES (0, 1, 1, -1, 'PUBLIC', 1119422380, 'Hello', 'Welcome to this Elgg installation.');

-- --------------------------------------------------------

--
-- Table structure for table `weblog_watchlist`
--

CREATE TABLE `prefix_weblog_watchlist` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0' COMMENT '-> users.ident, watcher',
  `weblog_post` int(11) NOT NULL default '0' COMMENT '-> weblog_posts.ident, watched post',
  PRIMARY KEY (`ident`),
  KEY `owner` (`owner`),
  KEY `weblog_post` (`weblog_post`)
) ;

-- --------------------------------------------------------

--
-- Table for antispam and more
--

CREATE TABLE `prefix_datalists` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY (`ident`),
  KEY `name` (`name`)
) ;

-- --------------------------------------------------------

--
-- Table for aliases for users from lms hosts.
--

CREATE TABLE `prefix_users_alias` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `installid` varchar(32) NOT NULL default '',
  `username` varchar(32) NOT NULL default '',
  `firstname` varchar(64) NOT NULL default '',
  `lastname` varchar(64) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY (`ident`),
  KEY `username` (`username`),
  KEY `installid` (`installid`),
  KEY `user_id` (`user_id`)
) ;

-- --------------------------------------------------------

--
-- Table for incoming files from lms hosts.
--

CREATE TABLE `prefix_files_incoming` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `installid` varchar(32) NOT NULL default '',
  `intentiondate` int(11) unsigned NOT NULL default 0,
  `size` bigint unsigned NOT NULL default 0,
  `foldername` varchar(128) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY (`ident`),
  KEY `user_id` (`user_id`)
) ;


-- --------------------------------------------------------

--
-- Table structure for table `feed_posts`
-- 

CREATE TABLE `prefix_feed_posts` (
  `ident` int(11) NOT NULL auto_increment,
  `posted` varchar(64) NOT NULL default '0' COMMENT 'imported human readable date',
  `added` int(11) NOT NULL default '0' COMMENT 'unix timestamp',
  `feed` int(11) NOT NULL default '0' COMMENT '-> feeds.ident',
  `title` text NOT NULL,
  `body` text NOT NULL,
  `url` varchar(255) NOT NULL default '' COMMENT 'post-specific or permalink URL',
  PRIMARY KEY (`ident`),
  KEY `feed` (`feed`),
  KEY `posted` (`posted`,`added`),
  KEY `added` (`added`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `feed_subscriptions`
-- 

CREATE TABLE `prefix_feed_subscriptions` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0' COMMENT '-> users.ident',
  `feed_id` int(10) unsigned NOT NULL default '0' COMMENT '-> feeds.ident',
  `autopost` enum('yes','no') NOT NULL default 'no' COMMENT 'whether to insert into subscriber\'s own blog',
  `autopost_tag` varchar(128) NOT NULL default '' COMMENT 'tag list to add to auto-posts',
  PRIMARY KEY (`ident`),
  KEY `feed_id` (`feed_id`),
  KEY `user_id` (`user_id`),
  KEY `autopost` (`autopost`)
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `feeds`
-- 

CREATE TABLE `prefix_feeds` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(128) NOT NULL default '' COMMENT 'URL of actual feed',
  `feedtype` varchar(16) NOT NULL default '' COMMENT 'not used?',
  `name` text NOT NULL,
  `tagline` varchar(128) NOT NULL default '',
  `siteurl` varchar(128) NOT NULL default '' COMMENT 'URL of parent site/page',
  `last_updated` int(11) NOT NULL default '0' COMMENT 'unix timestamp',
  PRIMARY KEY (`ident`),
  KEY `url` (`url`,`feedtype`),
  KEY `last_updates` (`last_updated`),
  KEY `siteurl` (`siteurl`),
  KEY `tagline` (`tagline`)
) ;


-- 
-- Table structure for table `messages`
-- 

CREATE TABLE `prefix_messages` (
  `ident` int(11) NOT NULL auto_increment,
  `title` text NOT NULL default '',
  `body` text NOT NULL default '',
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `posted` int(11) NOT NULL,
  `status` enum('read','unread') NOT NULL default 'unread',
  PRIMARY KEY  (`ident`),
  KEY `from` (`from_id`,`to_id`,`posted`)
) ;
