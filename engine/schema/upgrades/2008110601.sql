-- Alter datalists to have a primary key
ALTER TABLE `prefix_datalists` DROP KEY `name`;
ALTER TABLE `prefix_datalists` ADD PRIMARY KEY `name` (`name`);
