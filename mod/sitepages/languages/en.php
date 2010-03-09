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
	'sitepages:frontpage' => "Frontpage",
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
	 * Key words
	 */
	'sitepages:keywords_title' => 'Keywords',
	'sitepages:keywords_instructions' =>
		'Keywords are replaced with content when viewed.  They must be surrounded by
		two square brackets ([[ and ]]).  You can build your own or use the ones listed below.
		Hover over a keyword to read its description.',

	'sitepages:keywords_instructions_more' =>
		'
		You can build your own keywords for views and entities.<br /><br />

		[[entity: type=type, subtype=subtype, owner=username, limit=number]]<br />

		EX: To show 5 blog posts by admin:<br />
		[[entity: type=object, subtype=blog, owner=admin, limit=5]]

		<br /><br />

		You can also specify a valid Elgg view:<br />
		[[view: elgg_view, name=value]]<br />

		Ex: To show a text input with a default value:<br />
		[[view: input/text, value=This is a default value]]

		<br /><br />',

	'sitepages:keywords:login_box' => 'A standard login box.  Useful for the logged out content area.',
	'sitepages:keywords:site_stats' => 'This does not exist yet.',
);

add_translation('en', $english);