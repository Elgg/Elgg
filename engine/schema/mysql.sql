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
-- *** The main tables ***
--

-- Define entities. 
CREATE TABLE `prefix_entities` (
	`guid` bigint(20) unsigned  NOT NULL auto_increment,
	
	`type` enum ('object', 'user', 'collection', 'site') NOT NULL,
	`subtype` int(11) NULL,
	
	`owner_guid` bigint(20) unsigned NOT NULL,
  	`site_guid` bigint(20) unsigned NOT NULL default '0',
	`access_id` int(11) NOT NULL,
	
	`time_created` int(11) NOT NULL,
	`time_updated` int(11) NOT NULL,
	
	primary key (`guid`)
);

-- Entity subtypes - lets you subtype one of the main objects (sites/objects/etc)
CREATE TABLE `prefix_entity_subtypes` (
	`id` int(11) NOT NULL auto_increment,
	`guid` bigint(20) unsigned  NOT NULL,
	
	`subtype` varchar(50) NOT NULL,
	
	PRIMARY KEY (`id`),
	UNIQUE KEY (`guid`),
	UNIQUE KEY (`subtype`)
) ENGINE=MyISAM;

-- Describe relationships between entities, can describe friendships but also site membership, depending on context
CREATE TABLE `prefix_entity_relationships` (
  `id` int(11) NOT NULL auto_increment,
  
  `guid_one` bigint(20) unsigned  NOT NULL,
  `relationship_type` varchar(50) NOT NULL,
  `guid_two` bigint(20) unsigned  NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`guid_one`,`relationship_type`,`guid_two`)
) ENGINE=MyISAM ;

--
-- *** Access controls ***
--

-- Table structure for table `access_groups`
CREATE TABLE `prefix_access_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM ;

-- Dumping data for table `access_groups`
INSERT INTO `prefix_access_groups` (`id`, `name`, `site_guid`) VALUES
(0, 'PRIVATE', 0),
(1, 'LOGGED_IN', 0),
(2, 'PUBLIC', 0);

--
-- *** Entity superclass details ***
--

-- Extra information relating to "objects"
CREATE TABLE `prefix_objects` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `title` text NOT NULL,
  `description` text NOT NULL,

  PRIMARY KEY  (`guid`)
) ENGINE=MyISAM ;

-- Extra information relating to "sites"
CREATE TABLE `prefix_sites_entity` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `name` text NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL, 
   
  PRIMARY KEY  (`guid`)
) ENGINE=MyISAM ;

-- Extra information relating to "users"
CREATE TABLE `prefix_users` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `name` text NOT NULL,
  `username` varchar(12) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` text NOT NULL,
  `language` varchar(6)  NOT NULL default '',
  `code` varchar(32) NOT NULL default '',
  

  `last_action` int(11) NOT NULL default '0',
  `prev_last_action` int(11) NOT NULL default '0',
  `last_login` int(11) NOT NULL default '0',
  `prev_last_login` int(11) NOT NULL default '0',
  
  PRIMARY KEY  (`guid`),
  KEY `password` (`password`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM ;


-- TODO: Collection


--
-- *** Annotations and tags ***
--

-- Table structure for annotations
CREATE TABLE `prefix_annotations` (
	`id` int(11) NOT NULL auto_increment,
	
	`entity_guid` bigint(20) unsigned  NOT NULL,
	
	`name` varchar(255) NOT NULL,
	`value` int(11) NOT NULL,
	`value_type` enum ('integer','tag','text','file') NOT NULL,
	
	`owner_guid` bigint(20) unsigned NOT NULL,
	`access_id` int(11) NOT NULL,
	
	`time_created` int(11) NOT NULL,
	
	PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- Table structure for metadata
CREATE TABLE `prefix_metadata` (
	`id` int(11) NOT NULL auto_increment,
	
	`entity_guid` bigint(20) unsigned  NOT NULL,
	
	`name` varchar(255) NOT NULL,
	`value` int(11) NOT NULL,
	`value_type` enum ('integer','tag','text','file') NOT NULL,

	`owner_guid` bigint(20) unsigned NOT NULL,
	`access_id` int(11) NOT NULL,
	
	`time_created` int(11) NOT NULL,
	
	PRIMARY KEY (`id`),
	UNIQUE KEY (`entity_guid`, `name`)
	
) ENGINE=MyISAM;

-- Meta strings table (avoids storing text strings more than once)
CREATE TABLE `prefix_metastrings` (
	`id` int(11) NOT NULL auto_increment,
	`string` varchar(255) NOT NULL,
	
	PRIMARY KEY (`id`),
	UNIQUE KEY (`string`)
) ENGINE=MyISAM;

--
-- *** Misc ***
--

-- API Users
CREATE TABLE `prefix_api_users` (
	id     int(11)     auto_increment,
	
	site_guid bigint(20) unsigned,
	
	api_key   varchar(40),
	secret    varchar(40) NOT NULL,
	active    int(1) default 1,
	
	unique key (api_key),
	primary key (id)
);

-- API Sessions
CREATE TABLE `prefix_users_apisessions` (
	`id` int(11) NOT NULL auto_increment,
	`user_guid` bigint(20) unsigned NOT NULL,
  	`site_guid` bigint(20) unsigned NOT NULL,
  	
  	`token` varchar(40),
  	
  	`expires` int(11) NOT NULL,
	
	PRIMARY KEY  (`id`),
	UNIQUE KEY (`user_guid`,`site_guid`)
) ENGINE=MyISAM;

