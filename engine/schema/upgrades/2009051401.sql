-- Fix error in geocode cache table
-- $db->from('geocode_cache')->delete();
DELETE FROM `prefix_geocode_cache`;

-- $db->alterTable('geocode_cache')->dropKey('location');
ALTER TABLE `prefix_geocode_cache` DROP KEY `location`;
-- $db->alterTable('geocode_cache')->addUniqueKey('location', ['location']);
ALTER TABLE `prefix_geocode_cache` ADD UNIQUE KEY `location` (`location`);
