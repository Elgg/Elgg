
CREATE TABLE `prefix_dashboard_data` (
  `ident` int(11) NOT NULL auto_increment,
  `widget` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `widget` (`widget`)
) ENGINE=MyISAM  ;

CREATE TABLE `prefix_dashboard_widgets` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL,
  `widget_type` varchar(128) NOT NULL,
  `display_order` int(11) NOT NULL,
  `access` varchar(128) NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`display_order`,`access`)
) ENGINE=MyISAM ;