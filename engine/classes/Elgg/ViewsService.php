<?php

namespace Elgg;

use Elgg\Cache\ServerCache;
use Elgg\Http\Request as HttpRequest;
use Elgg\Project\Paths;
use Elgg\Traits\Loggable;

/**
 * Views service
 *
 * @internal
 * @since 1.9.0
 */
class ViewsService {

	use Loggable;

	const VIEW_HOOK = 'view';
	const VIEW_VARS_HOOK = 'view_vars';
	const OUTPUT_KEY = '__view_output';
	const BASE_VIEW_PRIORITY = 500;

	/**
	 * @see ViewsService::fileExists()
	 */
	protected array $file_exists_cache = [];

	/**
	 * [viewtype][view] => '/path/to/views/style.css'
	 */
	protected array $locations = [];

	/**
	 * Tracks location changes for views
	 *
	 * [viewtype][view][] => '/path/to/views/style.css'
	 */
	protected array $overrides = [];

	/**
	 * [view][priority] = extension_view
	 */
	protected array $extensions = [];

	/**
	 * @var string[] A list of fallback viewtypes
	 */
	protected array $fallbacks = [];

	protected ?string $viewtype;
	
	protected bool $locations_loaded_from_cache = false;

	/**
	 * Constructor
	 *
	 * @param EventsService      $events       Events service
	 * @param \Elgg\Http\Request $request      Http Request
	 * @param \Elgg\Config       $config       Elgg configuration
	 * @param ServerCache        $server_cache Server cache
	 */
	public function __construct(
		protected EventsService $events,
		protected HttpRequest $request,
		protected Config $config,
		protected ServerCache $server_cache
	) {
	}

	/**
	 * Set the viewtype
	 *
	 * @param string $viewtype Viewtype
	 *
	 * @return bool
	 */
	public function setViewtype(string $viewtype = ''): bool {
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
	public function getViewtype(): string {
		if (!isset($this->viewtype)) {
			$this->viewtype = $this->resolveViewtype();
		}

		return $this->viewtype;
	}

	/**
	 * Resolve the initial viewtype
	 *
	 * @return string
	 */
	protected function resolveViewtype(): string {
		if ($this->request) {
			$view = $this->request->getParam('view', '', false);
			if ($this->isValidViewtype($view) && !empty($this->locations[$view])) {
				return $view;
			}
		}

		$view = (string) $this->config->view;
		if ($this->isValidViewtype($view) && !empty($this->locations[$view])) {
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
	public function isValidViewtype(string $viewtype): bool {
		if ($viewtype === '') {
			return false;
		}

		if (preg_match('/\W/', $viewtype)) {
			return false;
		}

		return true;
	}
	
	/**
	 * Discover the core views if the system cache did not load
	 *
	 * @return void
	 * @since 6.1
	 */
	public function registerCoreViews(): void {
		if ($this->isViewLocationsLoadedFromCache()) {
			return;
		}
		
		// Core view files in /views
		$this->registerViewsFromPath(Paths::elgg());
		
		// Core view definitions in /engine/views.php
		$file = Paths::elgg() . 'engine/views.php';
		if (!is_file($file)) {
			return;
		}
		
		$spec = Includer::includeFile($file);
		if (is_array($spec)) {
			// check for uploaded fontawesome font
			if ($this->config->font_awesome_zip) {
				$spec['default']['font-awesome/css/'] = elgg_get_data_path() . 'fontawesome/webfont/css/';
				$spec['default']['font-awesome/otfs/'] = elgg_get_data_path() . 'fontawesome/webfont/otfs/';
				$spec['default']['font-awesome/webfonts/'] = elgg_get_data_path() . 'fontawesome/webfont/webfonts/';
			}
			
			$this->mergeViewsSpec($spec);
		}
	}
	
	/**
	 * Auto-registers views from a location.
	 *
	 * @param string $view_base The base of the view name without the view type.
	 * @param string $folder    Required The folder to begin looking in
	 * @param string $viewtype  The type of view we're looking at (default, rss, etc)
	 *
	 * @return bool returns false if folder can't be read
	 */
	public function autoregisterViews(string $view_base, string $folder, string $viewtype): bool {
		$folder = Paths::sanitize($folder);
		$view_base = Paths::sanitize($view_base, false);
		$view_base = $view_base ? $view_base . '/' : $view_base;
		
		try {
			$dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS));
		} catch (\Throwable $t) {
			$this->getLogger()->error($t->getMessage());
			return false;
		}

		/* @var $file \SplFileInfo */
		foreach ($dir as $file) {
			$path = $file->getPath() .  '/' . $file->getBasename('.php');
			$path = Paths::sanitize($path, false);

			// found a file add it to the views
			$view = $view_base . substr($path, strlen($folder));
			$this->setViewLocation($view, $viewtype, $file->getPathname());
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
	 */
	public function findViewFile(string $view, string $viewtype): string {
		if (!isset($this->locations[$viewtype][$view])) {
			return '';
		}

		$path = $this->locations[$viewtype][$view];
		
		return $this->fileExists($path) ? $path : '';
	}

	/**
	 * Register a viewtype to fall back to a default view if a view isn't
	 * found for that viewtype.
	 *
	 * @param string $viewtype The viewtype to register
	 *
	 * @return void
	 *
	 * @see elgg_register_viewtype_fallback()
	 */
	public function registerViewtypeFallback(string $viewtype): void {
		$this->fallbacks[] = $viewtype;
	}

	/**
	 * Checks if a viewtype falls back to default.
	 *
	 * @param string $viewtype Viewtype
	 *
	 * @return bool
	 */
	public function doesViewtypeFallback(string $viewtype): bool {
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
	 * @see elgg_view()
	 */
	public function renderDeprecatedView(string $view, array $vars, string $suggestion, string $version): string {
		$rendered = $this->renderView($view, $vars, '', false);
		if ($rendered) {
			$this->logDeprecatedMessage("The '{$view}' view has been deprecated. {$suggestion}", $version);
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
	 */
	public function getViewList(string $view): array {
		return $this->extensions[$view] ?? [self::BASE_VIEW_PRIORITY => $view];
	}

	/**
	 * Renders a view
	 *
	 * @param string    $view                 Name of the view
	 * @param array     $vars                 Variables to pass to the view
	 * @param string    $viewtype             Viewtype to use
	 * @param null|bool $issue_missing_notice Should a missing notice be issued
	 * @param array     $extensions_tree      Array of views that are before the current view in the extension path
	 *
	 * @return string
	 *
	 * @see elgg_view()
	 */
	public function renderView(string $view, array $vars = [], string $viewtype = '', ?bool $issue_missing_notice = null, array $extensions_tree = []): string {
		// basic checking for bad paths
		if (str_contains($view, '..')) {
			return '';
		}

		// check for extension deadloops
		if (in_array($view, $extensions_tree)) {
			$this->getLogger()->error("View {$view} is detected as an extension of itself. This is not allowed");

			return '';
		}
		
		$extensions_tree[] = $view;

		// Get the current viewtype
		if ($viewtype === '' || !$this->isValidViewtype($viewtype)) {
			$viewtype = $this->getViewtype();
		}
		
		if (!isset($issue_missing_notice)) {
			$issue_missing_notice = $viewtype === 'default';
		}

		// allow altering $vars
		$vars_event_params = [
			'view' => $view,
			'vars' => $vars,
			'viewtype' => $viewtype,
		];
		$vars = $this->events->triggerResults(self::VIEW_VARS_HOOK, $view, $vars_event_params, $vars);

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

		$params = [
			'view' => $view,
			'vars' => $vars,
			'viewtype' => $viewtype,
		];
		
		return (string) $this->events->triggerResults(self::VIEW_HOOK, $view, $params, $content);
	}

	/**
	 * Wrapper for file_exists() that caches false results (the stat cache only caches true results).
	 * This saves us from many unneeded file stat calls when a common view uses a fallback.
	 *
	 * @param string $path Path to the file
	 *
	 * @return bool
	 */
	protected function fileExists(string $path): bool {
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
	protected function renderViewFile(string $view, array $vars, string $viewtype, bool $issue_missing_notice): string|false {
		$file = $this->findViewFile($view, $viewtype);
		if (!$file) {
			if ($issue_missing_notice) {
				$this->getLogger()->notice("{$viewtype}/{$view} view does not exist.");
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
	 * @see elgg_view_exists()
	 */
	public function viewExists(string $view, string $viewtype = '', bool $recurse = true): bool {
		if (empty($view)) {
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
		if ($viewtype !== 'default' && $this->doesViewtypeFallback($viewtype)) {
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
	 * @see elgg_extend_view()
	 */
	public function extendView(string $view, string $view_extension, int $priority = 501): void {
		if ($view === $view_extension) {
			// do not allow direct extension on self with self
			return;
		}

		if (!isset($this->extensions[$view])) {
			$this->extensions[$view][self::BASE_VIEW_PRIORITY] = $view;
		}

		// raise priority until it doesn't match one already registered
		while (isset($this->extensions[$view][$priority])) {
			$priority++;
		}

		$this->extensions[$view][$priority] = $view_extension;
		ksort($this->extensions[$view]);
	}

	/**
	 * Unextends a view.
	 *
	 * @param string $view           The view that was extended.
	 * @param string $view_extension This view that was added to $view
	 *
	 * @return bool
	 *
	 * @see elgg_unextend_view()
	 */
	public function unextendView(string $view, string $view_extension): bool {
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
	 * Register all views in a given path
	 *
	 * @param string $path Base path to scan for views
	 *
	 * @return bool
	 */
	public function registerViewsFromPath(string $path): bool {
		$path = Paths::sanitize($path) . 'views/';

		// do not fail on non existing views folder
		if (!is_dir($path)) {
			return true;
		}
		
		try {
			$dir = new \DirectoryIterator($path);
		} catch (\Throwable $t) {
			$this->getLogger()->error($t->getMessage());
			return false;
		}
		
		foreach ($dir as $folder) {
			$folder_name = $folder->getBasename();
			if (!$folder->isDir() || str_starts_with($folder_name, '.')) {
				continue;
			}
			
			if (!$this->autoregisterViews('', $folder->getPathname(), $folder_name)) {
				return false;
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
	 */
	public function mergeViewsSpec(array $spec): void {
		foreach ($spec as $viewtype => $list) {
			foreach ($list as $view => $paths) {
				if (!is_array($paths)) {
					$paths = [$paths];
				}

				foreach ($paths as $path) {
					if (!preg_match('~^([/\\\\]|[a-zA-Z]\:)~', $path)) {
						// relative path
						$path = Paths::project() . $path;
					}

					if (str_ends_with($view, '/')) {
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
	 */
	public function listViews(string $viewtype = 'default'): array {
		return array_keys($this->locations[$viewtype] ?? []);
	}

	/**
	 * Get inspector data
	 *
	 * @return array
	 */
	public function getInspectorData(): array {
		$cached_overrides = $this->server_cache->load('view_overrides');
		
		return [
			'locations' => $this->locations,
			'overrides' => is_array($cached_overrides) ? $cached_overrides : $this->overrides,
			'extensions' => $this->extensions,
			'simplecache' => _elgg_services()->simpleCache->getCacheableViews(),
		];
	}

	/**
	 * Configure locations from the cache
	 *
	 * @return void
	 */
	public function configureFromCache(): void {
		if (!$this->server_cache->isEnabled()) {
			return;
		}
		
		$data = $this->server_cache->load('view_locations');
		if (!is_array($data)) {
			return;
		}
		
		$this->locations = $data['locations'];
		$this->locations_loaded_from_cache = true;
	}

	/**
	 * Cache the configuration
	 *
	 * @return void
	 */
	public function cacheConfiguration(): void {
		if (!$this->server_cache->isEnabled()) {
			return;
		}
		
		// only cache if not already loaded
		if ($this->isViewLocationsLoadedFromCache()) {
			return;
		}
		
		if (empty($this->locations)) {
			$this->server_cache->delete('view_locations');
			return;
		}
		
		$this->server_cache->save('view_locations', ['locations' => $this->locations]);

		// this is saved just for the inspector and is not loaded in loadAll()
		$this->server_cache->save('view_overrides', $this->overrides);
	}
	
	/**
	 * Checks if view_locations have been loaded from cache.
	 * This can be used to determine if there is a need to (re)load view locations
	 *
	 * @return bool
	 */
	public function isViewLocationsLoadedFromCache(): bool {
		return $this->locations_loaded_from_cache;
	}
	
	/**
	 * Returns an array of names of ES modules detected based on view location
	 *
	 * @return array
	 */
	public function getESModules(): array {
		$modules = $this->server_cache->load('esmodules');
		if (is_array($modules)) {
			return $modules;
		}
		
		$modules = [];
		foreach ($this->locations['default'] as $name => $path) {
			if (!str_ends_with($name, '.mjs')) {
				continue;
			}
			
			$modules[] = $name;
		}
		
		$this->server_cache->save('esmodules', $modules);
		
		return $modules;
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
	protected function setViewLocation(string $view, string $viewtype, string $path): void {
		$path = strtr($path, '\\', '/');

		if (isset($this->locations[$viewtype][$view]) && $path !== $this->locations[$viewtype][$view]) {
			$this->overrides[$viewtype][$view][] = $this->locations[$viewtype][$view];
		}
		
		$this->locations[$viewtype][$view] = $path;

		// Test if view is cacheable and push it to the cacheable views stack,
		// if it's not registered as cacheable explicitly
		_elgg_services()->simpleCache->isCacheableView($view);
	}
}
