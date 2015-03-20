<?php
/**
 * Advanced site settings, content access section.
 */

$default_access_label = elgg_echo('installation:sitepermissions');
$default_access_input = elgg_view('input/access', array(
	'options_values' => array(
		ACCESS_PRIVATE => elgg_echo("PRIVATE"),
		ACCESS_FRIENDS => elgg_echo("access:friends:label"),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC"),
	),
	'name' => 'default_access',
	'value' => elgg_get_config('default_access'),
));

$user_default_access_input = elgg_view('input/checkbox', array(
	'label' => elgg_echo('installation:allow_user_default_access:label'),
	'name' => 'allow_user_default_access',
	'checked' => (bool)elgg_get_config('allow_user_default_access'),
));

?>

<fieldset class="elgg-fieldset" id="elgg-settings-advanced-content-access">
	<legend><?php echo elgg_echo('admin:legend:content_access'); ?></legend>
	
	<div>
		<label>
			<?php 
			echo $default_access_label;
			echo $default_access_input;
			?>
		</label>
		<p class="elgg-text-help"><?php echo elgg_echo('admin:site:access:warning'); ?></p>
	</div>
		
	<div>
		<?php echo $user_default_access_input; ?>
		<p class="elgg-text-help">
			<?php echo elgg_echo('installation:allow_user_default_access:description'); ?>
		</p>
	</div>
	
</fieldset>