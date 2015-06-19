<?php


/**
 * Registers a function to handle templates.
 *
 * Alternative template handlers can be registered to handle
 * all output functions.  By default, {@link elgg_view()} will
 * simply include the view file.  If an alternate template handler
 * is registered, the view name and passed $vars will be passed to the
 * registered function, which is then responsible for generating and returning
 * output.
 *
 * Template handlers need to accept two arguments: string $view_name and array
 * $vars.
 *
 * @warning This is experimental.
 *
 * @param string $function_name The name of the function to pass to.
 *
 * @return bool
 * @see elgg_view()
 * @deprecated 1.12
 */
function set_template_handler($function_name) {
	elgg_deprecated_notice("Support for custom template handlers will end soon.", "1.12");
	global $CONFIG;

	if (is_callable($function_name)) {
		$CONFIG->template_handler = $function_name;
		return true;
	}
	return false;
}
