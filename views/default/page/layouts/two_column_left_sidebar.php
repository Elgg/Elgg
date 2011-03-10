<?php
/**
 * Deprecated layout from 1.0-1.7
 *
 * Use one_sidebar instead
 */

if (!isset($vars['content'])) {
	$vars['content'] = $vars['area2'];
}
if (!isset($vars['content'])) {
	$vars['sidebar'] = $vars['area1'] . $vars['area3'];
}

unset($vars['area1']);
unset($vars['area2']);
unset($vars['area3']);

// backward compatability support for plugins that are not using the new approach
// of routing through 'admin'
if (elgg_get_context() == 'admin') {
	echo elgg_view('page/layouts/admin', $vars);
	return true;
}

echo elgg_view('page/layouts/one_sidebar', $vars);
