<?php
/**
 * Helper functions for external files like css/js
 */

/**
 * Request that Elgg load an ES module onto the page.
 *
 * @param string $name The ES module name
 *
 * @return void
 *
 * @since 6.0
 */
function elgg_import_esm(string $name): void {
	_elgg_services()->esm->import($name);
}

/**
 * Registers an ES module to the import map
 *
 * @param string $name name of the module
 * @param string $href location where the module should be imported from
 *
 * @return void
 *
 * @since 6.0
 */
function elgg_register_esm(string $name, string $href): void {
	_elgg_services()->esm->register($name, $href);
}

/**
 * Register a CSS view name to be included in the HTML head
 *
 * @param string $view The css view name
 *
 * @return void
 * @since 3.1
 */
function elgg_require_css(string $view): void {
	$view_name = "{$view}.css";
	if (!elgg_view_exists($view_name)) {
		$view_name = $view;
	}
	
	elgg_register_external_file('css', $view, elgg_get_simplecache_url($view_name));
	elgg_load_external_file('css', $view);
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
