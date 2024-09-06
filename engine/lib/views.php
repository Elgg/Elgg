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
function elgg_set_viewtype(string $viewtype = ''): bool {
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
function elgg_get_viewtype(): string {
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
function elgg_register_viewtype_fallback(string $viewtype): void {
	_elgg_services()->views->registerViewtypeFallback($viewtype);
}

/**
 * Register a view to be available for ajax calls
 *
 * @warning Only views that begin with 'js/' and 'css/' have their content
 * type set to 'text/javascript' and 'text/css'. Other views are served as
 * 'text/html'.
 *
 * @param string $view The view name
 *
 * @return void
 * @since 1.8.3
 */
function elgg_register_ajax_view(string $view): void {
	_elgg_services()->ajax->registerView($view);
}

/**
 * Unregister a view for ajax calls
 *
 * @param string $view The view name
 *
 * @return void
 * @since 1.8.3
 */
function elgg_unregister_ajax_view(string $view): void {
	_elgg_services()->ajax->unregisterView($view);
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
function elgg_view_exists(string $view, string $viewtype = '', bool $recurse = true): bool {
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
 * The input of views can be intercepted by registering for the 'view_vars', '<$view_name>' event.
 *
 * If the input contains the key "__view_output", the view will output this value as a string.
 * No extensions are used, and the "view" event is not triggered).
 *
 * The output of views can be intercepted by registering for the 'view', '<$view_name>' event.
 *
 * @param string $view     The name and location of the view to use
 * @param array  $vars     Variables to pass to the view.
 * @param string $viewtype If set, forces the viewtype for the elgg_view call to be this value (default: standard detection)
 *
 * @return string The parsed view
 */
function elgg_view(string $view, array $vars = [], string $viewtype = ''): string {
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
function elgg_extend_view(string $view, string $view_extension, int $priority = 501): void {
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
function elgg_unextend_view(string $view, string $view_extension): bool {
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
function elgg_get_view_extensions(string $view): array {
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
 * For HTML pages, use the 'head', 'page' event for setting meta elements
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
function elgg_view_page(string $title, string|array $body, string $page_shell = 'default', array $vars = []): string {
	
	if (elgg_is_xhr() && get_input('_elgg_ajax_list')) {
		// requested by ajaxed pagination
		return is_array($body) ? (string) elgg_extract('content', $body) : $body;
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
	$page_shell = elgg_trigger_event_results('shell', 'page', $params, $page_shell);

	$system_messages = _elgg_services()->system_messages;

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

	$vars['head'] = elgg_trigger_event_results('head', 'page', $vars, ['metas' => [], 'links' => []]);

	$vars = elgg_trigger_event_results('output:before', 'page', [], $vars);

	$output = elgg_view("page/{$page_shell}", $vars);

	// Allow plugins to modify the output
	$output = elgg_trigger_event_results('output', 'page', $vars, $output);

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
function elgg_view_resource(string $name, array $vars = []): string {
	$view = "resources/{$name}";

	if (elgg_view_exists($view)) {
		return _elgg_services()->views->renderView($view, $vars);
	}

	if (elgg_get_viewtype() !== 'default' && elgg_view_exists($view, 'default')) {
		return _elgg_services()->views->renderView($view, $vars, 'default');
	}

	_elgg_services()->logger->error("The view {$view} is missing.");

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
 *                            the layout events and views.
 *                            Route 'identifier' and 'segments' of the page being
 *                            rendered will be added to this array automatially,
 *                            allowing plugins to alter layout views and subviews
 *                            based on the current route.
 * @return string
 */
function elgg_view_layout(string $layout_name, array $vars = []): string {
	$timer = _elgg_services()->timer;
	if (!$timer->hasEnded(['build page'])) {
		$timer->end(['build page']);
	}
	
	$timer->begin([__FUNCTION__]);
	
	$vars['identifier'] = _elgg_services()->request->getFirstUrlSegment();
	$vars['segments'] = _elgg_services()->request->getUrlSegments();
	array_shift($vars['segments']);

	$layout_name = elgg_trigger_event_results('layout', 'page', $vars, $layout_name);

	$vars['layout'] = $layout_name;

	$layout_views = [
		"page/layouts/{$layout_name}",
		'page/layouts/default',
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
 * This function triggers a 'register', 'menu:<menu name>' event that enables
 * plugins to add menu items just before a menu is rendered. This is used by
 * dynamic menus (menus that change based on some input such as the user hover
 * menu). Using elgg_register_menu_item() in response to the event can cause
 * incorrect links to show up. See the blog plugin's blog_owner_block_menu()
 * for an example of using this event.
 *
 * An additional event is the 'prepare', 'menu:<menu name>' which enables plugins
 * to modify the structure of the menu (sort it, remove items, set variables on
 * the menu items).
 *
 * Preset (unprepared) menu items passed to the this function with the $vars
 * argument, will be merged with the registered items (registered with
 * elgg_register_menu_item()). The combined set of menu items will be passed
 * to 'register', 'menu:<menu_name>' event.
 *
 * Plugins that pass preset menu items to this function and do not wish to be
 * affected by events (e.g. if you are displaying multiple menus with
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
 *    item_contents_view   => (string) the view to use for the output of the menu item contents (default: 'navigation/menu/elements/item/url')
 *
 * @param string|Menu|UnpreparedMenu $menu Menu name (or object)
 * @param array                      $vars An associative array of display options for the menu.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_menu($menu, array $vars = []): string {

	$menu_view = (string) elgg_extract('menu_view', $vars);
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
		"navigation/menu/{$name}",
		'navigation/menu/default',
	];

	foreach ($views as $view) {
		if (elgg_view_exists($view)) {
			return elgg_view($view, $params);
		}
	}
	
	return '';
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
 * @return string HTML to display
 */
function elgg_view_entity(\ElggEntity $entity, array $vars = []): string {

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
		(string) elgg_extract('item_view', $vars, ''),
		"{$entity_type}/{$entity_subtype}",
		"{$entity_type}/default",
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
function elgg_view_entity_icon(\ElggEntity $entity, string $size = 'medium', array $vars = []): string {

	$vars['entity'] = $entity;
	$vars['size'] = $size;

	$entity_type = $entity->getType();

	$subtype = $entity->getSubtype();

	$contents = '';
	if (elgg_view_exists("icon/{$entity_type}/{$subtype}")) {
		$contents = elgg_view("icon/{$entity_type}/{$subtype}", $vars);
	}
	
	if (empty($contents) && elgg_view_exists("icon/{$entity_type}/default")) {
		$contents = elgg_view("icon/{$entity_type}/default", $vars);
	}
	
	if (empty($contents)) {
		$contents = elgg_view('icon/default', $vars);
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
 * @return string Rendered annotation
 */
function elgg_view_annotation(\ElggAnnotation $annotation, array $vars = []): string {
	$defaults = [
		'full_view' => true,
	];

	$vars = array_merge($defaults, $vars);
	$vars['annotation'] = $annotation;

	$name = $annotation->name;
	if (empty($name)) {
		return '';
	}

	$annotation_views = [
		(string) elgg_extract('item_view', $vars, ''),
		"annotation/{$name}",
		'annotation/default',
	];

	foreach ($annotation_views as $view) {
		if (elgg_view_exists($view)) {
			return elgg_view($view, $vars);
		}
	}

	return '';
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
function elgg_view_entity_list(array $entities, array $vars = []): string {
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
 */
function elgg_view_annotation_list(array $annotations, array $vars = []): string {
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
 */
function elgg_view_relationship_list(array $relationships, array $vars = []): string {
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
 * @return string Rendered relationship
 */
function elgg_view_relationship(\ElggRelationship $relationship, array $vars = []): string {
	$defaults = [
		'full_view' => true,
	];
	
	$vars = array_merge($defaults, $vars);
	$vars['relationship'] = $relationship;
	
	$name = $relationship->relationship;
	if (empty($name)) {
		return '';
	}
	
	$relationship_views = [
		(string) elgg_extract('item_view', $vars, ''),
		"relationship/{$name}",
		'relationship/default',
	];
	
	foreach ($relationship_views as $view) {
		if (elgg_view_exists($view)) {
			return elgg_view($view, $vars);
		}
	}
	
	return '';
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
function elgg_view_title(string $title, array $vars = []): string {
	$vars['title'] = $title;

	return elgg_view('page/elements/title', $vars);
}

/**
 * Displays a UNIX timestamp in a friendly way
 *
 * @see elgg_get_friendly_time()
 *
 * @param int|string|\DateTime|\Elgg\I18n\DateTime $time         A UNIX epoch timestamp, a date string or a DateTime object
 * @param int|string|\DateTime|\Elgg\I18n\DateTime $time_updated A UNIX epoch timestamp, a date string or a DateTime object
 *
 * @return string The friendly time HTML
 * @since 1.7.2
 */
function elgg_view_friendly_time($time, $time_updated = null): string {
	$view = 'output/friendlytime';
	$vars = [
		'time' => $time,
		'time_updated' => $time_updated,
	];
	$viewtype = elgg_view_exists($view) ? '' : 'default';

	return _elgg_view_under_viewtype($view, $vars, $viewtype);
}

/**
 * Returns rendered comments and a comment form for an entity.
 *
 * @tip Plugins can override the output by registering a handler
 * for the 'comments', '<$entity_type>' event.  The handler is responsible
 * for formatting the comments and the add comment form.
 *
 * @param \ElggEntity $entity      The entity to view comments of
 * @param bool        $add_comment Include a form to add comments?
 * @param array       $vars        Variables to pass to comment view
 *
 * @return string Rendered comments
 */
function elgg_view_comments(\ElggEntity $entity, bool $add_comment = true, array $vars = []): string {
	if (!$entity->hasCapability('commentable')) {
		return '';
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");
	
	$default_id = 'comments';
	if ($entity instanceof \ElggComment) {
		$default_id .= "-{$entity->guid}";
	}
	
	$vars['id'] = elgg_extract('id', $vars, $default_id);

	$output = elgg_trigger_event_results('comments', $entity->getType(), $vars, false);
	if (is_string($output)) {
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
function elgg_view_image_block(string $image, string $body, array $vars = []): string {
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
function elgg_view_module(string $type, string $title, string $body, array $vars = []): string {
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
function elgg_view_message(string $type, string $body, array $vars = []): string {
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
function elgg_view_river_item(\ElggRiverItem $item, array $vars = []): string {

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

	foreach ($river_views as $view) {
		if (elgg_view_exists($view)) {
			return elgg_view($view, $vars);
		}
	}

	return '';
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
function elgg_view_form(string $action, array $form_vars = [], array $body_vars = []): string {
	return _elgg_services()->forms->render($action, $form_vars, $body_vars);
}

/**
 * Sets form footer and defers its rendering until the form view and extensions have been rendered.
 * Deferring footer rendering allows plugins to extend the form view while maintaining
 * logical DOM structure.
 * Footer will be rendered using 'elements/forms/footer' view after form body has finished rendering
 *
 * @param string $footer Footer
 * @return void
 */
function elgg_set_form_footer(string $footer = ''): void {
	_elgg_services()->forms->setFooter($footer);
}

/**
 * Returns currently set footer, or false if not in the form rendering stack
 * @return string
 */
function elgg_get_form_footer(): string {
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
 * @internal
 */
function _elgg_split_vars(array $vars = [], array $prefixes = null): array {

	if (!isset($prefixes)) {
		$prefixes = ['#'];
	}

	$return = [];
	$default_section = ''; // something weird with PHP 8.1 compatibility
	
	foreach ($vars as $key => $value) {
		foreach ($prefixes as $prefix) {
			if (substr($key, 0, 1) === $prefix) {
				$key = substr($key, 1);
				$return[$prefix][$key] = $value;
				break;
			} else {
				$return[$default_section][$key] = $value;
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
function elgg_view_field(array $params = []): string {

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
		return elgg_view("input/{$input_type}", $params['']);
	}

	if (empty($params['id'])) {
		$params['id'] = 'elgg-field-' . base_convert(mt_rand(), 10, 36);
	}

	$make_special_checkbox_label = false;
	if (in_array($input_type, ['checkbox', 'switch']) && (isset($params['label']) || isset($params['#label']))) {
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

	// correct the label vars
	$label_vars = $element_vars;
	if (in_array($input_type, ['checkboxes', 'radio'])) {
		// the field label shouldn't point to the wrapping <ul> as that isn't a valid target for a <label>
		unset($label_vars['id']);
	}

	$element_vars['label'] = elgg_view('elements/forms/label', $label_vars);

	// wrap if present
	$element_vars['help'] = elgg_view('elements/forms/help', $element_vars);

	if ($make_special_checkbox_label) {
		$input_vars['label'] = $element_vars['label'];
		$input_vars['label_tag'] = 'div';
		unset($element_vars['label']);
	}
	
	$element_vars['input'] = elgg_view('elements/forms/input', $input_vars);

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
function elgg_view_tagcloud(array $options = []): string {
	return elgg_view('output/tagcloud', [
		'value' => elgg_get_tags($options),
		'type' => elgg_extract('type', $options, ''),
		'subtype' => elgg_extract('subtype', $options, ''),
	]);
}

/**
 * View an item in a list
 *
 * @param mixed $item Entity, annotation, river item, or other data
 * @param array $vars Additional parameters for the rendering
 *                    'item_view' - Alternative view used to render list items (required if rendering list items that are not entity, annotation, relationship or river)
 *
 * @return string
 * @since 1.8.0
 */
function elgg_view_list_item($item, array $vars = []): string {

	if ($item instanceof \ElggEntity) {
		return elgg_view_entity($item, $vars);
	} else if ($item instanceof \ElggAnnotation) {
		return elgg_view_annotation($item, $vars);
	} else if ($item instanceof \ElggRiverItem) {
		return elgg_view_river_item($item, $vars);
	} else if ($item instanceof ElggRelationship) {
		return elgg_view_relationship($item, $vars);
	}

	$view = (string) elgg_extract('item_view', $vars);
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
 * @param array  $vars An array of variables (['class' => 'float']) to pass to the icon view.
 *
 * @return string The html for displaying an icon
 */
function elgg_view_icon(string $name, array $vars = []): string {
	$vars['class'] = elgg_extract_class($vars, "elgg-icon-{$name}");

	return elgg_view('output/icon', $vars);
}

/**
 * Include the RSS icon link and link element in the head
 *
 * @return void
 */
function elgg_register_rss_link(): void {
	_elgg_services()->config->_elgg_autofeed = true;
}

/**
 * Remove the RSS icon link and link element from the head
 *
 * @return void
 */
function elgg_unregister_rss_link(): void {
	_elgg_services()->config->_elgg_autofeed = false;
}

/**
 * Should the RSS view of this URL be linked to?
 *
 * @return bool
 * @internal
 */
function _elgg_has_rss_link(): bool {
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
 */
function elgg_views_boot(): void {
	_elgg_services()->views->registerCoreViews();

	elgg_register_external_file('css', 'font-awesome', elgg_get_simplecache_url('font-awesome/css/all.min.css'));
	elgg_load_external_file('css', 'font-awesome');

	elgg_extend_view('elgg.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
	elgg_extend_view('elgg.css', 'entity/edit/icon/crop.css');
	
	elgg_require_css('elgg');
	
	elgg_register_esm('cropperjs', elgg_get_simplecache_url('cropperjs/cropper.esm.js'));
	elgg_register_esm('jquery', elgg_get_simplecache_url('elgg/jquery.mjs'));
	elgg_register_esm('jquery-ui', elgg_get_simplecache_url('jquery-ui.js'));
	elgg_register_esm('jquery-cropper/jquery-cropper', elgg_get_simplecache_url('jquery-cropper/jquery-cropper.esm.js'));
	
	elgg_import_esm('elgg');
	elgg_import_esm('elgg/lightbox');
	elgg_import_esm('elgg/security');

	elgg_extend_view('jquery-ui.js', 'jquery.ui.touch-punch.js');
	elgg_extend_view('initialize_elgg.js', 'elgg/prevent_clicks.js', 1);

	elgg_register_ajax_view('languages.js');
}

/**
 * Get the initial contents of "elgg" client side. Will be extended by elgg.js.
 *
 * @param array $params page related parameters
 *
 * @return array
 * @internal
 */
function _elgg_get_js_page_data(array $params = []): array {
	$data = elgg_trigger_event_results('elgg.data', 'page', $params, []);
	if (!is_array($data)) {
		_elgg_services()->logger->error('"elgg.data" Event handlers must return an array. Returned ' . gettype($data) . '.');
		$data = [];
	}
	
	$message_delay = (int) elgg_get_config('message_delay');
	if ($message_delay < 1) {
		$message_delay = 6;
	}

	$elgg = [
		'config' => [
			'lastcache' => (int) _elgg_services()->config->lastcache,
			'viewtype' => elgg_get_viewtype(),
			'simplecache_enabled' => (int) _elgg_services()->simpleCache->isEnabled(),
			'current_language' => elgg_get_current_language(),
			'language' => _elgg_services()->config->language ?: 'en',
			'wwwroot' => elgg_get_site_url(),
			'message_delay' => $message_delay * 1000,
		],
		'release' => elgg_get_release(),
		'security' => [
			// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
			'interval' => (int) _elgg_services()->csrf->getActionTokenTimeout() * 333,
			
			'token' => [
				'__elgg_ts' => $ts = _elgg_services()->csrf->getCurrentTime()->getTimestamp(),
				'__elgg_token' => _elgg_services()->csrf->generateActionToken($ts),
			],
		],
		'session' => [
			'user' => null,
			'token' => _elgg_services()->session->get('__elgg_session'),
		],
		'data' => $data,
	];

	$user = elgg_get_logged_in_user_entity();
	if ($user instanceof ElggUser) {
		$user_object = $user->toObject();
		$user_object->admin = $user->isAdmin();
		
		$elgg['user'] = (array) $user_object;
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
 * @return string
 * @internal
 */
function _elgg_view_under_viewtype(string $view, array $vars, string $viewtype): string {
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
 */
function elgg_view_deprecated(string $view, array $vars, string $suggestion, string $version): string {
	return _elgg_services()->views->renderDeprecatedView($view, $vars, $suggestion, $version);
}
