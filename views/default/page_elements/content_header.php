<?php
/**
 * Displays the Add New button, and the All, Mine, My Friends tabs for plugins
 * If a user is not logged in, this only displays the All tab.
 * If this is in a group context, it doesn't display any tabs
 * 
 * @uses string $vars['type'] The section type.  Should be the same as the page handler.  Used for generating URLs.
 * @uses string $vars['context'] Which filter we're looking at: all, mine, friends, or action. Nothing to do with get_context().
 * 
 * @uses string $vars['all_link'] Optional. The URL to use for the "All" tab link.  Defaults to mod/$type/all.php
 * @uses string $vars['mine_link'] Optional. The URL to use for the "Mine" tab link.  Defaults to pg/$type/$username
 * @uses string $vars['friends_link'] Optional. The URL to use for the "Friends" tab link.  Defaults to pg/$type/$username/friends
 * @uses string $vars['new_link'] Optional. The URL to use for the "New" button.  Defaults to pg/$type/$username/new
 * @uses array $vars['tabs'] Optional. Override all tab generation.  See view:navgiation/tabs for formatting
 *
 * @package Elgg
 * @subpackage Core
 */

$page_owner = elgg_get_page_owner();
$logged_in_user = get_loggedin_user();
$username = $logged_in_user->username;

if (!$page_owner) {
	$page_owner = $logged_in_user;
}

// so we know if the user is looking at their own, everyone's or all friends
$filter_context = $vars['context'];

// get the object type
$type = $vars['type'];

// create an empty string to start with
$new_button = '';

// generate a list of default tabs
$default_tabs = array(
	'all' => array(
		'title' => elgg_echo('all'),
		'url' => (isset($vars['all_link'])) ? $vars['all_link'] : "mod/$type/all.php",
		'selected' => ($filter_context == 'everyone'),
	),
	'mine' => array(
		'title' => elgg_echo('mine'),
		'url' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "pg/$type/$username",
		'selected' => ($filter_context == 'mine'),
	),
	'friend' => array(
		'title' => elgg_echo('friends'),
		'url' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "pg/$type/$username/friends",
		'selected' => ($filter_context == 'friends'),
	),
);

// determine if using default or overwritten tabs
$tabs = (isset($vars['tabs'])) ? $vars['tabs'] : $default_tabs;
$tab_list = elgg_view('navigation/tabs', array('tabs' => $tabs));

$title = elgg_echo($type);
$title = '<div class="content-header-title">' . elgg_view_title($title) . '</div>';

// must be logged in to see any action buttons
if (isloggedin()) {
	// only show the new button when not on the add form.
	// hide the tabs when on the add form.
	if ($filter_context == 'action') {
		$tab_list = '';
	} else {
		// @todo remove the hard coded reference to the videolist plugin
		if (elgg_get_context() == "videolist"){
			$video_link = elgg_get_site_url() . "pg/videolist/browse/$username/";
			$new_button = "<a href=\"{$video_link}\" class='action-button'>" . elgg_echo('videolist:browsemenu') . '</a>';
		} else {
			$new_link = elgg_normalize_url((isset($vars['new_link'])) ? $vars['new_link'] : "pg/$type/$username/new");
			$new_button = "<a href=\"{$new_link}\" class='action-button'>" . elgg_echo($type . ':new') . '</a>';
		}
		$new_button = "<div class='content-header-options'>$new_button</div>";
	}

	// also hide the tabs if in a group context (ie, listing groups) or
	// when viewing tools belonging to a group
	if (elgg_get_context() == 'groups' || $page_owner instanceof ElggGroup) {
		$tab_list = '';
	}
}

echo <<<HTML
<div id="content_header" class="clearfix">
	$title $new_button
</div>
HTML;

echo $tab_list;
