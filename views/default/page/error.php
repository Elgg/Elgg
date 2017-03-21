<?php
/**
 * Page shell for errors
 *
 * This is for errors that are not unhandled exceptions. Those are handled
 * through the failsafe viewtype to guarantee that no further exceptions occur.
 * An example error would be 404 (page not found).
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
	echo elgg_view('page/admin', $vars);
} else {
	echo elgg_view('page/default', $vars);
}
