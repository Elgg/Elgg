ALTER TABLE `file_folders` ADD `files_owner` INT NOT NULL AFTER `owner` ;
ALTER TABLE `file_folders` ADD INDEX ( `files_owner` ) ;
UPDATE file_folders SET files_owner = owner;

ALTER TABLE `files` ADD `files_owner` INT NOT NULL AFTER `owner` ;
ALTER TABLE `files` ADD INDEX ( `files_owner` ) ;
UPDATE files SET files_owner = owner;

ALTER TABLE `users` DROP `community` ,
DROP `community_owner` ;
ALTER TABLE `users` ADD `owner` INT DEFAULT '-1' NOT NULL AFTER `template_id` ,
ADD `user_type` VARCHAR( 128 ) DEFAULT 'person' NOT NULL AFTER `owner` ;
ALTER TABLE `users` ADD INDEX ( `owner` );
ALTER TABLE `users` ADD INDEX ( `user_type` ) ;

ALTER TABLE `weblog_posts` DROP `community`;
ALTER TABLE `weblog_posts` ADD `weblog` INT DEFAULT '-1' NOT NULL AFTER `owner` ;
ALTER TABLE `weblog_posts` ADD INDEX ( `weblog` ) ;
UPDATE weblog_posts SET weblog = owner;