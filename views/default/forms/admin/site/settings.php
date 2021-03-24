<?php
/**
 * The site settings form
 */

echo elgg_view('forms/admin/site/settings/basic', $vars);
echo elgg_view('forms/admin/site/settings/i18n', $vars);
echo elgg_view('forms/admin/site/settings/users', $vars);
echo elgg_view('forms/admin/site/settings/caching', $vars);
echo elgg_view('forms/admin/site/settings/content', $vars);
echo elgg_view('forms/admin/site/settings/debugging', $vars);
echo elgg_view('forms/admin/site/settings/email', $vars);
echo elgg_view('forms/admin/site/settings/other', $vars);

$footer = elgg_view('input/submit', ['value' => elgg_echo('save')]);
elgg_set_form_footer($footer);
