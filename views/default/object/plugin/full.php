<?php
/**
 * Displays a plugin
 *
 * @uses $vars['entity']
 * @uses $vars['display_reordering'] Do we allow the plugins to be rearranged with drag and drop
 *
 */

if (!elgg_is_admin_logged_in()) {
	return;
}

/* @var ElggPlugin $plugin */
$plugin = elgg_extract('entity', $vars);
$reordering = elgg_extract('display_reordering', $vars, false);
$active = $plugin->isActive();

$classes = ['elgg-plugin'];
$classes[] = $reordering ? 'elgg-state-draggable' : 'elgg-state-undraggable';

$error = '';
if ($active) {
	$can_activate = false;
	try {
		$plugin->assertCanDeactivate();
		$can_deactivate = true;
	} catch (\Elgg\Exceptions\PluginException $e) {
		$error = $e->getMessage();
		$can_deactivate = false;
	}
} else {
	$can_deactivate = false;
	try {
		$plugin->assertCanActivate();
		$can_activate = true;
	} catch (\Elgg\Exceptions\PluginException $e) {
		$error = $e->getMessage();
		$can_activate = false;
	}
}

// activate / deactivate button
$options = [
	'is_action' => true,
];

if ($active) {
	$classes[] = 'elgg-state-active';
	
	$options['title'] = elgg_echo('admin:plugins:deactivate');
	$options['text'] = elgg_echo('admin:plugins:deactivate');
	if ($can_deactivate) {
		$options['href'] = elgg_generate_action_url('admin/plugins/deactivate', ['plugin_guids[]' => $plugin->guid]);
		$options['class'] = 'elgg-button elgg-button-cancel elgg-plugin-state-change';
	} else {
		$classes[] = 'elgg-state-cannot-deactivate';
		
		$options['title'] = elgg_echo('admin:plugins:cannot_deactivate');
		$options['class'] = 'elgg-button elgg-button-cancel';
		$options['disabled'] = true;
	}
} else if ($can_activate) {
	$classes[] = 'elgg-state-inactive';
	
	$options['href'] = elgg_generate_action_url('admin/plugins/activate', ['plugin_guids[]' => $plugin->guid]);
	$options['title'] = elgg_echo('admin:plugins:activate');
	$options['class'] = 'elgg-button elgg-button-submit elgg-plugin-state-change';
	$options['text'] = elgg_echo('admin:plugins:activate');
} else {
	$classes[] = 'elgg-state-inactive';
	$classes[] = 'elgg-state-cannot-activate';
	
	$options['title'] = elgg_echo('admin:plugins:cannot_activate');
	$options['class'] = 'elgg-button elgg-button-submit';
	$options['text'] = elgg_echo('admin:plugins:activate');
	$options['disabled'] = true;
}

$action_button = elgg_trigger_plugin_hook('action_button', 'plugin', ['entity' => $plugin], elgg_view('output/url', $options));

// Display categories and make category classes
$categories = array_keys($plugin->getCategories());

$categories[] = 'all';
$categories[] = $active ? 'active' : 'inactive';

if (!in_array('bundled', $categories)) {
	$categories[] = 'nonbundled';
}

$categories = array_map('strtolower', $categories);

$style = null;
if (!in_array(elgg_extract('active_filter', $vars), $categories)) {
	$style = 'display: none;';
}

foreach ($categories as $category) {
	$css_class = preg_replace('/[^a-z0-9-]/i', '-', $category);
	$classes[] = "elgg-plugin-category-{$css_class}";
}

$title = elgg_view('output/url', [
	'href' => "ajax/view/object/plugin/details?guid={$plugin->guid}",
	'text' => $plugin->getDisplayName(),
	'class' => 'elgg-lightbox',
]);

$content = '';

if ($error) {
	$type = $active ? 'notice' : 'error';

	$content .= elgg_view_message($type, $error, ['title' => false, 'class' => 'elgg-subtext']);
}
	
echo elgg_view('object/elements/summary', [
	'entity' => $plugin,
	'class' => $classes,
	'image_block_vars' => [
		'style' => $style,
		'id' => preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID()),
	],
	'data-guid' => $plugin->guid,
	'icon' => $action_button,
	'title' => $title,
	'subtitle' => elgg_view('output/longtext', [
		'value' => $plugin->getDescription(),
	]),
	'content' => $content,
	'display_reordering' => $reordering,
]);
