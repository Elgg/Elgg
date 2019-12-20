<?php

$guid = (int) get_input('guid');

$plugin = get_entity($guid);
if (!$plugin instanceof ElggPlugin || !$plugin->canEdit()) {
	return;
}

$show_dependencies = false;
$package = $plugin->getPackage();
if ($package && !$package->checkDependencies()) {
	$show_dependencies = true;
}

// table contents
$info = [];

$info[elgg_echo('admin:plugins:label:version')] = htmlspecialchars($plugin->getManifest()->getVersion());

$info[elgg_echo('admin:plugins:label:id')] = elgg_view('output/text', [
	'value' => $plugin->getID(),
]);

$info[elgg_echo('admin:plugins:label:author')] = elgg_view('output/text', [
	'value' => $plugin->getManifest()->getAuthor(),
]);

$url = $plugin->getManifest()->getWebsite();
if (!empty($url)) {
	$info[elgg_echo('admin:plugins:label:website')] = elgg_view('output/url', [
		'href' => $plugin->getManifest()->getWebsite(),
		'text' => $plugin->getManifest()->getWebsite(),
		'is_trusted' => true,
	]);
}

$info[elgg_echo('admin:plugins:label:licence')] = elgg_view('output/text', [
	'value' => $plugin->getManifest()->getLicense(),
]);

$site_path = elgg_get_root_path();
$path = $plugin->getPath();
if (0 === strpos($path, $site_path)) {
	$path = substr($path, strlen($site_path));
}
$info[elgg_echo('admin:plugins:label:location')] = htmlspecialchars($path);

$categories = (array) $plugin->getManifest()->getCategories();
array_walk($categories, function(&$value) {
	$value = htmlspecialchars(ElggPluginManifest::getFriendlyCategory($value));
});

$info[elgg_echo('admin:plugins:label:categories')] = implode(', ', $categories);

// assemble table
$rows = '';
foreach ($info as $name => $value) {
	if (trim($value) === '') {
		continue;
	}
	$rows .= "<tr><th>$name</th><td>$value</td></tr>";
}

$info_html = elgg_format_element('table', ['class' => 'elgg-table'], $rows);

$extra_info = elgg_echo("admin:plugins:info:" . $plugin->getID());
if ($extra_info !== ("admin:plugins:info:" . $plugin->getID())) {
	$info_html .= "<div class='mtm'>" . $extra_info . "</div>";
}

$resources = [
	'repository' => $plugin->getManifest()->getRepositoryURL(),
	'bugtracker' => $plugin->getManifest()->getBugTrackerURL(),
];

$resources_html = '';
foreach ($resources as $id => $href) {
	if ($href) {
		$resources_html .= "<li>";
		$resources_html .= elgg_view('output/url', [
				'href' => $href,
				'text' => elgg_echo("admin:plugins:label:$id"),
				'is_trusted' => true,
		]);
		$resources_html .= "</li>";
	}
}

if (!empty($resources_html)) {
	$resources_html = elgg_format_element('ul', [], $resources_html);
}

// show links to text files
$files = $plugin->getAvailableTextFiles();

$files_html = '';
if (!empty($files)) {
	$files_html = '<ul>';
	foreach ($files as $file => $path) {
		$url = 'admin_plugin_text_file/' . $plugin->getID() . "/$file";
		$link = elgg_view('output/url', [
				'text' => $file,
				'href' => $url,
				'is_trusted' => true,
		]);
		$files_html .= "<li>$link</li>";
	}
	$files_html .= '</ul>';
}

$body = "<div class='elgg-plugin'>";

$body .= "<div class='elgg-plugin-details-container pvm'>";

$body .= elgg_view('output/longtext', ['value' => $plugin->getManifest()->getDescription()]);

// tabs
$tabs = [
	'elgg-plugin-details-info' => [
		'text' => elgg_echo("admin:plugins:label:info"),
		'selected' => !$show_dependencies,
		'content' => $info_html,
	],
];

if (!empty($resources_html)) {
	$tabs['elgg-plugin-details-resources'] = [
		'text' => elgg_echo("admin:plugins:label:resources"),
		'content' => $resources_html,
	];
}

if (!empty($files_html)) {
	$tabs['elgg-plugin-details-files'] = [
		'text' => elgg_echo("admin:plugins:label:files"),
		'content' => $files_html,
	];
}

$tabs['elgg-plugin-details-dependencies'] = [
	'text' => elgg_echo("admin:plugins:label:dependencies"),
	'selected' => $show_dependencies,
	'content' => elgg_view('object/plugin/elements/dependencies', ['plugin' => $plugin]),
];

$body .= elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);

$body .= "</div>";
$body .= "</div>";

echo elgg_view_module("plugin-details", $plugin->getDisplayName(), $body);
