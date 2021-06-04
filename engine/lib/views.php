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
 */

use Elgg\Exceptions\Http\PageNotFoundException;
use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Menu\Menu;
use Elgg\Menu\UnpreparedMenu;

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
 * @param string $viewtype If set, forces the viewtype for the elgg_view call to be this value (default: standard detection)
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
 * Assembles and outputs a full page.
 *
 * A "page" in Elgg is determined by the current view type and
 * can be HTML for a browser, RSS for a feed reader, or
 * Javascript, PHP and a number of other formats.
 *
 * For HTML pages, use the 'head', 'page' plugin hook for setting meta elements
 * and links.
 *
 * @param string       $title      Title
 * @param string|array $body       Body as a string or as an array (which will be passed to elgg_view_layout('default', $body)
 * @param string       $page_shell Optional page shell to use. See page/shells view directory
 * @param array        $vars       Optional vars array to pass to the page
 *                                 shell. Automatically adds title, body, head, and sysmessages
 *
 * @return string The contents of the page
 * @since  1.8
 */
function elgg_view_page($title, $body, $page_shell = 'default', $vars = []) {
	
	if (elgg_is_xhr() && get_input('_elgg_ajax_list')) {
		// requested by ajaxed pagination
		return is_array($body) ? elgg_extract('content', $body) : $body;
	}
	
	if (is_array($body)) {
		$vars['entity'] = elgg_extract('entity', $body, elgg_extract('entity', $vars));

		$body['title'] = elgg_extract('title', $body, $title);
		$body = elgg_view_layout('default', $body);
	}
	
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

	$vars['head'] = elgg_trigger_plugin_hook('head', 'page', $vars, ['metas' => [], 'links' => []]);

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
 * @throws PageNotFoundException
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
	throw new PageNotFoundException();
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
 * Commonly used menu vars:
 *    items                => (array) an array of unprepared menu items as ElggMenuItem or menu item factory options
 *    sort_by              => (string) or php callback string options: 'name', 'priority' (default), 'text'
 *                            or a php callback (a compare function for usort)
 *    handler              => (string) the page handler to build action URLs
 *    entity               => (\ElggEntity) entity to use to build action URLs
 *    class                => (string) the class for the entire menu
 *    menu_view            => (string) name of the view to be used to render the menu
 *    show_section_headers => (bool) show headers before menu sections
 *    selected_item_name   => (string) the menu item name to be selected
 *    prepare_vertical     => (bool) prepares the menu items for vertical display (default false)
 *    prepare_dropdown     => (bool) will put all menu items (section=default) behind a dropdown (default false)
 *
 * @param string|Menu|UnpreparedMenu $menu Menu name (or object)
 * @param array                      $vars An associative array of display options for the menu.
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
		throw new ElggInvalidArgumentException('$menu must be a menu name, a Menu, or UnpreparedMenu');
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
 * @param \ElggEntity $entity The entity to display
 * @param array       $vars   Array of variables to pass to the entity view.
 *                            'full_view'         Whether to show a full or condensed view. (Default: true)
 *                            'item_view'         Alternative view used to render this entity
 *                            'register_rss_link' Register the rss link availability (default: depending on full_view)
 *
 * @return false|string HTML to display or false
 */
function elgg_view_entity(\ElggEntity $entity, array $vars = []) {

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
 * The annotation view is called with the following in $vars:
 *  - \ElggEntity 'annotation' The annotation being viewed.
 *
 * @param \ElggAnnotation $annotation The annotation to display
 * @param array           $vars       Variable array for view.
 *                                    'item_view' Alternative view used to render an annotation
 *
 * @return string|false Rendered annotation
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
 *                        'count'            The total number of entities across all pages
 *                        'offset'           The current indexing offset
 *                        'limit'            The number of entities to display per page (default from settings)
 *                        'full_view'        Display the full view of the entities?
 *                        'list_class'       CSS class applied to the list
 *                        'item_class'       CSS class applied to the list items
 *                        'item_view'        Alternative view to render list items content
 *                        'list_item_view'   Alternative view to render list items
 *                        'pagination'       Display pagination?
 *                        'base_url'         Base URL of list (optional)
 *                        'url_fragment'     URL fragment to add to links if not present in base_url (optional)
 *                        'position'         Position of the pagination: before, after, or both
 *                        'list_type'        List type: 'list' (default), 'gallery'
 *                        'no_results'       Message to display if no results (string|true|Closure)
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
 *                           'count'      The total number of annotations across all pages
 *                           'offset'     The current indexing offset
 *                           'limit'      The number of annotations to display per page
 *                           'full_view'  Display the full view of the annotation?
 *                           'list_class' CSS Class applied to the list
 *                           'list_type'  List type: 'list' (default), 'gallery'
 *                           'item_view'  Alternative view to render list items
 *                           'offset_key' The url parameter key used for offset
 *                           'no_results' Message to display if no results (string|true|Closure)
 *
 * @return string The list of annotations
 * @internal
 */
function elgg_view_annotation_list($annotations, array $vars = []) {
	// list type can be passed as request parameter
	$list_type = get_input('list_type', 'list');

	$defaults = [
		'items' => $annotations,
		'offset' => null,
		'limit' => null,
		'list_class' => 'elgg-list-annotation',
		'full_view' => true,
		'list_type' => $list_type,
		'offset_key' => 'annoff',
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
 * Returns a rendered list of relationships, plus pagination. This function
 * should be called by wrapper functions.
 *
 * @param array $relationships Array of relationships
 * @param array $vars          Display variables
 *                             'count'      The total number of relationships across all pages
 *                             'offset'     The current indexing offset
 *                             'limit'      The number of relationships to display per page
 *                             'full_view'  Display the full view of the relationships?
 *                             'list_class' CSS Class applied to the list
 *                             'list_type'  List type: 'list' (default), 'gallery'
 *                             'item_view'  Alternative view to render list items
 *                             'offset_key' The url parameter key used for offset
 *                             'no_results' Message to display if no results (string|true|Closure)
 *
 * @return string The list of relationships
 * @internal
 */
function elgg_view_relationship_list($relationships, array $vars = []) {
	// list type can be passed as request parameter
	$list_type = get_input('list_type', 'list');

	$defaults = [
		'items' => $relationships,
		'offset' => null,
		'limit' => null,
		'list_class' => 'elgg-list-relationship',
		'full_view' => false,
		'list_type' => $list_type,
		'offset_key' => 'reloff',
	];
	
	$vars = array_merge($defaults, $vars);
	
	if (!$vars['limit'] && !$vars['offset']) {
		// no need for pagination if listing is unlimited
		$vars['pagination'] = false;
	}
	
	$view = "page/components/{$vars['list_type']}";
	if (!elgg_view_exists($view)) {
		$view = 'page/components/list';
	}
	
	return elgg_view($view, $vars);
}

/**
 * Returns a string of a rendered relationship.
 *
 * Relationship views are expected to be in relationship/$relationship_name.
 * If a view is not found for $relationship_name, the default relationship/default
 * will be used.
 *
 * The relationship view is called with the following in $vars:
 *  - \ElggRelationship 'relationship' The relationship being viewed.
 *
 * @param \ElggRelationship $relationship The relationship to display
 * @param array             $vars         Variable array for view.
 *                                        'item_view'  Alternative view used to render a relationship
 *
 * @return string|false Rendered relationship
 */
function elgg_view_relationship(\ElggRelationship $relationship, array $vars = []) {
	$defaults = [
		'full_view' => true,
	];
	
	$vars = array_merge($defaults, $vars);
	$vars['relationship'] = $relationship;
	
	$name = $relationship->relationship;
	if (empty($name)) {
		return false;
	}
	
	$relationship_views = [
		elgg_extract('item_view', $vars, ''),
		"relationship/$name",
		"relationship/default",
	];
	
	$contents = '';
	foreach ($relationship_views as $view) {
		if (elgg_view_exists($view)) {
			$contents = elgg_view($view, $vars);
			break;
		}
	}
	
	return $contents;
}

/**
 * Renders a title.
 *
 * This is a shortcut for {@elgg_view page/elements/title}.
 *
 * @param string $title The page title
 * @param array  $vars  View variables
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
	
	if (!$entity instanceof \ElggEntity) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");

	$output = elgg_trigger_plugin_hook('comments', $entity->getType(), $vars, false);
	if ($output !== false) {
		return $output;
	}
	
	return elgg_view('page/elements/comments', $vars);
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
 *                             'item_view'         Alternative view to render the item
 *                             'register_rss_link' Register the rss link availability (default: false)
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
 *                          - 'ajax' bool If true, the form will be submitted with an Ajax request
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
 *                      - #type: specifies input view. E.g. "text" uses the view "input/text".
 *                      - #label: field label HTML
 *                      - #help: field help HTML
 *                      - #class: field class name
 *                      - #view: custom view to use to render the field
 *                      - #html: can be used to render custom HTML instead of in put field, helpful when you need to add a help paragraph or similar
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
 * @param array $options Any elgg_get_tags() options except:
 *                       - type => must be single entity type
 *                       - subtype => must be single entity subtype
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
 *                    'item_view' - Alternative view used to render list items (required if rendering list items that are not entity, annotation, relationship or river)
 *
 * @return false|string
 * @since 1.8.0
 * @internal
 */
function elgg_view_list_item($item, array $vars = []) {

	if ($item instanceof \ElggEntity) {
		return elgg_view_entity($item, $vars);
	} else if ($item instanceof \ElggAnnotation) {
		return elgg_view_annotation($item, $vars);
	} else if ($item instanceof \ElggRiverItem) {
		return elgg_view_river_item($item, $vars);
	} else if ($item instanceof ElggRelationship) {
		return elgg_view_relationship($item, $vars);
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
 * @throws \Elgg\Exceptions\InvalidArgumentException
 */
function elgg_view_icon($name, $vars = []) {
	if (empty($vars)) {
		$vars = [];
	}

	if (is_string($vars)) {
		$vars = ['class' => $vars];
	}

	if (!is_array($vars)) {
		throw new ElggInvalidArgumentException('$vars needs to be a string or an array');
	}

	$vars['class'] = elgg_extract_class($vars, "elgg-icon-$name");

	return elgg_view('output/icon', $vars);
}

/**
 * Include the RSS icon link and link element in the head
 *
 * @return void
 */
function elgg_register_rss_link() {
	_elgg_services()->config->_elgg_autofeed = true;
}

/**
 * Remove the RSS icon link and link element from the head
 *
 * @return void
 */
function elgg_unregister_rss_link() {
	_elgg_services()->config->_elgg_autofeed = false;
}

/**
 * Should the RSS view of this URL be linked to?
 *
 * @return bool
 * @internal
 */
function _elgg_has_rss_link() {
	if (_elgg_services()->config->disable_rss) {
		return false;
	}

	return (bool) _elgg_services()->config->_elgg_autofeed;
}

/**
 * Initialize viewtypes on system boot event
 * This ensures simplecache is cleared during upgrades. See #2252
 *
 * @return void
 * @internal
 * @elgg_event_handler boot system
 */
function elgg_views_boot() {
	_elgg_services()->viewCacher->registerCoreViews();

	// jQuery and UI must come before require. See #9024
	elgg_register_external_file('js', 'jquery', elgg_get_simplecache_url('jquery.js'), 'head');
	elgg_load_external_file('js', 'jquery');

	elgg_extend_view('require.js', 'elgg/require_config.js', 100);

	elgg_register_external_file('js', 'require', elgg_get_simplecache_url('require.js'), 'head');
	elgg_load_external_file('js', 'require');

	elgg_register_external_file('js', 'elgg', elgg_get_simplecache_url('elgg.js'), 'head');
	elgg_load_external_file('js', 'elgg');

	elgg_register_external_file('css', 'font-awesome', elgg_get_simplecache_url('font-awesome/css/all.min.css'));
	elgg_load_external_file('css', 'font-awesome');

	elgg_define_js('cropperjs', [
		'src' => elgg_get_simplecache_url('cropperjs/cropper.min.js'),
	]);
	elgg_define_js('jquery-cropper/jquery-cropper', [
		'src' => elgg_get_simplecache_url('jquery-cropper/jquery-cropper.min.js'),
	]);

	elgg_require_css('elgg');

	elgg_extend_view('initialize_elgg.js', 'elgg/prevent_clicks.js', 1);

	elgg_extend_view('elgg.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
	elgg_extend_view('elgg.css', 'entity/edit/icon/crop.css');

	elgg_define_js('jquery.ui.autocomplete.html', [
		'deps' => ['jquery-ui/widgets/autocomplete'],
	]);

	elgg_register_ajax_view('languages.js');
}

/**
 * Get the site data to be merged into "elgg" in elgg.js.
 *
 * Unlike _elgg_get_js_page_data(), the keys returned are literal expressions.
 *
 * @return array
 * @internal
 */
function _elgg_get_js_site_data() {
	return [
		'elgg.data' => (object) elgg_trigger_plugin_hook('elgg.data', 'site', null, []),
		'elgg.version' => elgg_get_version(),
		'elgg.release' => elgg_get_version(true),
		'elgg.config.wwwroot' => elgg_get_site_url(),

		// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
		'elgg.security.interval' => (int) elgg()->csrf->getActionTokenTimeout() * 333,
		'elgg.config.language' => _elgg_services()->config->language ?: 'en',
	];
}

/**
 * Get the initial contents of "elgg" client side. Will be extended by elgg.js.
 *
 * @return array
 * @internal
 */
function _elgg_get_js_page_data() {
	$data = elgg_trigger_plugin_hook('elgg.data', 'page', null, []);
	if (!is_array($data)) {
		elgg_log('"elgg.data" plugin hook handlers must return an array. Returned ' . gettype($data) . '.', 'ERROR');
		$data = [];
	}

	$elgg = [
		'config' => [
			'lastcache' => (int) _elgg_services()->config->lastcache,
			'viewtype' => elgg_get_viewtype(),
			'simplecache_enabled' => (int) elgg_is_simplecache_enabled(),
			'current_language' => get_current_language(),
		],
		'security' => [
			'token' => [
				'__elgg_ts' => $ts = elgg()->csrf->getCurrentTime()->getTimestamp(),
				'__elgg_token' => elgg()->csrf->generateActionToken($ts),
			],
		],
		'session' => [
			'user' => null,
			'token' => _elgg_services()->session->get('__elgg_session'),
		],
		'_data' => (object) $data,
	];

	if (_elgg_services()->config->elgg_load_sync_code) {
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
 * @internal
 */
function _elgg_view_under_viewtype($view, $vars, $viewtype) {
	$current_view_type = null;
	if ($viewtype) {
		$current_view_type = elgg_get_viewtype();
		elgg_set_viewtype($viewtype);
	}

	$ret = elgg_view($view, $vars);

	if (isset($current_view_type)) {
		elgg_set_viewtype($current_view_type);
	}

	return $ret;
}

/**
 * Converts icon classes to correct FontAwesome 5 classes
 *
 * @param array $classes Icon classes
 *
 * @return array
 * @internal
 */
function _elgg_map_icon_glyph_class(array $classes) {
	$common_icons = [
		'delete-alt' => 'times-circle',
		'delete' => 'times',
		'grid' => 'th',
		'info' => 'info-circle',
		'mail' => 'envelope-regular',
		'refresh' => 'redo',
		'remove' => 'times',
		'settings' => 'wrench',
		'settings-alt' => 'cog',
		'warning' => 'exclamation-triangle',
	];

	$brands = [
		'500px',
		'accessible-icon',
		'accusoft',
		'acquisitions-incorporated',
		'adn',
		'adobe',
		'adversal',
		'affiliatetheme',
		'airbnb',
		'algolia',
		'alipay',
		'amazon-pay',
		'amazon',
		'amilia',
		'android',
		'angellist',
		'angrycreative',
		'angular',
		'app-store-ios',
		'app-store',
		'apper',
		'apple-pay',
		'apple',
		'artstation',
		'asymmetrik',
		'atlassian',
		'audible',
		'autoprefixer',
		'avianex',
		'aviato',
		'aws',
		'bandcamp',
		'battle-net',
		'behance-square',
		'behance',
		'bimobject',
		'bitbucket',
		'bitcoin',
		'bity',
		'black-tie',
		'blackberry',
		'blogger-b',
		'blogger',
		'bluetooth-b',
		'bluetooth',
		'bootstrap',
		'btc',
		'buffer',
		'buromobelexperte',
		'buy-n-large',
		'buysellads',
		'canadian-maple-leaf',
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
		'centos',
		'chrome',
		'chromecast',
		'cloudscale',
		'cloudsmith',
		'cloudversify',
		'codepen',
		'codiepie',
		'confluence',
		'connectdevelop',
		'contao',
		'cotton-bureau',
		'cpanel',
		'creative-commons-by',
		'creative-commons-nc-eu',
		'creative-commons-nc-jp',
		'creative-commons-nc',
		'creative-commons-nd',
		'creative-commons-pd-alt',
		'creative-commons-pd',
		'creative-commons-remix',
		'creative-commons-sa',
		'creative-commons-sampling-plus',
		'creative-commons-sampling',
		'creative-commons-share',
		'creative-commons-zero',
		'creative-commons',
		'critical-role',
		'css3-alt',
		'css3',
		'cuttlefish',
		'd-and-d-beyond',
		'd-and-d',
		'dashcube',
		'delicious',
		'deploydog',
		'deskpro',
		'dev',
		'deviantart',
		'dhl',
		'diaspora',
		'digg',
		'digital-ocean',
		'discord',
		'discourse',
		'dochub',
		'docker',
		'draft2digital',
		'dribbble-square',
		'dribbble',
		'dropbox',
		'drupal',
		'dyalog',
		'earlybirds',
		'ebay',
		'edge',
		'elementor',
		'ello',
		'ember',
		'empire',
		'envira',
		'erlang',
		'ethereum',
		'etsy',
		'evernote',
		'expeditedssl',
		'facebook-f',
		'facebook-messenger',
		'facebook-square',
		'facebook',
		'fantasy-flight-games',
		'fedex',
		'fedora',
		'figma',
		'firefox-browser',
		'firefox',
		'first-order-alt',
		'first-order',
		'firstdraft',
		'flickr',
		'flipboard',
		'fly',
		'font-awesome-alt',
		'font-awesome-flag',
		'font-awesome-logo-full',
		'font-awesome',
		'fonticons-fi',
		'fonticons',
		'fort-awesome-alt',
		'fort-awesome',
		'forumbee',
		'foursquare',
		'free-code-camp',
		'freebsd',
		'fulcrum',
		'galactic-republic',
		'galactic-senate',
		'get-pocket',
		'gg-circle',
		'gg',
		'git-alt',
		'git-square',
		'git',
		'github-alt',
		'github-square',
		'github',
		'gitkraken',
		'gitlab',
		'gitter',
		'glide-g',
		'glide',
		'gofore',
		'goodreads-g',
		'goodreads',
		'google-drive',
		'google-play',
		'google-plus-g',
		'google-plus-square',
		'google-plus',
		'google-wallet',
		'google',
		'gratipay',
		'grav',
		'gripfire',
		'grunt',
		'gulp',
		'hacker-news-square',
		'hacker-news',
		'hackerrank',
		'hips',
		'hire-a-helper',
		'hooli',
		'hornbill',
		'hotjar',
		'houzz',
		'html5',
		'hubspot',
		'ideal',
		'imdb',
		'instagram',
		'intercom',
		'internet-explorer',
		'invision',
		'ioxhost',
		'itch-io',
		'itunes-note',
		'itunes',
		'java',
		'jedi-order',
		'jenkins',
		'jira',
		'joget',
		'joomla',
		'js-square',
		'js',
		'jsfiddle',
		'kaggle',
		'keybase',
		'keycdn',
		'kickstarter-k',
		'kickstarter',
		'korvue',
		'laravel',
		'lastfm-square',
		'lastfm',
		'leanpub',
		'less',
		'line',
		'linkedin-in',
		'linkedin',
		'linode',
		'linux',
		'lyft',
		'magento',
		'mailchimp',
		'mandalorian',
		'markdown',
		'mastodon',
		'maxcdn',
		'mdb',
		'medapps',
		'medium-m',
		'medium',
		'medrt',
		'meetup',
		'megaport',
		'mendeley',
		'microblog',
		'microsoft',
		'mix',
		'mixcloud',
		'mizuni',
		'modx',
		'monero',
		'napster',
		'neos',
		'nimblr',
		'node-js',
		'node',
		'npm',
		'ns8',
		'nutritionix',
		'odnoklassniki-square',
		'odnoklassniki',
		'old-republic',
		'opencart',
		'openid',
		'opera',
		'optin-monster',
		'orcid',
		'osi',
		'page4',
		'pagelines',
		'palfed',
		'patreon',
		'paypal',
		'penny-arcade',
		'periscope',
		'phabricator',
		'phoenix-framework',
		'phoenix-squadron',
		'php',
		'pied-piper-alt',
		'pied-piper-hat',
		'pied-piper-pp',
		'pied-piper-square',
		'pied-piper',
		'pinterest-p',
		'pinterest-square',
		'pinterest',
		'playstation',
		'product-hunt',
		'pushed',
		'python',
		'qq',
		'quinscape',
		'quora',
		'r-project',
		'raspberry-pi',
		'ravelry',
		'react',
		'reacteurope',
		'readme',
		'rebel',
		'red-river',
		'reddit-alien',
		'reddit-square',
		'reddit',
		'redhat',
		'renren',
		'replyd',
		'researchgate',
		'resolving',
		'rev',
		'rocketchat',
		'rockrms',
		'safari',
		'salesforce',
		'sass',
		'schlix',
		'scribd',
		'searchengin',
		'sellcast',
		'sellsy',
		'servicestack',
		'shirtsinbulk',
		'shopware',
		'simplybuilt',
		'sistrix',
		'sith',
		'sketch',
		'skyatlas',
		'skype',
		'slack-hash',
		'slack',
		'slideshare',
		'snapchat-ghost',
		'snapchat-square',
		'snapchat',
		'soundcloud',
		'sourcetree',
		'speakap',
		'speaker-deck',
		'spotify',
		'squarespace',
		'stack-exchange',
		'stack-overflow',
		'stackpath',
		'staylinked',
		'steam-square',
		'steam-symbol',
		'steam',
		'sticker-mule',
		'strava',
		'stripe-s',
		'stripe',
		'studiovinari',
		'stumbleupon-circle',
		'stumbleupon',
		'superpowers',
		'supple',
		'suse',
		'swift',
		'symfony',
		'teamspeak',
		'telegram-plane',
		'telegram',
		'tencent-weibo',
		'the-red-yeti',
		'themeco',
		'themeisle',
		'think-peaks',
		'trade-federation',
		'trello',
		'tripadvisor',
		'tumblr-square',
		'tumblr',
		'twitch',
		'twitter-square',
		'twitter',
		'typo3',
		'uber',
		'ubuntu',
		'uikit',
		'umbraco',
		'uniregistry',
		'unity',
		'untappd',
		'ups',
		'usb',
		'usps',
		'ussunnah',
		'vaadin',
		'viacoin',
		'viadeo-square',
		'viadeo',
		'viber',
		'vimeo-square',
		'vimeo-v',
		'vimeo',
		'vine',
		'vk',
		'vnv',
		'vuejs',
		'waze',
		'weebly',
		'weibo',
		'weixin',
		'whatsapp-square',
		'whatsapp',
		'whmcs',
		'wikipedia-w',
		'windows',
		'wix',
		'wizards-of-the-coast',
		'wolf-pack-battalion',
		'wordpress-simple',
		'wordpress',
		'wpbeginner',
		'wpexplorer',
		'wpforms',
		'wpressr',
		'xbox',
		'xing-square',
		'xing',
		'y-combinator',
		'yahoo',
		'yammer',
		'yandex-international',
		'yandex',
		'yarn',
		'yelp',
		'yoast',
		'youtube-square',
		'youtube',
		'zhihu',
	];
	
	foreach ($classes as $icon_class) {
		if (!preg_match_all('/^elgg-icon-(.+)/i', $icon_class)) {
			continue;
		}
		
		// strip elgg-icon-
		$base_icon = preg_replace('/^elgg-icon-(.+)/i', '$1', $icon_class);
		
		// convert common icons
		$base_icon = elgg_extract($base_icon, $common_icons, $base_icon);
		
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
		} elseif (in_array($base_icon, $brands)) {
			$classes[] = 'fab';
		} else {
			$classes[] = 'fas';
		}

		$classes[] = "fa-{$base_icon}";
	}

	$classes = array_unique($classes);

	return elgg_trigger_plugin_hook('classes', 'icon', null, $classes);
}

/**
 * Helper function for outputting urls. Using this helper function defaults to trusted urls
 *
 * @param string $href    The URL
 * @param string $text    The visible text
 * @param array  $options Additional options to pass to the output/url View
 *
 * @return string
 * @since 4.0
 */
function elgg_view_url(string $href, string $text = null, array $options = []): string {
	$options['is_trusted'] = elgg_extract('is_trusted', $options, true);
	$options['href'] = $href;
	$options['text'] = $text;
	
	return elgg_view('output/url', $options);
}

/**
 * Helper function for outputting a link to an entity
 *
 * @param \ElggEntity $entity  The entity to draw the link for
 * @param array       $options Additional options to pass to the output view
 *
 * @return string
 * @since 4.0
 */
function elgg_view_entity_url(\ElggEntity $entity, array $options = []): string {
	return elgg_view_url($entity->getURL(), $entity->getDisplayName(), $options);
}
