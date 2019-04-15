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
use Elgg\Project\Paths;

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
function elgg_set_viewtype($viewtype = '') {
	return _elgg_services()->views->setViewtype($viewtype);
}

/**
 * Return the current view type.
 *
 * Viewtypes are automatically detected and can be set with $_REQUEST['view']
 * or {@link elgg_set_viewtype()}.
 *
 * @return string The viewtype
 * @see elgg_set_viewtype()
 */
function elgg_get_viewtype() {
	return _elgg_services()->views->getViewtype();
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
	return _elgg_services()->views->isValidViewtype($viewtype);
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
 * @param string $view     The name and location of the view to use
 * @param array  $vars     Variables to pass to the view.
 * @param string $viewtype If set, forces the viewtype for the elgg_view call to be
 *                          this value (default: standard detection)
 *
 * @return string The parsed view
 */
function elgg_view($view, $vars = [], $viewtype = '') {
	if (func_num_args() == 5) {
		elgg_log(__FUNCTION__ . ' now has only 3 arguments. Update your usage.', 'ERROR');
		$viewtype = func_get_arg(4);
	}
	return _elgg_services()->views->renderView($view, $vars, $viewtype);
}

/**
 * Display a view with a deprecation notice. No missing view NOTICE is logged
 *
 * @param string $view       The name and location of the view to use
 * @param array  $vars       Variables to pass to the view
 * @param string $suggestion Suggestion with the deprecation message
 * @param string $version    Human-readable *release* version: 1.7, 1.8, ...
 *
 * @return string The parsed view
 *
 * @see elgg_view()
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
function elgg_view_page($title, $body, $page_shell = 'default', $vars = []) {
	$timer = _elgg_services()->timer;
	if (!$timer->hasEnded(['build page'])) {
		$timer->end(['build page']);
	}
	$timer->begin([__FUNCTION__]);

	$params = [];
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
			$errors = [
				'error' => $messages['error']
			];

			unset($messages['error']);
			$messages = array_merge($errors, $messages);
		}
	}

	$vars['title'] = $title;
	$vars['body'] = $body;
	$vars['sysmessages'] = $messages;
	$vars['page_shell'] = $page_shell;

	// head has keys 'title', 'metas', 'links'
	$head_params = _elgg_views_prepare_head($title);

	$vars['head'] = elgg_trigger_plugin_hook('head', 'page', $vars, $head_params);

	$vars = elgg_trigger_plugin_hook('output:before', 'page', null, $vars);

	$output = elgg_view("page/$page_shell", $vars);


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
 * @throws \Elgg\PageNotFoundException
 */
function elgg_view_resource($name, array $vars = []) {
	$view = "resources/$name";

	if (elgg_view_exists($view)) {
		return _elgg_services()->views->renderView($view, $vars);
	}

	if (elgg_get_viewtype() !== 'default' && elgg_view_exists($view, 'default')) {
		return _elgg_services()->views->renderView($view, $vars, 'default');
	}

	_elgg_services()->logger->error("The view $view is missing.");

	// only works for default viewtype
	throw new \Elgg\PageNotFoundException();
}

/**
 * Prepare the variables for the html head
 *
 * @param string $title Page title for <head>
 * @return array
 * @access private
 */
function _elgg_views_prepare_head($title) {
	$params = [
		'links' => [],
		'metas' => [],
	];

	if (empty($title)) {
		$params['title'] = _elgg_config()->sitename;
	} else {
		$params['title'] = $title . ' : ' . _elgg_config()->sitename;
	}

	$params['metas']['content-type'] = [
		'http-equiv' => 'Content-Type',
		'content' => 'text/html; charset=utf-8',
	];

	$params['metas']['description'] = [
		'name' => 'description',
		'content' => _elgg_config()->sitedescription
	];

	// https://developer.chrome.com/multidevice/android/installtohomescreen
	$params['metas']['viewport'] = [
		'name' => 'viewport',
		'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0',
	];
	$params['metas']['mobile-web-app-capable'] = [
		'name' => 'mobile-web-app-capable',
		'content' => 'yes',
	];
	$params['metas']['apple-mobile-web-app-capable'] = [
		'name' => 'apple-mobile-web-app-capable',
		'content' => 'yes',
	];

	// RSS feed link
	if (_elgg_has_rss_link()) {
		$url = current_page_url();
		if (substr_count($url, '?')) {
			$url .= "&view=rss";
		} else {
			$url .= "?view=rss";
		}
		$params['links']['rss'] = [
			'rel' => 'alternative',
			'type' => 'application/rss+xml',
			'title' => 'RSS',
			'href' => $url,
		];
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

	$head_params['links']['apple-touch-icon'] = [
		'rel' => 'apple-touch-icon',
		'href' => elgg_get_simplecache_url('graphics/favicon-128.png'),
	];

	// favicons
	$head_params['links']['icon-ico'] = [
		'rel' => 'icon',
		'href' => elgg_get_simplecache_url('graphics/favicon.ico'),
	];
	$head_params['links']['icon-vector'] = [
		'rel' => 'icon',
		'sizes' => '16x16 32x32 48x48 64x64 128x128',
		'type' => 'image/svg+xml',
		'href' => elgg_get_simplecache_url('graphics/favicon.svg'),
	];
	$head_params['links']['icon-16'] = [
		'rel' => 'icon',
		'sizes' => '16x16',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('graphics/favicon-16.png'),
	];
	$head_params['links']['icon-32'] = [
		'rel' => 'icon',
		'sizes' => '32x32',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('graphics/favicon-32.png'),
	];
	$head_params['links']['icon-64'] = [
		'rel' => 'icon',
		'sizes' => '64x64',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('graphics/favicon-64.png'),
	];
	$head_params['links']['icon-128'] = [
		'rel' => 'icon',
		'sizes' => '128x128',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('graphics/favicon-128.png'),
	];

	return $head_params;
}

/**
 * Displays a layout with optional parameters.
 *
 * Layouts are templates provide consistency by organizing blocks of content on the page.
 *
 * Plugins should use one of the core layouts:
 *  - default     Primary template with one, two or no sidebars
 *  - admin       Admin page template
 *  - error       Error page template
 *  - widgets     Widgets canvas
 *
 * Plugins can create and use custom layouts by placing a layout view
 * in "page/layouts/<layout_name>" and calling elgg_view_layout(<layout_name>).
 *
 * For a full list of parameters supported by each of these layouts see
 * corresponding layout views.
 *
 * @param string $layout_name Layout name
 *                            Corresponds to a view in "page/layouts/<layout_name>".
 * @param array  $vars        Layout parameters
 *                            An associative array of parameters to pass to
 *                            the layout hooks and views.
 *                            Route 'identifier' and 'segments' of the page being
 *                            rendered will be added to this array automatially,
 *                            allowing plugins to alter layout views and subviews
 *                            based on the current route.
 * @return string
 */
function elgg_view_layout($layout_name, $vars = []) {
	$timer = _elgg_services()->timer;
	if (!$timer->hasEnded(['build page'])) {
		$timer->end(['build page']);
	}
	$timer->begin([__FUNCTION__]);

	// Help plugins transition without breaking them
	switch ($layout_name) {
		case 'content' :
			$layout_name = 'default';
			$vars = _elgg_normalize_content_layout_vars($vars);
			break;

		case 'one_sidebar' :
			$layout_name = 'default';
			$vars['sidebar'] = elgg_extract('sidebar', $vars, '', false);
			$vars['sidebar_alt'] = false;
			break;

		case 'one_column' :
			$layout_name = 'default';
			$vars['sidebar'] = false;
			$vars['sidebar_alt'] = false;
			break;

		case 'two_sidebar' :
			$layout_name = 'default';
			$vars['sidebar'] = elgg_extract('sidebar', $vars, '', false);
			$vars['sidebar_alt'] = elgg_extract('sidebar_alt', $vars, '', false);
			break;

		case 'default' :
			$filter_id = elgg_extract('filter_id', $vars, 'filter');
			$filter_context = elgg_extract('filter_value', $vars);
			if (isset($filter_context) && $filter_id === 'filter') {
				$context = elgg_extract('context', $vars, elgg_get_context());
				$vars['filter'] = elgg_get_filter_tabs($context, $filter_context, null, $vars);
				$vars['filter_id'] = $filter_id;
				$vars['filter_value'] = $filter_context;
			}
			break;
	}

	if (isset($vars['nav'])) {
		// Temporary helper until all core views are updated
		$vars['breadcrumbs'] = $vars['nav'];
		unset($vars['nav']);
	}

	$vars['identifier'] = _elgg_services()->request->getFirstUrlSegment();
	$vars['segments'] = _elgg_services()->request->getUrlSegments();
	array_shift($vars['segments']);

	$layout_name = elgg_trigger_plugin_hook('layout', 'page', $vars, $layout_name);

	$vars['layout'] = $layout_name;

	$layout_views = [
		"page/layouts/$layout_name",
		"page/layouts/default",
	];

	$output = '';
	foreach ($layout_views as $layout_view) {
		if (elgg_view_exists($layout_view)) {
			$output = elgg_view($layout_view, $vars);
			break;
		}
	}

	$timer->end([__FUNCTION__]);
	return $output;
}

/**
 * Normalizes deprecated content layout $vars for use in default layout
 * Helper function to assist plugins transitioning to 3.0
 *
 * @param array $vars Vars
 * @return array
 * @access private
 */
function _elgg_normalize_content_layout_vars(array $vars = []) {

	$context = elgg_extract('context', $vars, elgg_get_context());

	$vars['title'] = elgg_extract('title', $vars, '');
	if (!$vars['title'] && $vars['title'] !== false) {
		$vars['title'] = elgg_echo($context);
	}

	// 1.8 supported 'filter_override'
	if (isset($vars['filter_override'])) {
		$vars['filter'] = $vars['filter_override'];
	}

	// register the default content filters
	if (!isset($vars['filter']) && $context) {
		$selected = elgg_extract('filter_context', $vars);
		$vars['filter'] = elgg_get_filter_tabs($context, $selected, null, $vars);
		$vars['filter_id'] = $context;
		$vars['filter_value'] = $selected;
	}

	return $vars;
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
 *                                  string options: 'name', 'priority' (default), 'text'
 *                                  or a php callback (a compare function for usort)
 *                              handler: string the page handler to build action URLs
 *                              entity: \ElggEntity to use to build action URLs
 *                              class: string the class for the entire menu.
 *                              menu_view: name of the view to be used to render the menu
 *                              show_section_headers: bool show headers before menu sections.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_menu($menu, array $vars = []) {

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
function elgg_view_menu_item(\ElggMenuItem $item, array $vars = []) {

	$vars = array_merge($item->getValues(), $vars);
	$vars['class'] = elgg_extract_class($vars, ['elgg-menu-content']);

	if ($item->getLinkClass()) {
		$vars['class'][] = $item->getLinkClass();
	}

	if ($item->getHref() === false || $item->getHref() === null) {
		$vars['class'][] = 'elgg-non-link';
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
 *      'full_view'           Whether to show a full or condensed view. (Default: true)
 *      'item_view'           Alternative view used to render this entity
 *      'register_rss_link'   Register the rss link availability (default: depending on full_view)
 *
 * @return string HTML to display or false
 * @todo The annotation hook might be better as a generic plugin hook to append content.
 */
function elgg_view_entity(\ElggEntity $entity, array $vars = []) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof \ElggEntity)) {
		return false;
	}

	$defaults = [
		'full_view' => true,
	];

	$vars = array_merge($defaults, $vars);
	
	if (elgg_extract('register_rss_link', $vars, elgg_extract('full_view', $vars))) {
		elgg_register_rss_link();
	}

	$vars['entity'] = $entity;

	$entity_type = $entity->getType();
	$entity_subtype = $entity->getSubtype();

	$entity_views = [
		elgg_extract('item_view', $vars, ''),
		"$entity_type/$entity_subtype",
		"$entity_type/default",
	];

	$contents = '';
	foreach ($entity_views as $view) {
		if (elgg_view_exists($view)) {
			$contents = elgg_view($view, $vars);
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
function elgg_view_entity_icon(\ElggEntity $entity, $size = 'medium', $vars = []) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof \ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['size'] = $size;

	$entity_type = $entity->getType();

	$subtype = $entity->getSubtype();

	$contents = '';
	if (elgg_view_exists("icon/$entity_type/$subtype")) {
		$contents = elgg_view("icon/$entity_type/$subtype", $vars);
	}
	if (empty($contents) && elgg_view_exists("icon/$entity_type/default")) {
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
 *
 * @return string/false Rendered annotation
 */
function elgg_view_annotation(\ElggAnnotation $annotation, array $vars = []) {
	$defaults = [
		'full_view' => true,
	];

	$vars = array_merge($defaults, $vars);
	$vars['annotation'] = $annotation;

	$name = $annotation->name;
	if (empty($name)) {
		return false;
	}

	$annotation_views = [
		elgg_extract('item_view', $vars, ''),
		"annotation/$name",
		"annotation/default",
	];

	$contents = '';
	foreach ($annotation_views as $view) {
		if (elgg_view_exists($view)) {
			$contents = elgg_view($view, $vars);
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
 *
 * @param array $entities Array of entities
 * @param array $vars     Display variables
 *      'count'            The total number of entities across all pages
 *      'offset'           The current indexing offset
 *      'limit'            The number of entities to display per page (default from settings)
 *      'full_view'        Display the full view of the entities?
 *      'list_class'       CSS class applied to the list
 *      'item_class'       CSS class applied to the list items
 *      'item_view'        Alternative view to render list items content
 *      'list_item_view'   Alternative view to render list items
 *      'pagination'       Display pagination?
 *      'base_url'         Base URL of list (optional)
 *      'url_fragment'     URL fragment to add to links if not present in base_url (optional)
 *      'position'         Position of the pagination: before, after, or both
 *      'list_type'        List type: 'list' (default), 'gallery'
 *      'list_type_toggle' Display the list type toggle?
 *      'no_results'       Message to display if no results (string|true|Closure)
 *
 * @return string The rendered list of entities
 */
function elgg_view_entity_list($entities, array $vars = []) {
	$offset = (int) get_input('offset', 0);

	// list type can be passed as request parameter
	$list_type = get_input('list_type', 'list');

	$defaults = [
		'items' => $entities,
		'list_class' => 'elgg-list-entity',
		'full_view' => true,
		'pagination' => true,
		'list_type' => $list_type,
		'list_type_toggle' => false,
		'offset' => $offset,
		'limit' => null,
	];

	$vars = array_merge($defaults, $vars);

	if (!$vars["limit"] && !$vars["offset"]) {
		// no need for pagination if listing is unlimited
		$vars["pagination"] = false;
	}

	$view = "page/components/{$vars['list_type']}";
	if (!elgg_view_exists($view)) {
		$view = 'page/components/list';
	}
	
	return elgg_view($view, $vars);
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
 *      'no_results' Message to display if no results (string|true|Closure)
 *
 * @return string The list of annotations
 * @access private
 */
function elgg_view_annotation_list($annotations, array $vars = []) {
	$defaults = [
		'items' => $annotations,
		'offset' => null,
		'limit' => null,
		'list_class' => 'elgg-list-annotation elgg-annotation-list', // @todo remove elgg-annotation-list in Elgg 1.9
		'full_view' => true,
		'offset_key' => 'annoff',
	];

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
		[
			'entity' => $entity,
			'full_view' => $full_view,
		]
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
function elgg_view_title($title, array $vars = []) {
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
function elgg_view_comments($entity, $add_comment = true, array $vars = []) {
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
 * @note Use the $vars "image_alt" key to set an image on the right. If you do, you may pass
 *       in an empty string for $image to have only the right image.
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
function elgg_view_image_block($image, $body, $vars = []) {
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
function elgg_view_module($type, $title, $body, array $vars = []) {
	$vars['type'] = $type;
	$vars['title'] = $title;
	$vars['body'] = $body;
	return elgg_view('page/components/module', $vars);
}

/**
 * Wrapper function for the message display pattern.
 *
 * Box with header, body
 *
 * This is a shortcut for {@elgg_view page/components/message}.
 *
 * @param string $type The type of message (error, success, warning, help, notice)
 * @param string $body Content of the message
 * @param array  $vars Additional parameters for the message
 *
 * @return string
 * @since 3.0.0
 */
function elgg_view_message($type, $body, array $vars = []) {
	$vars['type'] = $type;
	$vars['body'] = $body;
	return elgg_view('page/components/message', $vars);
}

/**
 * Renders a human-readable representation of a river item
 *
 * @param \ElggRiverItem $item A river item object
 * @param array          $vars An array of variables for the view
 *      'item_view'         Alternative view to render the item
 *      'register_rss_link' Register the rss link availability (default: false)
 * @return string returns empty string if could not be rendered
 */
function elgg_view_river_item($item, array $vars = []) {

	if (!($item instanceof \ElggRiverItem)) {
		return '';
	}

	// checking default viewtype since some viewtypes do not have unique views per item (rss)
	$view = $item->getView();

	$subject = $item->getSubjectEntity();
	$object = $item->getObjectEntity();
	if (!$subject || !$object) {
		// subject is disabled or subject/object deleted
		return '';
	}
	
	if (elgg_extract('register_rss_link', $vars)) {
		elgg_register_rss_link();
	}

	$vars['item'] = $item;

	// create river view logic
	$type = $object->getType();
	$subtype = $object->getSubtype();
	$action = $item->action_type;

	$river_views = [
		elgg_extract('item_view', $vars, ''),
		'river/item', // important for other viewtypes, e.g. "rss"
		$view,
		"river/{$type}/{$subtype}/{$action}",
		"river/{$type}/{$subtype}/default",
		"river/{$type}/{$action}",
		"river/{$type}/default",
		'river/elements/layout',
	];

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
 *                           - 'ajax' bool If true, the form will be submitted with an Ajax request
 * @param array  $body_vars $vars environment passed to the "forms/$action" view
 *
 * @return string The complete form
 */
function elgg_view_form($action, $form_vars = [], $body_vars = []) {
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
 * Split array of vars into subarrays based on property prefixes
 *
 * @see elgg_view_field()
 *
 * @param array $vars     Vars to split
 * @param array $prefixes Prefixes to split
 *
 * @return array
 */
function _elgg_split_vars(array $vars = [], array $prefixes = null) {

	if (!isset($prefixes)) {
		$prefixes = ['#'];
	}

	$return = [];

	foreach ($vars as $key => $value) {
		foreach ($prefixes as $prefix) {
			if (substr($key, 0, 1) === $prefix) {
				$key = substr($key, 1);
				$return[$prefix][$key] = $value;
				break;
			} else {
				$return[''][$key] = $value;
			}
		}
	}

	return $return;
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
 *                       - #view: custom view to use to render the field
 *                       - #html: can be used to render custom HTML instead of in put field, helpful when you need to add a help paragraph or similar
 *                      Note: Both #label and #help are printed unescaped within their wrapper element.
 *                      Note: Some fields (like input/checkbox) need special attention because #label and label serve different purposes
 *                      "#label" will be used as a label in the field wrapper but "label" will be used in the input view
 *
 * @return string
 * @since 2.3
 */
function elgg_view_field(array $params = []) {

	if (!empty($params['#html'])) {
		return $params['#html'];
	}
	
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
		$params = _elgg_split_vars($params);
		return elgg_view("input/$input_type", $params['']);
	}

	$id = elgg_extract('id', $params);
	if (!$id) {
		$id = "elgg-field-" . base_convert(mt_rand(), 10, 36);
		$params['id'] = $id;
	}

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

	// Need to set defaults to prevent input keys with same name ending up in element vars if not provided
	$defaults = [
		'#class' => ELGG_ENTITIES_ANY_VALUE,
		'#help' => ELGG_ENTITIES_ANY_VALUE,
		'#label' => ELGG_ENTITIES_ANY_VALUE,
		'#view' => ELGG_ENTITIES_ANY_VALUE,
	];
	$params = array_merge($defaults, $params);
	
	// first pass non-hash keys into both
	$split_params = _elgg_split_vars($params);

	// $vars passed to input/$input_name
	$input_vars = $split_params[''];
	
	// $vars passed to label, help and field wrapper views
	$element_vars = array_merge($split_params[''], $split_params['#']);

	// field input view needs this
	$input_vars['input_type'] = $input_type;

	// field views get more data
	$element_vars['input_type'] = $input_type;

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
 *
 * @param array $options Any elgg_get_tags() options except:
 *
 * 	type => must be single entity type
 *
 * 	subtype => must be single entity subtype
 *
 * @return string
 *
 * @see elgg_get_tags()
 * @since 1.7.1
 */
function elgg_view_tagcloud(array $options = []) {

	$type = $subtype = '';
	if (isset($options['type'])) {
		$type = $options['type'];
	}
	if (isset($options['subtype'])) {
		$subtype = $options['subtype'];
	}

	$tag_data = elgg_get_tags($options);
	return elgg_view("output/tagcloud", [
		'value' => $tag_data,
		'type' => $type,
		'subtype' => $subtype,
	]);
}

/**
 * View an item in a list
 *
 * @param mixed $item Entity, annotation, river item, or other data
 * @param array $vars Additional parameters for the rendering
 *                    'item_view' - Alternative view used to render list items
 *                                  This parameter is required if rendering
 *                                  list items that are not entity, annotation or river
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_view_list_item($item, array $vars = []) {

	if ($item instanceof \ElggEntity) {
		return elgg_view_entity($item, $vars);
	} else if ($item instanceof \ElggAnnotation) {
		return elgg_view_annotation($item, $vars);
	} else if ($item instanceof \ElggRiverItem) {
		return elgg_view_river_item($item, $vars);
	}

	$view = elgg_extract('item_view', $vars);
	if ($view && elgg_view_exists($view)) {
		$vars['item'] = $item;
		return elgg_view($view, $vars);
	}

	return '';
}

/**
 * View an icon glyph
 *
 * @param string $name The specific icon to display
 * @param mixed  $vars The additional classname as a string ('float', 'float-alt' or a custom class)
 *                     or an array of variables (array('class' => 'float')) to pass to the icon view.
 *
 * @return string The html for displaying an icon
 * @throws InvalidArgumentException
 */
function elgg_view_icon($name, $vars = []) {
	if (empty($vars)) {
		$vars = [];
	}

	if (is_string($vars)) {
		$vars = ['class' => $vars];
	}

	if (!is_array($vars)) {
		throw new \InvalidArgumentException('$vars needs to be a string or an array');
	}

	$vars['class'] = elgg_extract_class($vars, "elgg-icon-$name");

	return elgg_view("output/icon", $vars);
}

/**
 * Include the RSS icon link and link element in the head
 *
 * @return void
 */
function elgg_register_rss_link() {
	_elgg_config()->_elgg_autofeed = true;
}

/**
 * Remove the RSS icon link and link element from the head
 *
 * @return void
 */
function elgg_unregister_rss_link() {
	_elgg_config()->_elgg_autofeed = false;
}

/**
 * Should the RSS view of this URL be linked to?
 *
 * @return bool
 * @access private
 */
function _elgg_has_rss_link() {
	if (_elgg_config()->disable_rss) {
		return false;
	}

	return (bool) _elgg_config()->_elgg_autofeed;
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
		if (_elgg_config()->simplecache_minify_js) {
			return JSMin::minify($content);
		}
	} elseif ($type == 'css') {
		if (_elgg_config()->simplecache_minify_css) {
			$cssmin = new CSSmin();
			return $cssmin->run($content);
		}
	}
}

/**
 * Preprocesses CSS views sent by /cache URLs
 *
 * @param string $hook    The name of the hook "simplecache:generate" or "cache:generate"
 * @param string $type    "css"
 * @param string $content Content of the view
 * @param array  $params  Array of parameters
 *
 * @return string|null View content
 * @access private
 */
function _elgg_views_preprocess_css($hook, $type, $content, $params) {
	$options = elgg_extract('compiler_options', $params, []);
	return _elgg_services()->cssCompiler->compile($content, $options);
}

/**
 * Inserts module names into anonymous modules by handling the "simplecache:generate" and "cache:generate" hook.
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
 * Sends X-Frame-Options header on page requests
 *
 * @return void
 *
 * @access private
 */
function _elgg_views_send_header_x_frame_options() {
	elgg_set_http_header('X-Frame-Options: SAMEORIGIN');
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
 * @param string $view View name. E.g. "elgg/init.js"
 * @param string $path Absolute file path, or path relative to the viewtype directory. E.g. "elgg/init.js.php"
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
		$expected_path = Paths::elgg() . "views/$viewtype/" . ltrim($path, '/\\');
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
	_elgg_services()->viewCacher->registerCoreViews();

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

	elgg_register_css('font-awesome', elgg_get_simplecache_url('font-awesome/css/all.min.css'));
	elgg_load_css('font-awesome');

	elgg_register_css('elgg', elgg_get_simplecache_url('elgg.css'));
	elgg_load_css('elgg');

	elgg_register_simplecache_view('elgg/init.js');

	elgg_extend_view('elgg.css', 'lightbox/elgg-colorbox-theme/colorbox.css');

	elgg_define_js('jquery.ui.autocomplete.html', [
		'deps' => ['jquery-ui'],
	]);

	elgg_register_js('elgg.avatar_cropper', elgg_get_simplecache_url('elgg/ui.avatar_cropper.js'));

	// @deprecated 2.2
	elgg_register_js('elgg.ui.river', elgg_get_simplecache_url('elgg/ui.river.js'));

	elgg_register_js('jquery.imgareaselect', elgg_get_simplecache_url('jquery.imgareaselect.js'));
	elgg_register_css('jquery.imgareaselect', elgg_get_simplecache_url('jquery.imgareaselect.css'));

	elgg_register_css('jquery.treeview', elgg_get_simplecache_url('jquery-treeview/jquery.treeview.css'));
	elgg_define_js('jquery.treeview', [
		'src' => elgg_get_simplecache_url('jquery-treeview/jquery.treeview.js'),
		'exports' => 'jQuery.fn.treeview',
		'deps' => ['jquery'],
	]);

	elgg_register_ajax_view('languages.js');

	// pre-process CSS regardless of simplecache
	elgg_register_plugin_hook_handler('cache:generate', 'css', '_elgg_views_preprocess_css');
	elgg_register_plugin_hook_handler('simplecache:generate', 'css', '_elgg_views_preprocess_css');

	elgg_register_plugin_hook_handler('simplecache:generate', 'js', '_elgg_views_amd');
	elgg_register_plugin_hook_handler('cache:generate', 'js', '_elgg_views_amd');
	elgg_register_plugin_hook_handler('simplecache:generate', 'css', '_elgg_views_minify');
	elgg_register_plugin_hook_handler('simplecache:generate', 'js', '_elgg_views_minify');

	elgg_register_plugin_hook_handler('output:before', 'page', '_elgg_views_send_header_x_frame_options');

	elgg_register_plugin_hook_handler('view_vars', 'elements/forms/help', '_elgg_views_file_help_upload_limit');

	// registered with high priority for BC
	// prior to 2.2 registration used to take place in _elgg_views_prepare_head() before the hook was triggered
	elgg_register_plugin_hook_handler('head', 'page', '_elgg_views_prepare_favicon_links', 1);

	// set default icon sizes - can be overridden with plugin
	if (!_elgg_config()->icon_sizes) {
		$icon_sizes = [
			'topbar' => ['w' => 16, 'h' => 16, 'square' => true, 'upscale' => true],
			'tiny' => ['w' => 25, 'h' => 25, 'square' => true, 'upscale' => true],
			'small' => ['w' => 40, 'h' => 40, 'square' => true, 'upscale' => true],
			'medium' => ['w' => 100, 'h' => 100, 'square' => true, 'upscale' => true],
			'large' => ['w' => 200, 'h' => 200, 'square' => true, 'upscale' => true],
			'master' => ['w' => 10240, 'h' => 10240, 'square' => false, 'upscale' => false, 'crop' => false],
		];
		elgg_set_config('icon_sizes', $icon_sizes);
	}

	// Configure lightbox
	elgg_register_plugin_hook_handler('elgg.data', 'site', '_elgg_set_lightbox_config');
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
	$language = _elgg_config()->language;
	if (!$language) {
		$language = 'en';
	}

	return [
		'elgg.data' => (object) elgg_trigger_plugin_hook('elgg.data', 'site', null, []),
		'elgg.version' => elgg_get_version(),
		'elgg.release' => elgg_get_version(true),
		'elgg.config.wwwroot' => elgg_get_site_url(),

		// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
		'elgg.security.interval' => (int) elgg()->csrf->getActionTokenTimeout() * 333,
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

	$elgg = [
		'config' => [
			'lastcache' => (int) _elgg_config()->lastcache,
			'viewtype' => elgg_get_viewtype(),
			'simplecache_enabled' => (int) elgg_is_simplecache_enabled(),
			'current_language' => get_current_language(),
		],
		'security' => [
			'token' => [
				'__elgg_ts' => $ts = time(),
				'__elgg_token' => generate_action_token($ts),
			],
		],
		'session' => [
			'user' => null,
			'token' => _elgg_services()->session->get('__elgg_session'),
		],
		'_data' => (object) $data,
	];

	if (_elgg_config()->elgg_load_sync_code) {
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
 * @param string $view     View name
 * @param array  $vars     View vars
 * @param string $viewtype Temporary viewtype ('' to leave current)
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

/**
 * Set lightbox config
 *
 * @param string $hook   "elgg.data"
 * @param string $type   "site"
 * @param array  $return Data
 * @param array  $params Hook params
 * @return array
 * @access private
 */
function _elgg_set_lightbox_config($hook, $type, $return, $params) {

	$return['lightbox'] = [
		'current' => elgg_echo('js:lightbox:current', ['{current}', '{total}']),
		'previous' => elgg_view_icon('caret-left'),
		'next' => elgg_view_icon('caret-right'),
		'close' => elgg_view_icon('times'),
		'opacity' => 0.5,
		'maxWidth' => '990px',
		'maxHeight' => '990px',
		'initialWidth' => '300px',
		'initialHeight' => '300px',
	];

	return $return;
}

/**
 * Add a help text to input/file about upload limit
 *
 * In order to not show the help text supply 'show_upload_limit' => false to elgg_view_field()
 *
 * @param \Elgg\Hook $hook 'view_vars' 'elements/forms/help'
 *
 * @return void|array
 * @access private
 */
function _elgg_views_file_help_upload_limit(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	if (elgg_extract('input_type', $return) !== 'file') {
		return;
	}

	if (!elgg_extract('show_upload_limit', $return, true)) {
		return;
	}

	$help = elgg_extract('help', $return, '');

	// Get post_max_size and upload_max_filesize
	$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
	$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

	// Determine the correct value
	$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

	$help .= ' ' . elgg_echo('input:file:upload_limit', [elgg_format_bytes($max_upload)]);

	$return['help'] = trim($help);

	return $return;
}

/**
 * Maps legacy sprite classes and FontAwesome 4 classes to FontAwesome 5 classes
 *
 * @param array $classes     Icon classes
 * @param bool  $map_sprites Map legacy Elgg sprites
 *
 * @return array
 * @access private
 */
function _elgg_map_icon_glyph_class(array $classes, $map_sprites = true) {

	// these 'old' Elgg 1.x sprite icons will be converted to the FontAwesome version
	$legacy_sprites = [
		"arrow-two-head" => "arrows-h",
		"attention" => "exclamation-triangle",
		"cell-phone" => "mobile",
		"checkmark" => "check",
		"clip" => "paperclip",
		"cursor-drag-arrow" => "arrows",
		"drag-arrow" => "arrows", // 'old' admin sprite
		"delete-alt" => "times-circle",
		"delete" => "times",
		"facebook" => "facebook-square",
		"grid" => "th",
		"hover-menu" => "caret-down",
		"info" => "info-circle",
		"lock-closed" => "lock",
		"lock-open" => "unlock",
		"mail" => "envelope-o",
		"mail-alt" => "envelope",
		"print-alt" => "print",
		"push-pin" => "thumb-tack",
		"push-pin-alt" => "thumb-tack",
		"redo" => "share",
		"round-arrow-left" => "arrow-circle-left",
		"round-arrow-right" => "arrow-circle-right",
		"round-checkmark" => "check-circle",
		"round-minus" => "minus-circle",
		"round-plus" => "plus-circle",
		"rss" => "rss-square",
		"search-focus" => "search",
		"settings" => "wrench",
		"settings-alt" => "cog",
		"share" => "share-alt-square",
		"shop-cart" => "shopping-cart",
		"speech-bubble" => "comment",
		"speech-bubble-alt" => "comments",
		"star-alt" => "star",
		"star-empty" => "star-o",
		"thumbs-down-alt" => "thumbs-down",
		"thumbs-up-alt" => "thumbs-up",
		"trash" => "trash-o",
		"twitter" => "twitter-square",
		"undo" => "reply",
		"video" => "film"
	];

	$fa5 = [
		'address-book-o' => ['address-book', 'far'],
		'address-card-o' => ['address-card', 'far'],
		'area-chart' => ['chart-area', 'fas'],
		'arrow-circle-o-down' => ['arrow-alt-circle-down', 'far'],
		'arrow-circle-o-left' => ['arrow-alt-circle-left', 'far'],
		'arrow-circle-o-right' => ['arrow-alt-circle-right', 'far'],
		'arrow-circle-o-up' => ['arrow-alt-circle-up', 'far'],
		'arrows-alt' => ['expand-arrows-alt', 'fas'],
		'arrows-h' => ['arrows-alt-h', 'fas'],
		'arrows-v' => ['arrows-alt-v', 'fas'],
		'arrows' => ['arrows-alt', 'fas'],
		'asl-interpreting' => ['american-sign-language-interpreting', 'fas'],
		'automobile' => ['car', 'fas'],
		'bank' => ['university', 'fas'],
		'bar-chart-o' => ['chart-bar', 'far'],
		'bar-chart' => ['chart-bar', 'far'],
		'bathtub' => ['bath', 'fas'],
		'battery-0' => ['battery-empty', 'fas'],
		'battery-1' => ['battery-quarter', 'fas'],
		'battery-2' => ['battery-half', 'fas'],
		'battery-3' => ['battery-three-quarters', 'fas'],
		'battery-4' => ['battery-full', 'fas'],
		'battery' => ['battery-full', 'fas'],
		'bell-o' => ['bell', 'far'],
		'bell-slash-o' => ['bell-slash', 'far'],
		'bitbucket-square' => ['bitbucket', 'fab'],
		'bitcoin' => ['btc', 'fab'],
		'bookmark-o' => ['bookmark', 'far'],
		'building-o' => ['building', 'far'],
		'cab' => ['taxi', 'fas'],
		'calendar-check-o' => ['calendar-check', 'far'],
		'calendar-minus-o' => ['calendar-minus', 'far'],
		'calendar-o' => ['calendar', 'far'],
		'calendar-plus-o' => ['calendar-plus', 'far'],
		'calendar-times-o' => ['calendar-times', 'far'],
		'calendar' => ['calendar-alt', 'fas'],
		'caret-square-o-down' => ['caret-square-down', 'far'],
		'caret-square-o-left' => ['caret-square-left', 'far'],
		'caret-square-o-right' => ['caret-square-right', 'far'],
		'caret-square-o-up' => ['caret-square-up', 'far'],
		'cc' => ['closed-captioning', 'far'],
		'chain-broken' => ['unlink', 'fas'],
		'chain' => ['link', 'fas'],
		'check-circle-o' => ['check-circle', 'far'],
		'check-square-o' => ['check-square', 'far'],
		'circle-o-notch' => ['circle-notch', 'fas'],
		'circle-o' => ['circle', 'far'],
		'circle-thin' => ['circle', 'far'],
		'clock-o' => ['clock', 'far'],
		'close' => ['times', 'fas'],
		'cloud-download' => ['cloud-download-alt', 'fas'],
		'cloud-upload' => ['cloud-upload-alt', 'fas'],
		'cny' => ['yen-sign', 'fas'],
		'code-fork' => ['code-branch', 'fas'],
		'comment-o' => ['comment', 'far'],
		'commenting-o' => ['comment-alt', 'far'],
		'commenting' => ['comment-alt', 'fas'],
		'comments-o' => ['comments', 'far'],
		'credit-card-alt' => ['credit-card', 'fas'],
		'cutlery' => ['utensils', 'fas'],
		'dashboard' => ['tachometer-alt', 'fas'],
		'deafness' => ['deaf', 'fas'],
		'dedent' => ['outdent', 'fas'],
		'diamond' => ['gem', 'far'],
		'dollar' => ['dollar-sign', 'fas'],
		'dot-circle-o' => ['dot-circle', 'far'],
		'drivers-license-o' => ['id-card', 'far'],
		'drivers-license' => ['id-card', 'fas'],
		'eercast' => ['sellcast', 'fab'],
		'envelope-o' => ['envelope', 'far'],
		'envelope-open-o' => ['envelope-open', 'far'],
		'eur' => ['euro-sign', 'fas'],
		'euro' => ['euro-sign', 'fas'],
		'exchange' => ['exchange-alt', 'fas'],
		'external-link-square' => ['external-link-square-alt', 'fas'],
		'external-link' => ['external-link-alt', 'fas'],
		'eyedropper' => ['eye-dropper', 'fas'],
		'fa' => ['font-awesome', 'fab'],
		'facebook-f' => ['facebook-f', 'fab'],
		'facebook-official' => ['facebook', 'fab'],
		'facebook' => ['facebook-f', 'fab'],
		'feed' => ['rss', 'fas'],
		'file-archive-o' => ['file-archive', 'far'],
		'file-audio-o' => ['file-audio', 'far'],
		'file-code-o' => ['file-code', 'far'],
		'file-excel-o' => ['file-excel', 'far'],
		'file-image-o' => ['file-image', 'far'],
		'file-movie-o' => ['file-video', 'far'],
		'file-o' => ['file', 'far'],
		'file-pdf-o' => ['file-pdf', 'far'],
		'file-photo-o' => ['file-image', 'far'],
		'file-picture-o' => ['file-image', 'far'],
		'file-powerpoint-o' => ['file-powerpoint', 'far'],
		'file-sound-o' => ['file-audio', 'far'],
		'file-text-o' => ['file-alt', 'far'],
		'file-text' => ['file-alt', 'fas'],
		'file-video-o' => ['file-video', 'far'],
		'file-word-o' => ['file-word', 'far'],
		'file-zip-o' => ['file-archive', 'far'],
		'files-o' => ['copy', 'far'],
		'flag-o' => ['flag', 'far'],
		'flash' => ['bolt', 'fas'],
		'floppy-o' => ['save', 'far'],
		'folder-o' => ['folder', 'far'],
		'folder-open-o' => ['folder-open', 'far'],
		'frown-o' => ['frown', 'far'],
		'futbol-o' => ['futbol', 'far'],
		'gbp' => ['pound-sign', 'fas'],
		'ge' => ['empire', 'fab'],
		'gear' => ['cog', 'fas'],
		'gears' => ['cogs', 'fas'],
		'gittip' => ['gratipay', 'fab'],
		'glass' => ['glass-martini', 'fas'],
		'google-plus-circle' => ['google-plus', 'fab'],
		'google-plus-official' => ['google-plus', 'fab'],
		'google-plus' => ['google-plus-g', 'fab'],
		'group' => ['users', 'fas'],
		'hand-grab-o' => ['hand-rock', 'far'],
		'hand-lizard-o' => ['hand-lizard', 'far'],
		'hand-o-down' => ['hand-point-down', 'far'],
		'hand-o-left' => ['hand-point-left', 'far'],
		'hand-o-right' => ['hand-point-right', 'far'],
		'hand-o-up' => ['hand-point-up', 'far'],
		'hand-paper-o' => ['hand-paper', 'far'],
		'hand-peace-o' => ['hand-peace', 'far'],
		'hand-pointer-o' => ['hand-pointer', 'far'],
		'hand-rock-o' => ['hand-rock', 'far'],
		'hand-scissors-o' => ['hand-scissors', 'far'],
		'hand-spock-o' => ['hand-spock', 'far'],
		'hand-stop-o' => ['hand-paper', 'far'],
		'handshake-o' => ['handshake', 'far'],
		'hard-of-hearing' => ['deaf', 'fas'],
		'hdd-o' => ['hdd', 'far'],
		'header' => ['heading', 'fas'],
		'heart-o' => ['heart', 'far'],
		'hospital-o' => ['hospital', 'far'],
		'hotel' => ['bed', 'fas'],
		'hourglass-1' => ['hourglass-start', 'fas'],
		'hourglass-2' => ['hourglass-half', 'fas'],
		'hourglass-3' => ['hourglass-end', 'fas'],
		'hourglass-o' => ['hourglass', 'far'],
		'id-card-o' => ['id-card', 'far'],
		'ils' => ['shekel-sign', 'fas'],
		'image' => ['image', 'far'],
		'inr' => ['rupee-sign', 'fas'],
		'institution' => ['university', 'fas'],
		'intersex' => ['transgender', 'fas'],
		'jpy' => ['yen-sign', 'fas'],
		'keyboard-o' => ['keyboard', 'far'],
		'krw' => ['won-sign', 'fas'],
		'legal' => ['gavel', 'fas'],
		'lemon-o' => ['lemon', 'far'],
		'level-down' => ['level-down-alt', 'fas'],
		'level-up' => ['level-up-alt', 'fas'],
		'life-bouy' => ['life-ring', 'far'],
		'life-buoy' => ['life-ring', 'far'],
		'life-saver' => ['life-ring', 'far'],
		'lightbulb-o' => ['lightbulb', 'far'],
		'line-chart' => ['chart-line', 'fas'],
		'linkedin-square' => ['linkedin', 'fab'],
		'linkedin' => ['linkedin-in', 'fab'],
		'long-arrow-down' => ['long-arrow-alt-down', 'fas'],
		'long-arrow-left' => ['long-arrow-alt-left', 'fas'],
		'long-arrow-right' => ['long-arrow-alt-right', 'fas'],
		'long-arrow-up' => ['long-arrow-alt-up', 'fas'],
		'mail-forward' => ['share', 'fas'],
		'mail-reply-all' => ['reply-all', 'fas'],
		'mail-reply' => ['reply', 'fas'],
		'map-marker' => ['map-marker-alt', 'fas'],
		'map-o' => ['map', 'far'],
		'meanpath' => ['font-awesome', 'fab'],
		'meh-o' => ['meh', 'far'],
		'minus-square-o' => ['minus-square', 'far'],
		'mobile-phone' => ['mobile-alt', 'fas'],
		'mobile' => ['mobile-alt', 'fas'],
		'money' => ['money-bill-alt', 'far'],
		'moon-o' => ['moon', 'far'],
		'mortar-board' => ['graduation-cap', 'fas'],
		'navicon' => ['bars', 'fas'],
		'newspaper-o' => ['newspaper', 'far'],
		'paper-plane-o' => ['paper-plane', 'far'],
		'paste' => ['clipboard', 'far'],
		'pause-circle-o' => ['pause-circle', 'far'],
		'pencil-square-o' => ['edit', 'far'],
		'pencil-square' => ['pen-square', 'fas'],
		'pencil' => ['pencil-alt', 'fas'],
		'photo' => ['image', 'far'],
		'picture-o' => ['image', 'far'],
		'pie-chart' => ['chart-pie', 'fas'],
		'play-circle-o' => ['play-circle', 'far'],
		'plus-square-o' => ['plus-square', 'far'],
		'question-circle-o' => ['question-circle', 'far'],
		'ra' => ['rebel', 'fab'],
		'refresh' => ['sync', 'fas'],
		'remove' => ['times', 'fas'],
		'reorder' => ['bars', 'fas'],
		'repeat' => ['redo', 'fas'],
		'resistance' => ['rebel', 'fab'],
		'rmb' => ['yen-sign', 'fas'],
		'rotate-left' => ['undo', 'fas'],
		'rotate-right' => ['redo', 'fas'],
		'rouble' => ['ruble-sign', 'fas'],
		'rub' => ['ruble-sign', 'fas'],
		'ruble' => ['ruble-sign', 'fas'],
		'rupee' => ['rupee-sign', 'fas'],
		's15' => ['bath', 'fas'],
		'scissors' => ['cut', 'fas'],
		'send-o' => ['paper-plane', 'far'],
		'send' => ['paper-plane', 'fas'],
		'share-square-o' => ['share-square', 'far'],
		'shekel' => ['shekel-sign', 'fas'],
		'sheqel' => ['shekel-sign', 'fas'],
		'shield' => ['shield-alt', 'fas'],
		'sign-in' => ['sign-in-alt', 'fas'],
		'sign-out' => ['sign-out-alt', 'fas'],
		'signing' => ['sign-language', 'fas'],
		'sliders' => ['sliders-h', 'fas'],
		'smile-o' => ['smile', 'far'],
		'snowflake-o' => ['snowflake', 'far'],
		'soccer-ball-o' => ['futbol', 'far'],
		'sort-alpha-asc' => ['sort-alpha-down', 'fas'],
		'sort-alpha-desc' => ['sort-alpha-up', 'fas'],
		'sort-amount-asc' => ['sort-amount-down', 'fas'],
		'sort-amount-desc' => ['sort-amount-up', 'fas'],
		'sort-asc' => ['sort-up', 'fas'],
		'sort-desc' => ['sort-down', 'fas'],
		'sort-numeric-asc' => ['sort-numeric-down', 'fas'],
		'sort-numeric-desc' => ['sort-numeric-up', 'fas'],
		'spoon' => ['utensil-spoon', 'fas'],
		'square-o' => ['square', 'far'],
		'star-half-empty' => ['star-half', 'far'],
		'star-half-full' => ['star-half', 'far'],
		'star-half-o' => ['star-half', 'far'],
		'star-o' => ['star', 'far'],
		'sticky-note-o' => ['sticky-note', 'far'],
		'stop-circle-o' => ['stop-circle', 'far'],
		'sun-o' => ['sun', 'far'],
		'support' => ['life-ring', 'far'],
		'tablet' => ['tablet-alt', 'fas'],
		'tachometer' => ['tachometer-alt', 'fas'],
		'television' => ['tv', 'fas'],
		'thermometer-0' => ['thermometer-empty', 'fas'],
		'thermometer-1' => ['thermometer-quarter', 'fas'],
		'thermometer-2' => ['thermometer-half', 'fas'],
		'thermometer-3' => ['thermometer-three-quarters', 'fas'],
		'thermometer-4' => ['thermometer-full', 'fas'],
		'thermometer' => ['thermometer-full', 'fas'],
		'thumb-tack' => ['thumbtack', 'fas'],
		'thumbs-o-down' => ['thumbs-down', 'far'],
		'thumbs-o-up' => ['thumbs-up', 'far'],
		'ticket' => ['ticket-alt', 'fas'],
		'times-circle-o' => ['times-circle', 'far'],
		'times-rectangle-o' => ['window-close', 'far'],
		'times-rectangle' => ['window-close', 'fas'],
		'toggle-down' => ['caret-square-down', 'far'],
		'toggle-left' => ['caret-square-left', 'far'],
		'toggle-right' => ['caret-square-right', 'far'],
		'toggle-up' => ['caret-square-up', 'far'],
		'trash-o' => ['trash-alt', 'far'],
		'trash' => ['trash-alt', 'fas'],
		'try' => ['lira-sign', 'fas'],
		'turkish-lira' => ['lira-sign', 'fas'],
		'unsorted' => ['sort', 'fas'],
		'usd' => ['dollar-sign', 'fas'],
		'user-circle-o' => ['user-circle', 'far'],
		'user-o' => ['user', 'far'],
		'vcard-o' => ['address-card', 'far'],
		'vcard' => ['address-card', 'fas'],
		'video-camera' => ['video', 'fas'],
		'vimeo' => ['vimeo-v', 'fab'],
		'volume-control-phone' => ['phone-volume', 'fas'],
		'warning' => ['exclamation-triangle', 'fas'],
		'wechat' => ['weixin', 'fab'],
		'wheelchair-alt' => ['accessible-icon', 'fab'],
		'window-close-o' => ['window-close', 'far'],
		'won' => ['won-sign', 'fas'],
		'y-combinator-square' => ['hacker-news', 'fab'],
		'yc-square' => ['hacker-news', 'fab'],
		'yc' => ['y-combinator', 'fab'],
		'yen' => ['yen-sign', 'fas'],
		'youtube-play' => ['youtube', 'fab'],
		'youtube-square' => ['youtube', 'fab'],
	];

	$brands = [
		'500px',
		'accessible-icon',
		'accusoft',
		'adn',
		'adversal',
		'affiliatetheme',
		'algolia',
		'amazon',
		'amazon-pay',
		'amilia',
		'android',
		'angellist',
		'angrycreative',
		'angular',
		'app-store',
		'app-store-ios',
		'apper',
		'apple',
		'apple-pay',
		'asymmetrik',
		'audible',
		'autoprefixer',
		'avianex',
		'aviato',
		'aws',
		'bandcamp',
		'behance',
		'behance-square',
		'bimobject',
		'bitbucket',
		'bitcoin',
		'bity',
		'black-tie',
		'blackberry',
		'blogger',
		'blogger-b',
		'bluetooth',
		'bluetooth-b',
		'btc',
		'buromobelexperte',
		'buysellads',
		'cc-amazon-pay',
		'cc-amex',
		'cc-apple-pay',
		'cc-diners-club',
		'cc-discover',
		'cc-jcb',
		'cc-mastercard',
		'cc-paypal',
		'cc-stripe',
		'cc-visa',
		'centercode',
		'chrome',
		'cloudscale',
		'cloudsmith',
		'cloudversify',
		'codepen',
		'codiepie',
		'connectdevelop',
		'contao',
		'cpanel',
		'creative-commons',
		'css3',
		'css3-alt',
		'cuttlefish',
		'd-and-d',
		'dashcube',
		'delicious',
		'deploydog',
		'deskpro',
		'deviantart',
		'digg',
		'digital-ocean',
		'discord',
		'discourse',
		'dochub',
		'docker',
		'draft2digital',
		'dribbble',
		'dribbble-square',
		'dropbox',
		'drupal',
		'dyalog',
		'earlybirds',
		'edge',
		'elementor',
		'ember',
		'empire',
		'envira',
		'erlang',
		'ethereum',
		'etsy',
		'expeditedssl',
		'facebook',
		'facebook-f',
		'facebook-messenger',
		'facebook-square',
		'firefox',
		'first-order',
		'firstdraft',
		'flickr',
		'flipboard',
		'fly',
		'font-awesome',
		'font-awesome-alt',
		'font-awesome-flag',
		'fonticons',
		'fonticons-fi',
		'fort-awesome',
		'fort-awesome-alt',
		'forumbee',
		'foursquare',
		'free-code-camp',
		'freebsd',
		'get-pocket',
		'gg',
		'gg-circle',
		'git',
		'git-square',
		'github',
		'github-alt',
		'github-square',
		'gitkraken',
		'gitlab',
		'gitter',
		'glide',
		'glide-g',
		'gofore',
		'goodreads',
		'goodreads-g',
		'google',
		'google-drive',
		'google-play',
		'google-plus',
		'google-plus-g',
		'google-plus-square',
		'google-wallet',
		'gratipay',
		'grav',
		'gripfire',
		'grunt',
		'gulp',
		'hacker-news',
		'hacker-news-square',
		'hips',
		'hire-a-helper',
		'hooli',
		'hotjar',
		'houzz',
		'html5',
		'hubspot',
		'imdb',
		'instagram',
		'internet-explorer',
		'ioxhost',
		'itunes',
		'itunes-note',
		'jenkins',
		'joget',
		'joomla',
		'js',
		'js-square',
		'jsfiddle',
		'keycdn',
		'kickstarter',
		'kickstarter-k',
		'korvue',
		'laravel',
		'lastfm',
		'lastfm-square',
		'leanpub',
		'less',
		'line',
		'linkedin',
		'linkedin-in',
		'linode',
		'linux',
		'lyft',
		'magento',
		'maxcdn',
		'medapps',
		'medium',
		'medium-m',
		'medrt',
		'meetup',
		'microsoft',
		'mix',
		'mixcloud',
		'mizuni',
		'modx',
		'monero',
		'napster',
		'nintendo-switch',
		'node',
		'node-js',
		'npm',
		'ns8',
		'nutritionix',
		'odnoklassniki',
		'odnoklassniki-square',
		'opencart',
		'openid',
		'opera',
		'optin-monster',
		'osi',
		'page4',
		'pagelines',
		'palfed',
		'patreon',
		'paypal',
		'periscope',
		'phabricator',
		'phoenix-framework',
		'php',
		'pied-piper',
		'pied-piper-alt',
		'pied-piper-pp',
		'pinterest',
		'pinterest-p',
		'pinterest-square',
		'playstation',
		'product-hunt',
		'pushed',
		'python',
		'qq',
		'quinscape',
		'quora',
		'ravelry',
		'react',
		'rebel',
		'red-river',
		'reddit',
		'reddit-alien',
		'reddit-square',
		'rendact',
		'renren',
		'replyd',
		'resolving',
		'rocketchat',
		'rockrms',
		'safari',
		'sass',
		'schlix',
		'scribd',
		'searchengin',
		'sellcast',
		'sellsy',
		'servicestack',
		'shirtsinbulk',
		'simplybuilt',
		'sistrix',
		'skyatlas',
		'skype',
		'slack',
		'slack-hash',
		'slideshare',
		'snapchat',
		'snapchat-ghost',
		'snapchat-square',
		'soundcloud',
		'speakap',
		'spotify',
		'stack-exchange',
		'stack-overflow',
		'staylinked',
		'steam',
		'steam-square',
		'steam-symbol',
		'sticker-mule',
		'strava',
		'stripe',
		'stripe-s',
		'studiovinari',
		'stumbleupon',
		'stumbleupon-circle',
		'superpowers',
		'supple',
		'telegram',
		'telegram-plane',
		'tencent-weibo',
		'themeisle',
		'trello',
		'tripadvisor',
		'tumblr',
		'tumblr-square',
		'twitch',
		'twitter',
		'twitter-square',
		'typo3',
		'uber',
		'uikit',
		'uniregistry',
		'untappd',
		'usb',
		'ussunnah',
		'vaadin',
		'viacoin',
		'viadeo',
		'viadeo-square',
		'viber',
		'vimeo',
		'vimeo-square',
		'vimeo-v',
		'vine',
		'vk',
		'vnv',
		'vuejs',
		'weibo',
		'weixin',
		'whatsapp',
		'whatsapp-square',
		'whmcs',
		'wikipedia-w',
		'windows',
		'wordpress',
		'wordpress-simple',
		'wpbeginner',
		'wpexplorer',
		'wpforms',
		'xbox',
		'xing',
		'xing-square',
		'y-combinator',
		'yahoo',
		'yandex',
		'yandex-international',
		'yelp',
		'yoast',
		'youtube',
		'youtube-square',
	];

	foreach ($classes as $index => $c) {
		if ($c === 'fa') {
			// FontAwesome 5 deprecated the use of fa prefix in favour of fas, far and fab
			unset($classes[$index]);
			continue;
		}

		if (preg_match_all('/^elgg-icon-(.+)/i', $c)) {
			// convert
			$base_icon = preg_replace('/^elgg-icon-(.+)/i', '$1', $c);

			if ($map_sprites) {
				if (strpos($base_icon, '-hover') !== false) {
					$base_icon = str_replace('-hover', '', $base_icon);
					$classes[] = 'elgg-state';
					$classes[] = 'elgg-state-notice';
				}

				$base_icon = elgg_extract($base_icon, $legacy_sprites, $base_icon);
			}
			
			// map solid/regular/light iconnames to correct classes
			if (preg_match('/.*-solid$/', $base_icon)) {
				$base_icon = preg_replace('/(.*)-solid$/', '$1', $base_icon);
				$classes[] = 'fas';
			} elseif (preg_match('/.*-regular$/', $base_icon)) {
				$base_icon = preg_replace('/(.*)-regular$/', '$1', $base_icon);
				$classes[] = 'far';
			} elseif (preg_match('/.*-light$/', $base_icon)) {
				// currently light is only available in FontAwesome 5 Pro
				$base_icon = preg_replace('/(.*)-light$/', '$1', $base_icon);
				$classes[] = 'fal';
			} else {
				if (array_key_exists($base_icon, $fa5)) {
					$classes[] = $fa5[$base_icon][1];
					$base_icon = $fa5[$base_icon][0];
				} else if (in_array($base_icon, $brands)) {
					$classes[] = 'fab';
				} else {
					$classes[] = 'fas';
				}
			}

			$classes[] = "fa-{$base_icon}";
		}
	}

	$classes = array_unique($classes);

	return elgg_trigger_plugin_hook('classes', 'icon', null, $classes);

}