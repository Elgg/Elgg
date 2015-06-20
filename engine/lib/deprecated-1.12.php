<?php


/**
 * Returns the file location for a view.
 *
 * @warning This doesn't check if the file exists, but only
 * constructs (or extracts) the path and returns it.
 *
 * @param string $view     The view.
 * @param string $viewtype The viewtype
 *
 * @return string
 * @deprecated 1.12 This function is going away in 2.0.
 */
function elgg_get_view_location($view, $viewtype = '') {
	elgg_deprecated_notice("elgg_get_view_location() is going away in 2.0.", "1.12");
	return _elgg_services()->views->getViewLocation($view, $viewtype);
}
