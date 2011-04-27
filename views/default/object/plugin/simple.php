<?php
$plugin = $vars['entity'];

$plugin_guid = $plugin->guid;
$plugin_id = $plugin->getID();
$active = $plugin->isActive();
$can_activate = $plugin->canActivate();
$name = $plugin->getManifest()->getName();
$author = $plugin->getManifest()->getAuthor();
$version = $plugin->getManifest()->getVersion();
$website = $plugin->getManifest()->getWebsite();
$description = $plugin->getManifest()->getDescription();

if ($active) {
	$active_class = 'elgg-state-active';
	$checked = 'checked="checked"';
} else {
	$active_class = 'elgg-state-inactive';
	$checked = '';
}

if ($can_activate) {
	$disabled = '';
} else {
	$disabled = 'disabled="disabled"';
	$description .= '<p>' . elgg_echo('admin:plugins:simple:cannot_activate') . '</p>';
}

$description = elgg_view('output/longtext', array('value' => $description));

$plugin_footer = '<ul class="elgg-menu elgg-menu-footer">';

if ($author) {
	$plugin_footer .= '<li>' . elgg_echo('admin:plugins:author', array($author)) . '</li>';
}

if ($version) {
	$plugin_footer .= '<li>' . elgg_echo('admin:plugins:version', array($version)) . '</li>';
}

if ($website) {
	$plugin_footer .= "<li><a href=\"$website\">" . elgg_echo('admin:plugins:plugin_website') . '</a></li>';
}

// show links to text files
$files = $plugin->getAvailableTextFiles();

foreach ($files as $file => $path) {
	$url = 'admin_plugin_text_file/' . $plugin->getID() . "/$file";
	$link = elgg_view('output/url', array(
		'text' => $file,
		'href' => $url
	));
	$plugin_footer .= "<li>$link</li>";

}

if (elgg_view_exists("settings/$plugin_id/edit")) {
	$settings_href = elgg_get_site_url() . "admin/plugin_settings/$plugin_id";
	$plugin_footer .= "<li><a class='plugin_settings link' href='$settings_href'>" . elgg_echo('settings') . "</a></li>";
}

$plugin_footer .= "</ul>";

echo <<<___END
	<div class="elgg-plugin $active_class elgg-grid">
		<div class="elgg-col elgg-col-1of5">
			<input type="checkbox" id="$plugin_guid" $checked $disabled name="active_plugin_guids[]" value="$plugin_guid"/>
			<label for="$plugin_guid">$name</label>
		</div>
		<div class="elgg-col elgg-col-4of5">
			$description
			$plugin_footer
		</div>
	</div>
___END;
