<?php
/**
 * New users admin widget
 */

echo elgg_list_entities(array(
	'type' => 'user',
	'subtype'=> null,
	'full_view' => FALSE
));