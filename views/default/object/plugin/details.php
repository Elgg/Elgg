<?php

$guid = (int) get_input('guid');

$plugin = get_entity($guid);
if (!$plugin instanceof ElggPlugin || !$plugin->canEdit()) {
	return;
}

$show_dependencies_first = !$plugin->meetsDependencies();

// table contents
$info = [];

$info[elgg_echo('admin:plugins:label:version')] = htmlspecialchars($plugin->getVersion());

$info[elgg_echo('admin:plugins:label:id')] = elgg_view('output/text', [
	'value' => $plugin->getID(),
]);

$authors = $plugin->getAuthors();
if (!empty($authors)) {
	$authors_text = '';
	foreach ($authors as $author) {
		if (empty($author->name())) {
			continue;
		}
		
		$author_content = $author->name();
		if ($author->role()) {
			$author_content .= ' - ' . $author->role();
		}
		
		if ($author->email()) {
			$author_content .= elgg_view('output/email', [
				'text' => false,
				'icon' => 'envelope-regular',
				'value' => $author->email(),
				'class' => 'mls',
			]);
		}
		if ($author->homepage()) {
			$author_content .= elgg_view('output/url', [
				'icon' => 'globe-americas',
				'text' => false,
				'href' => $author->homepage(),
				'class' => 'mls',
			]);
		}
		
		$authors_text .= elgg_format_element('li', [], $author_content);
	}
	$info[elgg_echo('admin:plugins:label:authors')] = elgg_format_element('ul', [], $authors_text);
}

$url = $plugin->getWebsite();
if (!empty($url)) {
	$info[elgg_echo('admin:plugins:label:website')] = elgg_view('output/url', [
		'href' => $url,
	]);
}

$info[elgg_echo('admin:plugins:label:licence')] = elgg_view('output/text', [
	'value' => $plugin->getLicense(),
]);

$site_path = elgg_get_root_path();
$path = $plugin->getPath();
if (0 === strpos($path, $site_path)) {
	$path = substr($path, strlen($site_path));
}
$info[elgg_echo('admin:plugins:label:location')] = htmlspecialchars($path);

$categories = array_values($plugin->getCategories());
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

if (elgg_language_key_exists("admin:plugins:info:{$plugin->getID()}")) {
	$info_html .= elgg_format_element('div', ['class' => 'mtm'], elgg_echo("admin:plugins:info:{$plugin->getID()}"));
}

$resources = [
	'repository' => $plugin->getRepositoryURL(),
	'bugtracker' => $plugin->getBugTrackerURL(),
];

$resources_html = '';
foreach ($resources as $id => $href) {
	if ($href) {
		$resources_html .= elgg_format_element('li', [], elgg_view('output/url', [
			'href' => $href,
			'text' => elgg_echo("admin:plugins:label:{$id}"),
			'is_trusted' => true,
		]));
	}
}

if (!empty($resources_html)) {
	$resources_html = elgg_format_element('ul', [], $resources_html);
}

// show links to text files
$files_html = '';
foreach (\ElggPlugin::ADDITIONAL_TEXT_FILES as $file) {
	$file_path = $plugin->getPath() . $file;
	if (!is_file($file_path) || !is_readable($file_path)) {
		continue;
	}
	
	$link = elgg_view('output/url', [
		'text' => $file,
		'href' => elgg_generate_url('admin_plugin_text_file', [
			'plugin_id' => $plugin->getID(),
			'filename' => $file,
		]),
		'is_trusted' => true,
	]);
	$files_html .= elgg_format_element('li', [], $link);
}

if (!empty($files_html)) {
	$files_html = elgg_format_element('ul', [], $files_html);
}

$body = "<div class='elgg-plugin'>";

$body .= "<div class='elgg-plugin-details-container pvm'>";

$body .= elgg_view('output/longtext', ['value' => $plugin->getDescription()]);

// tabs
$tabs = [
	'elgg-plugin-details-info' => [
		'text' => elgg_echo('admin:plugins:label:info'),
		'selected' => !$show_dependencies_first,
		'content' => $info_html,
	],
];

if (!empty($resources_html)) {
	$tabs['elgg-plugin-details-resources'] = [
		'text' => elgg_echo('admin:plugins:label:resources'),
		'content' => $resources_html,
	];
}

if (!empty($files_html)) {
	$tabs['elgg-plugin-details-files'] = [
		'text' => elgg_echo('admin:plugins:label:files'),
		'content' => $files_html,
	];
}

$dependencies_html = elgg_view('object/plugin/elements/dependencies', ['plugin' => $plugin]);
if (!empty($dependencies_html)) {
	$tabs['elgg-plugin-details-dependencies'] = [
		'text' => elgg_echo('admin:plugins:label:dependencies'),
		'selected' => $show_dependencies_first,
		'content' => $dependencies_html,
	];
}

$body .= elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);

$body .= '</div>';
$body .= '</div>';

echo elgg_view_module('plugin-details', $plugin->getDisplayName(), $body);
