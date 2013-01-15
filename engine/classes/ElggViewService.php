<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Use the elgg_* versions instead.
 * 
 * @access private
 * @since 1.9.0
 */
class ElggViewService {

	/**
	 * @access private
	 * @since 1.9.0
	 */
	public function view($view, array $vars = array(), $bypass = false, $viewtype = '') {
		global $CONFIG;

		if (!is_string($view) || !is_string($viewtype)) {
			elgg_log("View and Viewtype in views must be a strings: $view", 'NOTICE');
			return '';
		}
		// basic checking for bad paths
		if (strpos($view, '..') !== false) {
			return '';
		}

		if (!is_array($vars)) {
			elgg_log("Vars in views must be an array: $view", 'ERROR');
			$vars = array();
		}

		// Get the current viewtype
		if ($viewtype === '') {
			$viewtype = elgg_get_viewtype();
		} elseif (preg_match('/\W/', $viewtype)) {
			// Viewtypes can only be alphanumeric
			return '';
		}
	
		$view_orig = $view;
	
		// Trigger the pagesetup event
		if (!isset($CONFIG->pagesetupdone) && $CONFIG->boot_complete) {
			$CONFIG->pagesetupdone = true;
			elgg_trigger_event('pagesetup', 'system');
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
		if (isset($vars['internalname']) && !isset($vars['__ignoreInternalname']) && !isset($vars['name'])) {
			elgg_deprecated_notice('You should pass $vars[\'name\'] now instead of $vars[\'internalname\']', 1.8, 2);
			$vars['name'] = $vars['internalname'];
		} elseif (isset($vars['name'])) {
			if (!isset($vars['internalname'])) {
				$vars['__ignoreInternalname'] = '';
			}
			$vars['internalname'] = $vars['name'];
		}
	
		// internalid => id (1.8)
		if (isset($vars['internalid']) && !isset($vars['__ignoreInternalid']) && !isset($vars['name'])) {
			elgg_deprecated_notice('You should pass $vars[\'id\'] now instead of $vars[\'internalid\']', 1.8, 2);
			$vars['id'] = $vars['internalid'];
		} elseif (isset($vars['id'])) {
			if (!isset($vars['internalid'])) {
				$vars['__ignoreInternalid'] = '';
			}
			$vars['internalid'] = $vars['id'];
		}
	
		// If it's been requested, pass off to a template handler instead
		if ($bypass == false && isset($CONFIG->template_handler) && !empty($CONFIG->template_handler)) {
			$template_handler = $CONFIG->template_handler;
			if (is_callable($template_handler)) {
				return call_user_func($template_handler, $view, $vars);
			}
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

			// try to include view
			if (!file_exists($view_file) || !include($view_file)) {
				// requested view does not exist
				$error = "$viewtype/$view view does not exist.";
	
				// attempt to load default view
				if ($viewtype !== 'default' && elgg_does_viewtype_fallback($viewtype)) {

					$default_location = elgg_get_view_location($view, 'default');
					$default_view_file = "{$default_location}default/$view.php";

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
	
		if ($content_tmp !== $content) {
			$content = $content_tmp;
			elgg_deprecated_notice('The display:view plugin hook is deprecated by view:view_name', 1.8);
		}
	
		return $content;
	}
	
	/**
	 * @access private
	 * @since 1.9.0
	 */
	public function viewExists($view, $viewtype = '', $recurse = true) {
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
	
		if (file_exists("{$location}{$viewtype}/{$view}.php")) {
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
	
		// Now check if the default view exists if the view is registered as a fallback
		if ($viewtype != 'default' && elgg_does_viewtype_fallback($viewtype)) {
			return elgg_view_exists($view, 'default');
		}
	
		return false;

	}

	/**
	 * @access private
	 * @since 1.9.0
	 */
	public function extendView($view, $view_extension, $priority = 501, $viewtype = '') {
		global $CONFIG;

		if (!isset($CONFIG->views)) {
			$CONFIG->views = (object) array(
				'extensions' => array(),
			);
			$CONFIG->views->extensions[$view][500] = (string) $view;
		} else {
			if (!isset($CONFIG->views->extensions[$view])) {
				$CONFIG->views->extensions[$view][500] = (string) $view;
			}
		}

		// raise priority until it doesn't match one already registered
		while (isset($CONFIG->views->extensions[$view][$priority])) {
			$priority++;
		}
	
		$CONFIG->views->extensions[$view][$priority] = (string) $view_extension;
		ksort($CONFIG->views->extensions[$view]);

	}
	
	/**
	 * @access private
	 * @since 1.9.0
	 */
	public function unextendView($view, $view_extension) {
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

	public static function getInstance() {
		static $instance;
		
		if(!isset($instance)) {
			$instance = new ElggViewService();
		}
		
		return $instance;
	}

}