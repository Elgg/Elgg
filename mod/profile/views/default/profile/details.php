<?php
/**
 * Elgg user display (details)
 *
 */

$vars['fields'] = elgg_get_config('profile_fields');

echo elgg_format_element('div', [
	'id' => 'profile-details',
	'class' => 'elgg-body pll',
], elgg_view('profile/fields', $vars));
