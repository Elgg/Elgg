--
-- Elgg database schema
--

-- record membership in an access collection
-- Access collections were replaced by ACLs
-- See https://docs.google.com/a/elgg.org/document/d/1R3v_bYno6fw8mV5_GDW93uTI0pvR9S4yQ-BHMKTz1zs/edit#
-- Each user-created "friend collection" is now an entity pointing to an ACL

-- define an access collection
-- Access collections were replaced by ACLs

-- internal type strings. Think of this as metastrings for code.
-- e.g. entity type, entity subtype, type of lists (the old relationship name)
CREATE TABLE `prefix_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- store an annotation on an entity
-- Annotations are now entities with type=annotation and container=(the annotated entity)

-- lists are ordered GUIDs identified by /GUID/type. e.g. /123/members
CREATE TABLE `prefix_lists` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `target_guid` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx` (`type_id`,`target_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- items in lists (item can be in a single list no more than once)
CREATE TABLE `prefix_list_items` (
  `position` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(11) unsigned NOT NULL,
  `item_guid` bigint(20) unsigned NOT NULL,
  `weight` float NOT NULL DEFAULT 1.0,
  `time_added` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`position`),
  KEY `idx` (`list_id`,`item_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- access control for various types

CREATE TABLE `prefix_acl_grants_entity` (
  `list_id` int(11) unsigned NOT NULL,
  `entity_guid` bigint(20) unsigned NOT NULL,
  KEY `list_id` (`list_id`),
  KEY `item_guid` (`entity_guid`),
  UNIQUE KEY `idx` (`list_id`,`entity_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_acl_grants_river` (
  `list_id` int(11) unsigned NOT NULL,
  `river_id` bigint(20) unsigned NOT NULL,
  KEY `list_id` (`list_id`),
  KEY `river_id` (`river_id`),
  UNIQUE KEY `idx` (`list_id`,`river_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_acl_grants_metadata` (
  `list_id` int(11) unsigned NOT NULL,
  `metadata_id` bigint(20) unsigned NOT NULL,
  KEY `list_id` (`list_id`),
  KEY `metadata_id` (`metadata_id`),
  UNIQUE KEY `idx` (`list_id`,`metadata_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_acl_grants_annotations` (
  `list_id` int(11) unsigned NOT NULL,
  `annotation_id` bigint(20) unsigned NOT NULL,
  KEY `list_id` (`list_id`),
  KEY `annotation_id` (`annotation_id`),
  UNIQUE KEY `idx` (`list_id`,`annotation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- api keys for old web services
CREATE TABLE `prefix_api_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_guid` bigint(20) unsigned DEFAULT NULL,
  `api_key` varchar(40) DEFAULT NULL,
  `secret` varchar(40) NOT NULL,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- site specific configuration
CREATE TABLE `prefix_config` (
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `site_guid` int(11) NOT NULL,
  PRIMARY KEY (`name`,`site_guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- application specific configuration
-- Now stored in config

-- primary entity table
CREATE TABLE `prefix_entities` (
  `guid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL, -- type
  `subtype_id` int(11) DEFAULT NULL, -- type
  `title_id` int(11) unsigned NOT NULL, -- metastring
  `description_id` int(11) unsigned NOT NULL, -- metastring
  `owner_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL,
  `container_guid` bigint(20) unsigned NOT NULL,
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) NOT NULL,
  `last_action` int(11) NOT NULL DEFAULT '0',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`guid`),
  KEY `type_id` (`type_id`),
  KEY `subtype_id` (`subtype_id`),
  KEY `owner_guid` (`owner_guid`),
  KEY `site_guid` (`site_guid`),
  KEY `container_guid` (`container_guid`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- relationships between entities
-- Relationships are now lists.
-- See https://docs.google.com/a/elgg.org/document/d/1R3v_bYno6fw8mV5_GDW93uTI0pvR9S4yQ-BHMKTz1zs/edit#

-- entity type/subtype pairs
-- No need to store these. Now "types" and the class is determined by plugin hook.

-- cache lookups of latitude and longitude for place names
CREATE TABLE `prefix_geocode_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(128) DEFAULT NULL,
  `lat` varchar(20) DEFAULT NULL,
  `long` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`location`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- secondary table for group entities
-- No longer needed. Info is in entities

-- cache for hmac signatures for old web services
CREATE TABLE `prefix_hmac_cache` (
  `hmac` varchar(255) NOT NULL,
  `ts` int(11) NOT NULL,
  PRIMARY KEY (`hmac`),
  KEY `ts` (`ts`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- metadata that describes an entity
CREATE TABLE `prefix_metadata` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `entity_guid` bigint(20) unsigned NOT NULL,
  `name_id` bigint(20) NOT NULL,
  `value_id` bigint(20) NOT NULL,

  -- I see no gain in having metadata keep track of whether it's text or integer.
  -- If you want to store ints, cast the value after read or query the in_value
  -- column for faster use.
  `int_value` int(11) NOT NULL,

  -- Access control on metadata causes more problems than solves IMO.
  -- If you need it, use an entity or annotation

  `time_created` int(11) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `entity_guid` (`entity_guid`),
  KEY `name_id` (`name_id`),
  KEY `value_id` (`value_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- string normalization table for metadata and annotations
CREATE TABLE `prefix_metastrings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `string` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string` (`string`(50))
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- secondary table for object entities
-- No longer needed. Info is in entities

-- settings for an entity
CREATE TABLE `prefix_private_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_guid` bigint(20) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entity_guid` (`entity_guid`,`name`),
  KEY `name` (`name`),
  KEY `value` (`value`(50))
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- queue for asynchronous operations
CREATE TABLE `prefix_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data` mediumblob NOT NULL,
  `timestamp` int(11) NOT NULL,
  `worker` varchar(32) NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `retrieve` (`timestamp`,`worker`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- activity stream
CREATE TABLE `prefix_river` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) NOT NULL,
  `subtype` varchar(32) NOT NULL,
  `action_type` varchar(32) NOT NULL,
  `view` text NOT NULL,
  `subject_guid` bigint(20) NOT NULL,
  `object_guid` bigint(20) NOT NULL,
  `target_guid` bigint(20) NOT NULL,
  `annotation_id` bigint(20) NOT NULL,
  `posted` int(11) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `action_type` (`action_type`),
  KEY `subject_guid` (`subject_guid`),
  KEY `object_guid` (`object_guid`),
  KEY `target_guid` (`target_guid`),
  KEY `annotation_id` (`annotation_id`),
  KEY `posted` (`posted`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- secondary table for site entities
-- No longer needed. Info is in entities

-- log activity for the admin
CREATE TABLE `prefix_system_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `object_class` varchar(50) NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `object_subtype` varchar(50) NOT NULL,
  `event` varchar(50) NOT NULL,
  `performed_by_guid` bigint(20) NOT NULL,
  `owner_guid` bigint(20) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `time_created` int(11) NOT NULL,
  `ip_address` varchar(46) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `object_class` (`object_class`),
  KEY `object_type` (`object_type`),
  KEY `object_subtype` (`object_subtype`),
  KEY `event` (`event`),
  KEY `performed_by_guid` (`performed_by_guid`),
  KEY `time_created` (`time_created`),
  KEY `river_key` (`object_type`,`object_subtype`,`event`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- session table for old web services
CREATE TABLE `prefix_users_apisessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL,
  `token` varchar(40) DEFAULT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_guid` (`user_guid`,`site_guid`),
  KEY `token` (`token`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- secondary table for user entities
CREATE TABLE `prefix_users_entity` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `username` varchar(128) NOT NULL DEFAULT '',
  -- 255 chars is recommended by PHP.net to hold future hash formats
  `password_hash` varchar(255) NOT NULL DEFAULT '',
  `email` text NOT NULL,
  `language` varchar(6) NOT NULL DEFAULT '',
  `banned` enum('yes','no') NOT NULL DEFAULT 'no',
  `admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_action` int(11) NOT NULL DEFAULT '0',
  `prev_last_action` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `prev_last_login` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`guid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(50)),
  KEY `last_action` (`last_action`),
  KEY `last_login` (`last_login`),
  KEY `admin` (`admin`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `name_2` (`name`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- user remember me cookies
CREATE TABLE `prefix_users_remember_me_cookies` (
  `code` varchar(32) NOT NULL,
  `guid` bigint(20) unsigned NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`code`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- user sessions
CREATE TABLE `prefix_users_sessions` (
  `session` varchar(255) NOT NULL,
  `ts` int(11) unsigned NOT NULL DEFAULT '0',
  `data` mediumblob,
  PRIMARY KEY (`session`),
  KEY `ts` (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
