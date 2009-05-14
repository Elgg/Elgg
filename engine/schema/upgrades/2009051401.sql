-- Fix error in geocode cache table
DELETE FROM `prefix_geocode_cache`;

ALTER TABLE `prefix_geocode_cache` DROP KEY `location`;
ALTER TABLE `prefix_geocode_cache` ADD UNIQUE KEY `location` (`location`);
