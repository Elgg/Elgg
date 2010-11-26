<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	// new groups default to open membership
	if (isset($vars['entity'])) {
		$membership = $vars['entity']->membership;
	} else {
		$membership = ACCESS_PUBLIC;
	}
	
?>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/groups/edit" enctype="multipart/form-data" method="post">

	<?php echo elgg_view('input/securitytoken'); ?>

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
			<?php echo elgg_view('input/access', array('internalname' => 'membership','value' => $membership, 'options' => array( ACCESS_PRIVATE => elgg_echo('groups:access:private'), ACCESS_PUBLIC => elgg_echo('groups:access:public')))); ?>
		</label>
	</p>
	
	<?php

	if (get_plugin_setting('hidden_groups', 'groups') == 'yes')
	{
?>

	<p>
		<label>
			<?php echo elgg_echo('groups:visibility'); ?><br />
			<?php 
			
			$this_owner = $vars['entity']->owner_guid;
			if (!$this_owner) {
				$this_owner = get_loggedin_userid();
			}
			
			$access = array(ACCESS_FRIENDS => elgg_echo("access:friends:label"), ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"), ACCESS_PUBLIC => elgg_echo("PUBLIC"));
			$collections = get_user_access_collections($vars['entity']->guid);
			if (is_array($collections)) {
				foreach ($collections as $c)
					$access[$c->id] = $c->name;
			}

			$current_access = ($vars['entity']->access_id ? $vars['entity']->access_id : ACCESS_PUBLIC);
			echo elgg_view('input/access', array('internalname' => 'vis', 
												'value' =>  $current_access,
												'options' => $access));
			
			
			?>
		</label>
	</p>

<?php 	
	}
	
	?>
	
    <?php
		if (isset($vars['config']->group_tool_options)) {
			foreach($vars['config']->group_tool_options as $group_option) {
				$group_option_toggle_name = $group_option->name."_enable";
				if ($group_option->default_on) {
					$group_option_default_value = 'yes';
				} else {
					$group_option_default_value = 'no';
				}
?>	
    <p>
			<label>
				<?php echo $group_option->label; ?><br />
				<?php

					echo elgg_view("input/radio",array(
									"internalname" => $group_option_toggle_name,
									"value" => $vars['entity']->$group_option_toggle_name ? $vars['entity']->$group_option_toggle_name : $group_option_default_value,
									'options' => array(
														elgg_echo('groups:yes') => 'yes',
														elgg_echo('groups:no') => 'no',
													   ),
													));
				?>
			</label>
	</p>
	<?php
		}
	}
	?>
	<p>
		<?php
			if ($vars['entity'])
			{ 
		?>
		<input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
		<?php
			}
		?>
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
		
	</p>

</form>
</div>

<?php
if ($vars['entity']) {
?>
<div class="contentWrapper">
<div id="delete_group_option">
	<form action="<?php echo $vars['url'] . "action/groups/delete"; ?>">
		<?php
			echo elgg_view('input/securitytoken');
				$warning = elgg_echo("groups:deletewarning");
			?>
			<input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
			<input type="submit" name="delete" value="<?php echo elgg_echo('groups:delete'); ?>" onclick="javascript:return confirm('<?php echo $warning; ?>')"/>
	</form>
</div><div class="clearfloat"></div>
</div>
<?php
}
?>