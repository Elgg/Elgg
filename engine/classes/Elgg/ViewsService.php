<?php

namespace Elgg;

use Elgg\Application\CacheHandler;
use Elgg\Cache\SystemCache;
use Elgg\Filesystem\Directory;
use Elgg\Http\Request;
use Psr\Log\LoggerInterface;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @since  1.9.0
 */
class ViewsService {

	use Loggable;

	const VIEW_HOOK = 'view';
	const VIEW_VARS_HOOK = 'view_vars';
	const OUTPUT_KEY = '__view_output';
	const BASE_VIEW_PRIORITY = 500;

	/**
	 * @see fileExists
	 * @var array
	 */
	protected $file_exists_cache = [];

	/**
	 * @var array
	 *
	 * [viewtype][view] => '/path/to/views/style.css'
	 */
	private $locations = [];

	/**
	 * @var array Tracks location changes for views
	 *
	 * [viewtype][view][] => '/path/to/views/style.css'
	 */
	private $overrides = [];

	/**
	 * @var array Simplecache views (view names are keys)
	 *
	 * [view] = true
	 */
	private $simplecache_views = [];

	/**
	 * @var array
	 *
	 * [view][priority] = extension_view
	 */
	private $extensions = [];

	/**
	 * @var string[] A list of fallback viewtypes
	 */
	private $fallbacks = [];

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var SystemCache|null This is set if the views are configured via cache
	 */
	private $cache;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var string
	 */
	private $viewtype;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks   The hooks service
	 * @param LoggerInterface    $logger  Logger
	 * @param Request            $request Http Request
	 */
	public function __construct(PluginHooksService $hooks, LoggerInterface $logger, Request $request = null) {
		$this->hooks = $hooks;
		$this->logger = $logger;
		$this->request = $request;
	}

	/**
	 * Set the viewtype
	 *
	 * @param string $viewtype Viewtype
	 *
	 * @return bool
	 */
	public function setViewtype($viewtype = '') {
		if (!$viewtype) {
			$this->viewtype = null;

			return true;
		}
		if ($this->isValidViewtype($viewtype)) {
			$this->viewtype = $viewtype;

			return true;
		}

		return false;
	}

	/**
	 * Get the viewtype
	 *
	 * @return string
	 */
	public function getViewtype() {
		if ($this->viewtype === null) {
			$this->viewtype = $this->resolveViewtype();
		}

		return $this->viewtype;
	}

	/**
	 * If the current viewtype has no views, reset it to "default"
	 *
	 * @return void
	 */
	public function clampViewtypeToPopulatedViews() {
		$viewtype = $this->getViewtype();
		if (empty($this->locations[$viewtype])) {
			$this->viewtype = 'default';
		}
	}

	/**
	 * Resolve the initial viewtype
	 *
	 * @return string
	 */
	private function resolveViewtype() {
		if ($this->request) {
			$view = $this->request->getParam('view', '', false);
			if ($this->isValidViewtype($view)) {
				return $view;
			}
		}

		$view = elgg_get_config('view');
		if ($this->isValidViewtype($view)) {
			return $view;
		}

		return 'default';
	}

	/**
	 * Checks if $viewtype is a string suitable for use as a viewtype name
	 *
	 * @param string $viewtype Potential viewtype name. Alphanumeric chars plus _ allowed.
	 *
	 * @return bool
	 */
	public function isValidViewtype($viewtype) {
		if (!is_string($viewtype) || $viewtype === '') {
			return false;
		}

		if (preg_match('/\W/', $viewtype)) {
			return false;
		}

		return true;
	}

	/**
	 * Takes a view name and returns the canonical name for that view.
	 *
	 * @param string $alias The possibly non-canonical view name.
	 *
	 * @return string The canonical view name.
	 */
	public static function canonicalizeViewName($alias) {
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
	 * Auto-registers views from a location.
	 *
	 * @param string $view_base Optional The base of the view name without the view type.
	 * @param string $folder    Required The folder to begin looking in
	 * @param string $viewtype  The type of view we're looking at (default, rss, etc)
	 *
	 * @return bool returns false if folder can't be read
	 *
	 * @see    autoregister_views()
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
	 * @access   private
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
	 * Set an alternative base location for a view
	 *
	 * @param string $view     Name of the view
	 * @param string $location Full path to the view file
	 * @param string $viewtype The viewtype to register this under
	 *
	 * @return void
	 *
	 * @see    elgg_set_view_location()
	 * @access private
	 */
	public function setViewDir($view, $location, $viewtype = '') {
		$view = self::canonicalizeViewName($view);

		if (empty($viewtype)) {
			$viewtype = 'default';
		}

		$location = rtrim($location, '/\\');

		if ($this->fileExists("$location/$viewtype/$view.php")) {
			$this->setViewLocation($view, $viewtype, "$location/$viewtype/$view.php");
		} else if ($this->fileExists("$location/$viewtype/$view")) {
			$this->setViewLocation($view, $viewtype, "$location/$viewtype/$view");
		}
	}

	/**
	 * Register a viewtype to fall back to a default view if a view isn't
	 * found for that viewtype.
	 *
	 * @param string $viewtype The viewtype to register
	 *
	 * @return void
	 *
	 * @see    elgg_register_viewtype_fallback()
	 * @access private
	 */
	public function registerViewtypeFallback($viewtype) {
		$this->fallbacks[] = $viewtype;
	}

	/**
	 * Checks if a viewtype falls back to default.
	 *
	 * @param string $viewtype Viewtype
	 *
	 * @return bool
	 *
	 * @see    elgg_does_viewtype_fallback()
	 * @access private
	 */
	public function doesViewtypeFallback($viewtype) {
		return in_array($viewtype, $this->fallbacks);
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
	 * @access private
	 * @see    elgg_view()
	 */
	public function renderDeprecatedView($view, array $vars, $suggestion, $version) {
		$view = self::canonicalizeViewName($view);

		$rendered = $this->renderView($view, $vars, '', false);
		if ($rendered) {
			elgg_deprecated_notice("The $view view has been deprecated. $suggestion", $version, 3);
		}

		return $rendered;
	}

	/**
	 * Get the views, including extensions, used to render a view
	 *
	 * Keys returned are view priorities. View existence is not checked.
	 *
	 * @param string $view View name
	 *
	 * @return string[]
	 * @access private
	 */
	public function getViewList($view) {
		if (isset($this->extensions[$view])) {
			return $this->extensions[$view];
		} else {
			return [self::BASE_VIEW_PRIORITY => $view];
		}
	}

	/**
	 * Renders a view
	 *
	 * @param string $view                 Name of the view
	 * @param array  $vars                 Variables to pass to the view
	 * @param string $viewtype             Viewtype to use
	 * @param bool   $issue_missing_notice Should a missing notice be issued
	 * @param array  $extensions_tree      Array of views that are before the current view in the extension path
	 *
	 * @return string
	 *
	 * @see elgg_view()
	 */
	public function renderView($view, array $vars = [], $viewtype = '', $issue_missing_notice = true, array $extensions_tree = []) {
		$view = self::canonicalizeViewName($view);

		if (!is_string($view) || !is_string($viewtype)) {
			$this->logger->notice("View and Viewtype in views must be a strings: $view");

			return '';
		}
		// basic checking for bad paths
		if (strpos($view, '..') !== false) {
			return '';
		}

		// check for extension deadloops
		if (in_array($view, $extensions_tree)) {
			$this->logger->error("View $view is detected as an extension of itself. This is not allowed");

			return '';
		}
		$extensions_tree[] = $view;

		if (!is_array($vars)) {
			$this->logger->error("Vars in views must be an array: $view");
			$vars = [];
		}

		// Get the current viewtype
		if ($viewtype === '' || !$this->isValidViewtype($viewtype)) {
			$viewtype = $this->getViewtype();
		}

		// allow altering $vars
		$vars_hook_params = [
			'view' => $view,
			'vars' => $vars,
			'viewtype' => $viewtype,
		];
		$vars = $this->hooks->trigger(self::VIEW_VARS_HOOK, $view, $vars_hook_params, $vars);

		// allow $vars to hijack output
		if (isset($vars[self::OUTPUT_KEY])) {
			return (string) $vars[self::OUTPUT_KEY];
		}

		$viewlist = $this->getViewList($view);

		$content = '';
		foreach ($viewlist as $priority => $view_name) {
			if ($priority !== self::BASE_VIEW_PRIORITY) {
				// the others are extensions
				$content .= $this->renderView($view_name, $vars, $viewtype, $issue_missing_notice, $extensions_tree);
				continue;
			}

			// actual rendering of a single view
			$rendering = $this->renderViewFile($view_name, $vars, $viewtype, $issue_missing_notice);
			if ($rendering !== false) {
				$content .= $rendering;
				continue;
			}

			// attempt to load default view
			if ($viewtype !== 'default' && $this->doesViewtypeFallback($viewtype)) {
				$rendering = $this->renderViewFile($view_name, $vars, 'default', $issue_missing_notice);
				if ($rendering !== false) {
					$content .= $rendering;
				}
			}
		}

		// Plugin hook
		$params = [
			'view' => $view,
			'vars' => $vars,
			'viewtype' => $viewtype,
		];
		$content = $this->hooks->trigger(self::VIEW_HOOK, $view, $params, $content);

		return $content;
	}

	/**
	 * Wrapper for file_exists() that caches false results (the stat cache only caches true results).
	 * This saves us from many unneeded file stat calls when a common view uses a fallback.
	 *
	 * @param string $path Path to the file
	 *
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
		$file = $this->findViewFile($view, $viewtype);
		if (!$file) {
			if ($issue_missing_notice) {
				$this->logger->notice("$viewtype/$view view does not exist.");
			}

			return false;
		}

		if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
			ob_start();

			try {
				// don't isolate, scripts use the local $vars
				include $file;

				return ob_get_clean();
			} catch (\Exception $e) {
				ob_get_clean();
				throw $e;
			}
		}

		return file_get_contents($file);
	}

	/**
	 * Returns whether the specified view exists
	 *
	 * @param string $view     The view name
	 * @param string $viewtype If set, forces the viewtype
	 * @param bool   $recurse  If false, do not check extensions
	 *
	 * @return bool
	 *
	 * @see    elgg_view_exists()
	 * @access private
	 */
	public function viewExists($view, $viewtype = '', $recurse = true) {
		$view = self::canonicalizeViewName($view);

		if (empty($view) || !is_string($view)) {
			return false;
		}

		// Detect view type
		if ($viewtype === '' || !$this->isValidViewtype($viewtype)) {
			$viewtype = $this->getViewtype();
		}


		$file = $this->findViewFile($view, $viewtype);
		if ($file) {
			return true;
		}

		// If we got here then check whether this exists as an extension
		// We optionally recursively check whether the extended view exists also for the viewtype
		if ($recurse && isset($this->extensions[$view])) {
			foreach ($this->extensions[$view] as $view_extension) {
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
	 * Extends a view with another view
	 *
	 * @param string $view           The view to extend.
	 * @param string $view_extension This view is added to $view
	 * @param int    $priority       The priority, from 0 to 1000, to add at (lowest numbers displayed first)
	 *
	 * @return void
	 *
	 * @see    elgg_extend_view()
	 * @access private
	 */
	public function extendView($view, $view_extension, $priority = 501) {
		$view = self::canonicalizeViewName($view);
		$view_extension = self::canonicalizeViewName($view_extension);

		if ($view === $view_extension) {
			// do not allow direct extension on self with self
			return;
		}

		if (!isset($this->extensions[$view])) {
			$this->extensions[$view][self::BASE_VIEW_PRIORITY] = (string) $view;
		}

		// raise priority until it doesn't match one already registered
		while (isset($this->extensions[$view][$priority])) {
			$priority++;
		}

		$this->extensions[$view][$priority] = (string) $view_extension;
		ksort($this->extensions[$view]);
	}

	/**
	 * Is the given view extended?
	 *
	 * @param string $view View name
	 *
	 * @return bool
	 * @internal Plugins should not use this
	 * @access   private
	 */
	public function viewIsExtended($view) {
		return count($this->getViewList($view)) > 1;
	}

	/**
	 * Do hook handlers exist to modify the view?
	 *
	 * @param string $view View name
	 *
	 * @return bool
	 * @internal Plugins should not use this
	 * @access   private
	 */
	public function viewHasHookHandlers($view) {
		return $this->hooks->hasHandler('view', $view) || $this->hooks->hasHandler('view_vars', $view);
	}

	/**
	 * Unextends a view.
	 *
	 * @param string $view           The view that was extended.
	 * @param string $view_extension This view that was added to $view
	 *
	 * @return bool
	 *
	 * @see    elgg_unextend_view()
	 * @access private
	 */
	public function unextendView($view, $view_extension) {
		$view = self::canonicalizeViewName($view);
		$view_extension = self::canonicalizeViewName($view_extension);

		if (!isset($this->extensions[$view])) {
			return false;
		}

		$extensions = $this->extensions[$view];
		unset($extensions[self::BASE_VIEW_PRIORITY]); // we do not want the base view to be removed from the list

		$priority = array_search($view_extension, $extensions);
		if ($priority === false) {
			return false;
		}

		unset($this->extensions[$view][$priority]);

		return true;
	}

	/**
	 * Register a view a cacheable
	 *
	 * @param string $view the view name
	 *
	 * @return void
	 *
	 * @access private
	 */
	public function registerCacheableView($view) {
		$view = self::canonicalizeViewName($view);

		$this->simplecache_views[$view] = true;
	}

	/**
	 * Is the view cacheable
	 *
	 * @param string $view the view name
	 *
	 * @return bool
	 *
	 * @access private
	 */
	public function isCacheableView($view) {
		$view = self::canonicalizeViewName($view);
		if (isset($this->simplecache_views[$view])) {
			return true;
		}

		// build list of viewtypes to check
		$current_viewtype = $this->getViewtype();
		$viewtypes = [$current_viewtype];

		if ($this->doesViewtypeFallback($current_viewtype) && $current_viewtype != 'default') {
			$viewtypes[] = 'default';
		}

		// If a static view file is found in any viewtype, it's considered cacheable
		foreach ($viewtypes as $viewtype) {
			$file = $this->findViewFile($view, $viewtype);

			if ($file && pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
				$this->simplecache_views[$view] = true;

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
	 *
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
				if (!$this->autoregisterViews('', $view_type_dir, $view_type)) {
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
	 *                    viewtype => [
	 *                    view_name => path or array of paths
	 *                    ]
	 *
	 * @return void
	 *
	 * @access private
	 */
	public function mergeViewsSpec(array $spec) {
		foreach ($spec as $viewtype => $list) {
			foreach ($list as $view => $paths) {
				if (!is_array($paths)) {
					$paths = [$paths];
				}

				foreach ($paths as $path) {
					if (preg_match('~^([/\\\\]|[a-zA-Z]\:)~', $path)) {
						// absolute path
					} else {
						// relative path
						$path = Directory\Local::projectRoot()->getPath($path);
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
			'extensions' => $this->extensions,
			'simplecache' => $this->simplecache_views,
		];
	}

	/**
	 * Configure locations from the cache
	 *
	 * @param SystemCache $cache The system cache
	 *
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
	 *
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
		$view = self::canonicalizeViewName($view);
		$path = strtr($path, '\\', '/');

		if (isset($this->locations[$viewtype][$view]) && $path !== $this->locations[$viewtype][$view]) {
			$this->overrides[$viewtype][$view][] = $this->locations[$viewtype][$view];
		}
		$this->locations[$viewtype][$view] = $path;

		// Test if view is cacheable and push it to the cacheable views stack,
		// if it's not registered as cacheable explicitly
		$this->isCacheableView($view);
	}
}
