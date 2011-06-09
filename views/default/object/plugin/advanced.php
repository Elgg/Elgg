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

$plugin = $vars['entity'];
$reordering = elgg_extract('display_reordering', $vars, false);
$priority = $plugin->getPriority();
$active = $plugin->isActive();

$name = $plugin->getManifest()->getName();
$can_activate = $plugin->canActivate();
$max_priority = elgg_get_max_plugin_priority();
$actions_base = '/action/admin/plugins/';

$ts = time();
$token = generate_action_token($ts);

// build reordering links
$links = '';

if ($reordering) {
	$draggable = 'elgg-state-draggable';

	// top and up link only if not at top
	if ($priority > 1) {
		$top_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
			'plugin_guid' => $plugin->guid,
			'priority' => 'first',
			'is_action' => true
		));

		$links .= "<li>" . elgg_view('output/url', array(
			'href' 		=> $top_url,
			'text'		=> elgg_echo('top'),
			'is_action'	=> true
		)) . "</li>";

		$up_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
			'plugin_guid' => $plugin->guid,
			'priority' => '-1',
			'is_action' => true
		));

		$links .= "<li>" . elgg_view('output/url', array(
			'href' 		=> $up_url,
			'text'		=> elgg_echo('up'),
			'is_action'	=> true
		)) . "</li>";
	}

	// down and bottom links only if not at bottom
	if ($priority < $max_priority) {
		$down_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
			'plugin_guid' => $plugin->guid,
			'priority' => '+1',
			'is_action' => true
		));

		$links .= "<li>" . elgg_view('output/url', array(
			'href' 		=> $down_url,
			'text'		=> elgg_echo('down'),
			'is_action'	=> true
		)) . "</li>";

		$bottom_url = elgg_http_add_url_query_elements($actions_base . 'set_priority', array(
			'plugin_guid' => $plugin->guid,
			'priority' => 'last',
			'is_action' => true
		));

		$links .= "<li>" . elgg_view('output/url', array(
			'href' 		=> $bottom_url,
			'text'		=> elgg_echo('bottom'),
			'is_action'	=> true
		)) . "</li>";
	}
} else {
	$draggable = 'elgg-state-undraggable';
}


// activate / deactivate links

// always let them deactivate
$options = array(
	'is_action' => true
);
if ($active) {
	$active_class = 'elgg-state-active';
	$action = 'deactivate';
	$options['text'] = elgg_echo('deactivate');
	$options['class'] = "elgg-button elgg-button-cancel";

	if (!$can_activate) {
		$active_class = 'elgg-state-active';
		$options['class'] = 'elgg-button elgg-state-warning';
	}
} else if ($can_activate) {
	$active_class = 'elgg-state-inactive';
	$action = 'activate';
	$options['text'] = elgg_echo('activate');
	$options['class'] = "elgg-button elgg-button-submit";
} else {
	$active_class = 'elgg-state-inactive';
	$action = '';
	$options['text'] = elgg_echo('admin:plugins:cannot_activate');
	$options['class'] = "elgg-button elgg-button-disabled";
	$options['disabled'] = 'disabled';
}

if ($action) {
	$url = elgg_http_add_url_query_elements($actions_base . $action, array(
		'plugin_guids[]' => $plugin->guid
	));

	$options['href'] = $url;
}
$action_button = elgg_view('output/url', $options);

// Display categories
$categories_html = '';
if ($categories) {
	$categories_arr = array();
	$base_url = elgg_get_site_url() . "admin/plugins?category=";

	foreach ($categories as $category) {
		$url = $base_url . urlencode($category);
		$categories_arr[] = "<a href=\"$url\">" . htmlspecialchars($category) . '</a>';
	}

	$categories_html = implode(', ', $categories_arr);
}

$screenshots_html = '';
$screenshots = $plugin->getManifest()->getScreenshots();
if ($screenshots) {
	$base_url = elgg_get_plugins_path() . $plugin->getID() . '/';
	foreach ($screenshots as $screenshot) {
		$desc = elgg_echo($screenshot['description']);
		$alt = htmlentities($desc, ENT_QUOTES, 'UTF-8');
		$screenshot_full = "{$vars['url']}admin_plugin_screenshot/{$plugin->getID()}/full/{$screenshot['path']}";
		$screenshot_src = "{$vars['url']}admin_plugin_screenshot/{$plugin->getID()}/thumbnail/{$screenshot['path']}";

		$screenshots_html .= "<li class=\"elgg-plugin-screenshot prm ptm\"><a href=\"$screenshot_full\">"
							. "<img src=\"$screenshot_src\" alt=\"$alt\"></a></li>";
	}
}

// metadata
$description = elgg_view('output/longtext', array('value' => $plugin->getManifest()->getDescription()));
$author = '<span>' . elgg_echo('admin:plugins:label:author') . '</span>: '
			. elgg_view('output/text', array('value' => $plugin->getManifest()->getAuthor()));
$version = htmlspecialchars($plugin->getManifest()->getVersion());
$website = elgg_view('output/url', array(
	'href' => $plugin->getManifest()->getWebsite(),
	'text' => $plugin->getManifest()->getWebsite()
));

$copyright = elgg_view('output/text', array('value' => $plugin->getManifest()->getCopyright()));
$license = elgg_view('output/text', array('value' => $plugin->getManifest()->getLicense()));

// show links to text files
$files = $plugin->getAvailableTextFiles();

$docs = '';
if ($files) {
	$docs = '<ul>';
	foreach ($files as $file => $path) {
		$url = 'admin_plugin_text_file/' . $plugin->getID() . "/$file";
		$link = elgg_view('output/url', array(
			'text' => $file,
			'href' => $url
		));
		$docs .= "<li>$link</li>";

	}
	$docs .= '</ul>';
}

?>

<div class="<?php echo $draggable; ?> elgg-plugin <?php echo $active_class ?>" id="elgg-plugin-<?php echo $plugin->guid; ?>">
	<div class="elgg-image-block">
		<div class="elgg-image-alt">
			<?php if ($links) : ?>
			<ul class="elgg-menu elgg-menu-metadata">
				<?php echo $links; ?>
			</ul>
			<?php endif; ?>
			<div class="clearfloat right mtm">
				<?php echo $action_button; ?>
			</div>
		</div>
		<div class="elgg-body">
<?php
$settings_view_old = 'settings/' . $plugin->getID() . '/edit';
$settings_view_new = 'plugins/' . $plugin->getID() . '/settings';
if (elgg_view_exists($settings_view_old) || elgg_view_exists($settings_view_new)) {
	$link = elgg_get_site_url() . "admin/plugin_settings/" . $plugin->getID();
	$settings_link = "<a class='plugin_settings small link' href='$link'>[" . elgg_echo('settings') . "]</a>";
}
?>
			<div class="elgg-head">
				<h3><?php echo $plugin->getManifest()->getName(). " $version $settings_link"; ?></h3>
			</div>
			<?php
			if ($plugin->getManifest()->getApiVersion() < 1.8) {
				$reqs = $plugin->getManifest()->getRequires();
				if (!$reqs) {
					$message = elgg_echo('admin:plugins:warning:elgg_version_unknown');
					echo "<p class=\"elgg-state-error\">$message</p>";
				}
			}
	
			if (!$can_activate) {
				if ($active) {
					$message = elgg_echo('admin:plugins:warning:unmet_dependencies_active');
					echo "<p class=\"elgg-state-warning\">$message</p>";
				} else {
					$message = elgg_echo('admin:plugins:warning:unmet_dependencies');
					echo "<p class=\"elgg-state-error\">$message</p>";
				}
			}
			?>
	
			<div class="plugin_description"><?php echo $description; ?></div>
			<p class="plugin_author"><?php echo $author . ' - ' . $website; ?></p>
			<?php echo $docs; ?>
	
			<div class="pts">
			<?php 
				echo elgg_view('output/url', array(
					'href' => "#elgg-plugin-manifest-{$plugin->getID()}",
					'text' => elgg_echo("admin:plugins:label:moreinfo"),
					'class' => 'elgg-toggler',
				));
			?>
			</div>
		</div>
	</div>
	<div class="hidden manifest_file" id="elgg-plugin-manifest-<?php echo $plugin->getID(); ?>">

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
			echo elgg_view('object/plugin/elements/dependencies', array('plugin' => $plugin));
		?>
		</div>
	</div>
</div>