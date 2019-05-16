<?php
/**
 * Parameter input functions.
 * This file contains functions for getting input from get/post variables.
 *
 * @package Elgg.Core
 * @subpackage Input
 */

/**
 * Get some input from variables passed submitted through GET or POST.
 *
 * If using any data obtained from get_input() in a web page, please be aware that
 * it is a possible vector for a reflected XSS attack. If you are expecting an
 * integer, cast it to an int. If it is a string, escape quotes.
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 * because of the filtering done in htmlawed from the filter_tags call.
 * @todo Is this ^ still true?
 *
 * @param string $variable      The variable name we want.
 * @param mixed  $default       A default value for the variable if it is not found.
 * @param bool   $filter_result If true, then the result is filtered for bad tags.
 *
 * @return mixed
 */
function get_input($variable, $default = null, $filter_result = true) {
	return _elgg_services()->request->getParam($variable, $default, $filter_result);
}

/**
 * Sets an input value that may later be retrieved by get_input
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 *
 * @param string          $variable The name of the variable
 * @param string|string[] $value    The value of the variable
 *
 * @return void
 */
function set_input($variable, $value) {
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
 */
function elgg_get_request_data($filter_result = true) {
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
function elgg_get_title_input($variable = 'title', $default = '') {
	$raw_input = get_input($variable, $default, false);
	return htmlspecialchars($raw_input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Filter tags from a given string based on registered hooks.
 *
 * @param mixed $var Anything that does not include an object (strings, ints, arrays)
 *					 This includes multi-dimensional arrays.
 *
 * @return mixed The filtered result - everything will be strings
 */
function filter_tags($var) {
	return elgg_trigger_plugin_hook('validate', 'input', null, $var);
}

/**
 * Returns the current page's complete URL.
 *
 * It uses the configured site URL for the hostname rather than depending on
 * what the server uses to populate $_SERVER.
 *
 * @return string The current page URL.
 */
function current_page_url() {
	return _elgg_services()->request->getCurrentURL();
}

/**
 * Validates an email address.
 *
 * @param string $address Email address.
 *
 * @return bool
 */
function is_email_address($address) {
	return elgg()->accounts->isValidEmail($address);
}

/**
 * Save form submission data (all GET and POST vars) into a session cache
 *
 * Call this from an action when you want all your submitted variables
 * available if the submission fails validation and is sent back to the form
 *
 * @param string $form_name Name of the sticky form
 *
 * @return void
 * @since 1.8.0
 */
function elgg_make_sticky_form($form_name) {
	_elgg_services()->stickyForms->makeStickyForm($form_name);
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
function elgg_clear_sticky_form($form_name) {
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
function elgg_is_sticky_form($form_name) {
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
 *
 * @todo should this filter the default value?
 * @since 1.8.0
 */
function elgg_get_sticky_value($form_name, $variable = '', $default = null, $filter_result = true) {
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
function elgg_get_sticky_values($form_name, $filter_result = true) {
	return _elgg_services()->stickyForms->getStickyValues($form_name, $filter_result);
}

/**
 * Remove one value of form submission data from the session
 *
 * @param string $form_name The name of the form
 * @param string $variable  The name of the variable to clear
 *
 * @return void
 * @since 1.8.0
 */
function elgg_clear_sticky_value($form_name, $variable) {
	_elgg_services()->stickyForms->clearStickyValue($form_name, $variable);
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
function elgg_is_empty($value) {
	return Elgg\Values::isEmpty($value);
}

/**
 * htmLawed filtering of data
 *
 * Called on the 'validate', 'input' plugin hook
 *
 * htmLawed's $config argument is filtered by the [config, htmlawed] hook.
 * htmLawed's $spec argument is filtered by the [spec, htmlawed] hook.
 *
 * For information on these arguments, see
 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.2
 *
 * @param string $hook   Hook name
 * @param string $type   The type of hook
 * @param mixed  $result Data to filter
 * @param array  $params Not used
 *
 * @return mixed
 */
function _elgg_htmlawed_filter_tags($hook, $type, $result, $params = null) {
	$var = $result;

	$config = [
		// seems to handle about everything we need.
		'safe' => true,

		// remove comments/CDATA instead of converting to text
		'comment' => 1,
		'cdata' => 1,

		'deny_attribute' => 'class, on*',
		'hook_tag' => '_elgg_htmlawed_tag_post_processor',

		'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
		// apparent this doesn't work.
		// 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
	];

	// add nofollow to all links on output
	if (!elgg_in_context('input')) {
		$config['anti_link_spam'] = ['/./', ''];
	}

	$config = elgg_trigger_plugin_hook('config', 'htmlawed', null, $config);
	$spec = elgg_trigger_plugin_hook('spec', 'htmlawed', null, '');

	if (!is_array($var)) {
		return Htmlawed::filter($var, $config, $spec);
	} else {
		array_walk_recursive($var, '_elgg_htmLawedArray', [$config, $spec]);
		return $var;
	}
}

// @codingStandardsIgnoreStart
/**
 * wrapper function for htmlawed for handling arrays
 */
function _elgg_htmLawedArray(&$v, $k, $config_spec) {
	list ($config, $spec) = $config_spec;
	$v = Htmlawed::filter($v, $config, $spec);
}
// @codingStandardsIgnoreEnd

/**
 * Post processor for tags in htmlawed
 *
 * This runs after htmlawed has filtered. It runs for each tag and filters out
 * style attributes we don't want.
 *
 * This function triggers the 'allowed_styles', 'htmlawed' plugin hook.
 *
 * @param string $element    The tag element name
 * @param array  $attributes An array of attributes
 * @return string
 */
function _elgg_htmlawed_tag_post_processor($element, $attributes = false) {
	if ($attributes === false) {
		// This is a closing tag. Prevent further processing to avoid inserting a duplicate tag
		return "</${element}>";
	}

	// this list should be coordinated with the WYSIWYG editor used (tinymce, ckeditor, etc.)
	$allowed_styles = [
		'color', 'cursor', 'text-align', 'vertical-align', 'font-size',
		'font-weight', 'font-style', 'border', 'border-top', 'background-color',
		'border-bottom', 'border-left', 'border-right',
		'margin', 'margin-top', 'margin-bottom', 'margin-left',
		'margin-right',	'padding', 'float', 'text-decoration'
	];

	$params = ['tag' => $element];
	$allowed_styles = elgg_trigger_plugin_hook('allowed_styles', 'htmlawed', $params, $allowed_styles);

	// must return something.
	$string = '';

	foreach ($attributes as $attr => $value) {
		if ($attr == 'style') {
			$styles = explode(';', $value);

			$style_str = '';
			foreach ($styles as $style) {
				if (!trim($style)) {
					continue;
				}
				list($style_attr, $style_value) = explode(':', trim($style));
				$style_attr = trim($style_attr);
				$style_value = trim($style_value);

				if (in_array($style_attr, $allowed_styles)) {
					$style_str .= "$style_attr: $style_value; ";
				}
			}

			if ($style_str) {
				$style_str = trim($style_str);
				$string .= " style=\"$style_str\"";
			}
		} else {
			$string .= " $attr=\"$value\"";
		}
	}

	// Some WYSIWYG editors do not like tags like <p > so only add a space if needed.
	if ($string = trim($string)) {
		$string = " $string";
	}

	$r = "<$element$string>";
	return $r;
}

/**
 * Runs unit tests for htmlawed
 *
 * @param string   $hook   "unit_test"
 * @param string   $type   "system"
 * @param string[] $value  Test files
 * @param array    $params Hook params
 *
 * @return array
 * @codeCoverageIgnore
 */
function _elgg_htmlawed_test($hook, $type, $value, $params) {
	$value[] = ElggHtmLawedTest::class;
	return $value;
}

/**
 * Disable the autocomplete feature on password fields
 *
 * @param string $hook         'view_vars'
 * @param string $type         'input/password'
 * @param array  $return_value the current view vars
 * @param array  $params       supplied params
 *
 * @return void|array
 */
function _elgg_disable_password_autocomplete($hook, $type, $return_value, $params) {
	
	if (!_elgg_config()->security_disable_password_autocomplete) {
		return;
	}
	
	$return_value['autocomplete'] = 'off';
	
	return $return_value;
}

/**
 * Initialize the input library
 *
 * @return void
 * @access private
 */
function _elgg_input_init() {

	elgg_register_plugin_hook_handler('validate', 'input', '_elgg_htmlawed_filter_tags', 1);

	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_htmlawed_test');
	
	elgg_register_plugin_hook_handler('view_vars', 'input/password', '_elgg_disable_password_autocomplete');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_input_init');
};
