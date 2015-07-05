<?php

$char_limit = elgg_get_plugin_setting('limit', 'thewire');
if ($char_limit === null) {
	elgg_set_plugin_setting('limit', 140, 'thewire');
}
