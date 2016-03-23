<?php
/**
 * Elgg 2.1.1 upgrade 2016032300
 * schema_column_align
 *
 * Align all GUID columns to be of the same type
 */

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
