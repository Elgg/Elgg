<?php
/**
 * Site pages save/edit
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 *
 */

// Make sure we're logged as admin
admin_gatekeeper();

// Get input data
$content = get_input('sitepages_content', '', FALSE);
$page_type = get_input('page_type');
$tags = get_input('sitepages_tags');
$tag_array = string_to_tag_array($tags);

// Cache to the session for sticky forms
// @todo make these work.
$_SESSION['sitepages_content'] = $content;
$_SESSION['sitepages_type'] = $type;
$_SESSION['sitepages_tags'] = $tags;

if (!$sitepage = sitepages_get_sitepage_object($page_type)) {
	$sitepage = sitepages_create_sitepage_object($page_type);
}

if (empty($content)) {
	register_error(elgg_echo('sitepages:blank'));
} else {
	$sitepage->title = $type;
	$sitepage->description = $content;
	$sitepage->tags = $tag_array;

	if (!$sitepage->save()) {
		register_error(elgg_echo('sitepages:error'));
	} else {
		system_message(elgg_echo('sitepages:posted'));
		// @todo this needs to be accurate for create or update.
		add_to_river('river/sitepages/create', 'create', $_SESSION['user']->guid, $sitepages->guid);
	}

	// @todo Good intensions...
	unset($_SESSION['sitepages_content']);
	unset($_SESSION['sitepagestitle']);
	unset($_SESSION['sitepagestags']);
}

forward($_SERVER['HTTP_REFERER']);