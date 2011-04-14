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

if(elgg_is_xhr()) {
	echo $content;
}
else {
	elgg_echo("notxhr");
}

ajax_forward_hook("", "", "", "");
