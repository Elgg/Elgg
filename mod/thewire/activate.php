<?php
/**
 * Register the ElggWire class for the object/thewire subtype
 */

if (get_subtype_id('object', 'thewire')) {
	update_subtype('object', 'thewire', 'ElggWire');
} else {
	add_subtype('object', 'thewire', 'ElggWire');
}

$char_limit = elgg_get_plugin_setting('limit', 'thewire');
if ($char_limit === null) {
	elgg_set_plugin_setting('limit', 140, 'thewire');
}
