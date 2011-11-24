<?php
/**
 * Update avatar river view
 */

$subject = $vars['item']->getSubjectEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$string = elgg_echo('river:update:user:avatar', array($subject_link));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'summary' => $string,
	'attachments' => elgg_view_entity_icon($subject, 'tiny', array(
		'use_hover' => false,
		'use_link' => false,
	)),
));
