<?php

	/**
	 * Elgg system settings form
	 * The form to change system settings
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['action'] If set, the place to forward the form to (usually action/systemsettings/save)
	 */

	// Set action appropriately
		if (!isset($vars['action'])) {
			$action = $vars['url'] . "action/systemsettings/save";
		} else {
			$action = $vars['action'];
		}

?>

	<form action="<?php echo $action; ?>" method="post">
		
<?php

		foreach(array('sitename','wwwroot','path','dataroot', 'language') as $field) {

?>
		<p>
			<?php echo elgg_echo($field); ?><br />
			<?php 
				echo elgg_view("input/text",array('internalname' => $field, 'value' => $vars['config']->$field)); 
			?>
		</p>
	
<?php

		}

?>
		<p>
			<input type="hidden" name="settings" value="go" />
			<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
		</p>
	
	</form>