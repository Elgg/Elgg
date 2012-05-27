CREATE TABLE `elgg_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_guid` bigint(20) unsigned NOT NULL,
  `name_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `value_type` enum('integer','text') NOT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `access_id` int(11) NOT NULL,
  `time_created` int(11) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `entity_guid` (`entity_guid`),
  KEY `name_id` (`name_id`),
  KEY `value_id` (`value_id`),
  KEY `owner_guid` (`owner_guid`),
  KEY `access_id` (`access_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `elgg_metadata` (`id`,`entity_guid`,`name_id`,`value_id`,`value_type`,`owner_guid`,`access_id`,`time_created`,`enabled`) VALUES 
(1,1,1,2,'text',0,2,1338155536,'yes'),
(2,36,3,4,'text',36,2,1338155538,'yes'),
(3,36,5,4,'text',0,2,1338155538,'yes'),
(4,36,6,7,'text',0,2,1338155538,'yes'),
(5,42,3,4,'text',42,2,1338155538,'yes');