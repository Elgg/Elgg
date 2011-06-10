<?php
/**
 * Messageboard river view
 */

$messageboard = $vars['item']->getAnnotation();

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $messageboard->value,
));
