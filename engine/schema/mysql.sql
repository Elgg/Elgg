--
-- Main Elgg database
-- 
-- @link http://elgg.org/
--

-- --------------------------------------------------------

--
-- *** The main tables ***
--

-- Site configuration.
CREATE TABLE `prefix_config` (
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `site_guid` int(11) NOT NULL,
  PRIMARY KEY  (`name`,`site_guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Define entities. 
CREATE TABLE `prefix_entities` (
	`guid` bigint(20) unsigned  NOT NULL auto_increment,
	
	`type` enum ('object', 'user', 'group', 'site') NOT NULL,
	`subtype` int(11) NULL,
	
	`owner_guid` bigint(20) unsigned NOT NULL,
    `site_guid` bigint(20) unsigned NOT NULL,
    `container_guid` bigint(20) unsigned NOT NULL,
	`access_id` int(11) NOT NULL,
	
	`time_created` int(11) NOT NULL,
	`time_updated` int(11) NOT NULL,

	`enabled` enum ('yes', 'no') NOT NULL default 'yes',
	
	primary key (`guid`),
	KEY `type` (`type`),
	KEY `subtype` (`subtype`),
	KEY `owner_guid` (`owner_guid`),
	KEY `site_guid` (`site_guid`),
	KEY `container_guid` (`container_guid`),
	KEY `access_id` (`access_id`),
	KEY `time_created` (`time_created`),
	KEY `time_updated` (`time_updated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Entity subtypes - lets you subtype one of the main objects (sites/objects/etc)
CREATE TABLE `prefix_entity_subtypes` (
	`id` int(11) NOT NULL auto_increment,
	
	`type` enum ('object', 'user', 'group', 'site') NOT NULL,
	`subtype` varchar(50) NOT NULL,
	
	class varchar(50) NOT NULL default '',
	
	PRIMARY KEY (`id`),
	UNIQUE KEY (`type`, `subtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Describe relationships between entities, can describe friendships but also site membership, depending on context
CREATE TABLE `prefix_entity_relationships` (
  `id` int(11) NOT NULL auto_increment,
  
  `guid_one` bigint(20) unsigned  NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `guid_two` bigint(20) unsigned  NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`guid_one`,`relationship`,`guid_two`),
  KEY `relationship` (`relationship`),
  KEY `guid_two` (`guid_two`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- *** Access controls ***
--

-- Table structure for table `access_collections`
CREATE TABLE `prefix_access_collections` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL default '0',

  PRIMARY KEY  (`id`),
  KEY `owner_guid` (`owner_guid`),
  KEY `site_guid` (`site_guid`)
) AUTO_INCREMENT=3  ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Access containers 
CREATE TABLE `prefix_access_collection_membership` (
  `user_guid` int(11) NOT NULL,
  `access_collection_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_guid`,`access_collection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- *** Entity superclass details ***
-- NB: Aside from GUID, these should now have any field names in common with the entities table.
--

-- Extra information relating to "objects"
CREATE TABLE `prefix_objects_entity` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `title` text NOT NULL,
  `description` text NOT NULL,

  PRIMARY KEY  (`guid`),
  FULLTEXT KEY (`title`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Extra information relating to "sites"
CREATE TABLE `prefix_sites_entity` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `name` text NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL, 
   
  PRIMARY KEY  (`guid`),
  UNIQUE KEY (`url`),
  FULLTEXT KEY (`name`,`description`, `url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Extra information relating to "users"
CREATE TABLE `prefix_users_entity` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `name` text NOT NULL,
  `username` varchar(128) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `salt`     varchar(8)  NOT NULL default '',
  `email` text NOT NULL,
  `language` varchar(6)  NOT NULL default '',
  `code` varchar(32) NOT NULL default '',
  `banned` enum ('yes', 'no') NOT NULL default 'no',
  
  `last_action` int(11) NOT NULL default '0',
  `prev_last_action` int(11) NOT NULL default '0',
  `last_login` int(11) NOT NULL default '0',
  `prev_last_login` int(11) NOT NULL default '0',
  
  PRIMARY KEY  (`guid`),
  UNIQUE KEY (`username`),
  KEY `password` (`password`),
  KEY `email` (`email`(50)),
  KEY `code` (`code`),
  KEY `last_action` (`last_action`),
  KEY `last_login` (`last_login`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY (`name`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Extra information relating to "groups"
CREATE TABLE `prefix_groups_entity` (
  `guid` bigint(20) unsigned  NOT NULL,
  
  `name` text NOT NULL,
  `description` text NOT NULL,
   
  PRIMARY KEY  (`guid`),
  KEY `name` (`name`(50)),
  KEY `description` (`description`(50)),
  FULLTEXT KEY (`name`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- *** Annotations and tags ***
--

-- Table structure for annotations
CREATE TABLE `prefix_annotations` (
	`id` int(11) NOT NULL auto_increment,
	
	`entity_guid` bigint(20) unsigned  NOT NULL,
	
	`name_id` int(11) NOT NULL,
	`value_id` int(11) NOT NULL,
	`value_type` enum ('integer','text') NOT NULL,
	
	`owner_guid` bigint(20) unsigned NOT NULL,
	`access_id` int(11) NOT NULL,
	
	`time_created` int(11) NOT NULL,

	`enabled` enum ('yes', 'no') NOT NULL default 'yes',
	
	PRIMARY KEY (`id`),
	KEY `entity_guid` (`entity_guid`),
	KEY `name_id` (`name_id`),
	KEY `value_id` (`value_id`),
	KEY `owner_guid` (`owner_guid`),
	KEY `access_id` (`access_id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Table structure for metadata
CREATE TABLE `prefix_metadata` (
	`id` int(11) NOT NULL auto_increment,
	
	`entity_guid` bigint(20) unsigned  NOT NULL,
	
	`name_id` int(11) NOT NULL,
	`value_id` int(11) NOT NULL,
	`value_type` enum ('integer','text') NOT NULL,

	`owner_guid` bigint(20) unsigned NOT NULL,
	`access_id` int(11) NOT NULL,
	
	`time_created` int(11) NOT NULL,

	`enabled` enum ('yes', 'no') NOT NULL default 'yes',
	
	PRIMARY KEY (`id`),
	KEY `entity_guid` (`entity_guid`),
	KEY `name_id` (`name_id`),
	KEY `value_id` (`value_id`),
	KEY `owner_guid` (`owner_guid`),
	KEY `access_id` (`access_id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Meta strings table (avoids storing text strings more than once)
CREATE TABLE `prefix_metastrings` (
	`id` int(11) NOT NULL auto_increment,
	`string` TEXT NOT NULL,
	
	PRIMARY KEY (`id`),
	KEY `string` (`string`(50))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- API Sessions
CREATE TABLE `prefix_users_apisessions` (
	`id` int(11) NOT NULL auto_increment,
	`user_guid` bigint(20) unsigned NOT NULL,
  	`site_guid` bigint(20) unsigned NOT NULL,
  	
  	`token` varchar(40),
  	
  	`expires` int(11) NOT NULL,
	
	PRIMARY KEY  (`id`),
	UNIQUE KEY (`user_guid`,`site_guid`),
	KEY `token` (`token`)
) ENGINE=MEMORY;

-- HMAC Cache protecting against Replay attacks
CREATE TABLE `prefix_hmac_cache` (
	`hmac` varchar(255) NOT NULL,
	`ts` int(11) NOT NULL,

	PRIMARY KEY  (`hmac`),
	KEY `ts` (`ts`)
) ENGINE=MEMORY;

-- Geocode engine cache
CREATE TABLE `prefix_geocode_cache` (
	id     int(11)     auto_increment,
	location varchar(128),
	`lat`    varchar(20),
	`long`   varchar(20),
	
	PRIMARY KEY (`id`),
    UNIQUE KEY `location` (`location`)
	
) ENGINE=MEMORY;

-- PHP Session storage
CREATE TABLE `prefix_users_sessions` (
	`session` varchar(255) NOT NULL,
 	`ts` int(11) unsigned NOT NULL default '0',
	`data` mediumblob,
	
	PRIMARY KEY `session` (`session`),
	KEY `ts` (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Datalists for things like db version
CREATE TABLE `prefix_datalists` (
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Ultra-private system settings for entities
CREATE TABLE `prefix_private_settings` (
	`id` INT NOT NULL auto_increment,
	`entity_guid` INT NOT NULL ,
	`name` varchar(128) NOT NULL ,
	`value` TEXT NOT NULL ,
	PRIMARY KEY ( `id` ) ,
	UNIQUE KEY ( `entity_guid` , `name` ),
	KEY `name` (`name`),
	KEY `value` (`value` (50))
) ENGINE = MYISAM  DEFAULT CHARSET=utf8;

-- System log
CREATE TABLE `prefix_system_log` (
	`id` int(11) NOT NULL auto_increment,
	
	`object_id` int(11) NOT NULL,

	`object_class` varchar(50) NOT NULL,
	`object_type` varchar(50) NOT NULL,
	`object_subtype` varchar(50) NOT NULL,
	
	`event` varchar(50) NOT NULL,
	`performed_by_guid` int(11) NOT NULL,

	`owner_guid` int(11) NOT NULL,
	`access_id` int(11) NOT NULL,
	
	`enabled` enum ('yes', 'no') NOT NULL default 'yes',

	`time_created` int(11) NOT NULL,
	
	PRIMARY KEY  (`id`),
	KEY `object_id` (`object_id`),
	KEY `object_class` (`object_class`),
	KEY `object_type` (`object_type`),
	KEY `object_subtype` (`object_subtype`),
	KEY `event` (`event`),
	KEY `performed_by_guid` (`performed_by_guid`),
	KEY `access_id` (`access_id`),
	KEY `time_created` (`time_created`),
	KEY `river_key` (`object_type`, `object_subtype`, `event`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- River
 CREATE TABLE `prefix_river` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`type` VARCHAR( 8 ) NOT NULL ,
	`subtype` VARCHAR( 32 ) NOT NULL ,
	`action_type` VARCHAR( 32 ) NOT NULL ,
	`access_id` INT NOT NULL ,
	`view` TEXT NOT NULL ,
	`subject_guid` INT NOT NULL ,
	`object_guid` INT NOT NULL ,
	`posted` INT NOT NULL ,
	PRIMARY KEY ( `id` ) ,
	KEY `type` (`type`),
	KEY `action_type` (`action_type`),
	KEY `access_id` (`access_id`),
	KEY `subject_guid` (`subject_guid`),
	KEY `object_guid` (`object_guid`),
	KEY `posted` (`posted`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8; 
