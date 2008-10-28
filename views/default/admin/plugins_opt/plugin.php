<?php
	/**
	 * Elgg plugin manifest class
	 * 
	 * This file renders a plugin for the admin screen, including active/deactive, manifest details & display plugin
	 * settings.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */


	$plugin = $vars['plugin'];
	$details = $vars['details'];
	
	$active = $details['active'];
	$manifest = $details['manifest'];
	
	$ts = time();
	$token = generate_action_token($ts);
?>
<div class="plugin_details <?php if ($active) echo "active"; else echo "not-active" ?>">
	<div class="admin_plugin_reorder">
		<?php
			if ($vars['order'] > 10) {
?>
			<a href="<?php echo $vars['url']; ?>actions/admin/plugins/reorder?plugin=<?php echo $plugin; ?>&order=1&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("top"); ?></a>
			<a href="<?php echo $vars['url']; ?>actions/admin/plugins/reorder?plugin=<?php echo $plugin; ?>&order=<?php echo $vars['order'] - 11; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("up"); ?></a>
<?php
			}
		?> 
		<?php
			if ($vars['order'] < $vars['maxorder']) {
?>
			<a href="<?php echo $vars['url']; ?>actions/admin/plugins/reorder?plugin=<?php echo $plugin; ?>&order=<?php echo $vars['order'] + 11; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("down"); ?></a>
			<a href="<?php echo $vars['url']; ?>actions/admin/plugins/reorder?plugin=<?php echo $plugin; ?>&order=<?php echo $vars['maxorder'] + 11; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("bottom"); ?></a>
<?php
			}
		?> 
	</div><div class="clearfloat"></div>
	<div class="admin_plugin_enable_disable">
		<?php if ($active) { ?>
			<a href="<?php echo $vars['url']; ?>actions/admin/plugins/disable?plugin=<?php echo $plugin; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("disable"); ?></a>
		<?php } else { ?>
			<a href="<?php echo $vars['url']; ?>actions/admin/plugins/enable?plugin=<?php echo $plugin; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("enable"); ?></a>
		<?php } ?>
	</div>

	<h3><?php echo $plugin; ?></h3>
	<p><a class="manifest_details"><?php echo elgg_echo("admin:plugins:label:moreinfo"); ?></a></p>

	<div class="manifest_file">
	
	<?php if ($manifest) { ?>
		<div><?php echo $manifest['description'] ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:author') . ": ". $manifest['author'] ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:copyright') . ": ". $manifest['copyright'] ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:licence') . ": ". $manifest['licence'] ?></div>
		<div><?php echo elgg_echo('admin:plugins:label:website') . ": "; ?><a href="<?php echo $manifest['website']; ?>"><?php echo $manifest['website']; ?></a></div>
	<?php } ?>
	
	<?php if (elgg_view("settings/{$plugin}/edit")) { ?>
	<div class="pluginsettings">
		<h3><?php echo elgg_echo("settings"); ?></h3>
			<div id="<?php echo $plugin; ?>_settings">
				<?php echo elgg_view("object/plugin", array('plugin' => $plugin, 'entity' => find_plugin_settings($plugin))) ?>
			</div>
	</div>
	<?php } ?>

	</div>
	
</div>