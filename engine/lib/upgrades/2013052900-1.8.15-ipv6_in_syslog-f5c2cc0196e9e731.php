<?php
/**
 * Elgg 1.8.15 upgrade 2013052900
 * ipv6_in_syslog
 *
 * Upgrade the ip column in system_log to be able to store ipv6 addresses
 */

$db_prefix = elgg_get_config('dbprefix');
$q = "ALTER TABLE {$db_prefix}system_log MODIFY COLUMN ip_address varchar(46) NOT NULL";

update_data($q);