<?php
/**
 * Displays a plugin on the admin screen.
 *
 * This file renders a plugin for the admin screen, including active/deactive,
 * manifest details & display plugin settings.
 *
 * @uses $vars['entity']
 * @uses $vars['display_reordering'] Do we display the priority reordering links?
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */
/* @var ElggPlugin $plugin */
$plugin = elgg_extract('entity', $vars);
$reordering = elgg_extract('display_reordering', $vars, false);
$priority = $plugin->getPriority();
$active = $plugin->isActive();
$plugin_id = $plugin->getID();

$actions_base = '/action/admin/plugins/';

// build reordering links
$links = '';
$classes = ['elgg-plugin'];

if ($reordering) {
	$max_priority = _elgg_get_max_plugin_priority();
	
	if ($active) {
		$can_activate = false;
		$can_deactivate = $plugin->canDeactivate();
	} else {
		$can_deactivate = false;
		$can_activate = $plugin->canActivate();
	}

	$classes[] = 'elgg-state-draggable';

	// top and up link only if not at top
	if ($priority > 1) {
		$top_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', [
			'plugin_guid' => $plugin->guid,
			'priority' => 'first',
			'is_action' => true
		]);

		$links .= "<li>" . elgg_view('output/url', [
			'href' => $top_url,
			'text' => elgg_echo('top'),
			'is_action' => true,
			'is_trusted' => true,
		]) . "</li>";

		$up_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', [
			'plugin_guid' => $plugin->guid,
			'priority' => '-1',
			'is_action' => true
		]);

		$links .= "<li>" . elgg_view('output/url', [
			'href' => $up_url,
			'text' => elgg_echo('up'),
			'is_action' => true,
			'is_trusted' => true,
		]) . "</li>";
	}

	// down and bottom links only if not at bottom
	if ($priority < $max_priority) {
		$down_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', [
			'plugin_guid' => $plugin->guid,
			'priority' => '+1',
			'is_action' => true
		]);

		$links .= "<li>" . elgg_view('output/url', [
			'href' => $down_url,
			'text' => elgg_echo('down'),
			'is_action' => true,
			'is_trusted' => true,
		]) . "</li>";

		$bottom_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', [
			'plugin_guid' => $plugin->guid,
			'priority' => 'last',
			'is_action' => true
		]);

		$links .= "<li>" . elgg_view('output/url', [
			'href' => $bottom_url,
			'text' => elgg_echo('bottom'),
			'is_action' => true,
			'is_trusted' => true,
		]) . "</li>";
	}

	if ($links) {
		$links = '<ul class="elgg-menu elgg-plugin-list-reordering">' . $links . '</ul>';
	}
} else {
	$classes[] = 'elgg-state-undraggable';
}

// activate / deactivate links
// always let them deactivate
$options = [
	'is_action' => true,
	'is_trusted' => true,
];
$action = false;
if ($active) {
	$classes[] = 'elgg-state-active';
	$options['title'] = elgg_echo('admin:plugins:deactivate');
	$options['text'] = elgg_echo('admin:plugins:deactivate');
	if ($can_deactivate) {
		$action = 'deactivate';
		$options['class'] = 'elgg-button elgg-button-cancel elgg-plugin-state-change';
	} else {
		$classes[] = 'elgg-state-cannot-deactivate';
		$options['title'] = elgg_echo('admin:plugins:cannot_deactivate');
		$options['class'] = 'elgg-button elgg-button-cancel elgg-state-disabled';
		$options['disabled'] = 'disabled';
	}
} else if ($can_activate) {
	$classes[] = 'elgg-state-inactive';
	$action = 'activate';
	$options['title'] = elgg_echo('admin:plugins:activate');
	$options['class'] = 'elgg-button elgg-button-submit elgg-plugin-state-change';
	$options['text'] = elgg_echo('admin:plugins:activate');
} else {
	$classes[] = 'elgg-state-inactive elgg-state-cannot-activate';
	$options['title'] = elgg_echo('admin:plugins:cannot_activate');
	$options['class'] = 'elgg-button elgg-button-submit elgg-state-disabled';
	$options['text'] = elgg_echo('admin:plugins:activate');
	$options['disabled'] = 'disabled';
}

if ($action) {
	$options['href'] = elgg_http_add_url_query_elements($actions_base . $action, [
		'plugin_guids[]' => $plugin->guid
	]);
}

$action_button = elgg_view('output/url', $options);

$action_button = elgg_trigger_plugin_hook("action_button", "plugin", ["entity" => $plugin], $action_button);

// Display categories and make category classes
$categories = $plugin->getManifest()->getCategories();

$categories[] = 'all';
$categories[] = $active ? 'active' : 'inactive';

if (!in_array('bundled', $categories)) {
	$categories[] = 'nonbundled';
}

foreach ($categories as $category) {
	$css_class = preg_replace('/[^a-z0-9-]/i', '-', $category);
	$classes[] = "elgg-plugin-category-$css_class";
}

$body = elgg_view('output/url', [
	'href' => "ajax/view/object/plugin/details?guid={$plugin->getGUID()}",
	'text' => $plugin->getDisplayName(),
	'class' => 'elgg-lightbox elgg-plugin-title',
]);

if (elgg_view_exists("plugins/{$plugin_id}/settings")) {
	$body .= elgg_view('output/url', [
		'href' => "admin/plugin_settings/{$plugin_id}",
		'title' => elgg_echo('settings'),
		'text' => elgg_view_icon('settings-alt'),
		'class' => 'elgg-plugin-settings',
	]);
}

$description = elgg_view('output/longtext', ['value' => $plugin->getManifest()->getDescription()]);
$body .= elgg_format_element('span', [
	'class' => 'elgg-plugin-list-description',
], $description);
	
$error = $plugin->getError();
if ($error) {
	$message = elgg_format_element('p', [
		'class' => $active ? 'elgg-text-help' : 'elgg-text-help elgg-state-error',
	], $error);
	
	$body .= "<div>$message</div>";
}

$result = elgg_view_image_block($action_button, $links . $body);
echo elgg_format_element('div', [
	'class' => $classes,
	'id' => preg_replace('/[^a-z0-9-]/i', '-', $plugin_id),
	'data-guid' => $plugin->guid,
], $result);
