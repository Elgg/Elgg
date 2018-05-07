<?php

/**
 * Avatar update attachment
 *
 * @uses $vars['subject'] River subject
 */
$subject = elgg_extract('subject', $vars);
if (!$subject instanceof ElggUser) {
	return;
}

echo elgg_view_entity_icon($subject, 'medium', [
	'use_hover' => false,
	'use_link' => false,
]);