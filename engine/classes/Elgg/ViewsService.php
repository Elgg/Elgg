<?php
namespace Elgg;

use Elgg\Application\CacheHandler;
use Elgg\Cache\SystemCache;
use Elgg\Filesystem\Directory;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @since 1.9.0
 */
class ViewsService {

	/**
	 * @see \Elgg\ViewsService::fileExists
	 * @var array
	 */
	protected $file_exists_cache = array();

	/**
	 * @var array
	 *
	 * [viewtype][view] => '/path/to/views/style.css'
	 */
	private $locations = [];

	/**
	 * @var array
	 *
	 * [viewtype][view][] => '/path/to/views/style.css'
	 */
	private $overrides = [];

	/**
	 * @var \stdClass
	 */
	private $views;

	/**
	 * @var \stdClass A list of valid view types as discovered.
	 */
	private $viewtype;

	/**
	 * @var \stdClass Global Elgg configuration
	 */
	private $CONFIG;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var SystemCache|null This is set if the views are configured via cache
	 */
	private $cache;

	/**
	 * @var bool
	 */
	private $allow_delay_pagesetup = true;
	
	/**
	 * Constructor
	 *
	 * @param \Elgg\PluginHooksService $hooks  The hooks service
	 * @param \Elgg\Logger             $logger Logger
	 */
	public function __construct(\Elgg\PluginHooksService $hooks, \Elgg\Logger $logger) {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
		$this->hooks = $hooks;
		$this->logger = $logger;

		$this->views = (object)[
			'extensions' => [],
		];
		$this->viewtype = (object)[
			/**
			 * A list of views to cache in the simple cache.
			 */
			'simplecache' => [],
			'fallback' => [],
		];
	}
	
	/**
	 * Takes a view name and returns the canonical name for that view.
	 *
	 * @param string $alias The possibly non-canonical view name.
	 *
	 * @return string The canonical view name.
	 */
	public function canonicalizeViewName(/*string*/ $alias) /*: string*/ {
		if (!is_string($alias)) {
			return false;
		}
		
		
		$canonical = $alias;
		
		$extension = pathinfo($canonical, PATHINFO_EXTENSION);
		$hasValidFileExtension = isset(CacheHandler::$extensions[$extension]);

		if (strpos($canonical, "js/") === 0) {
			$canonical = substr($canonical, 3);
			if (!$hasValidFileExtension) {
				$canonical .= ".js";
			}
		} else if (strpos($canonical, "css/") === 0) {
			$canonical = substr($canonical, 4);
			if (!$hasValidFileExtension) {
				$canonical .= ".css";
			}
		}
		
		return $canonical;
	}

	/**
	 * @access private
	 */
	public function autoregisterViews($view_base, $folder, $viewtype) {
		$folder = rtrim($folder, '/\\');
		$view_base = rtrim($view_base, '/\\');

		$handle = opendir($folder);
		if (!$handle) {
			return false;
		}
		
		while ($entry = readdir($handle)) {
			if ($entry[0] === '.') {
				continue;
			}

			$path = "$folder/$entry";

			if (!empty($view_base)) {
				$view_base_new = $view_base . "/";
			} else {
				$view_base_new = "";
			}

			if (is_dir($path)) {
				$this->autoregisterViews($view_base_new . $entry, $path, $viewtype);
			} else {
				$view = $view_base_new . basename($entry, '.php');
				$this->setViewLocation($view, $viewtype, $path);
			}
		}

		return true;
	}

	/**
	 * Find the view file
	 *
	 * @param string $view     View name
	 * @param string $viewtype Viewtype
	 *
	 * @return string Empty string if not found
	 * @access private
	 * @internal Plugins should not use this.
	 */
	public function findViewFile($view, $viewtype) {
		if (!isset($this->locations[$viewtype][$view])) {
			return "";
		}

		$path = $this->locations[$viewtype][$view];
		if ($this->fileExists($path)) {
			return $path;
		}

		return "";
	}

	/**
	 * @param string $view     Name of the view
	 * @param string $location Full path to the view file
	 * @param string $viewtype The viewtype to register this under
	 *
	 * @access private
	 */
	public function setViewDir($view, $location, $viewtype = '') {
		$view = $this->canonicalizeViewName($view);

		if (empty($viewtype)) {
			$viewtype = 'default';
		}

		$location = rtrim($location, '/\\');

		if ($this->fileExists("$location/$viewtype/$view.php")) {
			$this->setViewLocation($view, $viewtype, "$location/$viewtype/$view.php");
		} elseif ($this->fileExists("$location/$viewtype/$view")) {
			$this->setViewLocation($view, $viewtype, "$location/$viewtype/$view");
		}
	}

	/**
	 * @access private
	 */
	public function registerViewtypeFallback($viewtype) {
		$this->viewtype->fallback[] = $viewtype;
	}

	/**
	 * @access private
	 */
	public function doesViewtypeFallback($viewtype) {
		return in_array($viewtype, $this->viewtype->fallback);
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
		$view = $this->canonicalizeViewName($view);

		$rendered = $this->renderView($view, $vars, false, '', false);
		if ($rendered) {
			elgg_deprecated_notice("The $view view has been deprecated. $suggestion", $version, 3);
		}
		return $rendered;
	}

	/**
	 * Get the views, including extensions, used to render this view
	 *
	 * Keys returned are view priorities.
	 *
	 * @param string $view View name
	 * @return string[]
	 * @access private
	 * @internal Plugins should not use this
	 */
	public function getViewList($view) {
		if (isset($this->views->extensions[$view])) {
			return $this->views->extensions[$view];
		} else {
			return [500 => $view];
		}
	}

	/**
	 * @access private
	 */
	public function renderView($view, array $vars = array(), $bypass = false, $viewtype = '', $issue_missing_notice = true) {
		$view = $this->canonicalizeViewName($view);

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

		// allow altering $vars
		$vars_hook_params = [
			'view' => $view,
			'vars' => $vars,
			'viewtype' => $viewtype,
		];
		$vars = $this->hooks->trigger('view_vars', $view, $vars_hook_params, $vars);

		$view_orig = $view;

		$this->handlePageSetup($view);

		$viewlist = $this->getViewList($view);

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
		$content = $this->hooks->trigger('view', $view_orig, $params, $content);

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
	 * Trigger the system "pagesetup" event just before the 1st view rendering, or the 2nd if the 1st
	 * view starts with "resources/".
	 *
	 * We delay the pagesetup event if the first view is a resource view in order to allow plugins to
	 * move all page-specific logic like context setting into a resource view with more confidence
	 * that that state will be available in their pagesetup event handlers. See the commit message for
	 * more BG info.
	 *
	 * @param string $view View about to be rendered
	 * @return void
	 */
	private function handlePageSetup($view) {
		if (isset($GLOBALS['_ELGG']->pagesetupdone) || empty($this->CONFIG->boot_complete)) {
			return;
		}

		// only first rendering gets an opportunity to delay
		$allow_delay = $this->allow_delay_pagesetup;
		$this->allow_delay_pagesetup = false;

		if ($allow_delay && (0 === strpos($view, 'resources/'))) {
			return;
		}

		$GLOBALS['_ELGG']->pagesetupdone = true;
		_elgg_services()->events->trigger('pagesetup', 'system');
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
		$file = $this->findViewFile($view, $viewtype);
		if (!$file) {
			if ($issue_missing_notice) {
				$this->logger->log("$viewtype/$view view does not exist.", 'NOTICE');
			}
			return false;
		}

		if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
			ob_start();
			include $file;
			return ob_get_clean();
		}

		return file_get_contents($file);
	}

	/**
	 * @access private
	 */
	public function viewExists($view, $viewtype = '', $recurse = true) {
		$view = $this->canonicalizeViewName($view);
		
		if (empty($view) || !is_string($view)) {
			return false;
		}
		
		// Detect view type
		if ($viewtype === '' || !_elgg_is_valid_viewtype($viewtype)) {
			$viewtype = elgg_get_viewtype();
		}

		
		$file = $this->findViewFile($view, $viewtype);
		if ($file) {
			return true;
		}

		// If we got here then check whether this exists as an extension
		// We optionally recursively check whether the extended view exists also for the viewtype
		if ($recurse && isset($this->views->extensions[$view])) {
			foreach ($this->views->extensions[$view] as $view_extension) {
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
	public function extendView($view, $view_extension, $priority = 501) {
		$view = $this->canonicalizeViewName($view);
		$view_extension = $this->canonicalizeViewName($view_extension);

		if (!isset($this->views->extensions[$view])) {
			$this->views->extensions[$view][500] = (string) $view;
		}

		// raise priority until it doesn't match one already registered
		while (isset($this->views->extensions[$view][$priority])) {
			$priority++;
		}

		$this->views->extensions[$view][$priority] = (string) $view_extension;
		ksort($this->views->extensions[$view]);

	}

	/**
	 * @access private
	 */
	public function unextendView($view, $view_extension) {
		$view = $this->canonicalizeViewName($view);
		$view_extension = $this->canonicalizeViewName($view_extension);

		if (!isset($this->views->extensions[$view])) {
			return false;
		}

		$priority = array_search($view_extension, $this->views->extensions[$view]);
		if ($priority === false) {
			return false;
		}

		unset($this->views->extensions[$view][$priority]);

		return true;
	}

	/**
	 * @access private
	 */
	public function registerCacheableView($view) {
		$view = $this->canonicalizeViewName($view);

		$this->views->simplecache[$view] = true;
	}

	/**
	 * @access private
	 */
	public function isCacheableView($view) {
		$view = $this->canonicalizeViewName($view);
		if (isset($this->views->simplecache[$view])) {
			return true;
		}

		// build list of viewtypes to check
		$current_viewtype = elgg_get_viewtype();
		$viewtypes = array($current_viewtype);

		if ($this->doesViewtypeFallback($current_viewtype) && $current_viewtype != 'default') {
			$viewtypes[] = 'default';
		}

		// If a static view file is found in any viewtype, it's considered cacheable
		foreach ($viewtypes as $viewtype) {
			$file = $this->findViewFile($view, $viewtype);

			if ($file && pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
				$this->views->simplecache[$view] = true;
				return true;
			}
		}

		// Assume not-cacheable by default
		return false;
	}

	/**
	 * Register a plugin's views
	 *
	 * @param string $path       Base path of the plugin
	 * @param string $failed_dir This var is set to the failed directory if registration fails
	 * @return bool
	 *
	 * @access private
	 */
	public function registerPluginViews($path, &$failed_dir = '') {
		$path = rtrim($path, "\\/");
		$view_dir = "$path/views/";

		// plugins don't have to have views.
		if (!is_dir($view_dir)) {
			return true;
		}

		// but if they do, they have to be readable
		$handle = opendir($view_dir);
		if (!$handle) {
			$failed_dir = $view_dir;
			return false;
		}

		while (false !== ($view_type = readdir($handle))) {
			$view_type_dir = $view_dir . $view_type;

			if ('.' !== substr($view_type, 0, 1) && is_dir($view_type_dir)) {
				if ($this->autoregisterViews('', $view_type_dir, $view_type)) {
					elgg_register_viewtype($view_type);
				} else {
					$failed_dir = $view_type_dir;
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Merge a specification of absolute view paths
	 *
	 * @param array $spec Specification
	 *    viewtype => [
	 *        view_name => path
	 *    ]
	 *
	 * @access private
	 */
	public function mergeViewsSpec(array $spec) {
		foreach ($spec as $viewtype => $list) {
			foreach ($list as $view => $path) {
				if (preg_match('~^([/\\\\]|[a-zA-Z]\:)~', $path)) {
					// absolute path
				} else {
					// relative path
					$path = Directory\Local::root()->getPath($path);
				}

				if (substr($view, -1) === '/') {
					// prefix
					$this->autoregisterViews($view, $path, $viewtype);
				} else {
					$this->setViewLocation($view, $viewtype, $path);
				}
			}
		}
	}

	/**
	 * List all views in a viewtype
	 *
	 * @param string $viewtype Viewtype
	 *
	 * @return string[]
	 *
	 * @access private
	 */
	public function listViews($viewtype = 'default') {
		if (empty($this->locations[$viewtype])) {
			return [];
		}
		return array_keys($this->locations[$viewtype]);
	}

	/**
	 * Get inspector data
	 *
	 * @return array
	 *
	 * @access private
	 */
	public function getInspectorData() {
		$overrides = $this->overrides;

		if ($this->cache) {
			$data = $this->cache->load('view_overrides');
			if ($data) {
				$overrides = unserialize($data);
			}
		}

		return [
			'locations' => $this->locations,
			'overrides' => $overrides,
			'extensions' => $this->views->extensions,
			'simplecache' => $this->views->simplecache,
		];
	}

	/**
	 * Configure locations from the cache
	 *
	 * @param SystemCache $cache The system cache
	 * @return bool
	 * @access private
	 */
	public function configureFromCache(SystemCache $cache) {
		$data = $cache->load('view_locations');
		if (!is_string($data)) {
			return false;
		}
		// format changed, check version
		$data = unserialize($data);
		if (empty($data['version']) || $data['version'] !== '2.0') {
			return false;
		}
		$this->locations = $data['locations'];
		$this->cache = $cache;

		return true;
	}

	/**
	 * Cache the configuration
	 *
	 * @param SystemCache $cache The system cache
	 * @return void
	 * @access private
	 */
	public function cacheConfiguration(SystemCache $cache) {
		$cache->save('view_locations', serialize([
			'version' => '2.0',
			'locations' => $this->locations,
		]));

		// this is saved just for the inspector and is not loaded in loadAll()
		$cache->save('view_overrides', serialize($this->overrides));
	}

	/**
	 * Update the location of a view file
	 *
	 * @param string $view     View name
	 * @param string $viewtype Viewtype
	 * @param string $path     File path
	 *
	 * @return void
	 */
	private function setViewLocation($view, $viewtype, $path) {
		$view = $this->canonicalizeViewName($view);
		$path = strtr($path, '\\', '/');

		if (isset($this->locations[$viewtype][$view]) && $path !== $this->locations[$viewtype][$view]) {
			$this->overrides[$viewtype][$view][] = $this->locations[$viewtype][$view];
		}
		$this->locations[$viewtype][$view] = $path;
	}
}
