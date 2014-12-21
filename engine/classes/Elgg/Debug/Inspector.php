<?php
namespace Elgg\Debug;

use Elgg\Debug\Inspector\ViewComponent;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package Elgg.Core
 * @since   1.11
 */
class Inspector {

	/**
	 * Get Elgg event information
	 *
	 * @return array [event,type] => array(handlers)
	 */
	public function getEvents() {
		return $this->buildHandlerTree(_elgg_services()->events->getAllHandlers());
	}

	/**
	 * Get Elgg plugin hooks information
	 *
	 * @return array [hook,type] => array(handlers)
	 */
	public function getPluginHooks() {
		return $this->buildHandlerTree(_elgg_services()->hooks->getAllHandlers());
	}

	/**
	 * Get all view types for known views
	 *
	 * @return string[]
	 */
	public function getViewtypes() {
		global $CONFIG;

		return array_keys($CONFIG->views->locations);
	}

	/**
	 * Get Elgg view information
	 *
	 * @param string $viewtype The Viewtype we wish to inspect
	 *
	 * @return array [view] => map of priority to ViewComponent[]
	 */
	public function getViews($viewtype = 'default') {
		global $CONFIG;

		$overrides = null;
		if ($CONFIG->system_cache_enabled) {
			$data = _elgg_services()->systemCache->load('view_overrides');
			if ($data) {
				$overrides = unserialize($data);
			}
		} else {
			$overrides = _elgg_services()->views->getOverriddenLocations();
		}

		// maps view name to array of ViewComponent[] with priority as keys
		$views = array();

		$location = "{$CONFIG->viewpath}{$viewtype}/";
		$core_file_list = $this->recurseFileTree($location);

		// setup views array before adding extensions and plugin views
		foreach ($core_file_list as $path) {
			$component = ViewComponent::fromPaths($path, $location);
			$views[$component->view] = array(500 => $component);
		}

		// add plugins and handle overrides
		foreach ($CONFIG->views->locations[$viewtype] as $view => $location) {
			$component = new ViewComponent();
			$component->view = $view;
			$component->location = "{$location}{$viewtype}/";
			$views[$view] = array(500 => $component);
		}

		// now extensions
		foreach ($CONFIG->views->extensions as $view => $extensions) {
			$view_list = array();
			foreach ($extensions as $priority => $ext_view) {
				if (isset($views[$ext_view])) {
					$view_list[$priority] = $views[$ext_view][500];
				}
			}
			if (count($view_list) > 0) {
				$views[$view] = $view_list;
			}
		}

		ksort($views);

		// now overrides
		foreach ($views as $view => $view_list) {
			if (!empty($overrides[$viewtype][$view])) {
				$overrides_list = array();
				foreach ($overrides[$viewtype][$view] as $i => $location) {
					$component = new ViewComponent();
					$component->overridden = true;
					$component->view = $view;
					$component->location = "{$location}{$viewtype}/";
					$overrides_list["o:$i"] = $component;
				}
				$views[$view] = $overrides_list + $view_list;
			}
		}

		// view handlers
		$handlers = _elgg_services()->hooks->getAllHandlers();


		$filtered_views = array();
		if (!empty($handlers['view'])) {
			$filtered_views = array_keys($handlers['view']);
		}

		$global_hooks = array();
		if (!empty($handlers['view']['all'])) {
			$global_hooks[] = 'view,all';
		}
		if (!empty($handlers['display']['view'])) {
			$global_hooks[] = 'display,view';
		}
		if (!empty($handlers['display']['all'])) {
			$global_hooks[] = 'display,all';
		}

		return array(
			'views' => $views,
			'global_hooks' => $global_hooks,
			'filtered_views' => $filtered_views,
		);
	}

	/**
	 * Get Elgg widget information
	 *
	 * @return array [widget] => array(name, contexts)
	 */
	public function getWidgets() {
		$tree = array();
		foreach (_elgg_services()->widgets->getAllTypes() as $handler => $handler_obj) {
			$tree[$handler] = array($handler_obj->name, implode(',', array_values($handler_obj->context)));
		}

		ksort($tree);

		return $tree;
	}


	/**
	 * Get Elgg actions information
	 *
	 * returns [action] => array(file, access)
	 *
	 * @return array
	 */
	public function getActions() {
		$tree = array();
		$access = array(
			'public' => 'public',
			'logged_in' => 'logged in only',
			'admin' => 'admin only',
		);
		$start = strlen(elgg_get_root_path());
		foreach (_elgg_services()->actions->getAllActions() as $action => $info) {
			$info['file'] = substr($info['file'], $start);
			$tree[$action] = array($info['file'], $access[$info['access']]);
		}
		ksort($tree);
		return $tree;
	}

	/**
	 * Get simplecache information
	 *
	 * @return array [views]
	 */
	public function getSimpleCache() {
		global $CONFIG;

		$tree = array();
		foreach ($CONFIG->views->simplecache as $view => $foo) {
			$tree[$view] = "";
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get Elgg web services API methods
	 *
	 * @return array [method] => array(function, parameters, call_method, api auth, user auth)
	 */
	public function getWebServices() {
		global $API_METHODS;

		$tree = array();
		foreach ($API_METHODS as $method => $info) {
			$params = implode(', ', array_keys($info['parameters']));
			if (!$params) {
				$params = 'none';
			}
			$tree[$method] = array(
				$info['function'],
				"params: $params",
				$info['call_method'],
				($info['require_api_auth']) ? 'API authentication required' : 'No API authentication required',
				($info['require_user_auth']) ? 'User authentication required' : 'No user authentication required',
			);
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get information about registered menus
	 *
	 * @return array [menu name] => array(item name => array(text, href, section, parent))
	 */
	public function getMenus() {

		$menus = elgg_get_config('menus');

		// get JIT menu items
		// note that 'river' is absent from this list - hooks attempt to get object/subject entities cause problems
		$jit_menus = array('annotation', 'entity', 'login', 'longtext', 'owner_block', 'user_hover', 'widget');

		// create generic ElggEntity, ElggAnnotation, ElggUser, ElggWidget
		$annotation = new \ElggAnnotation();
		$annotation->id = 999;
		$annotation->name = 'generic_comment';
		$annotation->value = 'testvalue';

		$entity = new \ElggObject();
		$entity->guid = 999;
		$entity->subtype = 'blog';
		$entity->title = 'test entity';
		$entity->access_id = ACCESS_PUBLIC;

		$user = new \ElggUser();
		$user->guid = 999;
		$user->name = "Test User";
		$user->username = 'test_user';

		$widget = new \ElggWidget();
		$widget->guid = 999;
		$widget->title = 'test widget';

		// call plugin hooks
		foreach ($jit_menus as $type) {
			$params = array('entity' => $entity, 'annotation' => $annotation, 'user' => $user);
			switch ($type){
				case 'owner_block':
				case 'user_hover':
					$params['entity'] = $user;
					break;
				case 'widget':
					// this does not work because you cannot set a guid on an entity
					$params['entity'] = $widget;
					break;
				default:
					break;
			}
			$menus[$type] = elgg_trigger_plugin_hook('register', "menu:$type", $params, array());
		}

		// put the menus in tree form for inspection
		$tree = array();

		foreach ($menus as $menu_name => $attributes) {
			foreach ($attributes as $item) {
				/* @var \ElggMenuItem $item */
				$name = $item->getName();
				$text = htmlspecialchars($item->getText(), ENT_QUOTES, 'UTF-8', false);
				$href = $item->getHref();
				if ($href === false) {
					$href = 'not a link';
				} elseif ($href === "") {
					$href = 'not a direct link - possibly ajax';
				}
				$section = $item->getSection();
				$parent = $item->getParentName();
				if (!$parent) {
					$parent = 'none';
				}

				$tree[$menu_name][$name] = array(
					"text: $text",
					"href: $href",
					"section: $section",
					"parent: $parent",
				);
			}
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get a string description of a callback
	 *
	 * E.g. "function_name", "Static::method", "(ClassName)->method", "(Closure path/to/file.php:23)"
	 *
	 * @param mixed  $callable  The callable value to describe
	 * @param string $file_root if provided, it will be removed from the beginning of file names
	 * @return string
	 */
	public function describeCallable($callable, $file_root = '') {
		if (is_string($callable)) {
			return $callable;
		}
		if (is_array($callable) && array_keys($callable) === array(0, 1) && is_string($callable[1])) {
			if (is_string($callable[0])) {
				return "{$callable[0]}::{$callable[1]}";
			}
			return "(" . get_class($callable[0]) . ")->{$callable[1]}";
		}
		if ($callable instanceof \Closure) {
			$ref = new \ReflectionFunction($callable);
			$file = $ref->getFileName();
			$line = $ref->getStartLine();

			if ($file_root && 0 === strpos($file, $file_root)) {
				$file = substr($file, strlen($file_root));
			}

			return "(Closure {$file}:{$line})";
		}
		if (is_object($callable)) {
			return "(" . get_class($callable) . ")->__invoke()";
		}
		return "(unknown)";
	}

	/**
	 * Build a tree of event handlers
	 *
	 * @param array $all_handlers Set of handlers from a HooksRegistrationService
	 *
	 * @return array
	 */
	protected function buildHandlerTree($all_handlers) {
		$tree = array();
		$root = elgg_get_root_path();

		foreach ($all_handlers as $hook => $types) {
			foreach ($types as $type => $handlers) {
				array_walk($handlers, function (&$callable, $priority) use ($root) {
					$description = $this->describeCallable($callable, $root);
					$callable = "$priority: $description";
				});
				$tree[$hook . ',' . $type] = $handlers;
			}
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Create array of all php files in directory and subdirectories
	 *
	 * @param string $dir full path to directory to begin search
	 * @return array of every php file in $dir or below in file tree
	 */
	protected function recurseFileTree($dir) {
		$view_list = array();

		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if ($file[0] == '.') {

			} else if (is_dir($dir . $file)) {
				$view_list = array_merge($view_list, $this->recurseFileTree($dir . $file . "/"));
			} else {
				$extension = strrchr(trim($file, "/"), '.');
				if ($extension === ".php") {
					$view_list[] = $dir . $file;
				}
			}
		}
		closedir($handle);

		return $view_list;
	}
}
