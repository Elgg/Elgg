<?php
/**
 * Parameter input functions.
 * This file contains functions for getting input from get/post variables.
 */

/**
 * Get some input from variables passed submitted through GET or POST.
 *
 * If using any data obtained from get_input() in a web page, please be aware that
 * it is a possible vector for a reflected XSS attack. If you are expecting an
 * integer, cast it to an int. If it is a string, escape quotes.
 *
 * @param string $variable      The variable name we want.
 * @param mixed  $default       A default value for the variable if it is not found.
 * @param bool   $filter_result If true, then the result is filtered for bad tags.
 *
 * @return mixed
 */
function get_input(string $variable, $default = null, bool $filter_result = true) {
	return _elgg_services()->request->getParam($variable, $default, $filter_result);
}

/**
 * Sets an input value that may later be retrieved by get_input
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 *
 * @param string $variable The name of the variable
 * @param mixed  $value    The value of the variable
 *
 * @return void
 */
function set_input(string $variable, $value): void {
	_elgg_services()->request->setParam($variable, $value, true);
}

/**
 * Returns all values parsed from the current request, including $_GET and $_POST values,
 * as well as any values set with set_input()
 *
 * @see get_input()
 * @see set_input()
 *
 * @param bool $filter_result Sanitize input values
 *
 * @return array
 * @since 3.0
 */
function elgg_get_request_data(bool $filter_result = true): array {
	return _elgg_services()->request->getParams($filter_result);
}

/**
 * Get an HTML-escaped title from input. E.g. "How to use &lt;b&gt; tags"
 *
 * @param string $variable The desired variable name
 * @param string $default  The default if none given
 *
 * @return string
 * @since 3.0
 */
function elgg_get_title_input(string $variable = 'title', string $default = ''): string {
	$raw_input = get_input($variable, $default, false);
	return htmlspecialchars($raw_input ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Filter input from a given string based on registered events.
 *
 * @param mixed $input Anything that does not include an object (strings, ints, arrays)
 *					   This includes multi-dimensional arrays.
 *
 * @return mixed The filtered result
 * @since 4.3
 */
function elgg_sanitize_input($input) {
	return elgg_trigger_event_results('sanitize', 'input', [], $input);
}

/**
 * Validates an email address.
 *
 * @param string $address Email address.
 *
 * @return bool
 * @since 4.3
 */
function elgg_is_valid_email(string $address): bool {
	return _elgg_services()->accounts->isValidEmail($address);
}

/**
 * Save form submission data (all GET and POST vars) into a session cache
 *
 * Call this from an action when you want all your submitted variables
 * available if the submission fails validation and is sent back to the form
 *
 * @param string   $form_name           Name of the sticky form
 * @param string[] $ignored_field_names Field names which shouldn't be made sticky in this form
 *
 * @return void
 * @since 1.8.0
 */
function elgg_make_sticky_form(string $form_name, array $ignored_field_names = []): void {
	_elgg_services()->stickyForms->makeStickyForm($form_name, $ignored_field_names);
}

/**
 * Remove form submission data from the session
 *
 * Call this if validation is successful in the action handler or
 * when they sticky values have been used to repopulate the form
 * after a validation error.
 *
 * @param string $form_name Form namespace
 *
 * @return void
 * @since 1.8.0
 */
function elgg_clear_sticky_form(string $form_name): void {
	_elgg_services()->stickyForms->clearStickyForm($form_name);
}

/**
 * Does form submission data exist for this form?
 *
 * @param string $form_name Form namespace
 *
 * @return boolean
 * @since 1.8.0
 */
function elgg_is_sticky_form(string $form_name): bool {
	return _elgg_services()->stickyForms->isStickyForm($form_name);
}

/**
 * Get a specific value from cached form submission data
 *
 * @param string  $form_name     The name of the form
 * @param string  $variable      The name of the variable
 * @param mixed   $default       Default value if the variable does not exist in sticky cache
 * @param boolean $filter_result Filter for bad input if true
 *
 * @return mixed
 * @since 1.8.0
 */
function elgg_get_sticky_value(string $form_name, string $variable = '', $default = null, bool $filter_result = true) {
	return _elgg_services()->stickyForms->getStickyValue($form_name, $variable, $default, $filter_result);
}

/**
 * Get all submission data cached for a form
 *
 * @param string $form_name     The name of the form
 * @param bool   $filter_result Filter for bad input if true
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_sticky_values(string $form_name, bool $filter_result = true): array {
	return _elgg_services()->stickyForms->getStickyValues($form_name, $filter_result);
}

/**
 * Check if a value isn't empty, but allow 0 and '0'
 *
 * @param mixed $value the value to check
 *
 * @see empty()
 * @see Elgg\Values::isEmpty()
 *
 * @return bool
 * @since 3.0.0
 */
function elgg_is_empty($value): bool {
	return Elgg\Values::isEmpty($value);
}

/**
 * Post processor for tags in htmlawed
 *
 * This runs after htmlawed has filtered. It runs for each tag and allows sanitization of attributes.
 *
 * This function triggers the 'attributes', 'htmlawed' event.
 *
 * @param string      $tag        The tag element name
 * @param array|false $attributes An array of attributes (false indicates a closing tag)
 *
 * @return string
 * @internal
 */
function _elgg_htmlawed_tag_post_processor(string $tag, array|false $attributes = false): string {
	
	if ($attributes === false) {
		// This is a closing tag. Prevent further processing to avoid inserting a duplicate tag
		return "</{$tag}>";
	}
	
	$attributes = (array) elgg_trigger_event_results('attributes', 'htmlawed', [
		'attributes' => $attributes,
		'tag' => $tag,
	], $attributes);

	$result = '';
	foreach ($attributes as $attr => $value) {
		$result .= " {$attr}=\"{$value}\"";
	}
	
	return "<{$tag}{$result}>";
}

/**
 * Takes in a comma-separated string and returns an array of uniquely trimmed and stripped strings
 *
 * @param string $string Comma-separated string
 *
 * @return array
 * @since 4.3
 */
function elgg_string_to_array(string $string): array {
	$ar = explode(',', $string);
	$ar = array_map('trim', $ar);
	$ar = array_map('strip_tags', $ar);
	
	$ar = array_filter($ar, function($string) {
		return !elgg_is_empty($string);
	});
	
	$ar = array_unique($ar);
	
	// reset keys
	return array_values($ar);
}
