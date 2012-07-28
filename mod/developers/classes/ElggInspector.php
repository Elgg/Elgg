<?php
/**
 * Inspect Elgg variables
 *
 */

class ElggInspector {

	/**
	 * Get Elgg event information
	 *
	 * returns [event,type] => array(handlers)
	 */
	public function getEvents() {
		global $CONFIG;

		$tree = array();
		foreach ($CONFIG->events as $event => $types) {
			foreach ($types as $type => $handlers) {
				$tree[$event . ',' . $type] = array_values($handlers);
			}
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get Elgg plugin hooks information
	 *
	 * returns [hook,type] => array(handlers)
	 */
	public function getPluginHooks() {
		global $CONFIG;

		$tree = array();
		foreach ($CONFIG->hooks as $hook => $types) {
			foreach ($types as $type => $handlers) {
				$tree[$hook . ',' . $type] = array_values($handlers);
			}
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get Elgg view information
	 *
	 * returns [view] => array(view location and extensions)
	 */
	public function getViews() {
		global $CONFIG;

		$coreViews = $this->recurseFileTree($CONFIG->viewpath . "default/");

		// remove base path and php extension
		array_walk($coreViews, create_function('&$v,$k', 'global $CONFIG; $v = substr($v, strlen($CONFIG->viewpath . "default/"), -4);'));

		// setup views array before adding extensions and plugin views
		$views = array();
		foreach ($coreViews as $view) {
			$views[$view] = array($CONFIG->viewpath . "default/" . $view . ".php");
		}

		// add plugins and handle overrides
		foreach ($CONFIG->views->locations['default'] as $view => $location) {
			$views[$view] = array($location . $view . ".php");
		}

		// now extensions
		foreach ($CONFIG->views->extensions as $view => $extensions) {
			$view_list = array();
			foreach ($extensions as $priority => $ext_view) {
				if (isset($views[$ext_view])) {
					$view_list[] = $views[$ext_view][0];
				}
			}
			if (count($view_list) > 0) {
				$views[$view] = $view_list;
			}
		}

		ksort($views);

		return $views;
	}

	/**
	 * Get Elgg widget information
	 *
	 * returns [widget] => array(name, contexts)
	 */
	public function getWidgets() {
		global $CONFIG;

		$tree = array();
		foreach ($CONFIG->widgets->handlers as $handler => $handler_obj) {
			$tree[$handler] = array($handler_obj->name, implode(',', array_values($handler_obj->context)));
		}

		ksort($tree);

		return $tree;
	}


	/**
	 * Get Elgg actions information
	 *
	 * returns [action] => array(file, public, admin)
	 */
	public function getActions() {
		global $CONFIG;

		$tree = array();
		foreach ($CONFIG->actions as $action => $info) {
			$tree[$action] = array($info['file'], ($info['public']) ? 'public' : 'logged in only', ($info['admin']) ? 'admin only' : 'non-admin');
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get simplecache information
	 *
	 * returns [views]
	 */
	public function getSimpleCache() {
		global $CONFIG;

		$tree = array();
		foreach ($CONFIG->views->simplecache as $view) {
			$tree[$view] = "";
		}

		ksort($tree);

		return $tree;
	}

	/**
	 * Get Elgg web services API methods
	 *
	 * returns [method] => array(function, parameters, call_method, api auth, user auth)
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
	*	gets information about registered menus
	*
	*	returns array('Link Text' => array('Menu Name', 'Item Name', 'Link Text', 'Href', 'Section', 'Parent')
	*/
	
	public function getMenus() {
		global $CONFIG;
    
		//create user in memory to get as many user:hover menu items as possible
		$user = new ElggUser();
		$user->username = 'test_user';
		$user->name = "Test User";
		$user_hover_menu = elgg_trigger_plugin_hook('register', 'menu:user_hover', array('entity' => $user), array());
		
		$menus = $CONFIG->menus;
		$menus['user_hover'] = $user_hover_menu;
    
		$tree = array();

		foreach($menus as $menu_name => $attributes){
			foreach($attributes as $item){
				$name = $item->getName();
				$text = $item->getText();
				$href = $item->getHref();
				$section = $item->getSection();
				$parent = $item->getParentName();
    
				$tree[$menu_name][$text] = array(
							"Name: $name",
							"Href: $href",
							"Section: $section",
							"Parent: $parent"
				);
			}
		}
		
		ksort($tree);
		
		return $tree;
	}

	/**
	 * Create array of all php files in directory and subdirectories
	 *
	 * @param $dir full path to directory to begin search
	 * @return array of every php file in $dir or below in file tree
	 */
	protected function recurseFileTree($dir) {
		$view_list = array();

		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if ($file[0] == '.') {

			} else if (is_dir($dir . $file)) {
				$view_list = array_merge($view_list, $this->recurseFileTree($dir . $file. "/"));
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
