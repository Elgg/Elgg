<?php
/**
 * Language definitions for Site Pages
 *
 * @package SitePages
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
	'sitepages:ownfront' => "Turn on the custom frontpage for this network.",
	'sitepages:addcontent' => "You can add content here via your admin tools. Look for the external pages link under admin.",
	'item:object:front' => 'Front page items',

	'sitepages:error:no_login' => 'The logged out view for the front page must contain a [login_box] or your users can\'t login!',

	/**
	 * Status messages
	 */
	'sitepages:posted' => "Your page was successfully posted.",
	'sitepages:deleted' => "Your page was successfully deleted.",
	
	/**
	 * Frontpage form
	 **/

	'sitepages:welcometitle' => 'Welcome title',
	'sitepages:welcomemessage' => 'Welcome message.',
	'sitepages:welcomeintro' => 'Use the following fields to construct a welcome title and message for your users.',
	'sitepages:sidebarintro' => 'Use the following input boxes to set a title and some content for the sidebar freeform content area.',
	'sitepages:sidebartitle' => 'Title',
	'sitepages:sidebarmessage' => 'Message.',
	'sitepages:warning' => 'We recommend that you keep this to less than 300 characters.',
	'sitepagessimple:posted' => 'The frontpage has successfully updated.',

	/**
	 * Error messages
	 */
	'sitepages:deleteerror' => "There was a problem deleting the old page",
	'sitepages:error' => "There has been an error, please try again and if the problem persists, contact the administrator",

	/**
	 * ECML
	 */
	'sitepages:ecml:keywords:loginbox:desc' => 'A standard login box.',
	'sitepages:ecml:keywords:loginbox:usage' => '[loginbox] Useful for the logged out content area.',

	'sitepages:ecml:keywords:sitestats:desc' => 'This does not exist yet.',
	'sitepages:ecml:keywords:sitestats:usage' => 'This does not exist yet.',

	'sitepages:ecml:keywords:userlist:desc' => "Lists users.",
	'sitepages:ecml:keywords:userlist:usage' => "[userlist OPTIONS] Supports only_with_avatars=TRUE|FALSE, list_type=newest|online|random, limit",

	'sitepages:ecml:keywords:entity:desc' => 'Displays a list of any Elgg entity.',
	'sitepages:ecml:keywords:entity:usage' => '[entity] supports all options in elgg_get_entities()',

	'sitepages:ecml:keywords:view:desc' => 'Displays any Elgg view.',
	'sitepages:ecml:keywords:view:usage' => '[view src="valid/view" arg1=value1 arg2=value2]',

	'sitepages:ecml:views:custom_frontpage' => "Custom Front Page",
);

add_translation('en', $english);
