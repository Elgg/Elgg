<?php
$page = $vars['segments'];

elgg_admin_gatekeeper();
_elgg_admin_add_plugin_settings_menu();
elgg_set_context('admin');

elgg_unregister_css('elgg');
elgg_load_js('elgg.admin');
elgg_load_js('jquery.jeditable');

// default to dashboard
if (!isset($page[0]) || empty($page[0])) {
	$page = array('dashboard');
}

// was going to fix this in the page_handler() function but
// it's commented to explicitly return a string if there's a trailing /
if (empty($page[count($page) - 1])) {
	array_pop($page);
}

$vars = array('page' => $page);

// special page for plugin settings since we create the form for them
if ($page[0] == 'plugin_settings') {
	if (isset($page[1]) && (elgg_view_exists("settings/{$page[1]}/edit") || 
		elgg_view_exists("plugins/{$page[1]}/settings"))) {

		$view = 'admin/plugin_settings';
		$plugin = elgg_get_plugin_from_id($page[1]);
		$vars['plugin'] = $plugin;

		$title = elgg_echo("admin:{$page[0]}");
	} else {
		forward('', '404');
	}
} else {
	$view = 'admin/' . implode('/', $page);
	$title = elgg_echo("admin:{$page[0]}");
	if (count($page) > 1) {
		$title .= ' : ' . elgg_echo('admin:' .  implode(':', $page));
	}
}

// gets content and prevents direct access to 'components' views
if ($page[0] == 'components' || !($content = elgg_view($view, $vars))) {
	$title = elgg_echo('admin:unknown_section');
	$content = elgg_echo('admin:unknown_section');
}

$body = elgg_view_layout('admin', array('content' => $content, 'title' => $title));
echo elgg_view_page($title, $body, 'admin');