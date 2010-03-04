<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */
?>
<div id="profile_content">
	<?php
	if(is_plugin_enabled('thewire')) {
		// users last status msg, if they posted one
		echo elgg_view("profile/status", array("entity" => $vars['entity']));
	}
	if(is_plugin_enabled('riverdashboard')) {
		// users last 10 activites
		echo elgg_view('profile/profile_contents/profile_activity', array('entity' => $vars['entity']));
	} else {
		echo "Riverdashboard not loaded";
	}
	?>
</div>
