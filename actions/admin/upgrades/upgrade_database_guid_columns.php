<?php
/**
 * Convert all the GUID columns in the database to be of the same type/size
 */

// if upgrade has run correctly, mark it done
if (get_input('upgrade_completed')) {
	// set the upgrade as completed
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/database_guid_columns');
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}

	return true;
}

// for large databases this could take a while
set_time_limit(0);

// prepare
$dbprefix = elgg_get_config('dbprefix');

// Access collection membership
update_data("ALTER TABLE {$dbprefix}access_collection_membership MODIFY COLUMN user_guid bigint(20) unsigned NOT NULL");

// Config
update_data("ALTER TABLE {$dbprefix}config MODIFY COLUMN site_guid bigint(20) unsigned NOT NULL");

// Private settings
update_data("ALTER TABLE {$dbprefix}private_settings MODIFY COLUMN entity_guid bigint(20) unsigned NOT NULL");

// River
update_data("ALTER TABLE {$dbprefix}river MODIFY COLUMN subject_guid bigint(20) unsigned NOT NULL");
update_data("ALTER TABLE {$dbprefix}river MODIFY COLUMN object_guid bigint(20) unsigned NOT NULL");
update_data("ALTER TABLE {$dbprefix}river MODIFY COLUMN target_guid bigint(20) unsigned NOT NULL");

// System log
update_data("ALTER TABLE {$dbprefix}system_log MODIFY COLUMN performed_by_guid bigint(20) unsigned NOT NULL");
update_data("ALTER TABLE {$dbprefix}system_log MODIFY COLUMN owner_guid bigint(20) unsigned NOT NULL");

// Give some feedback for the UI
echo json_encode(array(
	'numSuccess' => 8,
	'numErrors' => 0,
));
