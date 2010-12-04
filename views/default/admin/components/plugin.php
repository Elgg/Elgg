<?php
/**
 * Elgg plugin manifest class
 *
 * This file renders a plugin for the admin screen, including active/deactive, manifest details & display plugin
 * settings.
 *
 * @package Elgg
 * @subpackage Core
 */

$plugin = $vars['plugin'];
$details = $vars['details'];

$active = $details['active'];
$manifest = $details['manifest'];

$plugin_pretty_name = (isset($manifest['name'])) ? $manifest['name'] : $plugin;

// Check elgg version if available
$version_check_valid = false;
if ($manifest['elgg_version']) {
	$version_check_valid = check_plugin_compatibility($manifest['elgg_version']);
}

$ts = time();
$token = generate_action_token($ts);
$active_class = ($active) ? 'active' : 'not_active';

$top_url = $up_url = $down_url = $bottom_url = '';
if ($vars['order'] > 10) {
	$top_url = elgg_get_site_url()."action/admin/plugins/reorder?plugin={$plugin}&order=1&__elgg_token=$token&__elgg_ts=$ts";
	$top_link = '<a href="' . elgg_format_url($top_url) . '">' . elgg_echo('top') . '</a>';

	$order = $vars['order'] - 11;

	$up_url = elgg_get_site_url()."action/admin/plugins/reorder?plugin={$plugin}&order=$order&__elgg_token=$token&__elgg_ts=$ts";
	$up_link = '<a href="' . elgg_format_url($up_url) . '">' . elgg_echo('up') . '</a>';
}

if ($vars['order'] < $vars['maxorder']) {
	$order =  $vars['order'] + 11;
	$down_url = elgg_get_site_url()."action/admin/plugins/reorder?plugin={$plugin}&order=$order&__elgg_token=$token&__elgg_ts=$ts";
	$down_link = '<a href="' . elgg_format_url($down_url) . '">' . elgg_echo('down') . '</a>';

	$order = $vars['maxorder'] + 11;
	$bottom_url = elgg_get_site_url()."action/admin/plugins/reorder?plugin={$plugin}&order=$order&__elgg_token=$token&__elgg_ts=$ts";
	$bottom_link = '<a href="' . elgg_format_url($bottom_url) . '">' . elgg_echo('bottom') . '</a>';
}

if ($active) {
	$url = elgg_get_site_url()."action/admin/plugins/disable?plugin=$plugin&__elgg_token=$token&__elgg_ts=$ts";
	$enable_disable = '<a class="cancel-button" href="' . elgg_format_url($url) . '">' . elgg_echo('disable') . '</a>';
} else {
	$url = elgg_get_site_url()."action/admin/plugins/enable?plugin=$plugin&__elgg_token=$token&__elgg_ts=$ts";
	$enable_disable = '<a class="submit-button" href="' . elgg_format_url($url) . '">' . elgg_echo('enable') . '</a>';
}


$categories_list = '';
if ($manifest['category']) {
	$categories_arr = array();
	$base_url = elgg_get_site_url()."pg/admin/plugins?category=";

	foreach($manifest['category'] as $category) {
		$url = $base_url . urlencode($category);
		$categories_arr[] = "<a href=\"$url\">" . htmlspecialchars($category) . '</a>';
	}

	$categories_list = implode(', ', $categories_arr);
}

$screenshots = '';
if ($manifest['screenshot']) {
	$base_url = elgg_get_site_url()."mod/";

	$limit = 4;
	foreach ($manifest['screenshot'] as $screenshot) {
		if ($limit <= 0) {
			break;
		}

		$screenshot_src = $base_url . $plugin . "/$screenshot";
		$screenshots .= "<li class=\"plugin_screenshot\"><a href=\"$screenshot_src\"><img src=\"$screenshot_src\"></a></li>";

		$limit--;
	}
}

?>

<div class="plugin_details <?php echo $active_class ?>">
	<div class="admin_plugin_reorder">
	<?php echo "$top_link $up_link $down_link $bottom_link"; ?>
	</div><div class="clearfloat"></div>

	<div class="admin_plugin_enable_disable"><?php echo $enable_disable; ?></div>

	<?php
	if (elgg_view_exists("settings/{$plugin}/edit")) {
		$link = elgg_get_site_url()."pg/admin/plugin_settings/$plugin";
		$settings_link = "<a class='plugin_settings small link' href='$link'>[". elgg_echo('settings') ."]</a>";
	}
	?>
	<h3><?php echo "$plugin_pretty_name $settings_link"; ?></h3>
	<?php
	echo $settings_panel;

	if ($manifest) {
		?>
		<div class="plugin_description"><?php echo elgg_view('output/longtext',array('value' => $manifest['description'])); ?></div>
		<p class="plugin_author"><span><?php echo elgg_echo('admin:plugins:label:author') . "</span>: ". htmlspecialchars($manifest['author']) ?></p>
		<p class="plugin_version"><span><?php echo elgg_echo('admin:plugins:label:version') . "</span>: ". htmlspecialchars($manifest['version']) ?></p>

		<p><a class="manifest_details small link"><?php echo elgg_echo("admin:plugins:label:moreinfo"); ?></a></p>

		<div class="manifest_file hidden">

		<?php
		if ((!$version_check_valid) || (!isset($manifest['elgg_version']))) {
			?>
			<div id="version_check">
				<?php
					if (!isset($manifest['elgg_version'])) {
						echo elgg_echo('admin:plugins:warning:elggversionunknown');
					} else {
						echo elgg_echo('admin:plugins:warning:elggtoolow');
					}
				?>
			</div>
			<?php
		}

		?>
		<div><?php echo elgg_echo('admin:plugins:label:directory') . ": ". htmlspecialchars($plugin) ?></div>
		<?php
		if ($categories_list) {
			?>
			<div><?php echo elgg_echo('admin:plugins:label:categories') . ": ". $categories_list ?></div>
			<?php
		}
		if ($screenshots) {
			?>
			<div><ul><?php echo $screenshots; ?></ul></div>
			<?php
		}
		?>
		<div><?php echo elgg_echo('admin:plugins:label:copyright') . ": ". htmlspecialchars($manifest['copyright']) ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:licence') . ": ". htmlspecialchars($manifest['licence'] . $manifest['license']) ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:website') . ": "; ?><a href="<?php echo $manifest['website']; ?>"><?php echo $manifest['website']; ?></a></div>
	<?php } ?>
	</div>
</div>