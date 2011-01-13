<?php
/**
 * Elgg developer tools
 */

elgg_register_event_handler('init', 'system', 'developers_init');

function developers_init() {

	elgg_register_event_handler('pagesetup', 'system', 'developers_setup_menu');
}

function developers_setup_menu() {
	if (elgg_in_context('admin')) {
		elgg_add_admin_menu_item('developers', elgg_echo('admin:developers'));
		elgg_add_admin_menu_item('settings', elgg_echo('admin:developers:settings'), 'developers');
		elgg_add_admin_menu_item('preview', elgg_echo('admin:developers:preview'), 'developers');
	}
}