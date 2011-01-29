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

echo elgg_view('layout/shells/one_sidebar', $vars);
