<?php
/**
 * Elgg's view system.
 *
 * The view system is the primary templating engine in Elgg and renders
 * all output.  Views are short, parameterised PHP scripts for displaying
 * output that can be regsitered, overridden, or extended.  The view type
 * determines the output format and location of the files that renders the view.
 *
 * Elgg uses a two step process to render full output: first
 * content-specific elements are rendered, then the resulting
 * content is inserted into a layout and displayed.  This makes it
 * easy to maintain a consistent look on all pages.
 *
 * A view corresponds to a single file on the filesystem and the views
 * name is its directory structure.  A file in
 * <code>mod/plugins/views/default/myplugin/example.php</code>
 * is called by saying (with the default viewtype):
 * <code>echo elgg_view('myplugin/example');</code>
 *
 * View names that are registered later override those that are
 * registered earlier.  For plugins this corresponds directly
 * to their load order: views in plugins lower in the list override
 * those higher in the list.
 *
 * Plugin views belong in the views/ directory under an appropriate
 * viewtype.  Views are automatically registered.
 *
 * Views can be embedded-you can call a view from within a view.
 * Views can also be prepended or extended by any other view.
 *
 * Any view can extend any other view if registered with
 * {@link elgg_extend_view()}.
 *
 * Viewtypes are set by passing $_REQUEST['view'].  The viewtype
 * 'default' is a standard HTML view.  Types can be defined on the fly
 * and you can get the current viewtype with {@link elgg_get_viewtype()}.
 *
 * @note Internal: Plugin views are autoregistered before their init functions
 * are called, so the init order doesn't affect views.
 *
 * @note Internal: The file that determines the output of the view is the last
 * registered by {@link elgg_set_view_location()}.
 *
 * @package Elgg.Core
 * @subpackage Views
 */

use Elgg\Menu\Menu;
use Elgg\Menu\UnpreparedMenu;
use Elgg\Includer;

/**
 * The viewtype override.
 *
 * @global string $CURRENT_SYSTEM_VIEWTYPE
 * @see elgg_set_viewtype()
 */
global $CURRENT_SYSTEM_VIEWTYPE;
$CURRENT_SYSTEM_VIEWTYPE = "";

/**
 * Manually set the viewtype.
 *
 * View types are detected automatically.  This function allows
 * you to force subsequent views to use a different viewtype.
 *
 * @tip Call elgg_set_viewtype() with no parameter to reset.
 *
 * @param string $viewtype The view type, e.g. 'rss', or 'default'.
 *
 * @return bool
 */
function elgg_set_viewtype($viewtype = "") {
	global $CURRENT_SYSTEM_VIEWTYPE;

	$CURRENT_SYSTEM_VIEWTYPE = $viewtype;

	return true;
}

/**
 * Return the current view type.
 *
 * Viewtypes are automatically detected and can be set with $_REQUEST['view']
 * or {@link elgg_set_viewtype()}.
 *
 * @note Internal: Viewtype is determined in this order:
 *  - $CURRENT_SYSTEM_VIEWTYPE Any overrides by {@link elgg_set_viewtype()}
 *  - $CONFIG->view  The default view as saved in the DB.
 *
 * @return string The view.
 * @see elgg_set_viewtype()
 */
function elgg_get_viewtype() {
	global $CURRENT_SYSTEM_VIEWTYPE;

	if (empty($CURRENT_SYSTEM_VIEWTYPE)) {
		$CURRENT_SYSTEM_VIEWTYPE = _elgg_get_initial_viewtype();
	}

	return $CURRENT_SYSTEM_VIEWTYPE;
}

/**
 * Get the initial viewtype
 *
 * @return string
 * @access private
 * @since 2.0.0
 */
function _elgg_get_initial_viewtype() {
	global $CONFIG;

	$viewtype = get_input('view', '', false);
	if (_elgg_is_valid_viewtype($viewtype)) {
		return $viewtype;
	}

	if (isset($CONFIG->view) && _elgg_is_valid_viewtype($CONFIG->view)) {
		return $CONFIG->view;
	}

	return 'default';
}

/**
 * Register a viewtype.
 *
 * @param string $viewtype The view type to register
 * @return bool
 */
function elgg_register_viewtype($viewtype) {
	if (!isset($GLOBALS['_ELGG']->view_types) || !is_array($GLOBALS['_ELGG']->view_types)) {
		$GLOBALS['_ELGG']->view_types = array();
	}

	if (!in_array($viewtype, $GLOBALS['_ELGG']->view_types)) {
		$GLOBALS['_ELGG']->view_types[] = $viewtype;
	}

	return true;
}

/**
 * Checks if $viewtype is registered.
 *
 * @param string $viewtype The viewtype name
 *
 * @return bool
 * @since 1.9.0
 */
function elgg_is_registered_viewtype($viewtype) {
	if (!isset($GLOBALS['_ELGG']->view_types) || !is_array($GLOBALS['_ELGG']->view_types)) {
		return false;
	}

	return in_array($viewtype, $GLOBALS['_ELGG']->view_types);
}


/**
 * Checks if $viewtype is a string suitable for use as a viewtype name
 *
 * @param string $viewtype Potential viewtype name. Alphanumeric chars plus _ allowed.
 *
 * @return bool
 * @access private
 * @since 1.9
 */
function _elgg_is_valid_viewtype($viewtype) {
	if (!is_string($viewtype) || $viewtype === '') {
		return false;
	}

	if (preg_match('/\W/', $viewtype)) {
		return false;
	}

	return true;
}

/**
 * Register a viewtype to fall back to a default view if a view isn't
 * found for that viewtype.
 *
 * @tip This is useful for alternate html viewtypes (such as for mobile devices).
 *
 * @param string $viewtype The viewtype to register
 *
 * @return void
 * @since 1.7.2
 */
function elgg_register_viewtype_fallback($viewtype) {
	_elgg_services()->views->registerViewtypeFallback($viewtype);
}

/**
 * Checks if a viewtype falls back to default.
 *
 * @param string $viewtype Viewtype
 *
 * @return boolean
 * @since 1.7.2
 */
function elgg_does_viewtype_fallback($viewtype) {
	return _elgg_services()->views->doesViewtypeFallback($viewtype);
}

/**
 * Register a view to be available for ajax calls
 *
 * @warning Only views that begin with 'js/' and 'css/' have their content
 * type set to 'text/javascript' and 'text/css'. Other views are served as
 * 'text/html'.
 *
 * @param string $view The view name
 * @return void
 * @since 1.8.3
 */
function elgg_register_ajax_view($view) {
	elgg_register_external_view($view, false);
}

/**
 * Unregister a view for ajax calls
 *
 * @param string $view The view name
 * @return void
 * @since 1.8.3
 */
function elgg_unregister_ajax_view($view) {
	elgg_unregister_external_view($view);
}

/**
 * Registers a view as being available externally (i.e. via URL).
 *
 * @param string  $view      The name of the view.
 * @param boolean $cacheable Whether this view can be cached.
 * @return void
 * @since 1.9.0
 */
function elgg_register_external_view($view, $cacheable = false) {
	
	_elgg_services()->ajax->registerView($view);

	if ($cacheable) {
		_elgg_services()->views->registerCacheableView($view);
	}
}

/**
 * Unregister a view for ajax calls
 *
 * @param string $view The view name
 * @return void
 * @since 1.9.0
 */
function elgg_unregister_external_view($view) {
	_elgg_services()->ajax->unregisterView($view);
}

/**
 * Set an alternative base location for a view.
 *
 * Views are expected to be in plugin_name/views/.  This function can
 * be used to change that location.
 *
 * @tip This is useful to optionally register views in a plugin.
 *
 * @param string $view     The name of the view
 * @param string $location The full path to the view
 * @param string $viewtype The view type
 *
 * @return void
 */
function elgg_set_view_location($view, $location, $viewtype = '') {
	_elgg_services()->views->setViewDir($view, $location, $viewtype);
}

/**
 * Returns whether the specified view exists
 *
 * @note If $recurse is true, also checks if a view exists only as an extension.
 *
 * @param string $view     The view name
 * @param string $viewtype If set, forces the viewtype
 * @param bool   $recurse  If false, do not check extensions
 *
 * @return bool
 */
function elgg_view_exists($view, $viewtype = '', $recurse = true) {
	return _elgg_services()->views->viewExists($view, $viewtype, $recurse);
}

/**
 * List all views in a viewtype
 *
 * @param string $viewtype Viewtype
 *
 * @return string[]
 *
 * @since 2.0
 */
function elgg_list_views($viewtype = 'default') {
	return _elgg_services()->views->listViews($viewtype);
}

/**
 * Return a parsed view.
 *
 * Views are rendered by a template handler and returned as strings.
 *
 * Views are called with a special $vars variable set,
 * which includes any variables passed as the second parameter.
 *
 * The input of views can be intercepted by registering for the
 * view_vars, $view_name plugin hook.
 *
 * If the input contains the key "__view_output", the view will output this value as a string.
 * No extensions are used, and the "view" hook is not triggered).
 *
 * The output of views can be intercepted by registering for the
 * view, $view_name plugin hook.
 *
 * @param string  $view     The name and location of the view to use
 * @param array   $vars     Variables to pass to the view.
 * @param boolean $ignore1  This argument is ignored and will be removed eventually
 * @param boolean $ignore2  This argument is ignored and will be removed eventually
 * @param string  $viewtype If set, forces the viewtype for the elgg_view call to be
 *                          this value (default: standard detection)
 *
 * @return string The parsed view
 */
function elgg_view($view, $vars = array(), $ignore1 = false, $ignore2 = false, $viewtype = '') {
	return _elgg_services()->views->renderView($view, $vars, $ignore1, $viewtype);
}

/**
 * Display a view with a deprecation notice. No missing view NOTICE is logged
 *
 * @see elgg_view()
 *
 * @param string  $view       The name and location of the view to use
 * @param array   $vars       Variables to pass to the view
 * @param string  $suggestion Suggestion with the deprecation message
 * @param string  $version    Human-readable *release* version: 1.7, 1.8, ...
 *
 * @return string The parsed view
 * @access private
 */
function elgg_view_deprecated($view, array $vars, $suggestion, $version) {
	return _elgg_services()->views->renderDeprecatedView($view, $vars, $suggestion, $version);
}

/**
 * Extends a view with another view.
 *
 * The output of any view can be prepended or appended to any other view.
 *
 * The default action is to append a view.  If the priority is less than 500,
 * the output of the extended view will be appended to the original view.
 *
 * Views can be extended multiple times, and extensions are not checked for
 * uniqueness. Use {@link elgg_unextend_view()} to help manage duplicates.
 *
 * Priority can be specified and affects the order in which extensions
 * are appended or prepended.
 *
 * @see elgg_prepend_css_urls() If the extension is CSS, you may need to use this to fix relative URLs.
 *
 * @param string $view           The view to extend.
 * @param string $view_extension This view is added to $view
 * @param int    $priority       The priority, from 0 to 1000, to add at (lowest numbers displayed first)
 *
 * @return void
 * @since 1.7.0
 */
function elgg_extend_view($view, $view_extension, $priority = 501) {
	_elgg_services()->views->extendView($view, $view_extension, $priority);
}

/**
 * Unextends a view.
 *
 * @param string $view           The view that was extended.
 * @param string $view_extension This view that was added to $view
 *
 * @return bool
 * @since 1.7.2
 */
function elgg_unextend_view($view, $view_extension) {
	return _elgg_services()->views->unextendView($view, $view_extension);
}

/**
 * Get the views (and priorities) that extend a view.
 *
 * @note extensions may change anytime, especially during the [init, system] event
 *
 * @param string $view View name
 *
 * @return string[] Keys returned are view priorities.
 * @since 2.3
 */
function elgg_get_view_extensions($view) {
	$list = _elgg_services()->views->getViewList($view);
	unset($list[500]);
	return $list;
}

/**
 * In CSS content, prepend a path to relative URLs.
 *
 * This is useful to process a CSS view being used as an extension.
 *
 * @param string $css  CSS
 * @param string $path Path to prepend. E.g. "foo/bar/" or "../"
 *
 * @return string
 * @since 2.2
 */
function elgg_prepend_css_urls($css, $path) {
	return Minify_CSS_UriRewriter::prepend($css, $path);
}

/**
 * Assembles and outputs a full page.
 *
 * A "page" in Elgg is determined by the current view type and
 * can be HTML for a browser, RSS for a feed reader, or
 * Javascript, PHP and a number of other formats.
 *
 * For HTML pages, use the 'head', 'page' plugin hook for setting meta elements
 * and links.
 *
 * @param string $title      Title
 * @param string $body       Body
 * @param string $page_shell Optional page shell to use. See page/shells view directory
 * @param array  $vars       Optional vars array to pass to the page
 *                           shell. Automatically adds title, body, head, and sysmessages
 *
 * @return string The contents of the page
 * @since  1.8
 */
function elgg_view_page($title, $body, $page_shell = 'default', $vars = array()) {
	$timer = _elgg_services()->timer;
	if (!$timer->hasEnded(['build page'])) {
		$timer->end(['build page']);
	}
	$timer->begin([__FUNCTION__]);

	$params = array();
	$params['identifier'] = _elgg_services()->request->getFirstUrlSegment();
	$params['segments'] = _elgg_services()->request->getUrlSegments();
	array_shift($params['segments']);
	$page_shell = elgg_trigger_plugin_hook('shell', 'page', $params, $page_shell);


	$system_messages = _elgg_services()->systemMessages;

	$messages = null;
	if ($system_messages->count()) {
		$messages = $system_messages->dumpRegister();

		if (isset($messages['error'])) {
			// always make sure error is the first type
			$errors = array(
				'error' => $messages['error']
			);

			unset($messages['error']);
			$messages = array_merge($errors, $messages);
		}
	}

	$vars['title'] = $title;
	$vars['body'] = $body;
	$vars['sysmessages'] = $messages;

	// head has keys 'title', 'metas', 'links'
	$head_params = _elgg_views_prepare_head($title);

	$vars['head'] = elgg_trigger_plugin_hook('head', 'page', $vars, $head_params);

	$vars = elgg_trigger_plugin_hook('output:before', 'page', null, $vars);

	$output = elgg_view("page/$page_shell", $vars);

	$vars['page_shell'] = $page_shell;

	// Allow plugins to modify the output
	$output = elgg_trigger_plugin_hook('output', 'page', $vars, $output);

	$timer->end([__FUNCTION__]);
	return $output;
}

/**
 * Render a resource view. Use this in your page handler to hand off page rendering to
 * a view in "resources/". If not found in the current viewtype, we try the "default" viewtype.
 *
 * @param string $name The view name without the leading "resources/"
 * @param array  $vars Arguments passed to the view
 *
 * @return string
 * @throws SecurityException
 */
function elgg_view_resource($name, array $vars = []) {
	$view = "resources/$name";

	if (elgg_view_exists($view)) {
		return _elgg_services()->views->renderView($view, $vars);
	}

	if (elgg_get_viewtype() !== 'default' && elgg_view_exists($view, 'default')) {
		return _elgg_services()->views->renderView($view, $vars, false, 'default');
	}

	_elgg_services()->logger->error("The view $view is missing.");

	if (elgg_get_viewtype() === 'default') {
		// only works for default viewtype
		forward('', '404');
	} else {
		register_error(elgg_echo('error:404:content'));
		forward('');
	}
}

/**
 * Prepare the variables for the html head
 *
 * @param string $title Page title for <head>
 * @return array
 * @access private
 */
function _elgg_views_prepare_head($title) {
	$params = array(
		'links' => array(),
		'metas' => array(),
	);

	if (empty($title)) {
		$params['title'] = elgg_get_config('sitename');
	} else {
		$params['title'] = $title . ' : ' . elgg_get_config('sitename');
	}

	$params['metas']['content-type'] = array(
		'http-equiv' => 'Content-Type',
		'content' => 'text/html; charset=utf-8',
	);

	$params['metas']['description'] = array(
		'name' => 'description',
		'content' => elgg_get_config('sitedescription')
	);

	// https://developer.chrome.com/multidevice/android/installtohomescreen
	$params['metas']['viewport'] = array(
		'name' => 'viewport',
		'content' => 'width=device-width',
	);
	$params['metas']['mobile-web-app-capable'] = array(
		'name' => 'mobile-web-app-capable',
		'content' => 'yes',
	);
	$params['metas']['apple-mobile-web-app-capable'] = array(
		'name' => 'apple-mobile-web-app-capable',
		'content' => 'yes',
	);
	
	// RSS feed link
	if (_elgg_has_rss_link()) {
		$url = current_page_url();
		if (substr_count($url,'?')) {
			$url .= "&view=rss";
		} else {
			$url .= "?view=rss";
		}
		$params['links']['rss'] = array(
			'rel' => 'alternative',
			'type' => 'application/rss+xml',
			'title' => 'RSS',
			'href' => elgg_format_url($url),
		);
	}
	
	return $params;
}


/**
 * Add favicon link tags to HTML head
 *
 * @param string $hook        "head"
 * @param string $type        "page"
 * @param array  $head_params Head params
 *                            <code>
 *                               [
 *                                  'title' => '',
 *                                  'metas' => [],
 *                                  'links' => [],
 *                               ]
 *                            </code>
 * @param array  $params      Hook params
 * @return array
 */
function _elgg_views_prepare_favicon_links($hook, $type, $head_params, $params) {

	$head_params['links']['apple-touch-icon'] = array(
		'rel' => 'apple-touch-icon',
		'href' => elgg_get_simplecache_url('favicon-128.png'),
	);

	// favicons
	$head_params['links']['icon-ico'] = array(
		'rel' => 'icon',
		'href' => elgg_get_simplecache_url('favicon.ico'),
	);
	$head_params['links']['icon-vector'] = array(
		'rel' => 'icon',
		'sizes' => '16x16 32x32 48x48 64x64 128x128',
		'type' => 'image/svg+xml',
		'href' => elgg_get_simplecache_url('favicon.svg'),
	);
	$head_params['links']['icon-16'] = array(
		'rel' => 'icon',
		'sizes' => '16x16',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-16.png'),
	);
	$head_params['links']['icon-32'] = array(
		'rel' => 'icon',
		'sizes' => '32x32',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-32.png'),
	);
	$head_params['links']['icon-64'] = array(
		'rel' => 'icon',
		'sizes' => '64x64',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-64.png'),
	);
	$head_params['links']['icon-128'] = array(
		'rel' => 'icon',
		'sizes' => '128x128',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-128.png'),
	);

	return $head_params;
}

/**
 * Displays a layout with optional parameters.
 *
 * Layouts provide consistent organization of pages and other blocks of content.
 * There are a few default layouts in core:
 *  - admin                   A special layout for the admin area.
 *  - one_column              A single content column.
 *  - one_sidebar             A content column with sidebar.
 *  - two_sidebar             A content column with two sidebars.
 *  - widgets                 A widget canvas.
 *
 * The layout views take the form page/layouts/$layout_name
 * See the individual layouts for what options are supported. The three most
 * common layouts have these parameters:
 * one_column
 *     content => string
 * one_sidebar
 *     content => string
 *     sidebar => string (optional)
 * content
 *     content => string
 *     sidebar => string (optional)
 *     buttons => string (override the default add button)
 *     title   => string (override the default title)
 *     filter_context => string (selected content filter)
 *     See the content layout view for more parameters
 *
 * @param string $layout_name The name of the view in page/layouts/.
 * @param array  $vars        Associative array of parameters for the layout view
 *
 * @return string The layout
 */
function elgg_view_layout($layout_name, $vars = array()) {
	$timer = _elgg_services()->timer;
	if (!$timer->hasEnded(['build page'])) {
		$timer->end(['build page']);
	}
	$timer->begin([__FUNCTION__]);

	$params = array();
	$params['identifier'] = _elgg_services()->request->getFirstUrlSegment();
	$params['segments'] = _elgg_services()->request->getUrlSegments();
	array_shift($params['segments']);
	$layout_name = elgg_trigger_plugin_hook('layout', 'page', $params, $layout_name);

	$param_array = $vars;

	$param_array['layout'] = $layout_name;

	$params = elgg_trigger_plugin_hook('output:before', 'layout', null, $param_array);

	if (elgg_view_exists("page/layouts/$layout_name")) {
		$output = elgg_view("page/layouts/$layout_name", $params);
	} else {
		$output = elgg_view("page/layouts/default", $params);
	}

	$output = elgg_trigger_plugin_hook('output:after', 'layout', $params, $output);

	$timer->end([__FUNCTION__]);
	return $output;
}

/**
 * Render a menu
 *
 * @see elgg_register_menu_item() for documentation on adding menu items and
 * navigation.php for information on the different menus available.
 *
 * This function triggers a 'register', 'menu:<menu name>' plugin hook that enables
 * plugins to add menu items just before a menu is rendered. This is used by
 * dynamic menus (menus that change based on some input such as the user hover
 * menu). Using elgg_register_menu_item() in response to the hook can cause
 * incorrect links to show up. See the blog plugin's blog_owner_block_menu()
 * for an example of using this plugin hook.
 *
 * An additional hook is the 'prepare', 'menu:<menu name>' which enables plugins
 * to modify the structure of the menu (sort it, remove items, set variables on
 * the menu items).
 *
 * Preset (unprepared) menu items passed to the this function with the $vars
 * argument, will be merged with the registered items (registered with
 * elgg_register_menu_item()). The combined set of menu items will be passed
 * to 'register', 'menu:<menu_name>' hook.
 *
 * Plugins that pass preset menu items to this function and do not wish to be
 * affected by plugin hooks (e.g. if you are displaying multiple menus with
 * the same name on the page) should instead choose a unqie menu name
 * and define a menu_view argument to render menus consistently.
 * For example, if you have multiple 'filter' menus on the page:
 * <code>
 *    elgg_view_menu("filter:$uid", [
 *        'items' => $items,
 *        'menu_view' => 'navigation/menu/filter',
 *    ]);
 * </code>
 *
 * elgg_view_menu() uses views in navigation/menu
 *
 * @param string|Menu|UnpreparedMenu $menu Menu name (or object)
 * @param array                      $vars An associative array of display options for the menu.
 *
 *                          Options include:
 *                              items => an array of unprepared menu items
 *                                       as ElggMenuItem or menu item factory options
 *                              sort_by => string or php callback
 *                                  string options: 'name', 'priority', 'title' (default),
 *                                  'register' (registration order) or a
 *                                  php callback (a compare function for usort)
 *                              handler: string the page handler to build action URLs
 *                              entity: \ElggEntity to use to build action URLs
 *                              class: string the class for the entire menu.
 *                              menu_view: name of the view to be used to render the menu
 *                              show_section_headers: bool show headers before menu sections.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_menu($menu, array $vars = array()) {

	$menu_view = elgg_extract('menu_view', $vars);
	unset($vars['menu_view']);

	if (is_string($menu)) {
		$menu = _elgg_services()->menus->getMenu($menu, $vars);

	} elseif ($menu instanceof UnpreparedMenu) {
		$menu = _elgg_services()->menus->prepareMenu($menu);
	}

	if (!$menu instanceof Menu) {
		throw new \InvalidArgumentException('$menu must be a menu name, a Menu, or UnpreparedMenu');
	}

	$name = $menu->getName();
	$params = $menu->getParams();

	$views = [
		$menu_view,
		"navigation/menu/$name",
		'navigation/menu/default',
	];

	foreach ($views as $view) {
		if (elgg_view_exists($view)) {
			return elgg_view($view, $params);
		}
	}
}

/**
 * Render a menu item (usually as a link)
 *
 * @param \ElggMenuItem $item The menu item
 * @param array         $vars Options to pass to output/url if a link
 * @return string
 * @since 1.9.0
 */
function elgg_view_menu_item(\ElggMenuItem $item, array $vars = array()) {
	if (!isset($vars['class'])) {
		$vars['class'] = 'elgg-menu-content';
	}

	$vars = array_merge($item->getValues(), $vars);

	if ($item->getLinkClass()) {
		$vars['class'] .= ' ' . $item->getLinkClass();
	}

	if ($item->getHref() === false || $item->getHref() === null) {
		$text = $item->getText();

		// if contains elements, don't wrap
		if (preg_match('~<[a-z]~', $text)) {
			return $text;
		} else {
			return elgg_format_element('span', array('class' => 'elgg-non-link'), $text);
		}
	}

	if (!isset($vars['rel']) && !isset($vars['is_trusted'])) {
		$vars['is_trusted'] = true;
	}

	if ($item->getConfirmText()) {
		$vars['confirm'] = $item->getConfirmText();
	}

	return elgg_view('output/url', $vars);
}

/**
 * Returns a string of a rendered entity.
 *
 * Entity views are either determined by setting the view property on the entity
 * or by having a view named after the entity $type/$subtype.  Entities that have
 * neither a view property nor a defined $type/$subtype view will fall back to
 * using the $type/default view.
 *
 * The entity view is called with the following in $vars:
 *  - \ElggEntity 'entity' The entity being viewed
 *
 * @tip This function can automatically appends annotations to entities if in full
 * view and a handler is registered for the entity:annotate.  See https://github.com/Elgg/Elgg/issues/964 and
 * {@link elgg_view_entity_annotations()}.
 *
 * @param \ElggEntity $entity The entity to display
 * @param array       $vars   Array of variables to pass to the entity view.
 *      'full_view'        Whether to show a full or condensed view. (Default: true)
 *      'item_view'        Alternative view used to render this entity
 * @param boolean     $bypass Ignored and will be removed eventually
 * @param boolean     $debug  Complain if views are missing
 *
 * @return string HTML to display or false
 * @todo The annotation hook might be better as a generic plugin hook to append content.
 */
function elgg_view_entity(\ElggEntity $entity, array $vars = array(), $bypass = false, $debug = false) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof \ElggEntity)) {
		return false;
	}

	elgg_register_rss_link();

	$defaults = array(
		'full_view' => true,
	);

	$vars = array_merge($defaults, $vars);

	$vars['entity'] = $entity;

	$entity_type = $entity->getType();
	$entity_subtype = $entity->getSubtype();
	if (empty($entity_subtype)) {
		$entity_subtype = 'default';
	}

	$entity_views = array(
		elgg_extract('item_view', $vars, ''),
		"$entity_type/$entity_subtype",
		"$entity_type/default",
	);

	$contents = '';
	foreach ($entity_views as $view) {
		if (elgg_view_exists($view)) {
			$contents = elgg_view($view, $vars, $bypass, $debug);
			break;
		}
	}

	// Marcus Povey 20090616 : Speculative and low impact approach for fixing #964
	if ($vars['full_view']) {
		$annotations = elgg_view_entity_annotations($entity, $vars['full_view']);

		if ($annotations) {
			$contents .= $annotations;
		}
	}
	return $contents;
}

/**
 * View the icon of an entity
 *
 * Entity views are determined by having a view named after the entity $type/$subtype.
 * Entities that do not have a defined icon/$type/$subtype view will fall back to using
 * the icon/$type/default view.
 *
 * @param \ElggEntity $entity The entity to display
 * @param string      $size   The size: tiny, small, medium, large
 * @param array       $vars   An array of variables to pass to the view. Some possible
 *                            variables are img_class and link_class. See the
 *                            specific icon view for more parameters.
 *
 * @return string HTML to display or false
 */
function elgg_view_entity_icon(\ElggEntity $entity, $size = 'medium', $vars = array()) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof \ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['size'] = $size;

	$entity_type = $entity->getType();

	$subtype = $entity->getSubtype();
	if (empty($subtype)) {
		$subtype = 'default';
	}

	$contents = '';
	if (elgg_view_exists("icon/$entity_type/$subtype")) {
		$contents = elgg_view("icon/$entity_type/$subtype", $vars);
	}
	if (empty($contents)) {
		$contents = elgg_view("icon/$entity_type/default", $vars);
	}
	if (empty($contents)) {
		$contents = elgg_view("icon/default", $vars);
	}

	return $contents;
}

/**
 * Returns a string of a rendered annotation.
 *
 * Annotation views are expected to be in annotation/$annotation_name.
 * If a view is not found for $annotation_name, the default annotation/default
 * will be used.
 *
 * @warning annotation/default is not currently defined in core.
 *
 * The annotation view is called with the following in $vars:
 *  - \ElggEntity 'annotation' The annotation being viewed.
 *
 * @param \ElggAnnotation $annotation The annotation to display
 * @param array           $vars       Variable array for view.
 *      'item_view'  Alternative view used to render an annotation
 * @param bool            $bypass     Ignored and will be removed eventually
 * @param bool            $debug      Complain if views are missing
 *
 * @return string/false Rendered annotation
 */
function elgg_view_annotation(\ElggAnnotation $annotation, array $vars = array(), $bypass = false, $debug = false) {
	elgg_register_rss_link();

	$defaults = array(
		'full_view' => true,
	);

	$vars = array_merge($defaults, $vars);
	$vars['annotation'] = $annotation;

	$name = $annotation->name;
	if (empty($name)) {
		return false;
	}

	$annotation_views = array(
		elgg_extract('item_view', $vars, ''),
		"annotation/$name",
		"annotation/default",
	);

	$contents = '';
	foreach ($annotation_views as $view) {
		if (elgg_view_exists($view)) {
			$contents = elgg_view($view, $vars, $bypass, $debug);
			break;
		}
	}

	return $contents;
}

/**
 * Returns a rendered list of entities with pagination. This function should be
 * called by wrapper functions.
 *
 * @see elgg_list_entities()
 * @see list_user_friends_objects()
 * @see elgg_list_entities_from_metadata()
 * @see elgg_list_entities_from_relationships()
 * @see elgg_list_entities_from_annotations()
 *
 * @param array $entities Array of entities
 * @param array $vars     Display variables
 *      'count'            The total number of entities across all pages
 *      'offset'           The current indexing offset
 *      'limit'            The number of entities to display per page (default from settings)
 *      'full_view'        Display the full view of the entities?
 *      'list_class'       CSS class applied to the list
 *      'item_class'       CSS class applied to the list items
 *      'item_view'        Alternative view to render list items
 *      'pagination'       Display pagination?
 *      'base_url'         Base URL of list (optional)
 *      'url_fragment'     URL fragment to add to links if not present in base_url (optional)
 *      'position'         Position of the pagination: before, after, or both
 *      'list_type'        List type: 'list' (default), 'gallery'
 *      'list_type_toggle' Display the list type toggle?
 *      'no_results'       Message to display if no results (string|Closure)
 *
 * @return string The rendered list of entities
 */
function elgg_view_entity_list($entities, array $vars = array()) {
	$offset = (int)get_input('offset', 0);

	// list type can be passed as request parameter
	$list_type = get_input('list_type', 'list');

	$defaults = array(
		'items' => $entities,
		'list_class' => 'elgg-list-entity',
		'full_view' => true,
		'pagination' => true,
		'list_type' => $list_type,
		'list_type_toggle' => false,
		'offset' => $offset,
		'limit' => null,
	);

	$vars = array_merge($defaults, $vars);

	if (!$vars["limit"] && !$vars["offset"]) {
		// no need for pagination if listing is unlimited
		$vars["pagination"] = false;
	}

	if ($vars['list_type'] == 'table') {
		return elgg_view('page/components/table', $vars);
	} elseif ($vars['list_type'] == 'list') {
		return elgg_view('page/components/list', $vars);
	} else {
		return elgg_view('page/components/gallery', $vars);
	}
}

/**
 * Returns a rendered list of annotations, plus pagination. This function
 * should be called by wrapper functions.
 *
 * @param array $annotations Array of annotations
 * @param array $vars        Display variables
 *      'count'      The total number of annotations across all pages
 *      'offset'     The current indexing offset
 *      'limit'      The number of annotations to display per page
 *      'full_view'  Display the full view of the annotation?
 *      'list_class' CSS Class applied to the list
 *      'item_view'  Alternative view to render list items
 *      'offset_key' The url parameter key used for offset
 *      'no_results' Message to display if no results (string|Closure)
 *
 * @return string The list of annotations
 * @access private
 */
function elgg_view_annotation_list($annotations, array $vars = array()) {
	$defaults = array(
		'items' => $annotations,
		'offset' => null,
		'limit' => null,
		'list_class' => 'elgg-list-annotation elgg-annotation-list', // @todo remove elgg-annotation-list in Elgg 1.9
		'full_view' => true,
		'offset_key' => 'annoff',
	);

	$vars = array_merge($defaults, $vars);

	if (!$vars["limit"] && !$vars["offset"]) {
		// no need for pagination if listing is unlimited
		$vars["pagination"] = false;
	}

	return elgg_view('page/components/list', $vars);
}

/**
 * Display a plugin-specified rendered list of annotations for an entity.
 *
 * This displays the output of functions registered to the entity:annotation,
 * $entity_type plugin hook.
 *
 * This is called automatically by the framework from {@link elgg_view_entity()}
 *
 * @param \ElggEntity $entity    Entity
 * @param bool        $full_view Display full view?
 *
 * @return mixed string or false on failure
 * @todo Change the hook name.
 */
function elgg_view_entity_annotations(\ElggEntity $entity, $full_view = true) {
	if (!($entity instanceof \ElggEntity)) {
		return false;
	}

	$entity_type = $entity->getType();

	$annotations = elgg_trigger_plugin_hook('entity:annotate', $entity_type,
		array(
			'entity' => $entity,
			'full_view' => $full_view,
		)
	);

	return $annotations;
}

/**
 * Renders a title.
 *
 * This is a shortcut for {@elgg_view page/elements/title}.
 *
 * @param string $title The page title
 * @param array  $vars  View variables (was submenu be displayed? (deprecated))
 *
 * @return string The HTML (etc)
 */
function elgg_view_title($title, array $vars = array()) {
	$vars['title'] = $title;

	return elgg_view('page/elements/title', $vars);
}

/**
 * Displays a UNIX timestamp in a friendly way
 *
 * @see elgg_get_friendly_time()
 *
 * @param int $time A UNIX epoch timestamp
 *
 * @return string The friendly time HTML
 * @since 1.7.2
 */
function elgg_view_friendly_time($time) {
	$view = 'output/friendlytime';
	$vars = ['time' => $time];
	$viewtype = elgg_view_exists($view) ? '' : 'default';

	return _elgg_view_under_viewtype($view, $vars, $viewtype);
}

/**
 * Returns rendered comments and a comment form for an entity.
 *
 * @tip Plugins can override the output by registering a handler
 * for the comments, $entity_type hook.  The handler is responsible
 * for formatting the comments and the add comment form.
 *
 * @param \ElggEntity $entity      The entity to view comments of
 * @param bool        $add_comment Include a form to add comments?
 * @param array       $vars        Variables to pass to comment view
 *
 * @return string|false Rendered comments or false on failure
 */
function elgg_view_comments($entity, $add_comment = true, array $vars = array()) {
	if (!($entity instanceof \ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");

	$output = elgg_trigger_plugin_hook('comments', $entity->getType(), $vars, false);
	if ($output !== false) {
		return $output;
	} else {
		return elgg_view('page/elements/comments', $vars);
	}
}

/**
 * Wrapper function for the image block display pattern.
 *
 * Fixed width media on the side (image, icon, flash, etc.).
 * Descriptive content filling the rest of the column.
 *
 * This is a shortcut for {@elgg_view page/components/image_block}.
 *
 * @param string $image The icon and other information
 * @param string $body  Description content
 * @param array  $vars  Additional parameters for the view
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_image_block($image, $body, $vars = array()) {
	$vars['image'] = $image;
	$vars['body'] = $body;
	return elgg_view('page/components/image_block', $vars);
}

/**
 * Wrapper function for the module display pattern.
 *
 * Box with header, body, footer
 *
 * This is a shortcut for {@elgg_view page/components/module}.
 *
 * @param string $type  The type of module (main, info, popup, aside, etc.)
 * @param string $title A title to put in the header
 * @param string $body  Content of the module
 * @param array  $vars  Additional parameters for the module
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_module($type, $title, $body, array $vars = array()) {
	$vars['type'] = $type;
	$vars['title'] = $title;
	$vars['body'] = $body;
	return elgg_view('page/components/module', $vars);
}

/**
 * Renders a human-readable representation of a river item
 *
 * @param \ElggRiverItem $item A river item object
 * @param array          $vars An array of variables for the view
 *      'item_view'  Alternative view to render the item
 * @return string returns empty string if could not be rendered
 */
function elgg_view_river_item($item, array $vars = array()) {
	if (!($item instanceof \ElggRiverItem)) {
		return '';
	}
	// checking default viewtype since some viewtypes do not have unique views per item (rss)
	$view = $item->getView();
	if (!$view || !elgg_view_exists($view, 'default')) {
		return '';
	}

	$subject = $item->getSubjectEntity();
	$object = $item->getObjectEntity();
	if (!$subject || !$object) {
		// subject is disabled or subject/object deleted
		return '';
	}

	// @todo this needs to be cleaned up
	// Don't hide objects in closed groups that a user can see.
	// see https://github.com/elgg/elgg/issues/4789
	//	else {
	//		// hide based on object's container
	//		$visibility = \Elgg\GroupItemVisibility::factory($object->container_guid);
	//		if ($visibility->shouldHideItems) {
	//			return '';
	//		}
	//	}

	$vars['item'] = $item;

	$river_views = array(
		elgg_extract('item_view', $vars, ''),
		"river/item",
	);

	$contents = '';
	foreach ($river_views as $view) {
		if (elgg_view_exists($view)) {
			$contents = elgg_view($view, $vars);
			break;
		}
	}

	return $contents;
}

/**
 * Convenience function for generating a form from a view in a standard location.
 *
 * This function assumes that the body of the form is located at "forms/$action" and
 * sets the action by default to "action/$action".  Automatically wraps the forms/$action
 * view with a <form> tag and inserts the anti-csrf security tokens.
 *
 * @tip This automatically appends elgg-form-action-name to the form's class. It replaces any
 * slashes with dashes (blog/save becomes elgg-form-blog-save)
 *
 * @example
 * <code>echo elgg_view_form('login');</code>
 *
 * This would assume a "login" form body to be at "forms/login" and would set the action
 * of the form to "http://yoursite.com/action/login".
 *
 * If elgg_view('forms/login') is:
 * <input type="text" name="username" />
 * <input type="password" name="password" />
 *
 * Then elgg_view_form('login') generates:
 * <form action="http://yoursite.com/action/login" method="post">
 *     ...security tokens...
 *     <input type="text" name="username" />
 *     <input type="password" name="password" />
 * </form>
 *
 * @param string $action    The name of the action. An action name does not include
 *                          the leading "action/". For example, "login" is an action name.
 * @param array  $form_vars $vars environment passed to the "input/form" view
 * @param array  $body_vars $vars environment passed to the "forms/$action" view
 *
 * @return string The complete form
 */
function elgg_view_form($action, $form_vars = array(), $body_vars = array()) {
	return _elgg_services()->forms->render($action, $form_vars, $body_vars);
}

/**
 * Sets form footer and defers its rendering until the form view and extensions have been rendered.
 * Deferring footer rendering allows plugins to extend the form view while maintaining
 * logical DOM structure.
 * Footer will be rendered using 'elements/forms/footer' view after form body has finished rendering
 *
 * @param string $footer Footer
 * @return bool
 */
function elgg_set_form_footer($footer = '') {
	return _elgg_services()->forms->setFooter($footer);
}

/**
 * Returns currently set footer, or false if not in the form rendering stack
 * @return string|false
 */
function elgg_get_form_footer() {
	return _elgg_services()->forms->getFooter();
}

/**
 * Renders a form field
 *
 * @param string $input_type Input type, used to generate an input view ("input/$input_type")
 * @param array  $vars       Fields and input vars.
 *                           Field vars contain both field and input params. 'label', 'help',
 *                           and 'field_class' params will not be passed on to the input view.
 *                           Others, including 'required' and 'id', will be available to the
 *                           input view. Both 'label' and 'help' params accept HTML, and
 *                           will be printed unescaped within their wrapper element.
 * @return string
 *
 * @since 2.1
 * @deprecated 2.3 Use elgg_view_field()
 */
function elgg_view_input($input_type, array $vars = array()) {

	elgg_deprecated_notice(__FUNCTION__ . '() is deprecated. Use elgg_view_field()', '2.3');

	$vars['#type'] = $input_type;

	if (isset($vars['label']) && $input_type !== 'checkbox') {
		$vars['#label'] = $vars['label'];
		unset($vars['label']);
	}
	if (isset($vars['help'])) {
		$vars['#help'] = $vars['help'];
		unset($vars['help']);
	}
	if (isset($vars['field_class'])) {
		$vars['#class'] = $vars['field_class'];
		unset($vars['field_class']);
	}

	return elgg_view_field($vars);
}

/**
 * Renders a form field, usually with a wrapper element, a label, help text, etc.
 *
 * @param array $params Field parameters and variables for the input view.
 *                      Keys not prefixed with hash (#) are passed to the input view as $vars.
 *                      Keys prefixed with a hash specify the field wrapper (.elgg-view-field) output.
 *                       - #type: specifies input view. E.g. "text" uses the view "input/text".
 *                       - #label: field label HTML
 *                       - #help: field help HTML
 *                       - #class: field class name
 *                      Note: Both #label and #help are printed unescaped within their wrapper element.
 *                      Note: Some fields (like input/checkbox) need special attention because #label and label serve different purposes
 *                      "#label" will be used as a label in the field wrapper but "label" will be used in the input view
 *
 * @return string
 * @since 2.3
 */
function elgg_view_field(array $params = []) {

	if (empty($params['#type'])) {
		_elgg_services()->logger->error(__FUNCTION__ . '(): $params["#type"] is required.');
		return '';
	}

	$input_type = $params['#type'];
	if (!elgg_view_exists("input/$input_type")) {
		return '';
	}

	$hidden_types = ['hidden', 'securitytoken'];
	if (in_array($input_type, $hidden_types)) {
		unset($params['#type']);
		unset($params['#label']);
		unset($params['#help']);
		unset($params['#class']);
		return elgg_view("input/$input_type", $params);
	}

	$id = elgg_extract('id', $params);
	if (!$id) {
		$id = "elgg-field-" . base_convert(mt_rand(), 10, 36);
		$params['id'] = $id;
	}

	// $vars passed to label, help and field wrapper views
	$element_vars = [];

	// $vars passed to input/$input_name
	$input_vars = [];
	
	$make_special_checkbox_label = false;
	if ($input_type == 'checkbox' && (isset($params['label']) || isset($params['#label']))) {
		if (isset($params['#label']) && isset($params['label'])) {
			$params['label_tag'] = 'div';
		} else {
			$label = elgg_extract('label', $params);
			$label = elgg_extract('#label', $params, $label);
			
			$params['#label'] = $label;
			unset($params['label']);

			// Single checkbox input view gets special treatment
			// We don't want the field label to appear a checkbox without a label
			$make_special_checkbox_label = true;
		}
	}

	// first pass non-hash keys into both
	foreach ($params as $key => $value) {
		if ($key[0] !== '#') {
			$element_vars[$key] = $value;
			$input_vars[$key] = $value;
		}
	}

	// field input view needs this
	$input_vars['input_type'] = $input_type;

	// field views get more data
	$element_vars['input_type'] = $input_type;
	
	unset($element_vars['class']);
	if (isset($params['#class'])) {
		$element_vars['class'] = $params['#class'];
	}
	unset($element_vars['help']);
	if (isset($params['#help'])) {
		$element_vars['help'] = $params['#help'];
	}
	unset($element_vars['label']);
	if (isset($params['#label'])) {
		$element_vars['label'] = $params['#label'];
	}
	
	// wrap if present
	$element_vars['label'] = elgg_view('elements/forms/label', $element_vars);
	$element_vars['help'] = elgg_view('elements/forms/help', $element_vars);

	if ($make_special_checkbox_label) {
		$input_vars['label'] = $element_vars['label'];
		$input_vars['label_tag'] = 'div';
		unset($element_vars['label']);
	}
	$element_vars['input'] = elgg_view("elements/forms/input", $input_vars);

	return elgg_view('elements/forms/field', $element_vars);
}

/**
 * Create a tagcloud for viewing
 *
 * @see elgg_get_tags
 *
 * @param array $options Any elgg_get_tags() options except:
 *
 * 	type => must be single entity type
 *
 * 	subtype => must be single entity subtype
 *
 * @return string
 * @since 1.7.1
 */
function elgg_view_tagcloud(array $options = array()) {

	$type = $subtype = '';
	if (isset($options['type'])) {
		$type = $options['type'];
	}
	if (isset($options['subtype'])) {
		$subtype = $options['subtype'];
	}

	$tag_data = elgg_get_tags($options);
	return elgg_view("output/tagcloud", array(
		'value' => $tag_data,
		'type' => $type,
		'subtype' => $subtype,
	));
}

/**
 * View an item in a list
 *
 * @param \ElggEntity|\ElggAnnotation $item
 * @param array  $vars Additional parameters for the rendering
 *      'item_view' Alternative view used to render list items
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_view_list_item($item, array $vars = array()) {

	if ($item instanceof \ElggEntity) {
		return elgg_view_entity($item, $vars);
	} else if ($item instanceof \ElggAnnotation) {
		return elgg_view_annotation($item, $vars);
	} else if ($item instanceof \ElggRiverItem) {
		return elgg_view_river_item($item, $vars);
	}

	return '';
}

/**
 * View one of the icons
 *
 * Shorthand for <span class="elgg-icon elgg-icon-$name"></span>
 *
 * @param string $name The specific icon to display
 * @param mixed  $vars The additional classname as a string ('float', 'float-alt' or a custom class)
 *                     or an array of variables (array('class' => 'float')) to pass to the icon view.
 *
 * @return string The html for displaying an icon
 * @throws InvalidArgumentException
 */
function elgg_view_icon($name, $vars = array()) {
	if (empty($vars)) {
		$vars = array();
	}

	if ($vars === true) {
		elgg_deprecated_notice("Using a boolean to float the icon is deprecated. Use the class float.", 1.9);
		$vars = array('class' => 'float');
	}

	if (is_string($vars)) {
		$vars = array('class' => $vars);
	}

	if (!is_array($vars)) {
		throw new \InvalidArgumentException('$vars needs to be a string or an array');
	}

	if (!array_key_exists('class', $vars)) {
		$vars['class'] = array();
	}

	if (!is_array($vars['class'])) {
		$vars['class'] = array($vars['class']);
	}

	$vars['class'][] = "elgg-icon-$name";

	return elgg_view("output/icon", $vars);
}

/**
 * Include the RSS icon link and link element in the head
 *
 * @return void
 */
function elgg_register_rss_link() {
	_elgg_services()->config->set('_elgg_autofeed', true);
}

/**
 * Remove the RSS icon link and link element from the head
 *
 * @return void
 */
function elgg_unregister_rss_link() {
	_elgg_services()->config->set('_elgg_autofeed', false);
}

/**
 * Should the RSS view of this URL be linked to?
 *
 * @return bool
 * @access private
 */
function _elgg_has_rss_link() {
	if (isset($GLOBALS['autofeed']) && is_bool($GLOBALS['autofeed'])) {
		elgg_deprecated_notice('Do not set the global $autofeed. Use elgg_register_rss_link()', '2.1');
		return $GLOBALS['autofeed'];
	}
	return (bool)_elgg_services()->config->getVolatile('_elgg_autofeed');
}

/**
 * Displays a user's access collections, using the core/friends/collections view
 *
 * @param int $owner_guid The GUID of the owning user
 *
 * @return string A formatted rendition of the collections
 * @todo Move to the friends/collection.php page.
 * @access private
 */
function elgg_view_access_collections($owner_guid) {
	if ($collections = get_user_access_collections($owner_guid)) {
		$user = get_user($owner_guid);
		if ($user) {
			$entities = $user->getFriends(array('limit' => 0));
		} else {
			$entities = array();
		}

		foreach ($collections as $key => $collection) {
			$collections[$key]->members = get_members_of_access_collection($collection->id, true);
			$collections[$key]->entities = $entities;
		}
	}

	return elgg_view('core/friends/collections', array('collections' => $collections));
}

/**
 * Auto-registers views from a location.
 *
 * @note Views in plugin/views/ are automatically registered for active plugins.
 * Plugin authors would only need to call this if optionally including
 * an entire views structure.
 *
 * @param string $view_base Optional The base of the view name without the view type.
 * @param string $folder    Required The folder to begin looking in
 * @param string $ignored   This argument is ignored
 * @param string $viewtype  The type of view we're looking at (default, rss, etc)
 *
 * @return bool returns false if folder can't be read
 * @since 1.7.0
 * @see elgg_set_view_location()
 * @access private
 */
function autoregister_views($view_base, $folder, $ignored, $viewtype) {
	return _elgg_services()->views->autoregisterViews($view_base, $folder, $viewtype);
}

/**
 * Minifies simplecache CSS and JS views by handling the "simplecache:generate" hook
 *
 * @param string $hook    The name of the hook
 * @param string $type    View type (css, js, or unknown)
 * @param string $content Content of the view
 * @param array  $params  Array of parameters
 *
 * @return string|null View content minified (if css/js type)
 * @access private
 */
function _elgg_views_minify($hook, $type, $content, $params) {
	if (preg_match('~[\.-]min\.~', $params['view'])) {
		// bypass minification
		return;
	}

	if ($type == 'js') {
		if (elgg_get_config('simplecache_minify_js')) {
			return JSMin::minify($content);
		}
	} elseif ($type == 'css') {
		if (elgg_get_config('simplecache_minify_css')) {
			$cssmin = new CSSmin();
			return $cssmin->run($content);
		}
	}
}


/**
 * Inserts module names into anonymous modules by handling the "simplecache:generate" hook.
 *
 * @param string $hook    The name of the hook
 * @param string $type    View type (css, js, or unknown)
 * @param string $content Content of the view
 * @param array  $params  Array of parameters
 *
 * @return string|null View content minified (if css/js type)
 * @access private
 */
function _elgg_views_amd($hook, $type, $content, $params) {
	$filter = new \Elgg\Amd\ViewFilter();
	return $filter->filter($params['view'], $content);
}

/**
 * Add the RSS link to the extras when if needed
 *
 * @return void
 * @access private
 */
function elgg_views_add_rss_link() {
	if (_elgg_has_rss_link()) {
		$url = current_page_url();
		if (substr_count($url, '?')) {
			$url .= "&view=rss";
		} else {
			$url .= "?view=rss";
		}

		$url = elgg_format_url($url);
		elgg_register_menu_item('extras', array(
			'name' => 'rss',
			'text' => elgg_view_icon('rss'),
			'href' => $url,
			'title' => elgg_echo('feed:rss'),
		));
	}
}

/**
 * Sends X-Frame-Options header on page requests
 *
 * @access private
 */
function _elgg_views_send_header_x_frame_options() {
	header('X-Frame-Options: SAMEORIGIN');
}

/**
 * Is there a chance a plugin is altering this view?
 *
 * @note Must be called after the [init, system] event, ideally as late as possible.
 *
 * @note Always returns true if the view's location is set in /engine/views.php. Elgg does not keep
 *       track of the defaults for those locations.
 *
 * <code>
 * // check a view in core
 * if (_elgg_view_may_be_altered('foo/bar', 'foo/bar.php')) {
 *     // use the view for BC
 * }
 *
 * // check a view in a bundled plugin
 * $dir = __DIR__ . "/views/" . elgg_get_viewtype();
 * if (_elgg_view_may_be_altered('foo.css', "$dir/foo.css.php")) {
 *     // use the view for BC
 * }
 * </code>
 *
 * @param string $view     View name. E.g. "elgg/init.js"
 * @param string $path     Absolute file path, or path relative to the viewtype directory. E.g. "elgg/init.js.php"
 *
 * @return bool
 * @access private
 */
function _elgg_view_may_be_altered($view, $path) {
	$views = _elgg_services()->views;

	if ($views->viewIsExtended($view) || $views->viewHasHookHandlers($view)) {
		return true;
	}

	$viewtype = elgg_get_viewtype();

	// check location
	if (0 === strpos($path, '/') || preg_match('~^([A-Za-z]\:)?\\\\~', $path)) {
		// absolute path
		$expected_path = $path;
	} else {
		// relative path
		$root = dirname(dirname(__DIR__));
		$expected_path = "$root/views/$viewtype/" . ltrim($path, '/\\');
	}

	$view_path = $views->findViewFile($view, $viewtype);
	
	return realpath($view_path) !== realpath($expected_path);
}

/**
 * Initialize viewtypes on system boot event
 * This ensures simplecache is cleared during upgrades. See #2252
 *
 * @return void
 * @access private
 * @elgg_event_handler boot system
 */
function elgg_views_boot() {
	global $CONFIG;

	if (!elgg_get_config('system_cache_loaded')) {
		// Core view files in /views
		_elgg_services()->views->registerPluginViews(realpath(__DIR__ . '/../../'));

		// Core view definitions in /engine/views.php
		$file = dirname(__DIR__) . '/views.php';
		if (is_file($file)) {
			$spec = Includer::includeFile($file);
			if (is_array($spec)) {
				_elgg_services()->views->mergeViewsSpec($spec);
			}
		}
	}

	// on every page

	// jQuery and UI must come before require. See #9024
	elgg_register_js('jquery', elgg_get_simplecache_url('jquery.js'), 'head');
	elgg_load_js('jquery');

	elgg_register_js('jquery-ui', elgg_get_simplecache_url('jquery-ui.js'), 'head');
	elgg_load_js('jquery-ui');

	elgg_register_js('elgg.require_config', elgg_get_simplecache_url('elgg/require_config.js'), 'head');
	elgg_load_js('elgg.require_config');

	elgg_register_js('require', elgg_get_simplecache_url('require.js'), 'head');
	elgg_load_js('require');

	elgg_register_js('elgg', elgg_get_simplecache_url('elgg.js'), 'head');
	elgg_load_js('elgg');
	
	elgg_register_css('font-awesome', elgg_get_simplecache_url('font-awesome/css/font-awesome.css'));
	elgg_load_css('font-awesome');

	elgg_register_css('elgg', elgg_get_simplecache_url('elgg.css'));
	elgg_load_css('elgg');

	elgg_register_simplecache_view('elgg/init.js');

	elgg_register_css('lightbox', elgg_get_simplecache_url('lightbox/elgg-colorbox-theme/colorbox.css'));
	elgg_load_css('lightbox');

	// provide warning to use elgg/lightbox AMD
	elgg_register_js('lightbox', elgg_get_simplecache_url('lightbox.js'));

	// just provides warning to use elgg/autocomplete AMD
	elgg_register_js('elgg.autocomplete', elgg_normalize_url('js/lib/ui.autocomplete.js'));

	elgg_define_js('jquery.ui.autocomplete.html', [
		'deps' => ['jquery-ui'],
	]);

	elgg_register_js('elgg.friendspicker', elgg_get_simplecache_url('elgg/ui.friends_picker.js'));
	elgg_register_js('elgg.avatar_cropper', elgg_get_simplecache_url('elgg/ui.avatar_cropper.js'));

	// @deprecated 2.2
	elgg_register_js('elgg.ui.river', elgg_get_simplecache_url('elgg/ui.river.js'));

	elgg_register_js('jquery.imgareaselect', elgg_get_simplecache_url('jquery.imgareaselect.js'));
	elgg_register_css('jquery.imgareaselect', elgg_get_simplecache_url('jquery.imgareaselect.css'));

	elgg_register_ajax_view('languages.js');

	elgg_register_plugin_hook_handler('simplecache:generate', 'js', '_elgg_views_amd');
	elgg_register_plugin_hook_handler('simplecache:generate', 'css', '_elgg_views_minify');
	elgg_register_plugin_hook_handler('simplecache:generate', 'js', '_elgg_views_minify');

	elgg_register_plugin_hook_handler('output:before', 'layout', 'elgg_views_add_rss_link');
	elgg_register_plugin_hook_handler('output:before', 'page', '_elgg_views_send_header_x_frame_options');

	// registered with high priority for BC
	// prior to 2.2 registration used to take place in _elgg_views_prepare_head() before the hook was triggered
	elgg_register_plugin_hook_handler('head', 'page', '_elgg_views_prepare_favicon_links', 1);
	
	// @todo the cache is loaded in load_plugins() but we need to know viewtypes earlier
	$view_path = _elgg_services()->views->view_path;
	$viewtype_dirs = scandir($view_path);
	foreach ($viewtype_dirs as $viewtype) {
		if (_elgg_is_valid_viewtype($viewtype) && is_dir($view_path . $viewtype)) {
			elgg_register_viewtype($viewtype);
		}
	}

	// set default icon sizes - can be overridden in settings.php or with plugin
	if (!isset($CONFIG->icon_sizes)) {
		$icon_sizes = array(
			'topbar' => array('w' => 16, 'h' => 16, 'square' => true, 'upscale' => true),
			'tiny' => array('w' => 25, 'h' => 25, 'square' => true, 'upscale' => true),
			'small' => array('w' => 40, 'h' => 40, 'square' => true, 'upscale' => true),
			'medium' => array('w' => 100, 'h' => 100, 'square' => true, 'upscale' => true),
			'large' => array('w' => 200, 'h' => 200, 'square' => false, 'upscale' => false),
			'master' => array('w' => 550, 'h' => 550, 'square' => false, 'upscale' => false),
		);
		elgg_set_config('icon_sizes', $icon_sizes);
	}

	// Patches and features that were included between major releases
	// sometimes require additional styling, but adding them to core CSS files
	// is not always feasible, because those can be replaced by themes.
	// @todo Remove in 3.0
	elgg_extend_view('elgg.css', 'elements/pathces.css');
	elgg_extend_view('admin.css', 'elements/pathces.css');
}

/**
 * Handle triggering the pagesetup event at the right time
 *
 * Trigger the system "pagesetup" event just before the 1st view rendering, or the 2nd if the 1st
 * view starts with "resources/".
 *
 * We delay the pagesetup event if the first view is a resource view in order to allow plugins to
 * move all page-specific logic like context setting into a resource view with more confidence
 * that that state will be available in their pagesetup event handlers. See the commit message for
 * more BG info.
 *
 * @param string $hook   "view_vars"
 * @param string $view   View name
 * @param array  $value  View arguments
 * @param array  $params Hook params
 * @return void
 */
function _elgg_manage_pagesetup($hook, $view, $value, $params) {
	global $CONFIG;

	static $allow_delay_pagesetup = true;

	if (isset($GLOBALS['_ELGG']->pagesetupdone) || empty($CONFIG->boot_complete)) {
		return;
	}

	// only first rendering gets an opportunity to delay
	$allow_delay = $allow_delay_pagesetup;
	$allow_delay_pagesetup = false;

	if ($allow_delay && (0 === strpos($view, 'resources/'))) {
		return;
	}

	$GLOBALS['_ELGG']->pagesetupdone = true;

	// don't call this anymore
	_elgg_services()->hooks->unregisterHandler('view_vars', 'all', '_elgg_manage_pagesetup');

	_elgg_services()->events->trigger('pagesetup', 'system');
}

/**
 * Get the site data to be merged into "elgg" in elgg.js.
 *
 * Unlike _elgg_get_js_page_data(), the keys returned are literal expressions.
 *
 * @return array
 * @access private
 */
function _elgg_get_js_site_data() {
	$language = elgg_get_config('language');
	if (!$language) {
		$language = 'en';
	}

	return [
		'elgg.data' => (object)elgg_trigger_plugin_hook('elgg.data', 'site', null, []),
		'elgg.version' => elgg_get_version(),
		'elgg.release' => elgg_get_version(true),
		'elgg.config.wwwroot' => elgg_get_site_url(),

		// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
		'elgg.security.interval' => (int)_elgg_services()->actions->getActionTokenTimeout() * 333,
		'elgg.config.language' => $language,
	];
}

/**
 * Get the initial contents of "elgg" client side. Will be extended by elgg.js.
 *
 * @return array
 * @access private
 */
function _elgg_get_js_page_data() {
	$data = elgg_trigger_plugin_hook('elgg.data', 'page', null, []);
	if (!is_array($data)) {
		elgg_log('"elgg.data" plugin hook handlers must return an array. Returned ' . gettype($data) . '.', 'ERROR');
		$data = [];
	}

	$elgg = array(
		'config' => array(
			'lastcache' => (int) elgg_get_config('lastcache'),
			'viewtype' => elgg_get_viewtype(),
			'simplecache_enabled' => (int) elgg_is_simplecache_enabled(),
		),
		'security' => array(
			'token' => array(
				'__elgg_ts' => $ts = time(),
				'__elgg_token' => generate_action_token($ts),
			),
		),
		'session' => array(
			'user' => null,
			'token' => _elgg_services()->session->get('__elgg_session'),
		),
		'_data' => (object) $data,
	);

	if (elgg_get_config('elgg_load_sync_code')) {
		$elgg['config']['load_sync_code'] = true;
	}

	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner instanceof ElggEntity) {
		$elgg['page_owner'] = $page_owner->toObject();
	}

	$user = elgg_get_logged_in_user_entity();
	if ($user instanceof ElggUser) {
		$user_object = $user->toObject();
		$user_object->admin = $user->isAdmin();
		$elgg['session']['user'] = $user_object;
	}

	return $elgg;
}

/**
 * Render a view while the global viewtype is temporarily changed. This makes sure that
 * nested views use the same viewtype.
 *
 * @param string  $view     View name
 * @param array   $vars     View vars
 * @param string  $viewtype Temporary viewtype ('' to leave current)
 *
 * @return mixed
 * @access private
 */
function _elgg_view_under_viewtype($view, $vars, $viewtype) {
	if ($viewtype) {
		$old = elgg_get_viewtype();
		elgg_set_viewtype($viewtype);
	}

	$ret = elgg_view($view, $vars);

	if ($viewtype) {
		elgg_set_viewtype($old);
	}

	return $ret;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('boot', 'system', 'elgg_views_boot');
	$hooks->registerHandler('view_vars', 'all', '_elgg_manage_pagesetup', 1000);
};
