<?php
/**
 * Displays a plugin on the admin screen.
 *
 * This file renders a plugin for the admin screen, including active/deactive,
 * manifest details & display plugin settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

$plugin = $vars['plugin'];
$priority = $plugin->getPriority();
$active = $plugin->isActive();

$name = $plugin->manifest->getName();
$can_activate = $plugin->canActivate();
$max_priority = elgg_get_max_plugin_priority();
$actions_base = '/action/admin/plugins/';

$ts = time();
$token = generate_action_token($ts);
$active_class = ($active && $can_activate) ? 'active' : 'not_active';

// build reordering links
$links = '';

// top and up link only if not at top
if ($priority > 1) {
	$top_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
		'plugin_guid' => $plugin->guid,
		'priority' => 'first',
		'is_action' => true
	));

	$links .= elgg_view('output/url', array(
		'href' 		=> $top_url,
		'text'		=> elgg_echo('top'),
		'is_action'	=> true
	));

	$up_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
		'plugin_guid' => $plugin->guid,
		'priority' => '-1',
		'is_action' => true
	));

	$links .= elgg_view('output/url', array(
		'href' 		=> $up_url,
		'text'		=> elgg_echo('up'),
		'is_action'	=> true
	));
}

// down and bottom links only if not at bottom
if ($priority < $max_priority) {
	$down_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
		'plugin_guid' => $plugin->guid,
		'priority' => '+1',
		'is_action' => true
	));

	$links .= elgg_view('output/url', array(
		'href' 		=> $down_url,
		'text'		=> elgg_echo('down'),
		'is_action'	=> true
	));

	$bottom_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
		'plugin_guid' => $plugin->guid,
		'priority' => 'last',
		'is_action' => true
	));

	$links .= elgg_view('output/url', array(
		'href' 		=> $bottom_url,
		'text'		=> elgg_echo('bottom'),
		'is_action'	=> true
	));
}

// activate / deactivate links
if ($can_activate) {
	if ($active) {
		$action = 'deactivate';
		$class = 'elgg-cancel-button';
	} else {
		$action = 'activate';
		$class = 'elgg-submit-button';
	}

	$url = elgg_http_add_url_query_elements($actions_base . $action, array(
		'plugin_guids[]' => $plugin->guid,
		'is_action' => true
	));

	$action_button = elgg_view('output/url', array(
		'href' 		=> $url,
		'text'		=> elgg_echo($action),
		'is_action'	=> true,
		'class'		=> "elgg-button $class"
	));
} else {
	$action_button = elgg_view('output/url', array(
		'text'		=> elgg_echo('admin:plugins:cannot_activate'),
		'disabled'	=> 'disabled',
		'class'		=> "elgg-action-button disabled"
	));
}

// Display categories
$categories_html = '';
if ($categories) {
	$categories_arr = array();
	$base_url = elgg_get_site_url() . "pg/admin/plugins?category=";

	foreach ($categories as $category) {
		$url = $base_url . urlencode($category);
		$categories_arr[] = "<a href=\"$url\">" . htmlspecialchars($category) . '</a>';
	}

	$categories_html = implode(', ', $categories_arr);
}

$screenshots_html = '';
$screenshots = $plugin->manifest->getScreenshots();
if ($screenshots) {
	$base_url = elgg_get_plugins_path() . $plugin->getID() . '/';
	foreach ($screenshots as $screenshot) {
		$desc = elgg_echo($screenshot['description']);
		$alt = htmlentities($desc, ENT_QUOTES, 'UTF-8');
		$screenshot_full = "{$vars['url']}pg/admin_plugin_screenshot/{$plugin->getID()}/full/{$screenshot['path']}";
		$screenshot_src = "{$vars['url']}pg/admin_plugin_screenshot/{$plugin->getID()}/thumbnail/{$screenshot['path']}";

		$screenshots_html .= "<li class=\"elgg-plugin-screenshot prm ptm\"><a href=\"$screenshot_full\">"
							. "<img src=\"$screenshot_src\" alt=\"$alt\"></a></li>";
	}
}

// metadata
$description = elgg_view('output/longtext', array('value' => $plugin->manifest->getDescription()));
$author = '<span>' . elgg_echo('admin:plugins:label:author') . '</span>: '
			. elgg_view('output/text', array('value' => $plugin->manifest->getAuthor()));
$version = htmlspecialchars($plugin->manifest->getVersion());
$website = elgg_view('output/url', array(
	'href' => $plugin->manifest->getWebsite(),
	'text' => $plugin->manifest->getWebsite()
));

$copyright = elgg_view('output/text', array('value' => $plugin->manifest->getCopyright()));
$license = elgg_view('output/text', array('value' => $plugin->manifest->getLicense()));

?>

<div id="elgg-plugin-<?php echo $plugin->guid; ?>" class="elgg-state-draggable plugin_details <?php echo $active_class ?>">
	<div class="admin_plugin_reorder">
	<?php echo "$links"; ?>
	</div><div class="clearfloat"></div>

	<div class="admin_plugin_enable_disable"><?php echo $action_button; ?></div>

<?php
$settings_view = 'settings/' . $plugin->getID() . '/edit';
if (elgg_view_exists($settings_view)) {
	$link = elgg_get_site_url() . "pg/admin/plugin_settings/" . $plugin->getID();
	$settings_link = "<a class='plugin_settings small link' href='$link'>[" . elgg_echo('settings') . "]</a>";
}
?>
	<h3 class="elgg-head"><?php echo $plugin->manifest->getName() . " $version $settings_link"; ?></h3>
		<div class="plugin_description"><?php echo $description; ?></div>
		<p class="plugin_author"><?php echo $author . ' - ' . $website; ?></p>

		<?php
		if ($plugin->manifest->getApiVersion() < 1.8) {
			$reqs = $plugin->manifest->getRequires();
			if (!$reqs) {
				$message = elgg_echo('admin:plugins:warning:elgg_version_unknown');
				echo "<p class=\"plugin-cannot-activate\">$message</p>";
			}
		}

		if (!$can_activate) {
			$message = elgg_echo('admin:plugins:warning:unmet_dependencies');
			echo "<p class=\"elgg-unsatisfied-dependency\">$message</p>";
		}
		?>

		<div class="pts"><a class="manifest_details small link"><?php echo elgg_echo("admin:plugins:label:moreinfo"); ?></a></div>

		<div class="manifest_file hidden">

		<?php
		if ($screenshots_html) {
			?>
			<div><ul><?php echo $screenshots_html; ?></ul></div>
			<?php
		}

		if ($categories_html) {
			?>
			<div><?php echo elgg_echo('admin:plugins:label:categories') . ": " . $categories_html; ?></div>
			<?php
		}

		?>
		<div><?php echo elgg_echo('admin:plugins:label:copyright') . ": " . $copyright; ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:licence') . ": " . $license; ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:location') . ": " . htmlspecialchars($plugin->getPath()) ?></div>

		<div><?php echo elgg_echo('admin:plugins:label:dependencies'); ?>:
		<?php
			echo elgg_view('admin/components/plugin_dependencies', array('plugin' => $plugin));
		?>
		</div>
	</div>
</div>