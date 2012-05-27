<?php
/**
 *  Copyright (C) 2012 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 */

/**
 * Create the settings page for the plugin. Show the settings grouped by categories.
 * 
 * @author andres
 */

/**
 * Answer only those settings that belong to the given section
 * 
 * @param string $sectionName - The name of the section whose settings we want
 * @param associative array $settings - The set of key=>value pairs of all the plugin settings
 */
function settingsForSection($sectionName, $settings)
{
	$result = array();
	
	foreach ($settings as $key => $value)
	{
		$pos = strpos($key, $sectionName);
		if ($pos === 0)
		{
			$result[$key] = $value;
		}
	}
	ksort($result);
	return $result;
}

/**
 * Write the appropriate HTML for a section and its settings
 * 
 * @param string $section - The section name
 * @param associative array $settings - The set of key=>value pairs to show inside the section
 */
function writeSectionForm($section, $settings)
{
	echo '<div class="elgg-module elgg-module-inline">';
	echo '<div class="elgg-head">';
	echo '<h3>' . elgg_echo('phpunit_test:section:' . $section) . '</h3>';
	echo '</div>';
	echo '<div class="elgg-body">';
	foreach ($settings as $key => $value)
	{
		echo elgg_echo('phpunit_test:label:' . $key) . ' ';
		echo elgg_view('input/text', array(
											'name' => 'params['. $key .']',
											'value' => $value)
											);
	}
	echo '</div>';
	echo '</div>';
	
}

?>
<div>
	<?php 
		$settings = $vars['entity']->getAllSettings();
		$sections = array('site', 'admin', 'user');
		
		foreach ($sections as $section)
		{
			$sectionSettings = settingsForSection($section, $settings);
			writeSectionForm($section, $sectionSettings);
		}
	?>
</div>
