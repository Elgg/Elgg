<?php

$guid = (int) get_input("guid");

if (empty($guid)) {
	return;
}

$plugin = get_entity($guid);
if (!elgg_instanceof($plugin, 'object', 'plugin')) {
	return;
}

$active = $plugin->isActive();
$can_activate = $plugin->canActivate();

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
		
		$thumbnail = elgg_view('output/img', array(
			'src' => "admin_plugin_screenshot/{$plugin->getID()}/thumbnail/{$screenshot['path']}",
			'alt' => $alt
		));
		$attr = array(
			'rel' => $rel,
			'class' => "elgg-plugin-screenshot pas $state",
			'title' => $alt
		);
		$screenshots_menu .= elgg_format_element('li', $attr, $thumbnail);
		
		$screenshots_body .= elgg_view('output/img', array(
			'src' => "admin_plugin_screenshot/{$plugin->getID()}/full/{$screenshot['path']}",
			'alt' => $alt,
			'title' => $alt,
			'class' => "hidden $state",
			'rel' => $rel
		));
	}
	
	$screenshots_menu = "<ul>" . $screenshots_menu . "</ul>";
	$screenshots_body = "<div>" . $screenshots_body . "</div>";
}

$info_html = "<table class='elgg-table'>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:name') . "</th><td>" . elgg_view('output/text', array('value' => $plugin->getManifest()->getName())) . "</td></tr>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:id') . "</th><td>" . elgg_view('output/text', array('value' => $plugin->getID())) . "</td></tr>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:version') . "</th><td>" . htmlspecialchars($plugin->getManifest()->getVersion()) . "</td></tr>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:author') . "</th><td>" . elgg_view('output/text', array('value' => $plugin->getManifest()->getAuthor())) . "</td></tr>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:website') . "</th><td>" . elgg_view('output/url', array(
		'href' => $plugin->getManifest()->getWebsite(),
		'text' => $plugin->getManifest()->getWebsite(),
		'is_trusted' => true,
)) . "</td></tr>";

$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:copyright') . "</th><td>" . elgg_view('output/text', array('value' => $plugin->getManifest()->getCopyright())) . "</td></tr>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:licence') . "</th><td>" . elgg_view('output/text', array('value' => $plugin->getManifest()->getLicense())) . "</td></tr>";
$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:location') . "</th><td>" . htmlspecialchars($plugin->getPath()) . "</td></tr>";

$categories = $plugin->getManifest()->getCategories();
if ($categories) {
	$info_html .= "<tr><th>" . elgg_echo('admin:plugins:label:categories') . "</th><td>";
	array_walk($categories, function(&$value) {
        $value = htmlspecialchars(ElggPluginManifest::getFriendlyCategory($value));
    });
	$info_html .= implode(', ', $categories);
	$info_html .= "</td></tr>";
}
$info_html .= "</table>";

$extra_info = elgg_echo("admin:plugins:info:" . $plugin->getID());
if ($extra_info !== ("admin:plugins:info:" . $plugin->getID())) {
	$info_html .= "<div class='mtm'>" . $extra_info . "</div>";
}

$resources = array(
	'repository' => $plugin->getManifest()->getRepositoryURL(),
	'bugtracker' => $plugin->getManifest()->getBugTrackerURL(),
	'donate' => $plugin->getManifest()->getDonationsPageURL(),
);

$resources_html = "";
foreach ($resources as $id => $href) {
	if ($href) {
		$resources_html .= "<li>";
		$resources_html .= elgg_view('output/url', array(
				'href' => $href,
				'text' => elgg_echo("admin:plugins:label:$id"),
				'is_trusted' => true,
		));
		$resources_html .= "</li>";
	}
}

if (!empty($resources_html)) {
	$resources_html = "<ul>" . $resources_html . "</ul>";
}

// show links to text files
$files = $plugin->getAvailableTextFiles();

$files_html = '';
if ($files) {
	$files_html = '<ul>';
	foreach ($files as $file => $path) {
		$url = 'admin_plugin_text_file/' . $plugin->getID() . "/$file";
		$link = elgg_view('output/url', array(
				'text' => $file,
				'href' => $url,
				'is_trusted' => true,
		));
		$files_html .= "<li>$link</li>";

	}
	$files_html .= '</ul>';
}

$body = "<div class='elgg-plugin'>";

$body .= "<div class='elgg-plugin-details-container pvm'>";


// tabs
$tabs = array();

$tabs[] = array(
	'text' => elgg_echo("admin:plugins:label:info"),
	'rel' => 'elgg-plugin-details-info',
	'selected' => ($can_activate) ? true : false
);

if ($resources_html) {
	$tabs[] = array(
		'text' => elgg_echo("admin:plugins:label:resources"),
		'rel' => 'elgg-plugin-details-resources'
	);
}

if ($files_html) {
	$tabs[] = array(
		'text' => elgg_echo("admin:plugins:label:files"),
		'rel' => 'elgg-plugin-details-files'
	);
}

if ($screenshots) {
	$tabs[] = array(
		'text' => elgg_echo("admin:plugins:label:screenshots"),
		'rel' => 'elgg-plugin-details-screenshots'
	);
}

$tabs[] = array(
	'text' => elgg_echo("admin:plugins:label:dependencies"),
	'rel' => 'elgg-plugin-details-dependencies',
	'selected' => (!$can_activate) ? true : false
);

$body .= elgg_view('navigation/tabs', array(
	'tabs' => $tabs
));

$body .= "<div>";

// info
if ($can_activate) {
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
if ($can_activate) {
	$body .= "<div class='elgg-plugin-details-dependencies hidden'>";
} else {
	$body .= "<div class='elgg-plugin-details-dependencies'>";
}
$body .= elgg_view('object/plugin/elements/dependencies', array('plugin' => $plugin));
$body .= "</div>";

$body .= "</div>";

$body .= "</div>";

$body .= "</div>";

echo elgg_view_module("plugin-details", $plugin->getManifest()->getName(), $body);
