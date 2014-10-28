<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @todo 1.10 remove deprecated view injections
 * @todo inject/remove dependencies: $CONFIG, hooks, site_url
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Views
 * @since      1.9.0
 */
class Elgg_ViewsService {

	protected $config_wrapper;
	protected $site_url_wrapper;
	protected $user_wrapper;
	protected $user_wrapped;

	/**
	 * @see Elgg_ViewsService::fileExists
	 * @var array
	 */
	protected $file_exists_cache = array();

	/**
	 * Constructor
	 *
	 * @param Elgg_PluginHooksService $hooks  The hooks service
	 * @param Elgg_Logger             $logger Logger
	 */
	public function __construct(Elgg_PluginHooksService $hooks, Elgg_Logger $logger) {
		$this->hooks = $hooks;
		$this->logger = $logger;
	}

	/**
	 * Get the user object in a wrapper
	 *
	 * @return Elgg_DeprecationWrapper|null
	 */
	protected function getUserWrapper() {
		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			if ($user !== $this->user_wrapped) {
				$warning = 'Use elgg_get_logged_in_user_entity() rather than assuming elgg_view() '
							. 'populates $vars["user"]';
				$this->user_wrapper = new Elgg_DeprecationWrapper($user, $warning, 1.8);
			}
			$user = $this->user_wrapper;
		}
		return $user;
	}

	/**
	 * @access private
	 */
	public function autoregisterViews($view_base, $folder, $base_location_path, $viewtype) {
		$handle = opendir($folder);
		if ($handle) {
			while ($view = readdir($handle)) {
				if (!empty($view_base)) {
					$view_base_new = $view_base . "/";
				} else {
					$view_base_new = "";
				}

				if (substr($view, 0, 1) !== '.') {
					if (is_dir($folder . "/" . $view)) {
						$this->autoregisterViews($view_base_new . $view, $folder . "/" . $view,
							$base_location_path, $viewtype);
					} else {
						$this->setViewLocation($view_base_new . basename($view, '.php'),
							$base_location_path, $viewtype);
					}
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * @access private
	 */
	public function getViewLocation($view, $viewtype = '') {
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
	}

	/**
	 * @access private
	 */
	public function setViewLocation($view, $location, $viewtype = '') {
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
	 * @access private
	 */
	public function registerViewtypeFallback($viewtype) {
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
	 * @access private
	 */
	public function doesViewtypeFallback($viewtype) {
		global $CONFIG;

		if (isset($CONFIG->viewtype) && isset($CONFIG->viewtype->fallback)) {
			return in_array($viewtype, $CONFIG->viewtype->fallback);
		}

		return false;
	}

	/**
	 * Display a view with a deprecation notice. No missing view NOTICE is logged
	 *
	 * @see elgg_view()
	 *
	 * @param string  $view       The name and location of the view to use
	 * @param array   $vars       Variables to pass to the view
	 * @param string  $suggestion Suggestion with the deprecation message
	 * @param string  $version    Human-readable *release* version: 1.7, 1.8, ...
	 *
	 * @return string The parsed view
	 * @access private
	 */
	public function renderDeprecatedView($view, array $vars, $suggestion, $version) {
		$rendered = $this->renderView($view, $vars, false, '', false);
		if ($rendered) {
			elgg_deprecated_notice("The $view view has been deprecated. $suggestion", $version, 3);
		}
		return $rendered;
	}

	/**
	 * @access private
	 */
	public function renderView($view, array $vars = array(), $bypass = false, $viewtype = '', $issue_missing_notice = true) {
		global $CONFIG;

		if (!is_string($view) || !is_string($viewtype)) {
			$this->logger->log("View and Viewtype in views must be a strings: $view", 'NOTICE');
			return '';
		}
		// basic checking for bad paths
		if (strpos($view, '..') !== false) {
			return '';
		}

		if (!is_array($vars)) {
			$this->logger->log("Vars in views must be an array: $view", 'ERROR');
			$vars = array();
		}

		// Get the current viewtype
		if ($viewtype === '' || !_elgg_is_valid_viewtype($viewtype)) {
			$viewtype = elgg_get_viewtype();
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
			$vars['user'] = $this->getUserWrapper();
		}
		if (!isset($vars['config'])) {
			if (!$this->config_wrapper) {
				$warning = 'Do not rely on $vars["config"] or $CONFIG being available in views';
				$this->config_wrapper = new Elgg_DeprecationWrapper($CONFIG, $warning, 1.8);
			}
			$vars['config'] = $this->config_wrapper;
		}
		if (!isset($vars['url'])) {
			if (!$this->site_url_wrapper) {
				$warning = 'Do not rely on $vars["url"] being available in views';
				$this->site_url_wrapper = new Elgg_DeprecationWrapper(elgg_get_site_url(), $warning, 1.8);
			}
			$vars['url'] = $this->site_url_wrapper;
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

		$content = '';
		foreach ($viewlist as $view) {

			$rendering = $this->renderViewFile($view, $vars, $viewtype, $issue_missing_notice);
			if ($rendering !== false) {
				$content .= $rendering;
				continue;
			}

			// attempt to load default view
			if ($viewtype !== 'default' && $this->doesViewtypeFallback($viewtype)) {

				$rendering = $this->renderViewFile($view, $vars, 'default', $issue_missing_notice);
				if ($rendering !== false) {
					$content .= $rendering;
				}
			}
		}

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
	 * Wrapper for file_exists() that caches false results (the stat cache only caches true results).
	 * This saves us from many unneeded file stat calls when a common view uses a fallback.
	 *
	 * @param string $path Path to the file
	 * @return bool
	 */
	protected function fileExists($path) {
		if (!isset($this->file_exists_cache[$path])) {
			$this->file_exists_cache[$path] = file_exists($path);
		}
		return $this->file_exists_cache[$path];
	}

	/**
	 * Includes view PHP or static file
	 *
	 * @param string $view                 The view name
	 * @param array  $vars                 Variables passed to view
	 * @param string $viewtype             The viewtype
	 * @param bool   $issue_missing_notice Log a notice if the view is missing
	 *
	 * @return string|false output generated by view file inclusion or false
	 */
	private function renderViewFile($view, array $vars, $viewtype, $issue_missing_notice) {
		$view_location = $this->getViewLocation($view, $viewtype);

		// @warning - plugin authors: do not expect $CONFIG to be available in views
		// in the future. Instead, use elgg_get_config() in your views.
		// Note: this is intentionally a local var.
		$CONFIG = $this->config_wrapper;

		if ($this->fileExists("{$view_location}$viewtype/$view.php")) {
			ob_start();
			include("{$view_location}$viewtype/$view.php");
			return ob_get_clean();
		} else if ($this->fileExists("{$view_location}$viewtype/$view")) {
			return file_get_contents("{$view_location}$viewtype/$view");
		} else {
			if ($issue_missing_notice) {
				$this->logger->log("$viewtype/$view view does not exist.", 'NOTICE');
			}
			return false;
		}
	}

	/**
	 * @access private
	 */
	public function viewExists($view, $viewtype = '', $recurse = true) {
		global $CONFIG;

		// Detect view type
		if ($viewtype === '' || !_elgg_is_valid_viewtype($viewtype)) {
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

		if ($this->fileExists("{$location}$viewtype/$view.php") ||
				$this->fileExists("{$location}$viewtype/$view")) {
			return true;
		}

		// If we got here then check whether this exists as an extension
		// We optionally recursively check whether the extended view exists also for the viewtype
		if ($recurse && isset($CONFIG->views->extensions[$view])) {
			foreach ($CONFIG->views->extensions[$view] as $view_extension) {
				// do not recursively check to stay away from infinite loops
				if ($this->viewExists($view_extension, $viewtype, false)) {
					return true;
				}
			}
		}

		// Now check if the default view exists if the view is registered as a fallback
		if ($viewtype != 'default' && $this->doesViewtypeFallback($viewtype)) {
			return $this->viewExists($view, 'default');
		}

		return false;

	}

	/**
	 * @access private
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
	 */
	public function unextendView($view, $view_extension) {
		global $CONFIG;

		if (!isset($CONFIG->views)) {
			return false;
		}

		if (!isset($CONFIG->views->extensions)) {
			return false;
		}

		if (!isset($CONFIG->views->extensions[$view])) {
			return false;
		}

		$priority = array_search($view_extension, $CONFIG->views->extensions[$view]);
		if ($priority === false) {
			return false;
		}

		unset($CONFIG->views->extensions[$view][$priority]);

		return true;
	}

	/**
	 * @access private
	 */
	public function registerCacheableView($view) {
		global $CONFIG;

		if (!isset($CONFIG->views)) {
			$CONFIG->views = new stdClass;
		}

		if (!isset($CONFIG->views->simplecache)) {
			$CONFIG->views->simplecache = array();
		}

		$CONFIG->views->simplecache[$view] = true;
	}

	/**
	 * @access private
	 */
	public function isCacheableView($view) {
		global $CONFIG;

		if (!isset($CONFIG->views)) {
			$CONFIG->views = new stdClass;
		}

		if (!isset($CONFIG->views->simplecache)) {
			$CONFIG->views->simplecache = array();
		}

		if (isset($CONFIG->views->simplecache[$view])) {
			return true;
		} else {
			$currentViewtype = elgg_get_viewtype();
			$viewtypes = array($currentViewtype);

			if ($this->doesViewtypeFallback($currentViewtype) && $currentViewtype != 'default') {
				$viewtypes[] = 'defaut';
			}

			// If a static view file is found in any viewtype, it's considered cacheable
			foreach ($viewtypes as $viewtype) {
				$view_file = $this->getViewLocation($view, $viewtype) . "$viewtype/$view";
				if ($this->fileExists($view_file)) {
					return true;
				}
			}

			// Assume not-cacheable by default
			return false;
		}
	}
}