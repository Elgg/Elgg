CREATE TABLE `elgg_metastrings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `string` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string` (`string`(50))
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `elgg_metastrings` (`id`,`string`) VALUES 
(1,'email'),
(2,'andres@quanbit.com'),
(3,'notification:method:email'),
(4,1),
(5,'validated'),
(6,'validated_method'),
(7,'admin_user');