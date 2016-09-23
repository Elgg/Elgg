<?php
/**
 * The advanced site settings form
 */

echo elgg_view('forms/admin/site/advanced/system', $vars);
echo elgg_view('forms/admin/site/advanced/caching', $vars);
echo elgg_view('forms/admin/site/advanced/content_access', $vars);
echo elgg_view('forms/admin/site/advanced/site_access', $vars);
echo elgg_view('forms/admin/site/advanced/security', $vars);
echo elgg_view('forms/admin/site/advanced/debugging', $vars);

// @todo What is this for?
// and if it's important can we call it something better than "go?"
echo elgg_view('input/hidden', [
	'name' => 'settings',
	'value' => 'go',
]);

$footer = elgg_view('input/submit', ['value' => elgg_echo("save")]);
elgg_set_form_footer($footer);
