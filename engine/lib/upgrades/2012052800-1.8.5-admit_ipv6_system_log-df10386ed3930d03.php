<?php
/**
 * Elgg 1.8.5 upgrade 2012052800
 * admit_ipv6_system_log
 *
 * system_log_table only has room for textual IPv4 addresses. Lets augment the size of the ip_address
 * field to 46 bytes (INET6_ADDRSTRLEN) to make room for any IPv6 address.
 */

$db_prefix = elgg_get_config('dbprefix');
$q = "ALTER TABLE {$db_prefix}system_log MODIFY COLUMN ip_address varchar(46) NOT NULL";

update_data($q);
