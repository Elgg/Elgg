CREATE TABLE `prefix_widget_data` (
  `ident` int(11) NOT NULL auto_increment,
  `widget` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text  NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `widget` (`widget`)
)  ;

CREATE TABLE `prefix_widgets` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `location` varchar(128) NOT NULL,
  `location_id` int(11) NOT NULL,
  `wcolumn` int(11) NOT NULL,
  `display_order` int(11) NOT NULL,
  `access` varchar(128) NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`display_order`,`access`),
  KEY `location_id` (`location_id`) 
) ;