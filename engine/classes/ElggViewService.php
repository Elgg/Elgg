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
		static $usercache;
	
		$view = (string)$view;
	
		// basic checking for bad paths
		if (strpos($view, '..') !== false) {
			return false;
		}
	
		$view_orig = $view;
	
		// Trigger the pagesetup event
		if (!isset($CONFIG->pagesetupdone) && $CONFIG->boot_complete) {
			$CONFIG->pagesetupdone = true;
			elgg_trigger_event('pagesetup', 'system');
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

	public static function getInstance() {
		static $instance;
		
		if(!isset($instance)) {
			$instance = new ElggViewService();
		}
		
		return $instance;
	}

}