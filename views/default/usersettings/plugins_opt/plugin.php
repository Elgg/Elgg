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
	
	$user_guid = $details['user_guid'];
	if ($user_guid) $user_guid = $_SESSION['user']->guid;
	
	if (elgg_view("usersettings/{$plugin}/edit")) { 
?>
<div id="user_plugin_details">
	<div><h2><?php echo $plugin; ?></h2></div>
	
	<div id="<?php echo $plugin; ?>_settings">
		<?php echo elgg_view("object/plugin", array('plugin' => $plugin, 'entity' => find_plugin_usersettings($plugin, $user_guid), 'prefix' => 'user')) ?>
	</div>
</div>
<?php } ?>