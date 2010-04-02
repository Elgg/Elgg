<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */
 $limit = 20;
?>
<div id="profile_content">
	<?php
	if(is_plugin_enabled('thewire')) {
		// users last status msg, if they posted one
		echo elgg_view("profile/status", array("entity" => $vars['entity']));
	}
	if(is_plugin_enabled('conversations')) {
		// users last status msg, if they posted one
		echo elgg_view("profile/status", array("entity" => $vars['entity']));
	}
	if(is_plugin_enabled('riverdashboard')) {
		// users last 10 activites
		echo elgg_view_river_items($vars['entity']->getGuid(), 0, '', '', '', '', $limit,0,0,false,false);
	} else {
		echo "Riverdashboard not loaded";
	}
	?>
</div>
