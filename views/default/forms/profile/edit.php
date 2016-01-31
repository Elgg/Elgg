<?php
/**
 * Edit profile form
 *
 * @uses vars['entity']
 */

$owner = $vars['entity'];
/* @var ElggUser $owner */
?>

<div>
	<label><?php echo elgg_echo('user:name:label'); ?></label>
	<?php echo elgg_view('input/text', array('name' => 'name', 'value' => $vars['entity']->name)); ?>
</div>
<?php

$sticky_values = elgg_get_sticky_values('profile:edit');

$profile_fields = elgg_get_config('profile_fields');
if (is_array($profile_fields) && count($profile_fields) > 0) {
	foreach ($profile_fields as $shortname => $valtype) {

		$annotations = $owner->getAnnotations([
			'annotation_names' => "profile:$shortname",
			'limit' => false,
		]);
		if ($annotations) {
			$value = '';
			foreach ($annotations as $annotation) {
				if (!empty($value)) {
					$value .= ', ';
				}
				$value .= $annotation->value;
				$access_id = $annotation->access_id;
			}
		} else {
			$value = '';
			$access_id = ACCESS_DEFAULT;
		}

		// sticky form values take precedence over saved ones
		if (isset($sticky_values[$shortname])) {
			$value = $sticky_values[$shortname];
		}
		if (isset($sticky_values['accesslevel'][$shortname])) {
			$access_id = $sticky_values['accesslevel'][$shortname];
		}

?>
<div>
	<label><?php echo elgg_echo("profile:{$shortname}") ?></label>
	<?php
		$params = array(
			'name' => $shortname,
			'value' => $value,
		);
		echo elgg_view("input/{$valtype}", $params);
		$params = array(
			'name' => "accesslevel[$shortname]",
			'value' => $access_id,
		);
		echo elgg_view('input/access', $params);
	?>
</div>
<?php
	}
}

elgg_clear_sticky_form('profile:edit');

?>
<div class="elgg-foot">
<?php
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));
	echo elgg_view('input/submit', array('value' => elgg_echo('save')));
?>
</div>
