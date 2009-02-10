<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
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
			<?php echo elgg_view('input/access', array('internalname' => 'membership','value' => $vars['entity']->membership, 'options' => array( ACCESS_PRIVATE => elgg_echo('PRIVATE'), ACCESS_PUBLIC => elgg_echo('PUBLIC')))); ?>
		</label>
	</p>
	
	<p>
		<label>
			<?php echo elgg_echo('groups:access'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $vars['entity']->access_id )); ?>
		</label>
	</p>
    <p>
			<label>
				<?php echo elgg_echo('groups:enablepages'); ?><br />
				<?php

					echo elgg_view("input/radio",array(
									"internalname" => "pages_enable",
									"value" => $vars['entity']->pages_enable ? $vars['entity']->pages_enable : 'yes',
									'options' => array(
														elgg_echo('groups:yes') => 'yes',
														elgg_echo('groups:no') => 'no',
													   ),
													));
				?>
			</label>
	</p>
	<p>
			<label>
				<?php echo elgg_echo('groups:enableforum'); ?><br />
				<?php

					echo elgg_view("input/radio",array(
									"internalname" => "forum_enable",
									"value" => $vars['entity']->forum_enable ? $vars['entity']->forum_enable : 'yes',
									'options' => array(
														elgg_echo('groups:yes') => 'yes',
														elgg_echo('groups:no') => 'no',
													   ),
													));
				?>
			</label>
	</p>
	<p>
			<label>
				<?php echo elgg_echo('groups:enablefiles'); ?><br />
				<?php

					echo elgg_view("input/radio",array(
									"internalname" => "files_enable",
									"value" => $vars['entity']->files_enable ? $vars['entity']->files_enable : 'yes',
									'options' => array(
														elgg_echo('groups:yes') => 'yes',
														elgg_echo('groups:no') => 'no',
													   ),
													));
				?>
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

<div id="delete_group_option">
	<form action="<?php echo $vars['url'] . "action/groups/delete"; ?>">
		<?php
			if ($vars['entity'])
			{ 
			?>
			<input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
			<input type="submit" name="delete" value="<?php echo elgg_echo('groups:delete'); ?>" /><?php 
			}
		?>
	</form>
</div>