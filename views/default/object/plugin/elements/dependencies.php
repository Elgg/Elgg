<?php
/**
 * Shows a table of plugin dependecies for ElggPlugin in $vars['plugin'].
 *
 * This uses a table because it's a table of data.
 */

/** @var $plugin ElggPlugin **/
$plugin = elgg_extract('plugin', $vars, false);

// elgg-plugin dependencies
$deps = $plugin->getDependencies();
if (!empty($deps)) {
	$deps_info = '<table class="elgg-table">';
	$deps_info .= '<thead><tr><th>' . elgg_echo('item:object:plugin') . '</th><th>' . elgg_echo('ElggPlugin:Dependencies:MustBeActive') . '</th><th>' . elgg_echo('ElggPlugin:Dependencies:Position') . '</th></tr></thead>';
	$deps_info .= '<tbody>';
	
	foreach ($deps as $plugin_id => $plugin_dep) {
		$plugin_dep_info = elgg_format_element('td', [], $plugin_id);
		$dep_issue = false;
				
		$must_be_active = elgg_extract('must_be_active', $plugin_dep, true);
		$must_be_active_options = [];
		if ($must_be_active && !elgg_is_active_plugin($plugin_id)) {
			$must_be_active_options['class'] = ['elgg-loud'];
			$dep_issue = true;
		}
		
		$plugin_dep_info .= elgg_format_element('td', $must_be_active_options, $must_be_active ? elgg_echo('option:yes') : elgg_echo('option:no'));
		
		$position = elgg_extract('position', $plugin_dep);
		$position_options = [];
		
		$dependent_plugin = elgg_get_plugin_from_id($plugin_id);
		if (!empty($position) && !empty($dependent_plugin)) {
			if ($position == 'after' && ($plugin->getPriority() < $dependent_plugin->getPriority())) {
				$position_options['class'] = ['elgg-loud'];
				$dep_issue = true;
			} elseif ($position == 'before' && ($plugin->getPriority() > $dependent_plugin->getPriority())) {
				$position_options['class'] = ['elgg-loud'];
				$dep_issue = true;
			}
		}
		
		$plugin_dep_info .= elgg_format_element('td', $position_options, $position);

		$deps_options = [];
		if ($dep_issue) {
			$deps_options['class'] = ['elgg-state', 'elgg-state-error'];
		}
		
		$deps_info .= elgg_format_element('tr', $deps_options, $plugin_dep_info);
	}
	
	$deps_info .= '</tbody></table>';
	
	echo $deps_info;
}

try {
	$plugin->assertDependencies();
} catch (Exception $e) {
	echo elgg_view_message('error', $e->getMessage());
}
