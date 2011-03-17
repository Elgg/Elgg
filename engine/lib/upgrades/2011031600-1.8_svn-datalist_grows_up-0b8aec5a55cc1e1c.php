<?php
/**
 * Elgg 1.8-svn upgrade 2011031600
 * datalist_grows_up
 *
 * Ups the varchar to 256 for the datalist and config table.
 *
 * Keeping it as a varchar because of the trailing whitespace trimming it apparently does:
 * http://dev.mysql.com/doc/refman/5.0/en/char.html
 */

$db_prefix = elgg_get_config('dbprefix');

$q = "ALTER TABLE {$db_prefix}datalists CHANGE name name VARCHAR(255)";
update_data($q);

$q = "ALTER TABLE {$db_prefix}config CHANGE name name VARCHAR(255)";
update_data($q);
