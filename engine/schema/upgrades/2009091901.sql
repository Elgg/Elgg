-- add an additional column to the river table
ALTER TABLE `prefix_river` ADD COLUMN `annotation_id` int(11) NOT NULL AFTER `posted`;