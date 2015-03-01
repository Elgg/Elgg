<?php
/**
 * datalist_grows_up
 *
 * Prepare upgrade from Elgg 1.7 to Elgg 1.8
 *
 * Ups the varchar to 256 for the datalist and config table.
 *
 * Keeping it as a varchar because of the trailing whitespace trimming it apparently does:
 * http://dev.mysql.com/doc/refman/5.0/en/char.html
 */

$q = "ALTER TABLE {$CONFIG->dbprefix}datalists CHANGE name name VARCHAR(255)";
update_data($q);

$q = "ALTER TABLE {$CONFIG->dbprefix}config CHANGE name name VARCHAR(255)";
update_data($q);
