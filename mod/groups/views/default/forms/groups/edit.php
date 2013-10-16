<?php
/**
 * Group edit form
 *
 * @package ElggGroups
 */

// only extract these elements.
$name = $membership = $vis = $entity = null;
extract($vars, EXTR_IF_EXISTS);

/* @var ElggGroup $entity */

if (isset($vars['entity'])) {
	$entity = $vars['entity'];
	$owner_guid = $vars['entity']->owner_guid;
	$content_access_mode = $entity->getContentAccessMode();
} else {
	$entity = false;
	$content_access_mode = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
}

// context needed for input/access view
elgg_push_context('group-edit');

?>
<div>
	<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php echo elgg_view("input/file", array('name' => 'icon')); ?>
</div>
<div>
	<label><?php echo elgg_echo("groups:name"); ?></label><br />
	<?php echo elgg_view("input/text", array(
		'name' => 'name',
		'value' => $name,
	));
	?>
</div>
<?php

$group_profile_fields = elgg_get_config('group');
foreach ((array)$group_profile_fields as $shortname => $valtype) {
	if ($valtype == 'hidden') {
		echo elgg_view("input/{$valtype}", array(
			'name' => $shortname,
			'value' => elgg_extract($shortname, $vars),
		));
		continue;
	}

	$line_break = ($valtype == 'longtext') ? '' : '<br />';
	$label = elgg_echo("groups:{$shortname}");
	$input = elgg_view("input/{$valtype}", array(
		'name' => $shortname,
		'value' => elgg_extract($shortname, $vars),
	));

	echo "<div><label>{$label}</label>{$line_break}{$input}</div>";
}
?>

<div>
	<label>
		<?php echo elgg_echo('groups:membership'); ?><br />
		<?php echo elgg_view('input/select', array(
			'name' => 'membership',
			'value' => $membership,
			'options_values' => array(
				ACCESS_PRIVATE => elgg_echo('groups:access:private'),
				ACCESS_PUBLIC => elgg_echo('groups:access:public'),
			)
		));
		?>
	</label>
</div>

<div>
	<label>
		<?php echo elgg_echo('groups:content_access_mode'); ?><br />
		<?php echo elgg_view('input/select', array(
			'name' => 'content_access_mode',
			'value' => $content_access_mode,
			'options_values' => array(
				ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED => elgg_echo('groups:content_access_mode:unrestricted'),
				ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY => elgg_echo('groups:content_access_mode:membersonly'),
			),
		));
		?>
	</label>
</div>
	
<?php

if (elgg_get_plugin_setting('hidden_groups', 'groups') == 'yes') {
	$access_options = array(
		ACCESS_PRIVATE => elgg_echo('groups:access:group'),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC"),
	);
?>

<div>
	<label>
			<?php echo elgg_echo('groups:visibility'); ?><br />
			<?php echo elgg_view('input/access', array(
				'name' => 'vis',
				'value' =>  $vis,
				'options_values' => $access_options,
			));
			?>
	</label>
</div>

<?php
}

if ($entity && ($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in())) {
	$members = array();

	$options = array(
		'relationship' => 'member',
		'relationship_guid' => $vars['entity']->getGUID(),
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => 0,
	);

	$batch = new ElggBatch('elgg_get_entities_from_relationship', $options);
	foreach ($batch as $member) {
		$option_text = "$member->name (@$member->username)";
		$members[$member->guid] = htmlspecialchars($option_text, ENT_QUOTES, 'UTF-8', false);
	}
?>

<div>
	<label>
			<?php echo elgg_echo('groups:owner'); ?><br />
			<?php echo elgg_view('input/select', array(
				'name' => 'owner_guid',
				'value' =>  $owner_guid,
				'options_values' => $members,
				'class' => 'groups-owner-input',
			));
			?>
	</label>
	<?php
	if ($owner_guid == elgg_get_logged_in_user_guid()) {
		echo '<span class="elgg-text-help">' . elgg_echo('groups:owner:warning') . '</span>';
	}
	?>
</div>

<?php
}

$tools = elgg_get_config('group_tool_options');
if ($tools) {
	usort($tools, create_function('$a,$b', 'return strcmp($a->label,$b->label);'));
	foreach ($tools as $group_option) {
		$group_option_toggle_name = $group_option->name . "_enable";
		$value = elgg_extract($group_option_toggle_name, $vars);
?>
<div>
	<label>
		<?php echo $group_option->label; ?><br />
	</label>
		<?php echo elgg_view("input/radio", array(
			"name" => $group_option_toggle_name,
			"value" => $value,
			'options' => array(
				elgg_echo('option:yes') => 'yes',
				elgg_echo('option:no') => 'no',
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

if ($entity) {
	echo elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $entity->getGUID(),
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

if ($entity) {
	$delete_url = 'action/groups/delete?guid=' . $entity->getGUID();
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
