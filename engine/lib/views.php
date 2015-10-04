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
	if (!isset($GLOBALS['_ELGG']->allowed_ajax_views)) {
		$GLOBALS['_ELGG']->allowed_ajax_views = array();
	}

	$GLOBALS['_ELGG']->allowed_ajax_views[$view] = true;

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
	if (isset($GLOBALS['_ELGG']->allowed_ajax_views[$view])) {
		unset($GLOBALS['_ELGG']->allowed_ajax_views[$view]);
	}
}

/**
 * Set an alternative base location for a view.
 *
 * Views are expected to be in plugin_name/views/.  This function can
 * be used to change that location.
 *
 * @note Internal: Core view locations are stored in $CONFIG->view_path.
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
 * Get the file path for all registered views and viewtypes
 *
 * @return string[] [viewtype][view_name] => file_path
 * @since 2.0
 */
function elgg_get_view_locations() {
	return _elgg_services()->views->getViewLocations();
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
 * The output of views can be intercepted by registering for the
 * view, $view_name plugin hook.
 *
 * @param string  $view     The name and location of the view to use
 * @param array   $vars     Variables to pass to the view.
 * @param boolean $bypass   This argument is ignored and will be removed eventually
 * @param boolean $ignored  This argument is ignored and will be removed eventually
 * @param string  $viewtype If set, forces the viewtype for the elgg_view call to be
 *                          this value (default: standard detection)
 *
 * @return string The parsed view
 */
function elgg_view($view, $vars = array(), $bypass = false, $ignored = false, $viewtype = '') {
	return _elgg_services()->views->renderView($view, $vars, $bypass, $viewtype);
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
 * @note Internal: View extensions are stored in
 * $CONFIG->views->extensions[$view][$priority] = $view_extension
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
	return elgg_trigger_plugin_hook('output', 'page', $vars, $output);
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
	$params['links']['apple-touch-icon'] = array(
		'rel' => 'apple-touch-icon',
		'href' => elgg_get_simplecache_url('favicon-128.png'),
	);

	// favicons
	$params['links']['icon-ico'] = array(
		'rel' => 'icon',
		'href' => elgg_get_simplecache_url('favicon.ico'),
	);
	$params['links']['icon-vector'] = array(
		'rel' => 'icon',
		'sizes' => '16x16 32x32 48x48 64x64 128x128',
		'type' => 'image/svg+xml',
		'href' => elgg_get_simplecache_url('favicon.svg'),
	);
	$params['links']['icon-16'] = array(
		'rel' => 'icon',
		'sizes' => '16x16',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-16.png'),
	);
	$params['links']['icon-32'] = array(
		'rel' => 'icon',
		'sizes' => '32x32',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-32.png'),
	);
	$params['links']['icon-64'] = array(
		'rel' => 'icon',
		'sizes' => '64x64',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-64.png'),
	);
	$params['links']['icon-128'] = array(
		'rel' => 'icon',
		'sizes' => '128x128',
		'type' => 'image/png',
		'href' => elgg_get_simplecache_url('favicon-128.png'),
	);

	// RSS feed link
	global $autofeed;
	if (isset($autofeed) && $autofeed == true) {
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

	return elgg_trigger_plugin_hook('output:after', 'layout', $params, $output);
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
 * elgg_view_menu() uses views in navigation/menu
 *
 * @param string $menu_name The name of the menu
 * @param array  $vars      An associative array of display options for the menu.
 *                          Options include:
 *                              sort_by => string or php callback
 *                                  string options: 'name', 'priority', 'title' (default),
 *                                  'register' (registration order) or a
 *                                  php callback (a compare function for usort)
 *                              handler: string the page handler to build action URLs
 *                              entity: \ElggEntity to use to build action URLs
 *                              class: string the class for the entire menu.
 *                              show_section_headers: bool show headers before menu sections.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_menu($menu_name, array $vars = array()) {
	global $CONFIG;

	$vars['name'] = $menu_name;

	$vars = elgg_trigger_plugin_hook('parameters', "menu:$menu_name", $vars, $vars);

	$sort_by = elgg_extract('sort_by', $vars, 'text');

	if (isset($CONFIG->menus[$menu_name])) {
		$menu = $CONFIG->menus[$menu_name];
	} else {
		$menu = array();
	}

	// Give plugins a chance to add menu items just before creation.
	// This supports dynamic menus (example: user_hover).
	$menu = elgg_trigger_plugin_hook('register', "menu:$menu_name", $vars, $menu);

	$builder = new \ElggMenuBuilder($menu);
	$vars['menu'] = $builder->getMenu($sort_by);
	$vars['selected_item'] = $builder->getSelected();

	// Let plugins modify the menu
	$vars['menu'] = elgg_trigger_plugin_hook('prepare', "menu:$menu_name", $vars, $vars['menu']);

	if (elgg_view_exists("navigation/menu/$menu_name")) {
		return elgg_view("navigation/menu/$menu_name", $vars);
	} else {
		return elgg_view("navigation/menu/default", $vars);
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

	global $autofeed;
	$autofeed = true;

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
	global $autofeed;
	$autofeed = true;

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

	if ($vars['list_type'] != 'list') {
		return elgg_view('page/components/gallery', $vars);
	} else {
		return elgg_view('page/components/list', $vars);
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
	return elgg_view('output/friendlytime', array('time' => $time));
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
	global $CONFIG;

	$defaults = array(
		'action' => $CONFIG->wwwroot . "action/$action",
		'body' => elgg_view("forms/$action", $body_vars)
	);

	$form_class = 'elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $action);

	// append elgg-form class to any class options set
	if (isset($form_vars['class'])) {
		$form_vars['class'] = $form_vars['class'] . " $form_class";
	} else {
		$form_vars['class'] = $form_class;
	}

	$form_vars = array_merge($defaults, $form_vars);
	$form_vars['action_name'] = $action;

	return elgg_view('input/form', $form_vars);
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
 * Add the rss link to the extras when if needed
 *
 * @return void
 * @access private
 */
function elgg_views_add_rss_link() {
	global $autofeed;
	if (isset($autofeed) && $autofeed == true) {
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
 * Initialize viewtypes on system boot event
 * This ensures simplecache is cleared during upgrades. See #2252
 *
 * @return void
 * @access private
 * @elgg_event_handler boot system
 */
function elgg_views_boot() {
	if (!elgg_get_config('system_cache_loaded')) {
		_elgg_services()->views->registerPluginViews(realpath(__DIR__ . '/../../'));
	}
	
	global $CONFIG;

	if (!elgg_get_config('system_cache_loaded')) {
		_elgg_services()->views->registerPluginViews(realpath(__DIR__ . '/../../'));
	}

	// on every page
	elgg_register_js('elgg.require_config', elgg_get_simplecache_url('elgg/require_config.js'), 'head');
	elgg_load_js('elgg.require_config');

	elgg_register_js('require', elgg_get_simplecache_url('require.js'), 'head');
	elgg_load_js('require');

	elgg_register_js('jquery', elgg_get_simplecache_url('jquery.js'), 'head');
	elgg_load_js('jquery');

	elgg_register_js('jquery-ui', elgg_get_simplecache_url('jquery-ui.js'), 'head');
	elgg_load_js('jquery-ui');

	elgg_register_js('elgg', elgg_get_simplecache_url('elgg.js'), 'head');
	elgg_load_js('elgg');
	
	elgg_register_css('font-awesome', elgg_get_simplecache_url('font-awesome/css/font-awesome.css'));
	elgg_load_css('font-awesome');

	elgg_register_css('elgg', elgg_get_simplecache_url('elgg.css'));
	elgg_load_css('elgg');

	// optional stuff
	elgg_register_js('lightbox', elgg_get_simplecache_url('lightbox.js'));
	elgg_register_css('lightbox', elgg_get_simplecache_url('lightbox/elgg-colorbox-theme/colorbox.css'));

	elgg_register_js('elgg.autocomplete', elgg_get_simplecache_url('elgg/ui.autocomplete.js'));
	elgg_register_js('jquery.ui.autocomplete.html', elgg_get_simplecache_url('jquery.ui.autocomplete.html.js'));
	elgg_define_js('jquery.ui.autocomplete.html', [
		'deps' => ['jquery-ui'],
	]);

	elgg_register_js('elgg.friendspicker', elgg_get_simplecache_url('elgg/ui.friends_picker.js'));
	elgg_register_js('elgg.avatar_cropper', elgg_get_simplecache_url('elgg/ui.avatar_cropper.js'));
	elgg_register_js('elgg.ui.river', elgg_get_simplecache_url('elgg/ui.river.js'));

	elgg_register_js('jquery.imgareaselect', elgg_get_simplecache_url('jquery.imgareaselect.js'));
	elgg_register_css('jquery.imgareaselect', elgg_get_simplecache_url('jquery.imgareaselect.css'));

	elgg_register_ajax_view('languages.js');
	elgg_require_js('elgg/echo');

	elgg_register_plugin_hook_handler('simplecache:generate', 'js', '_elgg_views_amd');
	elgg_register_plugin_hook_handler('simplecache:generate', 'css', '_elgg_views_minify');
	elgg_register_plugin_hook_handler('simplecache:generate', 'js', '_elgg_views_minify');

	elgg_register_plugin_hook_handler('output:before', 'layout', 'elgg_views_add_rss_link');
	elgg_register_plugin_hook_handler('output:before', 'page', '_elgg_views_send_header_x_frame_options');

	// @todo the cache is loaded in load_plugins() but we need to know viewtypes earlier
	$view_path = $GLOBALS['_ELGG']->view_path;
	$viewtype_dirs = scandir($view_path);
	foreach ($viewtype_dirs as $viewtype) {
		if (_elgg_is_valid_viewtype($viewtype) && is_dir($view_path . $viewtype)) {
			elgg_register_viewtype($viewtype);
		}
	}

	// Declared views. Unlike plugins, Elgg's root views/ is never scanned, so Elgg cannot override
	// these view traditional view files.
	$file = dirname(dirname(__DIR__)) . '/views.php';
	if (is_file($file)) {
		$spec = (include $file);
		if (is_array($spec)) {
			_elgg_services()->views->mergeViewsSpec($spec);
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
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('boot', 'system', 'elgg_views_boot');
};
