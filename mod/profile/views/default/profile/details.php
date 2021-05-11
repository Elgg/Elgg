<?php
/**
 * Elgg user display (details)
 *
 */

$details = elgg_view('profile/fields', $vars);
if (!$details) {
	return;
}

echo elgg_format_element('div', [
	'id' => 'profile-details',
	'class' => 'h-card vcard',
], $details);
