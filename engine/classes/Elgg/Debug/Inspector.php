<?php

namespace Elgg\Debug;

use Elgg\Debug\Inspector\ViewComponent;
use Elgg\Includer;
use Elgg\Project\Paths;
use Elgg\Menu\MenuItems;

/**
 * Debug inspector
 *
 * @internal
 * @since 1.11
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
		$handlers = _elgg_services()->events->getAllHandlers();

		$input_filtered_views = [];
		if (!empty($handlers['view_vars'])) {
			$input_filtered_views = array_keys($handlers['view_vars']);
		}

		$filtered_views = [];
		if (!empty($handlers['view'])) {
			$filtered_views = array_keys($handlers['view']);
		}

		$global_events = [];
		if (!empty($handlers['view_vars']['all'])) {
			$global_events[] = 'view_vars, all';
		}
		
		if (!empty($handlers['view']['all'])) {
			$global_events[] = 'view, all';
		}

		return [
			'views' => $views,
			'global_events' => $global_events,
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
			'logged_out' => 'logged out only',
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
			$handler = $route->getDefault('_handler') ?: '';
			if ($handler) {
				$handler = $this->describeCallable($handler);
			}

			$controller = $route->getDefault('_controller') ?: '';
			if ($controller) {
				$controller = $this->describeCallable($controller);
			}

			$resource = $route->getDefault('_resource') ?: '';

			$file = $route->getDefault('_file') ?: '';

			$middleware = $route->getDefault('_middleware');
			if (!is_array($middleware)) {
				if (!empty($middleware)) {
					$middleware = [$middleware];
				} else {
					$middleware = [];
				}
			}
			
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
	 * Get information about registered menus
	 *
	 * @return array [menu name] => array(item name => array(text, href, section, parent))
	 */
	public function getMenus() {
		$menus = _elgg_services()->menus->getAllMenus();

		// get JIT menu items
		// note that 'river' is absent from this list - events attempt to get object/subject entities cause problems
		$jit_menus = ['annotation', 'entity', 'login', 'longtext', 'owner_block', 'user_hover', 'widget'];

		// create generic ElggEntity, ElggAnnotation, ElggUser, ElggWidget
		$annotation = new \ElggAnnotation();
		$annotation->id = 999;
		$annotation->name = 'generic_comment';
		$annotation->value = 'testvalue';
		$annotation->entity_guid = elgg_get_logged_in_user_guid();

		$entity = new \ElggObject();
		$entity->guid = 999;
		$entity->setSubtype('blog');
		$entity->title = 'test entity';
		$entity->access_id = ACCESS_PUBLIC;

		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			$user = new \ElggUser();
			$user->guid = 999;
			$user->name = 'Test User';
			$user->username = 'test_user';
		}

		$widget = new \ElggWidget();
		$widget->guid = 999;
		$widget->title = 'test widget';

		// call events
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
			
			$menus[$type] = _elgg_services()->events->triggerResults('register', "menu:{$type}", $params, new MenuItems());
		}

		// put the menus in tree form for inspection
		$tree = [];

		foreach ($menus as $menu_name => $attributes) {
			/* @var \ElggMenuItem $item */
			foreach ($attributes as $item) {
				$name = $item->getName();
				$text = htmlspecialchars($item->getText() ?? '', ENT_QUOTES, 'UTF-8', false);
				$href = $item->getHref();
				if ($href === false) {
					$href = 'not a link';
				} elseif ($href === '') {
					$href = 'not a direct link - possibly ajax';
				}
				
				$section = $item->getSection();
				$parent = $item->getParentName();
				if (!$parent) {
					$parent = 'none';
				}

				$tree[$menu_name][$name] = [
					"text: {$text}",
					"href: {$href}",
					"section: {$section}",
					"parent: {$parent}",
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
	 * @param array $all_handlers Set of handlers from a EventsService
	 *
	 * @return array
	 */
	protected function buildHandlerTree($all_handlers) {
		$tree = [];
		$root = elgg_get_root_path();
		$handlers_svc = _elgg_services()->handlers;

		foreach ($all_handlers as $event => $types) {
			foreach ($types as $type => $priorities) {
				ksort($priorities);

				foreach ($priorities as $priority => $handlers) {
					foreach ($handlers as $callable) {
						$description = $handlers_svc->describeCallable($callable, $root);
						$callable = "{$priority}: {$description}";
						$tree["{$event}, {$type}"][] = $callable;
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
		$sources = [
			\Elgg\Project\Paths::elgg() . 'engine/public_services.php',
		];

		$plugins = _elgg_services()->plugins->find('active');
		foreach ($plugins as $plugin) {
			$plugin->autoload(); // make sure all classes are loaded
			$sources[] = $plugin->getPath() . \ElggPlugin::PUBLIC_SERVICES_FILENAME;
		}

		$tree = [];
		foreach ($sources as $source) {
			if (!is_file($source)) {
				continue;
			}
			
			$services = Includer::includeFile($source);

			foreach ($services as $name => $service) {
				$tree[$name] = [get_class(elgg()->$name), Paths::sanitize($source, false)];
			}
		}

		ksort($tree);

		return $tree;
	}
	
	/**
	 * Get the registered database CLI seeders
	 *
	 * @return string[]
	 */
	public function getSeeders(): array {
		return _elgg_services()->seeder->getSeederClasses();
	}
	
	/**
	 * Get all registered notification handlers
	 *
	 * @return array
	 * @since 6.3
	 */
	public function getNotifications(): array {
		return _elgg_services()->notifications->getEvents();
	}
}
