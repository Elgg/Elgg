<?php
	/**
	 * Elgg plugin
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$entity = $vars['entity'];
	$plugin = $vars['plugin'];
	
?>
<div>
	<form action="<?php echo $vars['url']; ?>action/plugins/settings/save" method="post">
	
		<?php 
			echo elgg_view("settings/{$plugin}/edit",$vars);
		?>
	
		<p>
			<input type="hidden" name="plugin" value="<?php echo $plugin ?>" />
			<input type="submit" value="<?php
	
				echo elgg_echo('save');			
			
			?>" />
		</p>
	</form>
</div>