<?php
/**
 * AdminShout - send an email message to all site users
 */

elgg_register_event_handler('init', 'system', 'adminshout_init');

function adminshout_init() {
	elgg_register_admin_menu_item('administer', 'adminshout', 'administer_utilities');

	$base = dirname(__FILE__) . '/actions';
	elgg_register_action('adminshout/send', "$base/send.php", 'admin');
}
