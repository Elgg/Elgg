<?php
/**
 * Dispatch latest thewire post on XHR
 * 
 */

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => 1,
));

echo $content;
ajax_forward_hook("", "", "", "");
