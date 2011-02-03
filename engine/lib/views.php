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
 * registered by {@link set_view_location()}.
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
function set_view_location($view, $location, $viewtype = '') {
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
 *  - $vars['user'] The logged in user. (use {@link get_loggedin_user()} instead).
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
	// set by elgg_view() in the future. Instead, use get_loggedin_user(),
	// elgg_get_config(), and elgg_get_site_url() in your views.
	if (!isset($vars['user'])) {
		$vars['user'] = get_loggedin_user();
	}
	if (!isset($vars['config'])) {
		$vars['config'] = $CONFIG;
	}
	if (!isset($vars['url'])) {
		$vars['url'] = elgg_get_site_url();
	}

	// If it's been requested, pass off to a template handler instead
	if ($bypass == false && isset($CONFIG->template_handler) && !empty($CONFIG->template_handler)) {
		$template_handler = $CONFIG->template_handler;
		if (is_callable($template_handler)) {
			return $template_handler($view, $vars);
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

	// Draw the page
	$output = elgg_view("page/shells/$page_shell", $vars);

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
 * The layout views take the form layout/shells/$layout_name
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
 * @param string $layout The name of the view in layout/shells/.
 * @param array  $vars   Associative array of parameters for the layout view
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

	if (elgg_view_exists("layout/shells/$layout_name")) {
		return elgg_view("layout/shells/$layout_name", $param_array);
	} else {
		return elgg_view("layout/shells/default", $param_array);
	}
}

/**
 * Render a menu
 *
 * @param string $menu_name The name of the menu
 * @param array $vars An associative array of display options for the menu.
 *                    Options include:
 *                    sort_by => string or php callback
 *                       string options: 'name', 'title' (default), 'order' (registration order)
 *                       php callback: a compare function for usort
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_menu($menu_name, array $vars = array()) {
	global $CONFIG;

	$vars['name'] = $menu_name;

	$sort_by = elgg_get_array_value('sort_by', $vars, 'title');

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
 *  - bool 'full' Whether to show a full or condensed view.
 *
 * @tip This function can automatically appends annotations to entities if in full
 * view and a handler is registered for the entity:annotate.  See {@trac 964} and
 * {@link elgg_view_entity_annotations()}.
 *
 * @param ElggEntity $entity The entity to display
 * @param boolean    $full   Passed to entity view to decide how much information to show.
 *							 Beginning with Elgg 1.8, $full can be an array of vars for elgg_view()
 * @param boolean    $bypass If false, will not pass to a custom template handler.
 *                           {@see set_template_handler()}
 * @param boolean    $debug  Complain if views are missing
 *
 * @return string HTML to display or false
 * @link http://docs.elgg.org/Views/Entity
 * @link http://docs.elgg.org/Entities
 * @todo The annotation hook might be better as a generic plugin hook to append content.
 */
function elgg_view_entity(ElggEntity $entity, $full = false, $bypass = true, $debug = false) {

	// No point continuing if entity is null
	if (!$entity || !($entity instanceof ElggEntity)) {
		return false;
	}

	global $autofeed;
	$autofeed = true;

	$defaults = array(
		'full' => false,
	);

	if (is_array($full)) {
		$vars = $full;
		$vars = array_merge($defaults, $vars);
	} else {
		$vars = array(
			'full' => $full,
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
	if ($full) {
		$annotations = elgg_view_entity_annotations($entity, $full);

		if ($annotations) {
			$contents .= $annotations;
		}
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
 * @param bool           $full       Display the full view
 * @param bool           $bypass     If false, will not pass to a custom
 *                                   template handler. {@see set_template_handler()}
 * @param bool           $debug      Complain if views are missing
 *
 * @return string HTML (etc) to display
 */
function elgg_view_annotation(ElggAnnotation $annotation, $full = true, $bypass = true, $debug = false) {
	global $autofeed;
	$autofeed = true;

	$params = array(
		'annotation' => $annotation,
		'full' => $full,
	);

	$view = $annotation->view;
	if (is_string($view)) {
		return elgg_view($view, $params, $bypass, $debug);
	}

	$name = $annotation->name;
	$intname = (int) $name;
	if ("{$intname}" == "{$name}") {
		$name = get_metastring($intname);
	}
	if (empty($name)) {
		return "";
	}

	if (elgg_view_exists("annotation/{$name}")) {
		return elgg_view("annotation/{$name}", $params, $bypass, $debug);
	} else {
		return elgg_view("annotation/default", $params, $bypass, $debug);
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
 * @param array $entities    Array of entities
 * @param array $vars        Display variables
 *		'count'            The total number of entities across all pages
 *		'offset'           The current indexing offset
 *		'limit'            The number of entities to display per page
 *		'full_view'        Display the full view of the entities?
 *		'list_class'       CSS Class applied to the list
 *		'pagination'       Display pagination?
 *		'gallery'          Display as gallery?
 *		'list_type_toggle' Display the list type toggle?
 *
 * @return string The list of entities
 * @access private
 */
function elgg_view_entity_list($entities, $count, $offset = 0, $limit = 10, $full_view = true,
$list_type_toggle = true, $pagination = true) {

	if (!is_int($offset)) {
		$offset = (int)get_input('offset', 0);
	}

	if (func_num_args() == 2) {
		// new function
		$defaults = array(
			'items' => $entities,
			'list_class' => 'elgg-entity-list',
			'full_view' => true,
			'pagination' => true,
			'gallery' => false,
			'list_type_toggle' => false,
			'offset' => $offset,
		);
		
		$vars = array_merge($defaults, $count);

	} else {
		// old function - because this is an internal function we can remove
		// this in Elgg 1.9 without following the normal deprecation procedures
		$vars = array(
			'items' => $entities,
			'count' => (int) $count,
			'offset' => $offset,
			'limit' => (int) $limit,
			'full_view' => $full_view,
			'pagination' => $pagination,
			'gallery' => false,
			'list_type_toggle' => $list_type_toggle,
			'list_class' => 'elgg-entity-list',
		);
	}
	
	$listtype = get_input('listtype', 'list');
	if ($listtype != 'list') {
		$vars['gallery'] = true;
	}

	if ($vars['gallery']) {
		return elgg_view('layout/objects/gallery', $vars);
	} else {
		return elgg_view('layout/objects/list', $vars);
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

	return elgg_view('layout/objects/list', $vars);
}

/**
 * Display a plugin-specified rendered list of annotations for an entity.
 *
 * This displays the output of functions registered to the entity:annotation,
 * $entity_type plugin hook.
 *
 * This is called automatically by the framework from {@link elgg_view_entity()}
 *
 * @param ElggEntity $entity Entity
 * @param bool       $full   Full view?
 *
 * @return mixed string or false on failure
 * @todo Change the hook name.
 */
function elgg_view_entity_annotations(ElggEntity $entity, $full = true) {
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
			'full' => $full,
		)
	);

	return $annotations;
}

/**
 * Returns a rendered title.
 *
 * This is a shortcut for {@elgg_view layout/elements/title}.
 *
 * @param string $title   The page title
 * @param string $submenu Should a submenu be displayed? (default false, use not recommended and deprecated)
 *
 * @return string The HTML (etc)
 */
function elgg_view_title($title, $submenu = false) {
	if ($submenu !== false) {
		elgg_deprecated_notice('setting $submenu in elgg_view_title() is deprecated', 1.8);
	}

	$title = elgg_view('layout/elements/title', array('title' => $title, 'submenu' => $submenu));

	return $title;
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
 * for formatting the comments and add comment form.
 *
 * @param ElggEntity $entity      The entity to view comments of
 * @param bool       $add_comment Include a form to add comments
 *
 * @return string|false The HTML (etc) for the comments, or false on failure
 * @link http://docs.elgg.org/Entities/Comments
 * @link http://docs.elgg.org/Annotations/Comments
 */
function elgg_view_comments($entity, $add_comment = true) {
	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	$comments = elgg_trigger_plugin_hook('comments', $entity->getType(), array('entity' => $entity), false);
	if ($comemnts) {
		return $comments;
	} else {
		$params = array(
			'entity' => $entity,
			'show_add_form' => $add_comment,
			'id' => "{$entity->getSubtype()}-comments",
		);
		$output = elgg_view('layout/elements/comments', $params);

		return $output;
	}
}

/**
 * View the latest comments on a user's content
 *
 * @todo - get_annotations is due to be rewritten so update code and possibly parameters
 *
 * @param <type> $owner_guid
 * @param <type> $type
 * @param <type> $subtype
 * @param <type> $number
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_latest_comments($owner_guid, $type = 'object', $subtype = '', $number = 4) {
	$title = elgg_echo('generic_comments:latest');
	$comments = get_annotations(0, $type, $subtype, 'generic_comment', '', 0, $number, 0, 'desc', 0, 0, $owner_guid);
	$body = elgg_view('layout/objects/list', array(
		'items' => $comments,
		'pagination' => false,
		'list_class' => 'elgg-latest-comments',
	));
	$params = array(
		'title' => $title,
		'body' => $body,
		'class' => 'elgg-aside-module',
	);
	return elgg_view('layout/objects/module', $params);
}
/**
 * Wrapper function for the image block display pattern.
 *
 * Fixed width media on the side (image, icon, flash, etc.).
 * Descriptive content filling the rest of the column.
 *
 * This is a shortcut for {@elgg_view layout/objects/image_block}.
 *
 * @param string $image   The icon and other information
 * @param string $body    Description content
 * @param string $vars    Additional parameters for the view
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_image_block($image, $body, $vars = array()) {
	$vars['image'] = $image;
	$vars['body'] = $body;
	return elgg_view('layout/objects/image_block', $vars);
}

/**
 * Returns a human-readable representation of a river item
 *
 * @param ElggRiverItem $item A river item object
 *
 * @return string|false Depending on success
 */
function elgg_view_river_item($item) {
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

	return elgg_view('river/item', array('item' => $item));
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
 * @param string $action    The name of the action (without the leading "action/") -- e.g. "login"
 * @param array  $form_vars $vars environment passed to the "input/form" view
 * @param array  $body_vars $vars environment passed to the "forms/$action" view
 *
 * @return string The complete form
 */
function elgg_view_form($action, $form_vars = array(), $body_vars = array()) {
	global $CONFIG;

	if (isset($form_vars['action'])) {
		$form_vars['action'] = "action/{$form_vars['action']}";
	}

	$defaults = array(
		'action' => $CONFIG->wwwroot . "action/$action",
		'body' => elgg_view("forms/$action", $body_vars),
	);

	return elgg_view('input/form', array_merge($defaults, $form_vars));
}

/**
 * View an item in a list
 *
 * @param object $item      ElggEntity or ElggAnnotation
 * @param bool   $full_view Whether to render the full view of the object
 * @param array  $vars      Additional parameters for the rendering
 *
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_view_list_item($item, $full_view, array $vars = array()) {
	switch ($item->getType()) {
		case 'user':
		case 'object':
		case 'group':
		case 'site':
			return elgg_view_entity($item, $full_view);
		case 'annotation':
			return elgg_view_annotation($item, $full_view);
		case 'river':
			return elgg_view_river_item($item);
		default:
			break;
	}
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
 * @param string $base_location_path The base views directory to use with set_view_location
 * @param string $viewtype           The type of view we're looking at (default, rss, etc)
 *
 * @return void
 * @since 1.7.0
 * @see set_view_location()
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

					set_view_location($view_base_new . str_replace('.php', '', $view),
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
 * Registers a view to simple cache.
 *
 * Simple cache is a caching mechanism that saves the output of
 * views and its extensions into a file.  If the view is called
 * by the {@link simplecache/view.php} file, the Elgg framework will
 * not be loaded and the contents of the view will returned
 * from file.
 *
 * @warning Simple cached views must take no parameters and return
 * the same content no matter who is logged in.
 *
 * @note CSS and the basic JS views are cached by the engine.
 *
 * @param string $viewname View name
 *
 * @return void
 * @link http://docs.elgg.org/Views/Simplecache
 * @see elgg_view_regenerate_simplecache()
 */
function elgg_view_register_simplecache($viewname) {
	global $CONFIG;

	if (!isset($CONFIG->views)) {
		$CONFIG->views = new stdClass;
	}

	if (!isset($CONFIG->views->simplecache)) {
		$CONFIG->views->simplecache = array();
	}

	$CONFIG->views->simplecache[] = $viewname;
}

/**
 * Get the URL for the cached file
 *
 * @param string $type The file type: css or js
 * @param string $view The view name
 * @return string
 * @since 1.8.0
 */
function elgg_view_get_simplecache_url($type, $view) {
	global $CONFIG;
	$lastcache = (int)$CONFIG->lastcache;

	if (elgg_view_is_simplecache_enabled()) {
		$viewtype = elgg_get_viewtype();
		$url = elgg_get_site_url() . "cache/$type/$view/$viewtype/$view.$lastcache.$type";
	} else {
		$url = elgg_get_site_url() . "pg/$type/$view.$lastcache.$type";
	}
	return $url;
}

/**
 * Regenerates the simple cache.
 *
 * @warning This does not invalidate the cache, but actively resets it.
 *
 * @param string $viewtype Optional viewtype to regenerate
 *
 * @return void
 * @see elgg_view_register_simplecache()
 */
function elgg_view_regenerate_simplecache($viewtype = NULL) {
	global $CONFIG;

	if (!isset($CONFIG->views->simplecache) || !is_array($CONFIG->views->simplecache)) {
		return;
	}

	$lastcached = time();

	// @todo elgg_view() checks if the page set is done (isset($CONFIG->pagesetupdone)) and
	// triggers an event if it's not. Calling elgg_view() here breaks submenus
	// (at least) because the page setup hook is called before any
	// contexts can be correctly set (since this is called before page_handler()).
	// To avoid this, lie about $CONFIG->pagehandlerdone to force
	// the trigger correctly when the first view is actually being output.
	$CONFIG->pagesetupdone = TRUE;

	if (!file_exists($CONFIG->dataroot . 'views_simplecache')) {
		mkdir($CONFIG->dataroot . 'views_simplecache');
	}

	if (isset($viewtype)) {
		$viewtypes = array($viewtype);
	} else {
		$viewtypes = $CONFIG->view_types;
	}

	$original_viewtype = elgg_get_viewtype();

	foreach ($viewtypes as $viewtype) {
		elgg_set_viewtype($viewtype);
		foreach ($CONFIG->views->simplecache as $view) {
			$viewcontents = elgg_view($view);
			$viewname = md5(elgg_get_viewtype() . $view);
			if ($handle = fopen($CONFIG->dataroot . 'views_simplecache/' . $viewname, 'w')) {
				fwrite($handle, $viewcontents);
				fclose($handle);
			}
		}

		datalist_set("simplecache_lastupdate_$viewtype", $lastcached);
		datalist_set("simplecache_lastcached_$viewtype", $lastcached);
	}

	elgg_set_viewtype($original_viewtype);

	// needs to be set for links in html head
	$CONFIG->lastcache = $lastcached;

	unset($CONFIG->pagesetupdone);
}

/**
 * Is simple cache enabled
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_view_is_simplecache_enabled() {
	global $CONFIG;

	if ($CONFIG->simplecache_enabled) {
		return true;
	}

	return false;
}

/**
 * Enables the simple cache.
 *
 * @access private
 * @see elgg_view_register_simplecache()
 * @return void
 */
function elgg_view_enable_simplecache() {
	global $CONFIG;

	datalist_set('simplecache_enabled', 1);
	$CONFIG->simplecache_enabled = 1;
	elgg_view_regenerate_simplecache();
}

/**
 * Disables the simple cache.
 *
 * @warning Simplecache is also purged when disabled.
 *
 * @access private
 * @see elgg_view_register_simplecache()
 * @return void
 */
function elgg_view_disable_simplecache() {
	global $CONFIG;
	if ($CONFIG->simplecache_enabled) {
		datalist_set('simplecache_enabled', 0);
		$CONFIG->simplecache_enabled = 0;

		// purge simple cache
		if ($handle = opendir($CONFIG->dataroot . 'views_simplecache')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					unlink($CONFIG->dataroot . 'views_simplecache/' . $file);
				}
			}
			closedir($handle);
		}
	}
}

/**
 * Invalidates all cached views in the simplecache
 *
 * @return bool
 * @since 1.7.4
 */
function elgg_invalidate_simplecache() {
	global $CONFIG;

	$return = TRUE;

	if ($handle = opendir($CONFIG->dataroot . 'views_simplecache')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$return = $return && unlink($CONFIG->dataroot . 'views_simplecache/' . $file);
			}
		}
		closedir($handle);
	} else {
		$return = FALSE;
	}

	return $return;
}

/**
 * Add the core Elgg head elements that could be cached
 */
function elgg_views_register_core_head_elements() {
	$url = elgg_view_get_simplecache_url('js', 'elgg');
	elgg_register_js($url, 'elgg');

	$url = elgg_view_get_simplecache_url('css', 'screen');
	elgg_register_css($url, 'screen');
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

	elgg_view_register_simplecache('css/screen');
	elgg_view_register_simplecache('css/ie');
	elgg_view_register_simplecache('css/ie6');
	elgg_view_register_simplecache('js/friendsPickerv1');
	elgg_view_register_simplecache('js/elgg');

	$base = elgg_get_site_url();
	elgg_register_js("{$base}vendors/jquery/jquery-1.4.2.min.js", 'jquery');
	elgg_register_js("{$base}vendors/jquery/jquery-ui-1.7.2.min.js", 'jquery-ui');
	elgg_register_js("{$base}vendors/jquery/jquery.form.js", 'jquery.form');

	elgg_register_event_handler('ready', 'system', 'elgg_views_register_core_head_elements');

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