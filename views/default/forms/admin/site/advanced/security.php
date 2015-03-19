<?php
/**
 * Advanced site settings, site section.
 */

$strength = _elgg_get_site_secret_strength();
$current_strength = elgg_echo('site_secret:current_strength');
$strength_text = elgg_echo("site_secret:strength:$strength");
$strength_msg = elgg_echo("site_secret:strength_msg:$strength");


if ($strength != 'strong') {
	$title = "$current_strength: $strength_text";

	$status_msg = elgg_view_module('main', $title, $strength_msg, array(
		'class' => 'elgg-message elgg-state-error'
	));
} else {
	$status_msg = "<p>$strength_msg</p>";
}

$regenerate_input = elgg_view("input/checkboxes", array(
	'options' => array(elgg_echo('admin:site:secret:regenerate') => 1),
	'name' => 'regenerate_site_secret'
));

?>
<fieldset class="elgg-fieldset" id="elgg-settings-advanced-security">
	<legend><?php echo elgg_echo('admin:legend:security'); ?></legend>
	
	<div>
		<p><?php echo elgg_echo('admin:site:secret:intro'); ?></p>
		
		<?php 
		echo $status_msg;
		echo $regenerate_input; 
		?>
		
		<p class="elgg-text-help">
			<?php echo elgg_echo('admin:site:secret:regenerate:help'); ?>
		</p>
	</div>
</fieldset>