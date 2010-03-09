<?php
/**
 * Site Pages provides interfaces to create standard content-static pages
 * and to customize the front page layout and content.
 *
 * Formerly implemented as "external pages" and "custom index."
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 *
 * @todo
 * 	Check for SQL injection problems.
 * 	Make sure this stuff doesn't show up in search.
 * 	Check entity keyword views against fullview.  Force to FALSE?
 * 	DRY up actions and views
 * 	Implement sticky forms
 * 	Use $entity->view to redirect to url of page.
 * 	The tool settings view is probably not needed as it can be added to the front page edit tab.
 * 	You can say pg/sitepages/edit|read/any_page_i_want and it will let you.
 * 	Clean up and probably move the docs for keywords.
 */

/**
 * Start the site pages plugin.
 */
function sitepages_init() {
	require_once(dirname(__FILE__) . '/sitepages_functions.php');
	global $CONFIG;

	// register our subtype
	run_function_once('sitepages_runonce');

	// Register a page handler, so we can have nice URLs
	register_page_handler('sitepages', 'sitepages_page_handler');

	// Register a URL handler for external pages
	register_entity_url_handler('sitepages_url', 'object', 'sitepages');

	elgg_extend_view('footer/links', 'sitepages/footer_menu');
	elgg_extend_view('metatags', 'sitepages/metatags');

	// Replace the default index page if user has requested
	if (get_plugin_setting('ownfrontpage', 'sitepages') == 'yes') {
		register_plugin_hook('index', 'system', 'sitepages_custom_index');
	}

	// parse views for keywords
	register_plugin_hook('display', 'view', 'sitepages_parse_view');

	// register the views we want to parse for the keyword replacement
	// right now this is just the custom front page, but we can
	// expand it to the other pages later.
	$CONFIG->sitepages_parse_views = array(
		'sitepages/custom_frontpage'
	);

	// an example of how to register and respond to the get_keywords trigger
	register_plugin_hook('get_keywords', 'sitepages', 'sitepages_keyword_hook');

	// grab the list of keywords and their views from plugins
	if ($keywords = trigger_plugin_hook('get_keywords', 'sitepages', NULL, array())) {
		$CONFIG->sitepages_keywords = $keywords;
	}

	register_action("sitepages/add", FALSE, $CONFIG->pluginspath . "sitepages/actions/add.php");
	register_action("sitepages/addfront", FALSE, $CONFIG->pluginspath . "sitepages/actions/addfront.php");
	register_action("sitepages/addmeta", FALSE, $CONFIG->pluginspath . "sitepages/actions/addmeta.php");
	register_action("sitepages/edit", FALSE, $CONFIG->pluginspath . "sitepages/actions/edit.php");
	register_action("sitepages/delete", FALSE, $CONFIG->pluginspath . "sitepages/actions/delete.php");

}

/**
 * Registers the sitepages subtype to the right class.
 *
 * @return unknown_type
 */
function sitepages_runonce() {
	return add_subtype('object', 'sitepages_page', 'ElggSitePage');
}

/**
 * Override the index if requested.
 *
 * @return TRUE on override
 */
function sitepages_custom_index() {
	// context is checked by the extended metatags view to print out its custom CSS
	$context = get_context();
	set_context('sitepages:front');

	if ($contents = elgg_view('sitepages/custom_frontpage')) {
		page_draw(FALSE, $contents);

		set_context($context);
		// return TRUE to tell index.php we've got its content right here.
		return TRUE;
	}

	set_context($context);

	// return NULL to pass this to next in chain, or back to standard index.php.
	return NULL;
}

/**
 * Page setup. Adds admin controls to the admin panel.
 */
function sitepages_pagesetup(){
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('sitepages'), $CONFIG->wwwroot . 'pg/sitepages/edit/front');
	}
}

/**
 *
 * @param unknown_type $expage
 * @return unknown_type
 *
 * //@todo is this needed?
 */
function sitepages_url($expage) {
	global $CONFIG;
	return $CONFIG->url . 'pg/sitepages/';
}

/**
 * Serve out views for site pages.
 *
 * @param unknown_type $page
 * @return unknown_type
 */
function sitepages_page_handler($page) {
	global $CONFIG;

	// for the owner block.
	if ($logged_in_guid = get_loggedin_userid()) {
		set_page_owner($logged_in_guid);
	}

	// sanity checking.
	// on bad params we'll forward so people will bookmark the correct URLs
	// @todo valid page names need to be pulled out into some sort of config var or admin option.
	$default_page = 'About';

	$action = isset($page[0]) ? $page[0] : FALSE;
	$page_type = isset($page[1]) ? $page[1] : FALSE;

	switch ($action) {
		case 'edit':
			$title = elgg_echo('sitepages');
			$content = sitepages_get_edit_section_content($page_type);

			break;

		case 'read':
			$title = elgg_echo('sitepages:' . strtolower($page_type));
			$content = sitepages_get_page_content($page_type);

			break;

		default:
			forward("{$CONFIG->site->url}pg/sitepages/read/$default_page");
			break;
	}

	page_draw($title, $content);
}

/**
 * Parses a registered view for supported keywords.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 * @return string
 */
function sitepages_parse_view($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	// give me everything that is (string):(any thing that's not a ]) surrounded by [[ ]]s
	$keyword_regex = '/\[\[([a-z]+):([^\]]+)\]\]/';

	if (in_array($params['view'], $CONFIG->sitepages_parse_views)) {
		$keywords = $CONFIG->sitepages_keywords;

		$view_options = array(
			'view' => $params['view']
		);

		foreach ($keywords as $keyword => $info) {
			if ($content = elgg_view($info['view'], $view_options)) {
				$return_value = str_replace("[[$keyword]]", $content, $return_value);
			}
		}

		// parse for specialized tags:
		//	[[entity: key=value, key=value,etc]]
		//	[[view:viewname, vars_key=value,...]]
		$return_value = preg_replace_callback($keyword_regex, 'sitepages_parse_view_match', $return_value);
	}

	return $return_value;
}


/**
 * Register some default keywords.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 * @return unknown_type
 */
function sitepages_keyword_hook($hook, $entity_type, $return_value, $params) {
	$return_value['login_box'] = array(
		'view' => 'account/forms/login',
		'description' => elgg_echo('sitepages:keywords:login_box')
	);

	$return_value['site_stats'] = array(
		'view' => 'this/doesnt/exist/yet',
		'description' => elgg_echo('sitepages:keywords:site_stats')
	);

	return $return_value;
}

register_elgg_event_handler('init', 'system', 'sitepages_init');
register_elgg_event_handler('pagesetup', 'system', 'sitepages_pagesetup');