<?php

/**
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type is unrecognized
 * @deprecated 1.10 Use elgg_get_file_simple_type()
 */
function file_get_simple_type($mime_type) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_file_simple_type()', '1.10');
	return elgg_get_file_simple_type($mime_type);
}

/**
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type is unrecognized
 * @deprecated 1.10 Use elgg_get_file_simple_type()
 */
function file_get_general_file_type($mime_type) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_file_simple_type()', '1.10');
	return elgg_get_file_simple_type($mime_type);
}

/**
 * Register a php library.
 *
 * @param string $name     The name of the library
 * @param string $location The location of the file
 *
 * @return void
 * @since 1.8.0
 * @deprecated 1.10.0 Use class autoloading instead
 */
function elgg_register_library($name, $location) {
	global $CONFIG;

	if (!isset($CONFIG->libraries)) {
		$CONFIG->libraries = array();
	}

	$CONFIG->libraries[$name] = $location;
}

/**
 * Load a php library.
 *
 * @param string $name The name of the library
 *
 * @return void
 * @throws InvalidParameterException
 * @since 1.8.0
 * @deprecated 1.10.0 Use class autoloading instead
 */
function elgg_load_library($name) {
	global $CONFIG;

	static $loaded_libraries = array();

	if (in_array($name, $loaded_libraries)) {
		return;
	}

	if (!isset($CONFIG->libraries)) {
		$CONFIG->libraries = array();
	}

	if (!isset($CONFIG->libraries[$name])) {
		$error = $name . " is not a registered library";
		throw new \InvalidParameterException($error);
	}

	if (!include_once($CONFIG->libraries[$name])) {
		$error = "Could not load the " . $name . " library from " . $CONFIG->libraries[$name];
		throw new \InvalidParameterException($error);
	}

	$loaded_libraries[] = $name;
}