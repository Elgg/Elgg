<?php
/**
 * Elgg 1.9.0 upgrade 2014090900
 * fix_processed_upgrades
 *
 * Fixes incorrect values in the processed_upgrades setting.
 *
 * Both the upgrade file name and the class responsible for the actual upgrade
 * logic had been set as the value of variable called $upgrade. This mistake may
 * have caused the class to be saved to the list of processed upgrade instead
 * of the filename. This upgrade replaces the class with the filename.
 */

$upgrade_data = datalist_get('processed_upgrades');
$upgrade_data = unserialize($upgrade_data);

foreach ($upgrade_data as $key => $entry) {
	if (!$entry instanceof ElggUpgrade) {
		continue;
	}

	if ($entry->title == 'Comments Upgrade') {
		$upgrade_data[$key] = '2013010400-1.9.0_dev-comments_to_entities-faba94768b055b08.php';
	}
}

datalist_set('processed_upgrades', serialize($upgrade_data));
