-- Add banned column refs #668
ALTER TABLE `prefix_users_entity` ADD COLUMN `banned` enum ('yes', 'no') NOT NULL default 'no' AFTER `code`;

