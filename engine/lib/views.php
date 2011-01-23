<?php
/**
 * Provides interfaces for Elgg's views system
 *
 * @package Elgg
 * @subpackage Core
 */

global $CURRENT_SYSTEM_VIEWTYPE;
$CURRENT_SYSTEM_VIEWTYPE = "";

/**
 * Override the view mode detection for the elgg view system.
 *
 * This function will force any further views to be rendered using $viewtype. Remember to call elgg_set_viewtype() with
 * no parameters to reset.
 *
 * @param string $viewtype The view type, e.g. 'rss', or 'default'.
 * @return bool
 */
function elgg_set_viewtype($viewtype = "") {
	global $CURRENT_SYSTEM_VIEWTYPE;

	$CURRENT_SYSTEM_VIEWTYPE = $viewtype;

	return true;
}

/**
 * Return the current view type used by the elgg view system.
 *
 * By default, this function will return a value based on the default for your system or from the command line
 * view parameter. However, you may force a given view type by calling elgg_set_viewtype()
 *
 * @return string The view.
 */
function elgg_get_viewtype() {
	global $CURRENT_SYSTEM_VIEWTYPE, $CONFIG;

	$viewtype = NULL;

	if ($CURRENT_SYSTEM_VIEWTYPE != "") {
		return $CURRENT_SYSTEM_VIEWTYPE;
	}

	if ((empty($_SESSION['view'])) || ( (trim($CONFIG->view!="")) && ($_SESSION['view']!=$CONFIG->view) )) {
		$_SESSION['view'] = "default";
		// If we have a config default view for this site then use that instead of 'default'
		if (/*(is_installed()) && */(!empty($CONFIG->view)) && (trim($CONFIG->view)!="")) {
			$_SESSION['view'] = $CONFIG->view;
		}
	}

	if (empty($viewtype) && is_callable('get_input')) {
		$viewtype = get_input('view');
	}

	if (empty($viewtype)) {
		$viewtype = $_SESSION['view'];
	}

	return $viewtype;
}

/**
 * Register a viewtype to fall back to a default view if view does not exist in
 * that viewtype.
 *
 * This is useful for alternate html viewtypes (such as for mobile devices)
 *
 * @param string $viewtype The viewtype to register
 * @since 1.7.2
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
 * Checks if this viewtype falls back to default
 *
 * @param string $viewtype
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
 * Return the location of a given view.
 *
 * @param string $view The view.
 * @param string $viewtype The viewtype
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
 * Handles templating views
 *
 * @see set_template_handler
 *
 * @param string $view The name and location of the view to use
 * @param array $vars Any variables that the view requires, passed as an array
 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
 * @param string $viewtype If set, forces the viewtype for the elgg_view call to be this value (default: standard detection)
 * @return string The HTML content
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
		trigger_elgg_event('pagesetup','system');
		$CONFIG->pagesetupdone = true;
	}

	if (!is_array($usercache)) {
		$usercache = array();
	}

	if (!is_array($vars)) {
		elgg_log('Vars in views must be an array!', 'ERROR');
		$vars = array();
	}

	if (empty($vars)) {
		$vars = array();
	}

	// Load session and configuration variables into $vars
	// $_SESSION will always be an array if it is set
	if (isset($_SESSION) /*&& is_array($_SESSION)*/ ) {
		//= array_merge($vars, $_SESSION);
		$vars += $_SESSION;
	}

	$vars['config'] = array();

	if (!empty($CONFIG)) {
		$vars['config'] = $CONFIG;
	}

	$vars['url'] = $CONFIG->url;

	// Load page owner variables into $vars
	if (is_callable('page_owner')) {
		$vars['page_owner'] = page_owner();
	} else {
		$vars['page_owner'] = -1;
	}

	if (($vars['page_owner'] != -1) && (is_installed())) {
		if (!isset($usercache[$vars['page_owner']])) {
			$vars['page_owner_user'] = get_entity($vars['page_owner']);
			$usercache[$vars['page_owner']] = $vars['page_owner_user'];
		} else {
			$vars['page_owner_user'] = $usercache[$vars['page_owner']];
		}
	}

	if (!isset($vars['js'])) {
		$vars['js'] = "";
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

	foreach($viewlist as $priority => $view) {
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
	$content = trigger_plugin_hook('display', 'view',
		array('view' => $view_orig, 'vars' => $vars), $content);

	// Return $content
	return $content;
}

/**
 * Returns whether the specified view exists
 *
 * @param string $view The view name
 * @param string $viewtype If set, forces the viewtype
 * @param bool $recurse If false, do not recursively check extensions
 * @return true|false Depending on success
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
		foreach( $CONFIG->views->extensions[$view] as $view_extension ) {
			// do not recursively check to stay away from infinite loops
			if (elgg_view_exists($view_extension, $viewtype, false)) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Registers a view to be simply cached
 *
 * Views cached in this manner must take no parameters and be login agnostic -
 * that is to say, they look the same no matter who is logged in (or logged out).
 *
 * CSS and the basic jS views are automatically cached like this.
 *
 * @param string $viewname View name
 */
function elgg_view_register_simplecache($viewname) {
	global $CONFIG;

	if (!isset($CONFIG->views)) {
		$CONFIG->views = new stdClass;
	}

	if (!isset($CONFIG->views->simplecache)) {
		$CONFIG->views->simplecache = array();
	}

	//if (elgg_view_exists($viewname))
	$CONFIG->views->simplecache[] = $viewname;
}

/**
 * Regenerates the simple cache.
 *
 * @param string $viewtype Optional viewtype to regenerate
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
 * Enables the simple cache.
 *
 * @see elgg_view_register_simplecache()
 */
function elgg_view_enable_simplecache() {
	global $CONFIG;

	datalist_set('simplecache_enabled',1);
	$CONFIG->simplecache_enabled = 1;
	elgg_view_regenerate_simplecache();
}

/**
 * Disables the simple cache.
 *
 * @see elgg_view_register_simplecache()
 */
function elgg_view_disable_simplecache() {
	global $CONFIG;
	if ($CONFIG->simplecache_enabled) {
		datalist_set('simplecache_enabled',0);
		$CONFIG->simplecache_enabled = 0;

		elgg_invalidate_simplecache();
	}
}

/**
 * Invalidates all cached views in the simplecache
 *
 * @since 1.7.4
 */
function elgg_invalidate_simplecache() {
	global $CONFIG;

	$return = TRUE;

	if ($handle = opendir($CONFIG->dataroot . 'views_simplecache')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$return = $return && unlink($CONFIG->dataroot.'views_simplecache/'.$file);
			}
		}
		closedir($handle);
	} else {
		$return = FALSE;
	}

	return $return;
}

/**
 * Internal function for retrieving views used by elgg_view_tree
 *
 * @param string $dir
 * @param string $base
 * @return array
 * @since 1.7.0
 */
function elgg_get_views($dir, $base) {
	$return = array();
	if (file_exists($dir) && is_dir($dir)) {
		if ($handle = opendir($dir)) {
			while ($view = readdir($handle)) {
				if (!in_array($view, array('.','..','.svn','CVS'))) {
					if (is_dir($dir . '/' . $view)) {
						if ($val = elgg_get_views($dir . '/' . $view, $base . '/' . $view)) {
							$return = array_merge($return, $val);
						}
					} else {
						$view = str_replace('.php','',$view);
						$return[] = $base . '/' . $view;
					}
				}
			}
		}
	}
	return $return;
}

/**
 * @deprecated 1.7.  Use elgg_get_views().
 * @param $dir
 * @param $base
 */
function get_views($dir, $base) {
	elgg_deprecated_notice('get_views() was deprecated by elgg_get_views()!', 1.7);
	elgg_get_views($dir, $base);
}

/**
 * When given a partial view root (eg 'js' or 'page_elements'), returns an array of views underneath it
 *
 * @param string $view_root The root view
 * @param string $viewtype Optionally specify a view type other than the current one.
 * @return array A list of view names underneath that root view
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
		foreach($CONFIG->views->locations[$viewtype] as $view => $path) {
			$pos = strpos($view,$view_root);
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
 * When given an entity, views it intelligently.
 *
 * Expects a view to exist called entity-type/subtype, or for the entity to have a parameter
 * 'view' which lists a different view to display.  In both cases, elgg_view will be called with
 * array('entity' => $entity, 'full' => $full) as its parameters, and therefore this is what
 * the view should expect to receive.
 *
 * @param ElggEntity $entity The entity to display
 * @param boolean $full Determines whether or not to display the full version of an object, or a smaller version for use in aggregators etc
 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
 * @return string HTML to display or false
 */
function elgg_view_entity(ElggEntity $entity, $full = false, $bypass = true, $debug = false) {
	global $autofeed;
	$autofeed = true;

	// No point continuing if entity is null
	if (!$entity) {
		return '';
	}

	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	// if this entity has a view defined, use it
	$view = $entity->view;
	if (is_string($view)) {
		return elgg_view($view,
						array('entity' => $entity, 'full' => $full),
						$bypass,
						$debug);
	}

	$entity_type = $entity->getType();

	$subtype = $entity->getSubtype();
	if (empty($subtype)) {
		$subtype = $entity_type;
	}

	$contents = '';
	if (elgg_view_exists("{$entity_type}/{$subtype}")) {
		$contents = elgg_view("{$entity_type}/{$subtype}", array(
				'entity' => $entity,
				'full' => $full
				), $bypass, $debug);
	}
	if (empty($contents)) {
		$contents = elgg_view("{$entity_type}/default",array(
				'entity' => $entity,
				'full' => $full
				), $bypass, $debug);
	}
	// Marcus Povey 20090616 : Speculative and low impact approach for fixing #964
	if ($full)  {
		$annotations = elgg_view_entity_annotations($entity, $full);

		if ($annotations) {
			$contents .= $annotations;
		}
	}
	return $contents;
}

/**
 * When given an annotation, views it intelligently.
 *
 * This function expects annotation views to be of the form annotation/name, where name
 * is the type of annotation.
 *
 * @param ElggAnnotation $annotation The annotation to display
 * @param boolean $full Determines whether or not to display the full version of an object, or a smaller version for use in aggregators etc
 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
 * @return string HTML (etc) to display
 */
function elgg_view_annotation(ElggAnnotation $annotation, $bypass = true, $debug = false) {
	global $autofeed;
	$autofeed = true;

	$view = $annotation->view;
	if (is_string($view)) {
		return elgg_view($view,array('annotation' => $annotation), $bypass, $debug);
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
		return elgg_view("annotation/{$name}",array('annotation' => $annotation), $bypass, $debug);
	} else {
		return elgg_view("annotation/default",array('annotation' => $annotation), $bypass, $debug);
	}
}


/**
 * Returns a view of a list of entities, plus navigation. It is intended that this function
 * be called from other wrapper functions.
 *
 * @see list_entities
 * @see list_user_objects
 * @see list_user_friends_objects
 * @see list_entities_from_metadata
 * @see list_entities_from_metadata_multi
 * @see list_entities_from_relationships
 * @see list_site_members
 *
 * @param array $entities List of entities
 * @param int $count The total number of entities across all pages
 * @param int $offset The current indexing offset
 * @param int $limit The number of entities to display per page
 * @param true|false $fullview Whether or not to display the full view (default: true)
 * @param true|false $viewtypetoggle Whether or not to allow users to toggle to gallery view
 * @param bool $pagination Whether pagination is offered.
 * @return string The list of entities
 */
function elgg_view_entity_list($entities, $count, $offset, $limit, $fullview = true, $viewtypetoggle = true, $pagination = true) {
	$count = (int) $count;
	$limit = (int) $limit;

	// do not require views to explicitly pass in the offset
	if (!$offset = (int) $offset) {
		$offset = sanitise_int(get_input('offset', 0));
	}

	$context = get_context();

	$html = elgg_view('entities/entity_list',array(
		'entities' => $entities,
		'count' => $count,
		'offset' => $offset,
		'limit' => $limit,
		'baseurl' => $_SERVER['REQUEST_URI'],
		'fullview' => $fullview,
		'context' => $context,
		'viewtypetoggle' => $viewtypetoggle,
		'viewtype' => get_input('search_viewtype','list'),
		'pagination' => $pagination
	));

	return $html;
}

/**
 * Returns a view of a list of annotations, plus navigation. It is intended that this function
 * be called from other wrapper functions.
 *
 * @param array $annotations List of annotations
 * @param int $count The total number of annotations across all pages
 * @param int $offset The current indexing offset
 * @param int $limit The number of annotations to display per page
 * @return string The list of annotations
 */
function elgg_view_annotation_list($annotations, $count, $offset, $limit) {
	$count = (int) $count;
	$offset = (int) $offset;
	$limit = (int) $limit;

	$html = "";

	$nav = elgg_view('navigation/pagination',array(
		'baseurl' => $_SERVER['REQUEST_URI'],
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'word' => 'annoff',
		'nonefound' => false,
	));

	$html .= $nav;

	if (is_array($annotations) && sizeof($annotations) > 0) {
		foreach($annotations as $annotation) {
			$html .= elgg_view_annotation($annotation, "", false);
		}
	}

	if ($count) {
		$html .= $nav;
	}

	return $html;
}

/**
 * Display a selective rendered list of annotations for a given entity.
 *
 * The list is produced as the result of the entity:annotate plugin hook
 * and is designed to provide a more generic framework to allow plugins
 * to extend the generic display of entities with their own annotation
 * renderings.
 *
 * This is called automatically by the framework from elgg_view_entity()
 *
 * @param ElggEntity $entity
 * @param bool $full
 * @return string or false on failure
 */
function elgg_view_entity_annotations(ElggEntity $entity, $full = true) {

	// No point continuing if entity is null
	if (!$entity) {
		return false;
	}

	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	$entity_type = $entity->getType();

	$annotations = trigger_plugin_hook('entity:annotate', $entity_type,
		array(
			'entity' => $entity,
			'full' => $full,
		)
	);

	return $annotations;
}

/**
 * Displays an internal layout for the use of a plugin canvas.
 * Takes a variable number of parameters, which are made available
 * in the views as $vars['area1'] .. $vars['areaN'].
 *
 * @param string $layout The name of the views in canvas/layouts/.
 * @return string The layout
 */
function elgg_view_layout($layout) {
	$arg = 1;
	$param_array = array();
	while ($arg < func_num_args()) {
		$param_array['area' . $arg] = func_get_arg($arg);
		$arg++;
	}

	if (elgg_view_exists("canvas/layouts/{$layout}")) {
		return elgg_view("canvas/layouts/{$layout}",$param_array);
	} else {
		return elgg_view("canvas/default",$param_array);
	}
}

/**
 * Returns a view for the page title
 *
 * @param string $title The page title
 * @param string $submenu Should a submenu be displayed? (default false, use not recommended)
 * @return string The HTML (etc)
 */
function elgg_view_title($title, $submenu = false) {
	$title = elgg_view('page_elements/title', array('title' => $title, 'submenu' => $submenu));

	return $title;
}

/**
 * Displays a UNIX timestamp in a friendly way
 *
 * @see elgg_get_friendly_time()
 *
 * @param int $time A UNIX epoch timestamp
 * @return string The friendly time HTML
 * @since 1.7.2
 */
function elgg_view_friendly_time($time) {
	return elgg_view('output/friendlytime', array('time' => $time));
}


/**
 * Automatically views comments and a comment form relating to the given entity
 *
 * @param ElggEntity $entity The entity to comment on
 * @return string|false The HTML (etc) for the comments, or false on failure
 */
function elgg_view_comments($entity){

	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	if ($comments = trigger_plugin_hook('comments',$entity->getType(),array('entity' => $entity),false)) {
		return $comments;
	} else {
		$comments = list_annotations($entity->getGUID(),'generic_comment');

		//display the comment form
		$comments .= elgg_view('comments/forms/edit',array('entity' => $entity));

		return $comments;
	}
}


/**
 * Wrapper function to display search listings.
 *
 * @param string $icon The icon for the listing
 * @param string $info Any information that needs to be displayed.
 * @return string The HTML (etc) representing the listing
 */
function elgg_view_listing($icon, $info) {
	return elgg_view('entities/entity_listing',array('icon' => $icon, 'info' => $info));
}

/**
 * Sets an alternative function to handle templates, which will be passed to by elgg_view.
 * This function must take the $view and $vars parameters from elgg_view:
 *
 * 		function my_template_function(string $view, array $vars = array())
 *
 * @see elgg_view()
 *
 * @param string $function_name The name of the function to pass to.
 * @return true|false
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
 * Extends a view.
 *
 * The addititional views are displayed before or after the primary view.
 * Priorities less than 500 are displayed before the primary view and
 * greater than 500 after. The default priority is 501.
 *
 * @param string $view The view to extend.
 * @param string $view_extension This view is added to $view
 * @param int $priority The priority, from 0 to 1000, to add at (lowest numbers displayed first)
 * @param string $viewtype Not used
 * @since 1.7.0
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

	while(isset($CONFIG->views->extensions[$view][$priority])) {
		$priority++;
	}

	$CONFIG->views->extensions[$view][$priority] = "{$view_extension}";
	ksort($CONFIG->views->extensions[$view]);
}

/**
 * Unextends a view.
 *
 * @param string $view The view that was extended.
 * @param string $view_extension This view that was added to $view
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
 * @deprecated 1.7.  Use elgg_extend_view().
 * @param $view
 * @param $view_name
 * @param $priority
 * @param $viewtype
 */
function extend_view($view, $view_name, $priority = 501, $viewtype = '') {
	elgg_deprecated_notice('extend_view() was deprecated by elgg_extend_view()!', 1.7);
	elgg_extend_view($view, $view_name, $priority, $viewtype);
}

/**
 * Set an alternative base location for a view (as opposed to the default of $CONFIG->viewpath)
 *
 * @param string $view The name of the view
 * @param string $location The base location path
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
 * Auto-registers views from a particular starting location
 *
 * @param string $view_base The base of the view name
 * @param string $folder The folder to begin looking in
 * @param string $base_location_path The base views directory to use with set_view_location
 * @param string $viewtype The type of view we're looking at (default, rss, etc)
 * @since 1.7.0
 */
function autoregister_views($view_base, $folder, $base_location_path, $viewtype) {
	if (!isset($i)) {
		$i = 0;
	}

	if ($handle = opendir($folder)) {
		while ($view = readdir($handle)) {
			if (!in_array($view,array('.','..','.svn','CVS')) && !is_dir($folder . "/" . $view)) {
				// this includes png files because some icons are stored within view directories.
				// See commit [1705]
				if ((substr_count($view,".php") > 0) || (substr_count($view,".png") > 0)) {
					if (!empty($view_base)) {
						$view_base_new = $view_base . "/";
					} else {
						$view_base_new = "";
					}

					set_view_location($view_base_new . str_replace('.php', '', $view), $base_location_path, $viewtype);
				}
			} else if (!in_array($view, array('.', '..', '.svn', 'CVS')) && is_dir($folder . "/" . $view)) {
				if (!empty($view_base)) {
					$view_base_new = $view_base . "/";
				} else {
					$view_base_new = "";
				}
				autoregister_views($view_base_new . $view, $folder . "/" . $view, $base_location_path, $viewtype);
			}
		}
		return TRUE;
	}

	return FALSE;
}

/**
 * Returns a representation of a full 'page' (which might be an HTML page,
 * RSS file, etc, depending on the current viewtype)
 *
 * @param string $title
 * @param string $body
 * @return string
 */
function page_draw($title, $body, $sidebar = "") {

	// get messages - try for errors first
	$sysmessages = system_messages(null, "errors");
	if (count($sysmessages["errors"]) == 0) {
		// no errors so grab rest of messages
		$sysmessages = system_messages(null, "");
	} else {
		// we have errors - clear out remaining messages
		system_messages(null, "");
	}

	// Draw the page
	$output = elgg_view('pageshells/pageshell', array(
		'title' => $title,
		'body' => $body,
		'sidebar' => $sidebar,
		'sysmessages' => $sysmessages,
		)
	);
	$split_output = str_split($output, 1024);

	foreach($split_output as $chunk) {
		echo $chunk;
	}
}

/**
 * Checks if $view_type is valid on this installation.
 *
 * @param string $view_type
 * @return bool
 * @since 1.7.2
 */
function elgg_is_valid_view_type($view_type) {
	global $CONFIG;

	return in_array($view_type, $CONFIG->view_types);
}


/**
 * Initialize viewtypes on system boot event
 * This ensures simplecache is cleared during upgrades. See #2252
 */
function elgg_views_boot() {
	global $CONFIG;

	elgg_view_register_simplecache('css');
	elgg_view_register_simplecache('js/friendsPickerv1');
	elgg_view_register_simplecache('js/initialise_elgg');

	// discover the built-in view types
	// @todo the cache is loaded in load_plugins() but we need to know view_types earlier
	$view_path = $CONFIG->viewpath;
	$CONFIG->view_types = array();

	$views = scandir($view_path);

	foreach ($views as $view) {
		if ('.' !== substr($view, 0, 1) && is_dir($view_path . $view)) {
			$CONFIG->view_types[] = $view;
		}
	}
}

register_elgg_event_handler('boot', 'system', 'elgg_views_boot', 1000);
