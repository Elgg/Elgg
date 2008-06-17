CREATE TABLE `prefix_pages` (
`ident` INT( 11 ) NOT NULL AUTO_INCREMENT,
`name` VARCHAR( 128 ) NOT NULL ,
`uri` VARCHAR( 128 ) NOT NULL ,
`parent` INT( 11 ) NOT NULL DEFAULT '0',
`weight` TINYINT NOT NULL DEFAULT '0',
`title` TEXT NOT NULL ,
`content` TEXT NOT NULL ,
`owner` INT( 11 ) NOT NULL DEFAULT '-1',
`access` VARCHAR( 20 ) NOT NULL DEFAULT 'PUBLIC',
PRIMARY KEY (`ident`),
KEY `parent` (`parent`),
KEY `owner` (`owner`),
UNIQUE KEY `name` (`name`, `uri`, `owner`)
);
