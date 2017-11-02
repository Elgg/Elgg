<?php

$guid = (int) get_input("guid");

if (empty($guid)) {
	return;
}

$plugin = get_entity($guid);
if (!$plugin instanceof ElggPlugin) {
	return;
}

$show_dependencies = false;
$package = $plugin->getPackage();
if ($package && !$package->checkDependencies()) {
	$show_dependencies = true;
}

$screenshots_menu = '';
$screenshots_body = '';
$screenshots = $plugin->getManifest()->getScreenshots();
if ($screenshots) {
	foreach ($screenshots as $key => $screenshot) {
		$state = "";
		$rel = "elgg-plugin-details-screenshot-" . $key;
		if ($key == 0) {
			$state = " elgg-state-selected";
		}
		
		$desc = elgg_echo($screenshot['description']);
		$alt = htmlentities($desc, ENT_QUOTES, 'UTF-8');
		
		$thumbnail = elgg_view('output/img', [
			'src' => "mod/{$plugin->getID()}/{$screenshot['path']}",
			'alt' => $alt
		]);
		$attr = [
			'rel' => $rel,
			'class' => "elgg-plugin-screenshot pas $state",
			'title' => $alt
		];
		$screenshots_menu .= elgg_format_element('li', $attr, $thumbnail);
		
		$screenshots_body .= elgg_view('output/img', [
			'src' => "mod/{$plugin->getID()}/{$screenshot['path']}",
			'alt' => $alt,
			'title' => $alt,
			'class' => "hidden $state",
			'rel' => $rel
		]);
	}
	
	$screenshots_menu = elgg_format_element('ul', [], $screenshots_menu);
	$screenshots_body = elgg_format_element('div', [], $screenshots_body);
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
if ($url) {
	$info[elgg_echo('admin:plugins:label:website')] = elgg_view('output/url', [
		'href' => $plugin->getManifest()->getWebsite(),
		'text' => $plugin->getManifest()->getWebsite(),
		'is_trusted' => true,
	]);
}

$info[elgg_echo('admin:plugins:label:copyright')] = elgg_view('output/text', [
	'value' => $plugin->getManifest()->getCopyright(),
]);

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
	'donate' => $plugin->getManifest()->getDonationsPageURL(),
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
if ($files) {
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
$tabs = [];

$tabs[] = [
	'text' => elgg_echo("admin:plugins:label:info"),
	'rel' => 'elgg-plugin-details-info',
	'selected' => !$show_dependencies,
];

if ($resources_html) {
	$tabs[] = [
		'text' => elgg_echo("admin:plugins:label:resources"),
		'rel' => 'elgg-plugin-details-resources'
	];
}

if ($files_html) {
	$tabs[] = [
		'text' => elgg_echo("admin:plugins:label:files"),
		'rel' => 'elgg-plugin-details-files'
	];
}

if ($screenshots) {
	$tabs[] = [
		'text' => elgg_echo("admin:plugins:label:screenshots"),
		'rel' => 'elgg-plugin-details-screenshots'
	];
}

$tabs[] = [
	'text' => elgg_echo("admin:plugins:label:dependencies"),
	'rel' => 'elgg-plugin-details-dependencies',
	'selected' => $show_dependencies,
];

$body .= elgg_view('navigation/tabs', [
	'tabs' => $tabs,
	'class' => 'mtl',
]);

$body .= "<div>";

// info
if (!$show_dependencies) {
	$body .= "<div class='elgg-plugin-details-info'>";
} else {
	$body .= "<div class='elgg-plugin-details-info hidden'>";
}
$body .= $info_html;
$body .= "</div>";

// resources
if ($resources_html) {
	$body .= "<div class='elgg-plugin-details-resources hidden'>";
	$body .= $resources_html;
	$body .= "</div>";
}

// files
if ($files_html) {
	$body .= "<div class='elgg-plugin-details-files hidden'>";
	$body .= $files_html;
	$body .= "</div>";
}

// screenshots
if ($screenshots) {
	$body .= "<div class='elgg-plugin-details-screenshots hidden'>";
	$body .= $screenshots_menu;
	$body .= $screenshots_body;
	$body .= "</div>";
}

// dependencies
if (!$show_dependencies) {
	$body .= "<div class='elgg-plugin-details-dependencies hidden'>";
} else {
	$body .= "<div class='elgg-plugin-details-dependencies'>";
}
$body .= elgg_view('object/plugin/elements/dependencies', ['plugin' => $plugin]);
$body .= "</div>";

$body .= "</div>";

$body .= "</div>";

$body .= "</div>";

echo elgg_view_module("plugin-details", $plugin->getDisplayName(), $body);
