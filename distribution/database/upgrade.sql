
DELETE FROM templates ;
DELETE FROM template_elements ;

ALTER TABLE users ADD last_action INT NOT NULL after user_type ;

CREATE TABLE `content_flags` (
  `ident` int(11) NOT NULL auto_increment,
  `url` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `url` (`url`)
) ;
CREATE TABLE `password_requests` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `code` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`code`)
) ;

CREATE TABLE `user_flags` (
  `ident` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `flag` varchar(64) NOT NULL default '',
  `value` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `user_id` (`user_id`,`flag`,`value`)
) ;

INSERT INTO `user_flags` VALUES (0,1,'admin','1');