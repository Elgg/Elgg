<?php
/**
 * Update avatar river view
 */


$subject = $vars['item']->getSubjectEntity();

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'attachments' => elgg_view_entity_icon($subject, 'tiny'),
));

