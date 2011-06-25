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
 * View types are set by passing $_REQUEST['view'].  The view type
 * 'default' is a standard HTML view.  Types can be defined on the fly
 * and you can get the current view type with {@link get_current_view()}.
 *
 * @internal Plugin views are autoregistered before their init functions
 * are called, so the init order doesn't affect views.
 *
 * @internal The file that determines the output of the view is the last
 * registered by {@link elgg_set_view_location()}.
 *
 * @package Elgg.Core
 * @subpackage Views
 * @link http://docs.elgg.org/Views
 */

/**
 * The view type override.
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
 * @link http://docs.elgg.org/Views/Viewtype
 * @example views/viewtype.php
 */
function elgg_set_viewtype($viewtype = "") {
	global $CURRENT_SYSTEM_VIEWTYPE;

	$CURRENT_SYSTEM_VIEWTYPE = $viewtype;

	return true;
}

/**
 * Return the current view type.
 *
 * View types are automatically detected and can be set with $_REQUEST['view']
 * or {@link elgg_set_viewtype()}.
 *
 * @internal View type is determined in this order:
 *  - $CURRENT_SYSTEM_VIEWTYPE Any overrides by {@link elgg_set_viewtype()}
 *  - $CONFIG->view  The default view as saved in the DB.
 *  - $_SESSION['view']
 *
 * @return string The view.
 * @see elgg_set_viewtype()
 * @link http://docs.elgg.org/Views
 * @todo This function's sessions stuff needs rewritten, removed, or explained.
 */
function elgg_get_viewtype() {
	global $CURRENT_SYSTEM_VIEWTYPE, $CONFIG;

	if ($CURRENT_SYSTEM_VIEWTYPE != "") {
		return $CURRENT_SYSTEM_VIEWTYPE;
	}

	$viewtype = get_input('view', NULL);
	if ($viewtype) {
		return $viewtype;
	}

	if (isset($CONFIG->view) && !empty($CONFIG->view)) {
		return $CONFIG->view;
	}

	return 'default';
}

/**
 * Register a view type as valid.
 *
 * @param string $view_type The view type to register
 * @return bool
 */
function elgg_register_viewtype($view_type) {
	global $CONFIG;

	if (!isset($CONFIG->view_types) || !is_array($CONFIG->view_types)) {
		$CONFIG->view_types = array();
	}

	if (!in_array($view_type, $CONFIG->view_types)) {
		$CONFIG->view_types[] = $view_type;
	}

	return true;
}

/**
 * Checks if $view_type is valid on this installation.
 *
 * @param string $view_type View type
 *
 * @return bool
 * @since 1.7.2
 */
function elgg_is_valid_view_type($view_type) {
	global $CONFIG;

	if (!isset($CONFIG->view_types) || !is_array($CONFIG->view_types)) {
		return FALSE;
	}

	return in_array($view_type, $CONFIG->view_types);
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
 * @example views/viewtype_fallback.php Fallback from mobile to default.
 */
function elgg_register_viewtype_fallback($viewtype) {
	global $CONFIG;

	if (!isset($CONFIG->viewtype)) {
		$CONFIG->viewtype = new stdClass;
	}

	if (!isset($CONFIG->viewtype->fallback)) {
		$CONFIG->viewtype->fallback = array();
	}

	$CONFIG->viewtype->fallback[] = $viewtype;
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
	global $CONFIG;

	if (isset($CONFIG->viewtype) && isset($CONFIG->viewtype->fallback)) {
		return in_array($viewtype, $CONFIG->viewtype->fallback);
	}

	return FALSE;
}


/**
 * Returns the file location for a view.
 *
 * @warning This doesn't check if the file exists, but only
 * constructs (or extracts) the path and returns it.
 *
 * @param string $view     The view.
 * @param string $viewtype The viewtype
 *
 * @return string
 */
function elgg_get_view_location($view, $viewtype = '') {
	global $CONFIG;

	if (empty($viewtype)) {
		$viewtype = elgg_get_viewtype();
	}

	if (!isset($CONFIG->views->locations[$viewtype][$view])) {
		if (!isset($CONFIG->viewpath)) {
			return dirname(dirname(dirname(__FILE__))) . "/views/";
		} else {
			return $CONFIG->viewpath;
		}
	} else {
		return $CONFIG->views->locations[$viewtype][$view];
	}

	return false;
}

/**
 * Set an alternative base location for a view.
 *
 * Views are expected to be in plugin_name/views/.  This function can
 * be used to change that location.
 *
 * @internal Core view locations are stored in $CONFIG->viewpath.
 *
 * @tip This is useful to optionally register views in a plugin.
 *
 * @param string $view     The name of the view
 * @param string $location The base location path
 * @param string $viewtype The view type
 *
 * @return void
 */
function elgg_set_view_location($view, $location, $viewtype = '') {
	global $CONFIG;

	if (empty($viewtype)) {
		$viewtype = 'default';
	}

	if (!isset($CONFIG->views)) {
		$CONFIG->views = new stdClass;
	}

	if (!isset($CONFIG->views->locations)) {
		$CONFIG->views->locations = array($viewtype => array($view => $location));

	} else if (!isset($CONFIG->views->locations[$viewtype])) {
		$CONFIG->views->locations[$viewtype] = array($view => $location);

	} else {
		$CONFIG->views->locations[$viewtype][$view] = $location;
	}
}

/**
 * Returns whether the specified view exists
 *
 * @note If $recurse is strue, also checks if a view exists only as an extension.
 *
 * @param string $view     The view name
 * @param string $viewtype If set, forces the viewtype
 * @param bool   $recurse  If false, do not check extensions
 *
 * @return bool
 */
function elgg_view_exists($view, $viewtype = '', $recurse = true) {
	global $CONFIG;

	// Detect view type
	if (empty($viewtype)) {
		$viewtype = elgg_get_viewtype();
	}

	if (!isset($CONFIG->views->locations[$viewtype][$view])) {
		if (!isset($CONFIG->viewpath)) {
			$location = dirname(dirname(dirname(__FILE__))) . "/views/";
		} else {
			$location = $CONFIG->viewpath;
		}
	} else {
		$location = $CONFIG->views->locations[$viewtype][$view];
	}

	if (file_exists($location . "{$viewtype}/{$view}.php")) {
		return true;
	}

	// If we got here then check whether this exists as an extension
	// We optionally recursively check whether the extended view exists also for the viewtype
	if ($recurse && isset($CONFIG->views->extensions[$view])) {
		foreach ($CONFIG->views->extensions[$view] as $view_extension) {
			// do not recursively check to stay away from infinite loops
			if (elgg_view_exists($view_extension, $viewtype, false)) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Return a parsed view.
 *
 * Views are rendered by a template handler and returned as strings.
 *
 * Views are called with a special $vars variable set,
 * which includes any variables passed as the second parameter.
 * For backward compatbility, the following variables are also set but we
 * recommend that you do not use them:
 *  - $vars['config'] The $CONFIG global. (Use {@link elgg_get_config()} instead).
 *  - $vars['url'] The site URL. (use {@link elgg_get_site_url()} instead).
 *  - $vars['user'] The logged in user. (use {@link elgg_get_logged_in_user_entity()} instead).
 *
 * Custom template handlers can be set with {@link set_template_handler()}.
 *
 * The output of views can be intercepted by registering for the
 * view, $view_name plugin hook.
 *
 * @warning Any variables in $_SESSION will override passed vars
 * upon name collision.  See {@trac #2124}.
 *
 * @param string  $view     The name and location of the view to use
 * @param array   $vars     Variables to pass to the view.
 * @param boolean $bypass   If set to true, elgg_view will bypass any specified
 *                          alternative template handler; by default, it will
 *                          hand off to this if requested (see set_template_handler)
 * @param boolean $debug    If set to true, the viewer will complain if it can't find a view
 * @param string  $viewtype If set, forces the viewtype for the elgg_view call to be
 *                          this value (default: standard detection)
 *
 * @return string The parsed view
 * @see set_template_handler()
 * @example views/elgg_view.php
 * @link http://docs.elgg.org/View
 * @todo $debug isn't used.
 * @todo $usercache is redundant.
 */
function elgg_view($view, $vars = array(), $bypass = false, $debug = false, $viewtype = '') {
	global $CONFIG;
	static $usercache;

	$view = (string)$view;

	// basic checking for bad paths
	if (strpos($view, '..') !== false) {
		return false;
	}

	$view_orig = $view;

	// Trigger the pagesetup event
	if (!isset($CONFIG->pagesetupdone)) {
		elgg_trigger_event('pagesetup', 'system');
		$CONFIG->pagesetupdone = true;
	}

	if (!is_array($usercache)) {
		$usercache = array();
	}

	if (!is_array($vars)) {
		elgg_log("Vars in views must be an array: $view", 'ERROR');
		$vars = array();
	}

	if (empty($vars)) {
		$vars = array();
	}

	// @warning - plugin authors: do not expect user, config, and url to be
	// set by elgg_view() in the future. Instead, use elgg_get_logged_in_user_entity(),
	// elgg_get_config(), and elgg_get_site_url() in your views.
	if (!isset($vars['user'])) {
		$vars['user'] = elgg_get_logged_in_user_entity();
	}
	if (!isset($vars['config'])) {
		$vars['config'] = $CONFIG;
	}
	if (!isset($vars['url'])) {
		$vars['url'] = elgg_get_site_url();
	}

	// full_view is the new preferred key for full view on entities @see elgg_view_entity()
	// check if full_view is set because that means we've already rewritten it and this is
	// coming from another view passing $vars directly.
	if (isset($vars['full']) && !isset($vars['full_view'])) {
		elgg_deprecated_notice("Use \$vars['full_view'] instead of \$vars['full']", 1.8, 2);
		$vars['full_view'] = $vars['full'];
	}
	if (isset($vars['full_view'])) {
		$vars['full'] = $vars['full_view'];
	}

	// internalname => name (1.8)
	if (isset($vars['internalname']) && !isset($vars['name'])) {
		elgg_deprecated_notice('You should pass $vars[\'name\'] now instead of $vars[\'internalname\']', 1.8, 2);
		$vars['name'] = $vars['internalname'];
		$test=false;
	} elseif (isset($vars['name'])) {
		$vars['internalname'] = $vars['name'];
	}

	// internalid => id (1.8)
	if (isset($vars['internalid']) && !isset($vars['name'])) {
		elgg_deprecated_notice('You should pass $vars[\'id\'] now instead of $vars[\'internalid\']', 1.8, 2);
		$vars['id'] = $vars['internalid'];
	} elseif (isset($vars['id'])) {
		$vars['internalid'] = $vars['id'];
	}

	// If it's been requested, pass off to a template handler instead
	if ($bypass == false && isset($CONFIG->template_handler) && !empty($CONFIG->template_handler)) {
		$template_handler = $CONFIG->template_handler;
		if (is_callable($template_handler)) {
			return call_user_func($template_handler, $view, $vars);
		}
	}

	// Get the current viewtype
	if (empty($viewtype)) {
		$viewtype = elgg_get_viewtype();
	}

	// Viewtypes can only be alphanumeric
	if (preg_match('[\W]', $viewtype)) {
		return '';
	}

	// Set up any extensions to the requested view
	if (isset($CONFIG->views->extensions[$view])) {
		$viewlist = $CONFIG->views->extensions[$view];
	} else {
		$viewlist = array(500 => $view);
	}

	// Start the output buffer, find the requested view file, and execute it
	ob_start();

	foreach ($viewlist as $priority => $view) {
		$view_location = elgg_get_view_location($view, $viewtype);
		$view_file = "$view_location$viewtype/$view.php";

		$default_location = elgg_get_view_location($view, 'default');
		$default_view_file = "{$default_location}default/$view.php";

		// try to include view
		if (!file_exists($view_file) || !include($view_file)) {
			// requested view does not exist
			$error = "$viewtype/$view view does not exist.";

			// attempt to load default view
			if ($viewtype != 'default' && elgg_does_viewtype_fallback($viewtype)) {
				if (file_exists($default_view_file) && include($default_view_file)) {
					// default view found
					$error .= " Using default/$view instead.";
				} else {
					// no view found at all
					$error = "Neither $viewtype/$view nor default/$view view exists.";
				}
			}

			// log warning
			elgg_log($error, 'NOTICE');
		}
	}

	// Save the output buffer into the $content variable
	$content = ob_get_clean();

	// Plugin hook
	$params = array('view' => $view_orig, 'vars' => $vars, 'viewtype' => $viewtype);
	$content = elgg_trigger_plugin_hook('view', $view_orig, $params, $content);

	// backward compatibility with less granular hook will be gone in 2.0
	$content_tmp = elgg_trigger_plugin_hook('display', 'view', $params, $content);

	if ($content_tmp != $content) {
		$content = $content_tmp;
		elgg_deprecated_notice('The display:view plugin hook is deprecated by view:view_name', 1.8);
	}

	return $content;
}

/**
 * Extends a view with another view.
 *
 * The output of any view can be prepended or appended to any other view.
 *
 * The default action is to append a view.  If the priority is less than 500,
 * the output of the extended view will be appended to the original view.
 *
 * Priority can be specified and affects the order in which extensions
 * are appended or prepended.
 *
 * @internal View extensions are stored in
 * $CONFIG->views->extensions[$view][$priority] = $view_extension
 *
 * @param string $view           The view to extend.
 * @param string $view_extension This view is added to $view
 * @param int    $priority       The priority, from 0 to 1000,
 *                               to add at (lowest numbers displayed first)
 * @param string $viewtype       Not used
 *
 * @return void
 * @since 1.7.0
 * @link http://docs.elgg.org/Views/Ejxtend
 * @example views/extend.php
 */
function elgg_extend_view($view, $view_extension, $priority = 501, $viewtype = '') {
	global $CONFIG;

	if (!isset($CONFIG->views)) {
		$CONFIG->views = new stdClass;
	}

	if (!isset($CONFIG->views->extensions)) {
		$CONFIG->views->extensions = array();
	}

	if (!isset($CONFIG->views->extensions[$view])) {
		$CONFIG->views->extensions[$view][500] = "{$view}";
	}

	while (isset($CONFIG->views->extensions[$view][$priority])) {
		$priority++;
	}

	$CONFIG->views->extensions[$view][$priority] = "{$view_extension}";
	ksort($CONFIG->views->extensions[$view]);
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
	global $CONFIG;

	if (!isset($CONFIG->views)) {
		return FALSE;
	}

	if (!isset($CONFIG->views->extensions)) {
		return FALSE;
	}

	if (!isset($CONFIG->views->extensions[$view])) {
		return FALSE;
	}

	$priority = array_search($view_extension, $CONFIG->views->extensions[$view]);
	if ($priority === FALSE) {
		return FALSE;
	}

	unset($CONFIG->views->extensions[$view][$priority]);

	return TRUE;
}

/**
 * Assembles and outputs a full page.
 *
 * A "page" in Elgg is determined by the current view type and
 * can be HTML for a browser, RSS for a feed reader, or
 * Javascript, PHP and a number of other formats.
 *
 * @param string $title      Title
 * @param string $body       Body
 * @param string $page_shell Optional page shell to use. See page/shells view directory
 * @param array  $vars       Optional vars array to pass to the page
 *                           shell. Automatically adds title, body, and sysmessages
 *
 * @return string The contents of the page
 * @since  1.8
 */
function elgg_view_page($title, $body, $page_shell = 'default', $vars = array()) {

	$messages = null;
	if (count_messages()) {
		// get messages - try for errors first
		$messages = system_messages(NULL, "error");
		if (count($messages["error"]) == 0) {
			// no errors so grab rest of messages
			$messages = system_messages(null, "");
		} else {
			// we have errors - clear out remaining messages
			system_messages(null, "");
		}
	}

	$vars['title'] = $title;
	$vars['body'] = $body;
	$vars['sysmessages'] = $messages;
	
	// check for deprecated view
	if ($page_shell == 'default' && elgg_view_exists('pageshells/pageshell')) {
		elgg_deprecated_notice("pageshells/pageshell is deprecated by page/$page_shell", 1.8);
		global $CONFIG;
		
		$vars['config'] = $CONFIG;
		$output = elgg_view('pageshells/pageshell', $vars);
	} else {
		$output = elgg_view("page/$page_shell", $vars);
	}

	$vars['page_shell'] = $page_shell;

	// Allow plugins to mod output
	return elgg_trigger_plugin_hook('output', 'page', $vars, $output);
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

	if (is_string($vars) || $vars === null) {
		elgg_deprecated_notice("The use of unlimited optional string arguments in elgg_view_layout() was deprecated in favor of an options array", 1.8);
		$arg = 1;
		$param_array = array();
		while ($arg < func_num_args()) {
			$param_array['area' . $arg] = func_get_arg($arg);
			$arg++;
		}
	} else {
		$param_array = $vars;
	}

	// check deprecated location
	if (elgg_view_exists("canvas/layouts/$layout_name")) {
		elgg_deprecated_notice("canvas/layouts/$layout_name is deprecated by page/layouts/$layout_name", 1.8);
		return elgg_view("canvas/layouts/$layout_name", $param_array);
	} elseif (elgg_view_exists("page/layouts/$layout_name")) {
		return elgg_view("page/layouts/$layout_name", $param_array);
	} else {
		return elgg_view("page/layouts/default", $param_array);
	}
}

/**
 * Render a menu
 *
 * @see elgg_register_menu_item() for documentation on adding menu items and
 * navigation.php for information on the different menus available.
 *
 * This function triggers a 'register', 'menu:<menu name>' plugin hook that enables
 * plugins to add menu items just before a menu is rendered. This is used by
 * context-sensitive menus (menus that are specific to a particular entity such
 * as the user hover menu). Using elgg_register_menu_item() in response to the hook
 * can cause incorrect links to show up. See the blog plugin's blog_owner_block_menu()
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
 *                                  string options: 'name', 'priority', 'title' (default), 'register' (registration order)
 *                                  php callback: a compare function for usort
 *                              handler: string the page handler to build action URLs
 *                              entity: ElggEntity to use to build action URLs
 *                              class: string the class for the entire menu.
 *                              show_section_headers: bool show headers before menu sections.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_menu($menu_name, array $vars = array()) {
	global $CONFIG;

	$vars['name'] = $menu_name;

	$sort_by = elgg_extract('sort_by', $vars, 'text');

	$menu = $CONFIG->menus[$menu_name];

	// Give plugins a chance to add menu items just before creation.
	// This supports context sensitive menus (ex. user_hover).
	$menu = elgg_trigger_plugin_hook('register', "menu:$menu_name", $vars, $menu);

	$builder = new ElggMenuBuilder($menu);
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
 * Returns a string of a rendered entity.
 *
 * Entity views are either determined by setting the view property on the entity
 * or by having a view named after the entity $type/$subtype.  Entities that have
 * neither a view property nor a defined $type/$subtype view will fall back to
 * using the $type/default view.
 *
 * The entity view is called with the following in $vars:
 *  - ElggEntity 'entity' The entity being viewed
 *
 * Other common view $vars paramters:
 *  - bool 'full_view' Whether to show a full or condensed view.
 *
 * @tip This function can automatically appends annotations to entities if in full
 * view and a handler is registered for the entity:annotate.  See {@trac 964} and
 * {@link elgg_view_entity_annotations()}.
 *
 * @param ElggEntity $entity The entity to display
 * @param array      $vars   Array of variables to pass to the entity view.
 *                           In Elgg 1.7 and earlier it was the boolean $full_view
 * @param boolean    $bypass If false, will not pass to a custom template handler.
 *                           {@see set_template_handler()}
 * @param boolean    $debug  Complain if views are missing
 *
 * @return string HTML to display or false
 * @link http://docs.elgg.org/Views/Entity
 * @link http://docs.elgg.org/Entities
 * @todo The annotation hook might be better as a generic plugin hook to append content.
 */
function elgg_view_entity(ElggEntity $entity, $vars = array(), $bypass = true, $debug = false) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof ElggEntity)) {
		return false;
	}

	global $autofeed;
	$autofeed = true;

	$defaults = array(
		'full_view' => false,
	);

	if (is_array($vars)) {
		$vars = array_merge($defaults, $vars);
	} else {
		elgg_deprecated_notice("Update your use of elgg_view_entity()", 1.8);
		$vars = array(
			'full_view' => $vars,
		);
	}

	$vars['entity'] = $entity;


	// if this entity has a view defined, use it
	$view = $entity->view;
	if (is_string($view)) {
		return elgg_view($view, $vars, $bypass, $debug);
	}

	$entity_type = $entity->getType();

	$subtype = $entity->getSubtype();
	if (empty($subtype)) {
		$subtype = 'default';
	}

	$contents = '';
	if (elgg_view_exists("$entity_type/$subtype")) {
		$contents = elgg_view("$entity_type/$subtype", $vars, $bypass, $debug);
	}
	if (empty($contents)) {
		$contents = elgg_view("$entity_type/default", $vars, $bypass, $debug);
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
 * @param ElggEntity $entity The entity to display
 * @param string     $size   The size: tiny, small, medium, large
 * @param array      $vars   An array of variables to pass to the view
 *
 * @return string HTML to display or false
 */
function elgg_view_entity_icon(ElggEntity $entity, $size = 'medium', $vars = array()) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof ElggEntity)) {
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
 *  - ElggEntity 'annotation' The annotation being viewed.
 *
 * @param ElggAnnotation $annotation The annotation to display
 * @param array          $vars       Variable array for view.
 * @param bool           $bypass     If false, will not pass to a custom
 *                                   template handler. {@see set_template_handler()}
 * @param bool           $debug      Complain if views are missing
 *
 * @return string/false Rendered annotation
 */
function elgg_view_annotation(ElggAnnotation $annotation, array $vars = array(), $bypass = true, $debug = false) {
	global $autofeed;
	$autofeed = true;

	$defaults = array(
		'full_view' => true,
	);

	$vars = array_merge($defaults, $vars);
	$vars['annotation'] = $annotation;

	// @todo setting the view on an annotation is not advertised anywhere
	// do we want to keep this?
	$view = $annotation->view;
	if (is_string($view)) {
		return elgg_view($view, $vars, $bypass, $debug);
	}

	// @todo would be better to always make sure name is initialized properly
	$name = $annotation->name;
	$intname = (int) $name;
	if ("{$intname}" == "{$name}") {
		$name = get_metastring($intname);
	}
	if (empty($name)) {
		return false;
	}

	if (elgg_view_exists("annotation/$name")) {
		return elgg_view("annotation/$name", $vars, $bypass, $debug);
	} else {
		return elgg_view("annotation/default", $vars, $bypass, $debug);
	}
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
 *		'count'            The total number of entities across all pages
 *		'offset'           The current indexing offset
 *		'limit'            The number of entities to display per page
 *		'full_view'        Display the full view of the entities?
 *		'list_class'       CSS class applied to the list
 *		'item_class'       CSS class applied to the list items
 *		'pagination'       Display pagination?
 *		'list_type'        List type: 'list' (default), 'gallery'
 *		'list_type_toggle' Display the list type toggle?
 *
 * @return string The rendered list of entities
 * @access private
 */
function elgg_view_entity_list($entities, $vars = array(), $offset = 0, $limit = 10, $full_view = true,
$list_type_toggle = true, $pagination = true) {

	if (!is_int($offset)) {
		$offset = (int)get_input('offset', 0);
	}

	// list type can be passed as request parameter
	$list_type = get_input('list_type', 'list');
	if (get_input('listtype')) {
		elgg_deprecated_notice("'listtype' has been deprecated by 'list_type' for lists", 1.8);
		$list_type = get_input('listtype');
	}

	if (is_array($vars)) {
		// new function
		$defaults = array(
			'items' => $entities,
			'list_class' => 'elgg-list-entity',
			'full_view' => true,
			'pagination' => true,
			'list_type' => $list_type,
			'list_type_toggle' => false,
			'offset' => $offset,
		);

		$vars = array_merge($defaults, $vars);

	} else {
		// old function parameters
		elgg_deprecated_notice("Please update your use of elgg_view_entity_list()", 1.8);

		$vars = array(
			'items' => $entities,
			'count' => (int) $vars, // the old count parameter
			'offset' => $offset,
			'limit' => (int) $limit,
			'full_view' => $full_view,
			'pagination' => $pagination,
			'list_type' => $list_type,
			'list_type_toggle' => $list_type_toggle,
			'list_class' => 'elgg-list-entity',
		);
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
 *		'count'      The total number of annotations across all pages
 *		'offset'     The current indexing offset
 *		'limit'      The number of annotations to display per page
 *		'full_view'  Display the full view of the annotation?
 *		'list_class' CSS Class applied to the list
 *		'offset_key' The url parameter key used for offset
 *
 * @return string The list of annotations
 * @access private
 */
function elgg_view_annotation_list($annotations, array $vars = array()) {
	$defaults = array(
		'items' => $annotations,
		'list_class' => 'elgg-annotation-list',
		'full_view' => true,
		'offset_key' => 'annoff',
	);

	$vars = array_merge($defaults, $vars);

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
 * @param ElggEntity $entity    Entity
 * @param bool       $full_view Display full view?
 *
 * @return mixed string or false on failure
 * @todo Change the hook name.
 */
function elgg_view_entity_annotations(ElggEntity $entity, $full_view = true) {
	if (!$entity) {
		return false;
	}

	if (!($entity instanceof ElggEntity)) {
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
 * Returns a rendered title.
 *
 * This is a shortcut for {@elgg_view page/elements/title}.
 *
 * @param string $title   The page title
 * @param string $submenu Should a submenu be displayed? (deprecated)
 *
 * @return string The HTML (etc)
 */
function elgg_view_title($title, $submenu = false) {
	if ($submenu !== false) {
		elgg_deprecated_notice('setting $submenu in elgg_view_title() is deprecated', 1.8);
	}

	return elgg_view('page/elements/title', array('title' => $title, 'submenu' => $submenu));
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
 * @param ElggEntity $entity      The entity to view comments of
 * @param bool       $add_comment Include a form to add comments?
 * @param array      $vars        Variables to pass to comment view
 *
 * @return string|false Rendered comments or false on failure
 * @link http://docs.elgg.org/Entities/Comments
 * @link http://docs.elgg.org/Annotations/Comments
 */
function elgg_view_comments($entity, $add_comment = true, array $vars = array()) {
	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");

	$output = elgg_trigger_plugin_hook('comments', $entity->getType(), $vars, false);
	if ($output) {
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
 * @param string $vars  Additional parameters for the view
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
 * @param string $vars  Additional parameters for the module
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_module($type, $title, $body, $vars = array()) {
	$vars['class'] .= " elgg-module-$type"; //@todo this will probably cause errors?
	$vars['title'] = $title;
	$vars['body'] = $body;
	return elgg_view('page/components/module', $vars);
}

/**
 * Renders a human-readable representation of a river item
 *
 * @param ElggRiverItem $item A river item object
 * @param array         $vars An array of variables for the view
 *
 * @return string|false Depending on success
 */
function elgg_view_river_item($item, array $vars = array()) {
	// checking default viewtype since some viewtypes do not have unique views per item (rss)
	if (!$item || !$item->getView() || !elgg_view_exists($item->getView(), 'default')) {
		return '';
	}

	$subject = $item->getSubjectEntity();
	$object = $item->getObjectEntity();
	if (!$subject || !$object) {
		// subject is disabled or subject/object deleted
		return '';
	}

	$vars['item'] = $item;

	return elgg_view($item->getView(), $vars);
}

/**
 * Convenience function for generating a form from a view in a standard location.
 *
 * This function assumes that the body of the form is located at "forms/$action" and
 * sets the action by default to "action/$action".  Automatically wraps the forms/$action
 * view with a <form> tag and inserts the anti-csrf security tokens.
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
		'body' => elgg_view("forms/$action", $body_vars),
	);

	return elgg_view('input/form', array_merge($defaults, $form_vars));
}

/**
 * View an item in a list
 *
 * @param object $item ElggEntity or ElggAnnotation
 * @param array  $vars Additional parameters for the rendering
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_view_list_item($item, array $vars = array()) {

	switch ($item->getType()) {
		case 'user':
		case 'object':
		case 'group':
		case 'site':
			return elgg_view_entity($item, $vars);
		case 'annotation':
			return elgg_view_annotation($item, $vars);
		case 'river':
			return elgg_view_river_item($item, $vars);
		default:
			return false;
			break;
	}
}

/**
 * View one of the elgg sprite icons
 * 
 * Shorthand for <span class="elgg-icon elgg-icon-$name"></span>
 * 
 * @param string $name  The specific icon to display
 * @param bool   $float Whether to float the icon
 * 
 * @return string The html for displaying an icon
 */
function elgg_view_icon($name, $float = false) {
	if ($float) {
		$float = 'float';
	}
	return "<span class=\"elgg-icon elgg-icon-$name $float\"></span>";
}

/**
 * Registers a function to handle templates.
 *
 * Alternative template handlers can be registered to handle
 * all output functions.  By default, {@link elgg_view()} will
 * simply include the view file.  If an alternate template handler
 * is registered, the view name and passed $vars will be passed to the
 * registered function, which is then responsible for generating and returning
 * output.
 *
 * Template handlers need to accept two arguments: string $view_name and array
 * $vars.
 *
 * @warning This is experimental.
 *
 * @param string $function_name The name of the function to pass to.
 *
 * @return bool
 * @see elgg_view()
 * @link http://docs.elgg.org/Views/TemplateHandlers
 */
function set_template_handler($function_name) {
	global $CONFIG;
	if (!empty($function_name) && is_callable($function_name)) {
		$CONFIG->template_handler = $function_name;
		return true;
	}
	return false;
}

/**
 * Returns the name of views for in a directory.
 *
 * Use this to get all namespaced views under the first element.
 *
 * @param string $dir  The main directory that holds the views. (mod/profile/views/)
 * @param string $base The root name of the view to use, without the viewtype. (profile)
 *
 * @return array
 * @since 1.7.0
 * @todo Why isn't this used anywhere else but in elgg_view_tree()?
 * Seems like a useful function for autodiscovery.
 */
function elgg_get_views($dir, $base) {
	$return = array();
	if (file_exists($dir) && is_dir($dir)) {
		if ($handle = opendir($dir)) {
			while ($view = readdir($handle)) {
				if (!in_array($view, array('.', '..', '.svn', 'CVS'))) {
					if (is_dir($dir . '/' . $view)) {
						if ($val = elgg_get_views($dir . '/' . $view, $base . '/' . $view)) {
							$return = array_merge($return, $val);
						}
					} else {
						$view = str_replace('.php', '', $view);
						$return[] = $base . '/' . $view;
					}
				}
			}
		}
	}

	return $return;
}

/**
 * Returns all views below a partial view.
 *
 * Settings $view_root = 'profile' will show all available views under
 * the "profile" namespace.
 *
 * @param string $view_root The root view
 * @param string $viewtype  Optionally specify a view type
 *                          other than the current one.
 *
 * @return array A list of view names underneath that root view
 * @todo This is used once in the deprecated get_activity_stream_data() function.
 */
function elgg_view_tree($view_root, $viewtype = "") {
	global $CONFIG;
	static $treecache;

	// Get viewtype
	if (!$viewtype) {
		$viewtype = elgg_get_viewtype();
	}

	// Has the treecache been initialised?
	if (!isset($treecache)) {
		$treecache = array();
	}
	// A little light internal caching
	if (!empty($treecache[$view_root])) {
		return $treecache[$view_root];
	}

	// Examine $CONFIG->views->locations
	if (isset($CONFIG->views->locations[$viewtype])) {
		foreach ($CONFIG->views->locations[$viewtype] as $view => $path) {
			$pos = strpos($view, $view_root);
			if ($pos === 0) {
				$treecache[$view_root][] = $view;
			}
		}
	}

	// Now examine core
	$location = $CONFIG->viewpath;
	$viewtype = elgg_get_viewtype();
	$root = $location . $viewtype . '/' . $view_root;

	if (file_exists($root) && is_dir($root)) {
		$val = elgg_get_views($root, $view_root);
		if (!is_array($treecache[$view_root])) {
			$treecache[$view_root] = array();
		}
		$treecache[$view_root] = array_merge($treecache[$view_root], $val);
	}

	return $treecache[$view_root];
}

/**
 * Auto-registers views from a location.
 *
 * @note Views in plugin/views/ are automatically registered for active plugins.
 * Plugin authors would only need to call this if optionally including
 * an entire views structure.
 *
 * @param string $view_base          Optional The base of the view name without the view type.
 * @param string $folder             Required The folder to begin looking in
 * @param string $base_location_path The base views directory to use with elgg_set_view_location()
 * @param string $viewtype           The type of view we're looking at (default, rss, etc)
 *
 * @return void
 * @since 1.7.0
 * @see elgg_set_view_location()
 * @todo This seems overly complicated.
 */
function autoregister_views($view_base, $folder, $base_location_path, $viewtype) {
	if (!isset($i)) {
		$i = 0;
	}

	if ($handle = opendir($folder)) {
		while ($view = readdir($handle)) {
			if (!in_array($view, array('.', '..', '.svn', 'CVS')) && !is_dir($folder . "/" . $view)) {
				// this includes png files because some icons are stored within view directories.
				// See commit [1705]
				if ((substr_count($view, ".php") > 0) || (substr_count($view, ".png") > 0)) {
					if (!empty($view_base)) {
						$view_base_new = $view_base . "/";
					} else {
						$view_base_new = "";
					}

					elgg_set_view_location($view_base_new . str_replace('.php', '', $view),
						$base_location_path, $viewtype);
				}
			} else if (!in_array($view, array('.', '..', '.svn', 'CVS')) && is_dir($folder . "/" . $view)) {
				if (!empty($view_base)) {
					$view_base_new = $view_base . "/";
				} else {
					$view_base_new = "";
				}
				autoregister_views($view_base_new . $view, $folder . "/" . $view,
					$base_location_path, $viewtype);
			}
		}
		return TRUE;
	}

	return FALSE;
}

/**
 * Add the core Elgg head elements that could be cached
 *
 * @return void
 */
function elgg_views_register_core_head_elements() {
	$url = elgg_get_simplecache_url('js', 'elgg');
	elgg_register_js('elgg', $url, 'head', 10);
	elgg_load_js('elgg');

	$url = elgg_get_simplecache_url('css', 'elgg');
	elgg_register_css('elgg', $url, 10);
	elgg_load_css('elgg');
}

/**
 * Add the rss link to the extras when if needed
 *
 * @return void
 */
function elgg_views_add_rss_link() {
	global $autofeed;
	if (isset($autofeed) && $autofeed == true) {
		$url = full_url();
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
 * Initialize viewtypes on system boot event
 * This ensures simplecache is cleared during upgrades. See #2252
 *
 * @return void
 * @access private
 * @elgg_event_handler boot system
 */
function elgg_views_boot() {
	global $CONFIG;

	elgg_register_simplecache_view('css/elgg');
	elgg_register_simplecache_view('css/ie');
	elgg_register_simplecache_view('css/ie6');
	elgg_register_simplecache_view('js/elgg');

	elgg_register_js('jquery', '/vendors/jquery/jquery-1.5.min.js', 'head', 1);
	elgg_register_js('jquery-ui', '/vendors/jquery/jquery-ui-1.8.9.min.js', 'head', 2);
	elgg_register_js('jquery.form', '/vendors/jquery/jquery.form.js');
	elgg_load_js('jquery');
	elgg_load_js('jquery-ui');
	elgg_load_js('jquery.form');

	elgg_register_simplecache_view('js/lightbox');
	$lightbox_js_url = elgg_get_simplecache_url('js', 'lightbox');
	elgg_register_js('lightbox', $lightbox_js_url);
	$lightbox_css_url = 'vendors/jquery/fancybox/jquery.fancybox-1.3.4.css';
	elgg_register_css('lightbox', $lightbox_css_url);

	elgg_register_event_handler('ready', 'system', 'elgg_views_register_core_head_elements');
	elgg_register_event_handler('pagesetup', 'system', 'elgg_views_add_rss_link');

	// discover the built-in view types
	// @todo the cache is loaded in load_plugins() but we need to know view_types earlier
	$view_path = $CONFIG->viewpath;

	$views = scandir($view_path);

	foreach ($views as $view) {
		if ('.' !== substr($view, 0, 1) && is_dir($view_path . $view)) {
			elgg_register_viewtype($view);
		}
	}
}

elgg_register_event_handler('boot', 'system', 'elgg_views_boot', 1000);
