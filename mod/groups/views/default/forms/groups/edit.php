<?php
/**
 * Group edit form
 * 
 * @package ElggGroups
 */

/* @var ElggGroup $group */
$group = elgg_extract('entity', $vars);

// context needed for input/access view
elgg_push_context('group-edit');

// new groups default to open membership, unrestricted content
if ($group) {
	$membership = $group->membership;
	$access = $group->access_id;
	if ($access != ACCESS_PUBLIC && $access != ACCESS_LOGGED_IN) {
		// group only - this is done to handle access not created when group is created
		$access = ACCESS_PRIVATE;
	}
	$gatekeeper_mode = $group->getGatekeeperMode();
} else {
	$membership = ACCESS_PUBLIC;
	$access = ACCESS_PUBLIC;
	$gatekeeper_mode = ElggGroup::GATEKEEPER_MODE_UNRESTRICTED;
}

?>
<div>
	<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php echo elgg_view("input/file", array('name' => 'icon')); ?>
</div>
<div>
	<label><?php echo elgg_echo("groups:name"); ?></label><br />
	<?php echo elgg_view("input/text", array(
		'name' => 'name',
		'value' => $group->name,
	));
	?>
</div>
<?php

$group_profile_fields = elgg_get_config('group');
if ($group_profile_fields > 0) {
	foreach ($group_profile_fields as $shortname => $valtype) {
		$line_break = '<br />';
		if ($valtype == 'longtext') {
			$line_break = '';
		}
		echo '<div><label>';
		echo elgg_echo("groups:{$shortname}");
		echo "</label>$line_break";
		echo elgg_view("input/{$valtype}", array(
			'name' => $shortname,
			'value' => $group->$shortname,
		));
		echo '</div>';
	}
}
?>

<div>
	<label>
		<?php echo elgg_echo('groups:membership'); ?><br />
		<?php echo elgg_view('input/access', array(
			'name' => 'membership',
			'value' => $membership,
			'options_values' => array(
				ACCESS_PRIVATE => elgg_echo('groups:access:private'),
				ACCESS_PUBLIC => elgg_echo('groups:access:public')
			)
		));
		?>
	</label>
</div>

<div>
	<label>
		<?php echo elgg_echo('groups:gatekeeper_mode'); ?><br />
		<?php echo elgg_view('input/dropdown', array(
		'name' => 'gatekeeper_mode',
		'value' => $gatekeeper_mode,
		'options_values' => array(
			ElggGroup::GATEKEEPER_MODE_UNRESTRICTED => elgg_echo('groups:gatekeeper_mode:unrestricted'),
			ElggGroup::GATEKEEPER_MODE_MEMBERSONLY => elgg_echo('groups:gatekeeper_mode:membersonly'),
		)
	));
		?>
	</label>
</div>
	
<?php

if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
	$this_owner = $group->owner_guid;
	if (!$this_owner) {
		$this_owner = elgg_get_logged_in_user_guid();
	}
	$access_options = array(
		ACCESS_PRIVATE => elgg_echo('groups:access:group'),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC")
	);
?>

<div>
	<label>
			<?php echo elgg_echo('groups:visibility'); ?><br />
			<?php echo elgg_view('input/access', array(
				'name' => 'vis',
				'value' =>  $access,
				'options_values' => $access_options,
			));
			?>
	</label>
</div>

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
		$value = $group->$group_option_toggle_name;
		if (! $value) {
			$value = $group_option_default_value;
		}
?>	
<div>
	<label>
		<?php echo $group_option->label; ?><br />
	</label>
		<?php echo elgg_view("input/radio", array(
			"name" => $group_option_toggle_name,
			"value" => $value,
			'options' => array(
				elgg_echo('groups:yes') => 'yes',
				elgg_echo('groups:no') => 'no',
			),
		));
		?>
</div>
<?php
	}
}
?>
<div class="elgg-foot">
<?php

if ($group) {
	echo elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $group->getGUID(),
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

if ($group) {
	$delete_url = 'action/groups/delete?guid=' . $group->getGUID();
	echo elgg_view('output/confirmlink', array(
		'text' => elgg_echo('groups:delete'),
		'href' => $delete_url,
		'confirm' => elgg_echo('groups:deletewarning'),
		'class' => 'elgg-button elgg-button-delete float-alt',
	));
}

elgg_pop_context();
?>
</div>
