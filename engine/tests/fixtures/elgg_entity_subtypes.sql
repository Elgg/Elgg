CREATE TABLE `elgg_entity_subtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('object','user','group','site') NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`subtype`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `elgg_entity_subtypes` (`id`,`type`,`subtype`,`class`) VALUES 
(1,'object','file','ElggFile'),
(2,'object','plugin','ElggPlugin'),
(3,'object','widget','ElggWidget'),
(4,'object','blog','ElggBlog'),
(5,'object','thewire','ElggWire');