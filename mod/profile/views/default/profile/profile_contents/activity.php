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
		//select the correct river
		if (get_plugin_setting('activitytype', 'riverdashboard') == 'classic') {
			echo elgg_view_river_items($vars['entity']->getGuid(), 0, '', '', '', '', $limit,0,0,false,true);
		} else {
			echo elgg_view_river_items($vars['entity']->getGuid(), 0, '', '', '', '', $limit,0,0,false,false);
			echo elgg_view('riverdashboard/js');
		}
	} else {
		echo "Riverdashboard not loaded";
	}
	?>
</div>
