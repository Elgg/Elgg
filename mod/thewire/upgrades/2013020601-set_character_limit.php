<?php
/**
 * The limit is new in Elgg 1.9
 */

$char_limit = elgg_get_plugin_setting('limit', 'thewire');
if ($char_limit === null) {
	elgg_set_plugin_setting('limit', 140, 'thewire');
}
