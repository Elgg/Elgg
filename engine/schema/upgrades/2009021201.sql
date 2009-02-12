-- River
 CREATE TABLE IF NOT EXISTS `prefix_river` (
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