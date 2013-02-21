<?php
/**
 * Elgg AdminShout plugin
 * This plugin allows admins to send an email message to all site users.
 * 
 * @package ElggAdminShout
 */

elgg_register_event_handler('init', 'system', 'adminshout_init');

function adminshout_init() {
	elgg_register_admin_menu_item('administer', 'adminshout', 'administer_utilities');

	elgg_register_simplecache_view('js/adminshout');
	elgg_register_js('elgg.admin.shout', elgg_get_simplecache_url('js', 'adminshout'));

	elgg_extend_view('css/admin', 'css/adminshout');

	$base = elgg_get_plugins_path() . 'adminshout/actions/adminshout';
	elgg_register_action('adminshout/send', "$base/send.php", 'admin');
}
