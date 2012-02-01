<?php
/**
 * Elgg 1.8.3 upgrade 2012012000
 * ip_in_syslog
 *
 * Adds a field for an IP address in the system log table
 */

$db_prefix = elgg_get_config('dbprefix');
$q = "ALTER TABLE {$db_prefix}system_log ADD ip_address VARCHAR(15) NOT NULL AFTER time_created";

update_data($q);