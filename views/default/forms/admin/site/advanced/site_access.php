<?php
/**
 * Advanced site settings, site access section.
 */

// new user registration
$allow_reg_input = elgg_view('input/checkbox', array(
	'label' => elgg_echo('installation:registration:label'),
	'name' => 'allow_registration',
	'checked' => (bool)elgg_get_config('allow_registration'),
));

// walled garden
$walled_garen_input = elgg_view('input/checkbox', array(
	'label' => elgg_echo('installation:walled_garden:label'),
	'name' => 'walled_garden',
	'checked' => (bool)elgg_get_config('walled_garden'),
));

// https login
$https_input = elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:httpslogin:label'),
	'name' => 'https_login',
	'checked' => (bool)elgg_get_config('https_login'),
));

?>

<fieldset class="elgg-fieldset" id="elgg-settings-advanced-site-access">
	<legend><?php echo elgg_echo('admin:legend:site_access'); ?></legend>
	
	<div>
		<?php echo $allow_reg_input; ?>
		<p class="elgg-text-help"><?php echo elgg_echo('installation:registration:description'); ?></p>
	</div>
	
	<div>
		<?php echo $walled_garen_input; ?>
		<p class="elgg-text-help"><?php echo elgg_echo('installation:walled_garden:description'); ?></p>
	</div>
		
		
	<div>
		<?php echo $https_input; ?>
		<p class="elgg-text-help"><?php echo elgg_echo('installation:httpslogin'); ?></p>
	</div>
</fieldset>