<?php
/**
 * Group edit form
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
<p>
	<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php echo elgg_view("input/file", array('internalname' => 'icon')); ?>
</p>
<p>
	<label><?php echo elgg_echo("groups:name"); ?></label><br />
	<?php echo elgg_view("input/text", array(
		'internalname' => 'name',
		'value' => $vars['entity']->name,
	));
	?>
</p>
<?php

$group_profile_fields = elgg_get_config('group');
if ($group_profile_fields > 0) {
	foreach ($group_profile_fields as $shortname => $valtype) {
		$line_break = '<br />';
		if ($valtype == 'longtext') {
			$line_break = '';
		}
		echo '<p><label>';
		echo elgg_echo("groups:{$shortname}");
		echo "</label>$line_break";
		echo elgg_view("input/{$valtype}", array(
			'internalname' => $shortname,
			'value' => $vars['entity']->$shortname,
		));
		echo '</p>';
	}
}
?>

<p>
	<label>
		<?php echo elgg_echo('groups:membership'); ?><br />
		<?php echo elgg_view('input/access', array(
			'internalname' => 'membership',
			'value' => $membership,
			'options' => array(
				ACCESS_PRIVATE => elgg_echo('groups:access:private'),
				ACCESS_PUBLIC => elgg_echo('groups:access:public')
			)
		));
		?>
	</label>
</p>
	
<?php

if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
	$this_owner = $vars['entity']->owner_guid;
	if (!$this_owner) {
		$this_owner = elgg_get_logged_in_user_guid();
	}
	$access = array(
		ACCESS_FRIENDS => elgg_echo("access:friends:label"),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC")
	);
	$collections = get_user_access_collections($vars['entity']->guid);
	if (is_array($collections)) {
		foreach ($collections as $c) {
			$access[$c->id] = $c->name;
		}
	}

	$current_access = $vars['entity']->access_id ? $vars['entity']->access_id : ACCESS_PUBLIC;
?>

<p>
	<label>
			<?php echo elgg_echo('groups:visibility'); ?><br />
			<?php echo elgg_view('input/access', array(
				'internalname' => 'vis',
				'value' =>  $current_access,
				'options' => $access,
			));
			?>
	</label>
</p>

<?php 	
}

$tools = elgg_get_config('group_tool_options');
if ($tools) {
	usort($tools, create_function('$a,$b', 'return strcmp($a->label,$b->label);'));
	foreach ($tools as $group_option) {
		$group_option_toggle_name = $group_option->name . "_enable";
		if ($group_option->default_on) {
			$group_option_default_value = 'yes';
		} else {
			$group_option_default_value = 'no';
		}
		$value = $vars['entity']->$group_option_toggle_name ? $vars['entity']->$group_option_toggle_name : $group_option_default_value;
?>	
<p>
	<label>
		<?php echo $group_option->label; ?><br />
		<?php echo elgg_view("input/radio", array(
			"internalname" => $group_option_toggle_name,
			"value" => $value,
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
<p class="elgg-hrt">
<?php

if (isset($vars['entity'])) {
	echo elgg_view('input/hidden', array(
		'internalname' => 'group_guid',
		'value' => $vars['entity']->getGUID(),
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));
?>
</p>
