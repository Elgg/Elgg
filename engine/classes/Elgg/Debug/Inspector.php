<?php
namespace Elgg\Debug;

use Elgg\Debug\Inspector\ViewComponent;
use Elgg\Includer;
use Elgg\Project\Paths;

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
		return array_keys($this->getViewsData()['locations']);
	}

	/**
	 * Get Elgg view information
	 *
	 * @param string $viewtype The Viewtype we wish to inspect
	 *
	 * @return array [view] => map of priority to ViewComponent[]
	 */
	public function getViews($viewtype = 'default') {
		$view_data = $this->getViewsData();

		// maps view name to array of ViewComponent[] with priority as keys
		$views = [];

		// add plugins and handle overrides
		foreach ($view_data['locations'][$viewtype] as $view => $location) {
			$component = new ViewComponent();
			$component->view = $view;
			$component->file = $location;

			$views[$view] = [500 => $component];
		}

		// now extensions
		foreach ($view_data['extensions'] as $view => $extensions) {
			$view_list = [];
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
			if (!empty($view_data['overrides'][$viewtype][$view])) {
				$overrides_list = [];
				foreach ($view_data['overrides'][$viewtype][$view] as $i => $location) {
					$component = new ViewComponent();
					$component->overridden = true;
					$component->view = $view;
					$component->file = $location;

					$overrides_list["o:$i"] = $component;
				}
				$views[$view] = $overrides_list + $view_list;
			}
		}

		// view handlers
		$handlers = _elgg_services()->hooks->getAllHandlers();

		$input_filtered_views = [];
		if (!empty($handlers['view_vars'])) {
			$input_filtered_views = array_keys($handlers['view_vars']);
		}

		$filtered_views = [];
		if (!empty($handlers['view'])) {
			$filtered_views = array_keys($handlers['view']);
		}

		$global_hooks = [];
		if (!empty($handlers['view_vars']['all'])) {
			$global_hooks[] = 'view_vars, all';
		}
		if (!empty($handlers['view']['all'])) {
			$global_hooks[] = 'view, all';
		}

		return [
			'views' => $views,
			'global_hooks' => $global_hooks,
			'input_filtered_views' => $input_filtered_views,
			'filtered_views' => $filtered_views,
		];
	}

	/**
	 * Get Elgg widget information
	 *
	 * @return array [widget] => array(name, contexts)
	 */
	public function getWidgets() {
		$tree = [];
		foreach (_elgg_services()->widgets->getAllTypes() as $handler => $handler_obj) {
			$tree[$handler] = [$handler_obj->name, implode(',', array_values($handler_obj->context))];
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
		$tree = [];
		$access = [
			'public' => 'public',
			'logged_in' => 'logged in only',
			'admin' => 'admin only',
		];
		$start = strlen(elgg_get_root_path());
		foreach (_elgg_services()->actions->getAllActions() as $action => $info) {
			if (isset($info['file'])) {
				$info['file'] = substr($info['file'], $start);
			} else if ($info['controller']) {
				$info['file'] = $this->describeCallable($info['controller']);
			}
			$tree[$action] = [$info['file'], $access[$info['access']]];
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
		
		$simplecache = elgg_extract('simplecache', $this->getViewsData(), []);
		$locations = elgg_extract('locations', $this->getViewsData(), []);
		
		$tree = [];
		foreach ($simplecache as $view => $foo) {
			$tree[$view] = '';
		}
		
		// add all static views
		foreach ($locations as $viewtype) {
			foreach ($viewtype as $view => $location) {
				if (pathinfo($location, PATHINFO_EXTENSION) !== 'php') {
					$tree[$view] = '';
				}
			}
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get Elgg route information
	 *
	 * returns [route] => array(path, resource)
	 *
	 * @return array
	 */
	public function getRoutes() {
		$tree = [];
		foreach (_elgg_services()->routeCollection->all() as $name => $route) {
			$handler = $route->getDefault('_handler') ? : '';
			if ($handler) {
				$handler = $this->describeCallable($handler);
			}

			$controller = $route->getDefault('_controller') ? : '';
			if ($controller) {
				$controller = $this->describeCallable($controller);
			}

			$resource = $route->getDefault('_resource') ? : '';

			$file = $route->getDefault('_file') ? : '';

			$middleware = $route->getDefault('_middleware') ? : '';
			$middleware = array_map(function($e) {
				return $this->describeCallable($e);
			}, $middleware);

			$tree[$name] = [
				$route->getPath(),
				$resource,
				$handler,
				$controller,
				$file,
				$middleware,
			];
		}
		uasort($tree, function($e1, $e2) {
			return strcmp($e1[0], $e2[0]);
		});

		return $tree;
	}

	/**
	 * Get Elgg web services API methods
	 *
	 * @return array [method] => array(function, parameters, call_method, api auth, user auth)
	 */
	public function getWebServices() {
		global $API_METHODS;

		$tree = [];
		foreach ($API_METHODS as $method => $info) {
			$params = implode(', ', array_keys(elgg_extract('parameters', $info, [])));
			if (!$params) {
				$params = 'none';
			}
			$tree[$method] = [
				$info['function'],
				"params: $params",
				$info['call_method'],
				($info['require_api_auth']) ? 'API authentication required' : 'No API authentication required',
				($info['require_user_auth']) ? 'User authentication required' : 'No user authentication required',
			];
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

		$menus = _elgg_config()->menus;

		// get JIT menu items
		// note that 'river' is absent from this list - hooks attempt to get object/subject entities cause problems
		$jit_menus = ['annotation', 'entity', 'login', 'longtext', 'owner_block', 'user_hover', 'widget'];

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
			$params = ['entity' => $entity, 'annotation' => $annotation, 'user' => $user];
			switch ($type) {
				case 'owner_block':
				case 'user_hover':
					$params['entity'] = $user;
					break;
				case 'widget':
					// this does not work because you cannot set a guid on an entity
					$params['entity'] = $widget;
					break;
				case 'longtext':
					$params['id'] = rand();
					break;
				default:
					break;
			}
			$menus[$type] = _elgg_services()->hooks->trigger('register', "menu:$type", $params, []);
		}

		// put the menus in tree form for inspection
		$tree = [];

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

				$tree[$menu_name][$name] = [
					"text: $text",
					"href: $href",
					"section: $section",
					"parent: $parent",
				];
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
	 * @param mixed  $callable  Callable
	 * @param string $file_root If provided, it will be removed from the beginning of file names
	 * @return string
	 */
	public function describeCallable($callable, $file_root = '') {
		return _elgg_services()->handlers->describeCallable($callable, $file_root);
	}

	/**
	 * Build a tree of event handlers
	 *
	 * @param array $all_handlers Set of handlers from a HooksRegistrationService
	 *
	 * @return array
	 */
	protected function buildHandlerTree($all_handlers) {
		$tree = [];
		$root = elgg_get_root_path();
		$handlers_svc = _elgg_services()->handlers;

		foreach ($all_handlers as $hook => $types) {
			foreach ($types as $type => $priorities) {
				ksort($priorities);

				foreach ($priorities as $priority => $handlers) {
					foreach ($handlers as $callable) {
						$description = $handlers_svc->describeCallable($callable, $root);
						$callable = "$priority: $description";
						$tree["$hook, $type"][] = $callable;
					}
				}
			}
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get data from the Views service
	 *
	 * @return array
	 */
	private function getViewsData() {
		static $data;
		if ($data === null) {
			$data = _elgg_services()->views->getInspectorData();
		}
		return $data;
	}

	/**
	 * Returns public DI services
	 *
	 * returns [service_name => [class, path]]
	 *
	 * @return array
	 */
	public function getServices() {
		$tree = [];

		foreach (_elgg_services()->dic_loader->getDefinitions() as $definition) {
			$services = Includer::includeFile($definition);

			foreach ($services as $name => $service) {
				$tree[$name] = [get_class(elgg()->$name), Paths::sanitize($definition, false)];
			}
		}

		ksort($tree);

		return $tree;
	}
}
