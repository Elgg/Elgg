CREATE TABLE `elgg_config` (
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `site_guid` int(11) NOT NULL,
  PRIMARY KEY (`name`,`site_guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `elgg_config` (`name`,`value`,`site_guid`) VALUES 
('view','s:7:"default";',1),
('language','s:2:"en";',1),
('default_access','i:2;',1),
('allow_registration','b:1;',1),
('walled_garden','b:0;',1),
('allow_user_default_access','s:0:"";',1);