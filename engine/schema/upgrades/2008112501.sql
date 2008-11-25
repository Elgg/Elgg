CREATE TABLE IF NOT EXISTS `prefix_geocode_cache` (
	id     int(11)     auto_increment,
	location varchar(128),
	`lat`    varchar(20),
	`long`   varchar(20),
	
	PRIMARY KEY (`id`),
    KEY `location` (`location`)
	
) ENGINE=MEMORY;