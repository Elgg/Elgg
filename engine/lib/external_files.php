<?php
/**
 * Helper functions for external files like css/js
 */

/**
 * Defines a JS lib as an AMD module. This is useful for shimming
 * traditional JS or for setting the paths of AMD modules.
 *
 * Calling multiple times for the same name will:
 *     * set the preferred path to the last call setting a path
 *     * overwrite the shimmed AMD modules with the last call setting a shimmed module
 *
 * Use elgg_require_js($name) to load on the current page.
 *
 * Calling this function is not needed if your JS are in views named like `module/name.js`
 * Instead, simply call elgg_require_js("module/name").
 *
 * @note The configuration is cached in simplecache, so logic should not depend on user-
 *       specific values like elgg_get_current_language().
 *
 * @param string $name   The module name
 * @param array  $config An array like the following:
 *                       array  'deps'    An array of AMD module dependencies
 *                       string 'exports' The name of the exported module
 *                       string 'src'     The URL to the JS. Can be relative.
 *
 * @return void
 */
function elgg_define_js($name, $config) {
	$src = elgg_extract('src', $config);

	if ($src) {
		$url = elgg_normalize_url($src);
		_elgg_services()->amdConfig->addPath($name, $url);
	}

	// shimmed module
	if (isset($config['deps']) || isset($config['exports'])) {
		_elgg_services()->amdConfig->addShim($name, $config);
	}
}

/**
 * Request that Elgg load an AMD module onto the page.
 *
 * @param string $name The AMD module name.
 * @return void
 * @since 1.9.0
 */
function elgg_require_js($name) {
	_elgg_services()->amdConfig->addDependency($name);
}

/**
 * Cancel a request to load an AMD module onto the page.
 *
 * @param string $name The AMD module name.
 * @return void
 * @since 2.1.0
 */
function elgg_unrequire_js($name) {
	_elgg_services()->amdConfig->removeDependency($name);
}

/**
 * Register a CSS view name to be included in the HTML head
 *
 * @param string $view The css view name
 *
 * @return void
 *
 * @since 3.1
 */
function elgg_require_css(string $view) {
	$view_name = "{$view}.css";
	if (!elgg_view_exists($view_name)) {
		$view_name = $view;
	}
	
	elgg_register_external_file('css', $view, elgg_get_simplecache_url($view_name));
	elgg_load_external_file('css', $view);
}

/**
 * Unregister a CSS view name to be included in the HTML head
 *
 * @param string $view The css view name
 *
 * @return void
 *
 * @since 3.1
 */
function elgg_unrequire_css(string $view) {
	elgg_unregister_external_file('css', $view);
}

/**
 * Core registration function for external files
 *
 * @param string $type     Type of external resource (js or css)
 * @param string $name     Identifier used as key
 * @param string $url      URL
 * @param string $location Location in the page to include the file (default = 'footer' for js, 'head' for anything else)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_external_file(string $type, string $name, string $url, string $location = ''): bool {
	if (empty($location)) {
		$location = $type === 'js' ? 'footer' : 'head';
	}
	
	return _elgg_services()->externalFiles->register($type, $name, $url, $location);
}

/**
 * Unregister an external file
 *
 * @param string $type Type of file: js or css
 * @param string $name The identifier of the file
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unregister_external_file(string $type, string $name): bool {
	return _elgg_services()->externalFiles->unregister($type, $name);
}

/**
 * Load an external resource for use on this page
 *
 * @param string $type Type of file: js or css
 * @param string $name The identifier for the file
 *
 * @return void
 * @since 1.8.0
 */
function elgg_load_external_file(string $type, string $name): void {
	_elgg_services()->externalFiles->load($type, $name);
}

/**
 * Get external resource descriptors
 *
 * @param string $type     Type of file: js or css
 * @param string $location Page location
 *
 * @return array
 * @since 4.3
 */
function elgg_get_loaded_external_resources(string $type, string $location): array {
	return _elgg_services()->externalFiles->getLoadedResources($type, $location);
}
