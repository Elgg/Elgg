<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
?>
<form action="<?php echo $vars['url']; ?>action/groups/edit" enctype="multipart/form-data" method="post">

	<p>
		<label><?php echo elgg_echo("groups:icon"); ?><br />
		<?php

			echo elgg_view("input/file",array('internalname' => 'icon'));
		
		?>
		</label>
	</p>
<?php

	//var_export($vars['profile']);
	if (is_array($vars['config']->group) && sizeof($vars['config']->group) > 0)
		foreach($vars['config']->group as $shortname => $valtype) {
			
?>

	<p>
		<label>
			<?php echo elgg_echo("groups:{$shortname}") ?><br />
			<?php echo elgg_view("input/{$valtype}",array(
															'internalname' => $shortname,
															'value' => $vars['entity']->$shortname,
															)); ?>
		</label>
	</p>

<?php
			
		}

?>

	<p>
		<label>
			<?php echo elgg_echo('groups:membership'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'membership','value' => $vars['entity']->membership, 'options' => array( 0 => elgg_echo('PRIVATE'), 2=>elgg_echo('PUBLIC')))); ?>
		</label>
	</p>
	
	<p>
		<label>
			<?php echo elgg_echo('groups:access'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $vars['entity']->access_id )); ?>
		</label>
	</p>

	<p>
		<?php
			if ($vars['entity'])
			{ 
			?><input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /><?php 
			}
		?>
		<input type="hidden" name="user_guid" value="<?php echo page_owner_entity()->guid; ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>