<?php
/**
 * Saves granular access
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$whitelist = get_input('whitelist', array());
$keywords = $CONFIG->ecml_keywords;
$views = $CONFIG->ecml_parse_views;

// the front end uses a white list but the backend uses a
// blacklist for performance and extensibility.
// gotta convert.
$perms = array();

foreach ($views as $view => $view_info) {
	foreach ($keywords as $keyword => $keyword_info) {

		// don't need to add perms for restricted keywords
		// because those perms are checked separately
		if (isset($keyword_info['restricted'])) {
			continue;
		}
		if (!isset($whitelist[$view]) || !in_array($keyword, $whitelist[$view])) {
			$perms[$view][] = $keyword;
		}
	}
}

if (set_plugin_setting('ecml_permissions', serialize($perms), 'ecml')) {
	system_message(elgg_echo('ecml:admin:permissions_saved'));
} else {
	register_error(elgg_echo('ecml:admin:cannot_save_permissions'));
}

forward($_SERVER['HTTP_REFERER']);
