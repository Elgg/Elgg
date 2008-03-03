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
-- Table structure for table `metadata_type`
--

CREATE TABLE `prefix_metadata_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM ;


-- --------------------------------------------------------

--
-- Table structure for table `metadata_value`
--

CREATE TABLE `prefix_metadata_value` (
  `id` int(11) NOT NULL auto_increment,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
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
-- Table structure for table `object_metadata`
--

CREATE TABLE `prefix_object_metadata` (
  `id` int(11) NOT NULL auto_increment,
  `object_id` int(11) NOT NULL,
  `metadata_type_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`,`metadata_type_id`,`value_id`),
  KEY `access_id` (`access_id`)
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
  `code` varchar(32) NOT NULL default '',
  `last_updated` int(11) NOT NULL default '0',
  `registered` int(11) NOT NULL default '0',
  `enabled` enum('yes','no') NOT NULL default 'no',
  `last_action` int(11) NOT NULL default '0',
  `prev_last_action` int(11) NOT NULL default '0',
  `last_login` int(11) NOT NULL default '0',
  `prev_last_login` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `password` (`password`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `user_metadata`
--

CREATE TABLE `prefix_user_metadata` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `metadata_type_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`,`metadata_type_id`,`value_id`),
  KEY `access_id` (`access_id`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `prefix_sites` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `domain` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;

INSERT INTO `prefix_sites` (`id` ,`name` ,`domain`) VALUES 
(1 , 'New Elgg site', '');
