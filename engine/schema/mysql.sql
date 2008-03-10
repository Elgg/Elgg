--
-- Main Elgg database
-- 
-- @link http://elgg.org/
-- @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
-- @author Curverider Ltd
-- @copyright Curverider Ltd 2008
-- @link http://elgg.org/
--

-- --------------------------------------------------------

--
-- Table structure for table `access_groups`
--

CREATE TABLE `prefix_access_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM ;

--
-- Dumping data for table `access_groups`
--

INSERT INTO `prefix_access_groups` (`id`, `name`, `site_id`) VALUES
(0, 'PRIVATE', 0),
(1, 'LOGGED_IN', 0),
(2, 'PUBLIC', 0);

-- --------------------------------------------------------

--
-- Table structure for table `access_group_membership`
--

CREATE TABLE `prefix_access_group_membership` (
  `user_id` int(11) NOT NULL,
  `access_group_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_id`,`access_group_id`)
) ENGINE=MyISAM ;


-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `prefix_configuration` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `site_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM ;


-- --------------------------------------------------------

--
-- Table structure for table `objects`
--

CREATE TABLE `prefix_objects` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL default '0',
  `title` text NOT NULL,
  `description` text NOT NULL,
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `time_created` (`time_created`,`time_updated`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `object_types`
--

CREATE TABLE `prefix_object_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(16) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `prefix_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `username` varchar(12) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` text NOT NULL,
  `language` varchar(6)  NOT NULL default '',
  `code` varchar(32) NOT NULL default '',
  `created` int(11) NOT NULL default '0',
  `last_action` int(11) NOT NULL default '0',
  `prev_last_action` int(11) NOT NULL default '0',
  `last_login` int(11) NOT NULL default '0',
  `prev_last_login` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `password` (`password`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM ;


--
-- User sessions
-- 

CREATE TABLE `prefix_users_apisessions` (
	`id` int(11) NOT NULL auto_increment,
	`user_id` int(11) NOT NULL,
  	`site_id` int(11) NOT NULL,
  	
  	`token` varchar(40),
  	
  	`expires` int(11) NOT NULL,
	
	PRIMARY KEY  (`id`),
	UNIQUE KEY (`user_id`,`site_id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `prefix_sites` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL,
  
  `owner_id` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  
  `access_id` int(11) NOT NULL,
  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;

--
-- Link table between users and sites
--

CREATE TABLE `prefix_users_sites` (
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_id`,`site_id`)
) ENGINE=MyISAM ;

--
-- Table structure for friends
--

CREATE TABLE `prefix_friends` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=MyISAM;

-- 
-- Table structure for annotations
--
CREATE TABLE `prefix_annotations` (
	`id` int(11) NOT NULL auto_increment,
	
	`object_id` int(11) NOT NULL,
	`object_type` enum ('object', 'user', 'collection', 'site') NOT NULL,
	
	`name` varchar(255) NOT NULL,
	`value` text NOT NULL,
	`value_type` enum ('integer','tag','text','file') NOT NULL,
	
	`owner_id` int(11) NOT NULL,
	`created` int(11) NOT NULL,
	
	`access_id` int(11) NOT NULL,
	
	PRIMARY KEY (`id`)
) ENGINE=MyISAM;

--
-- Table structure for metadata
--
CREATE TABLE `prefix_metadata` (
	`id` int(11) NOT NULL auto_increment,motion netcam
	
	`object_id` int(11) NOT NULL,
	`object_type` enum ('object', 'user', 'collection', 'site') NOT NULL,
	
	`name` varchar(255) NOT NULL,
	`value` int(11) NOT NULL,
	`value_type` enum ('integer','tag','text','file') NOT NULL,
	
	`owner_id` int(11) NOT NULL,
	`created` int(11) NOT NULL,
	
	`access_id` int(11) NOT NULL,
	
	PRIMARY KEY (`id`),
	UNIQUE KEY (`object_id`,`object_type`, `name`)
	
) ENGINE=MyISAM;

--
-- Meta strings table
--
CREATE TABLE `prefix_metastrings` (
	`id` int(11) NOT NULL auto_increment,
	`value` text NOT NULL,
	`count` int(11) default 1,
	
	PRIMARY KEY (`id`),
	UNIQUE KEY `value`
) ENGINE=MyISAM;

--
-- API Users - Users who have access to the api (may not be real users)
--
CREATE TABLE `prefix_api_users` (
	id     int(11)     auto_increment,
	
	email_address varchar(128),
	site_id	  int(11),
	api_key   varchar(40),
	secret    varchar(40) NOT NULL,
	active    int default 1,
	
	unique key (email_address),
	unique key (api_key),
	primary key (id)
);
