<?php
/**
 * Language definitions for Site Pages
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$english = array(
	/**
	 * Menu items and titles
	 */
	'sitepages' => "Site pages",
	'sitepages:front' => "Front Page",
	'sitepages:about' => "About",
	'sitepages:terms' => "Terms",
	'sitepages:privacy' => "Privacy",
	'sitepages:analytics' => "Analytics",
	'sitepages:contact' => "Contact",
	'sitepages:nopreview' => "No preview yet available",
	'sitepages:preview' => "Preview",
	'sitepages:notset' => "This page has not been set up yet.",
	'sitepages:new' => "New page",
	'sitepages:css' => "CSS",
	'sitepages:seo' => "Metatags",
	'sitepages:metadescription' => "Meta description for search engines",
	'sitepages:metatags' => "Meta tags for search engines (use a comma)",
	'sitepages:seocreated' => "Your search engine information has been added",
	'sitepages:logged_in_front_content' => "Logged in front page content",
	'sitepages:logged_out_front_content' => "Logged out front page content",
	'sitepages:ownfront' => "Construct your own frontpage for this network. (Note:you will need to know html and css)",
	'sitepages:addcontent' => "You can add content here via your admin tools. Look for the external pages link under admin.",
	'item:object:front' => 'Front page items',

	'sitepages:error:no_login' => 'The logged out view for the front page must contain a [[login_box]] or your users can\'t login!',

	/**
	 * Status messages
	 */
	'sitepages:posted' => "Your page was successfully posted.",
	'sitepages:deleted' => "Your page was successfully deleted.",

	/**
	 * Error messages
	 */
	'sitepages:deleteerror' => "There was a problem deleting the old page",
	'sitepages:error' => "There has been an error, please try again and if the problem persists, contact the administrator",

	/**
	 * ECML
	 */
	'sitepages:ecml:keywords:login_box' => 'A standard login box.  Useful for the logged out content area.',
	'sitepages:ecml:keywords:site_stats' => 'This does not exist yet.',
	'sitepages:ecml:keywords:user_list' => "Lists users.  Supports only_with_avatars=TRUE|FALSE, list_type=newest|online|random, limit",
	'sitepages:ecml:views:custom_frontpage' => "The front page view",
);

add_translation('en', $english);