<?php
/**
 * Messageboard river view
 */

$messageboard = $vars['item']->getAnnotation();

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $messageboard->value,
));
