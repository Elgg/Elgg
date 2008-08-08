<?php
	/**
	 * Update client.
	 * 
	 * @package ElggUpdateClient
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	global $DEFAULT_UPDATE_SERVER;
?>

<p>
	<h3><?php echo elgg_echo('updateclient:settings:server'); ?>: </h3>
	<?php
		$server = ($vars['entity']->updateserver ? $vars['entity']->updateserver : $DEFAULT_UPDATE_SERVER);
		echo elgg_view('input/text', array('internalname' => 'params[updateserver]', 'value' => $server));
	?>
</p>
<p>
	<?php echo elgg_echo('updateclient:settings:days'); ?>
	
	<select name="params[days]">
		<option value="7" <?php if ($vars['entity']->days == 7) echo " selected=\"yes\" "; ?>>7</option>
		<option value="14" <?php if ((!$vars['entity']->days) || ($vars['entity']->days == 14)) echo " selected=\"yes\" "; ?>>14</option>
		<option value="21" <?php if ($vars['entity']->days == 21) echo " selected=\"yes\" "; ?>>21</option>
		<option value="28" <?php if ($vars['entity']->days == 28) echo " selected=\"yes\" "; ?>>28</option>
	</select>
	
	<?php echo elgg_echo('updateclient:days'); ?>.
</p>