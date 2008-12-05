CREATE TABLE  IF NOT EXISTS `prefix_private_settings` (
	`id` INT NOT NULL auto_increment,
	`entity_guid` INT NOT NULL ,
	`name` VARCHAR( 128 ) NOT NULL ,
	`value` TEXT NOT NULL ,
	PRIMARY KEY ( `id` ) ,
	UNIQUE KEY ( `entity_guid` , `name` )
) ENGINE = MYISAM  DEFAULT CHARSET=utf8;