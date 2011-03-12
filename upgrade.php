<?php
/**
 * Elgg upgrade script.
 *
 * This script triggers any necessary upgrades. If the site has been upgraded
 * to the most recent version of the code, no upgrades are run and the caches
 * are flushed. If you would prefer that this script is not accessible to others
 * after an upgrade, you can delete it. Future versions of Elgg will include a
 * new version of the script. Deleting the script is not a requirement and
 * leaving it behind does not affect the security of the site.
 *
 * @package Elgg.Core
 * @subpackage Upgrade
 */

define('UPGRADING', 'upgrading');
require_once(dirname(__FILE__) . "/engine/start.php");

if (get_input('upgrade') == 'upgrade') {
	if (elgg_get_unprocessed_upgrades()) {
		version_upgrade();
	}
	elgg_trigger_event('upgrade', 'system', null);
	elgg_invalidate_simplecache();
	elgg_filepath_cache_reset();
} else {
	// if upgrading from < 1.8.0, check for the core view 'welcome' and bail if it's found.
	// see http://trac.elgg.org/ticket/3064
	// we're checking the exact view location because it's likely themes will have this view.
	// we're only concerned with core.
	$welcome = dirname(__FILE__) . '/views/default/welcome.php';
	if (file_exists($welcome)) {
		$content = elgg_view_module('info', elgg_echo('upgrade:unable_to_upgrade'), 
				elgg_echo('upgrade:unable_to_upgrade_info'));
		
		$params = array(
			'content' => $content,
			'title' => elgg_echo('upgrade:abort'),
		);

		$body = elgg_view_layout('one_column', $params);
		echo elgg_view_page(elgg_echo('upgrade'), $body);
		exit;
	}

	echo elgg_view_page(elgg_echo('upgrading'), '', 'upgrade');
	exit;
}

forward();