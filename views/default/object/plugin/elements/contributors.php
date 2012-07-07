<?php
/**
 * Shows a list of contributors for ElggPlugin in $vars['plugin'].
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

$plugin = elgg_extract('plugin', $vars, false);
$contributors = $plugin->getManifest()->getContributors();

echo '<ul class="elgg-plugin-contributors">';

foreach ($contributors as $contributor) {
	echo "<li title=\"{$contributor['description']}\">";
	echo $contributor['name'];
	
	if ($contributor['email'] || $contributor['username'] || $contributor['website']) {
		echo " - ";
	}
	
	if ($contributor['email']) {
		echo "<a href=\"mailto:{$contributor['email']}\">&lt;{$contributor['email']}&gt;</a>";
	}
	
	if ($contributor['email'] && ($contributor['website'])) {
		echo ",  ";
	}
	
	if ($contributor['website']) {
		echo "<a href=\"{$contributor['website']}\">{$contributor['website']}</a>";
	}
	
	if (($contributor['email'] || $contributor['website']) && $contributor['username']) {
		echo ",  ";
	}
	
	if ($contributor['username']) {
		echo "<a href=\"http://community.elgg.org/profile/{$contributor['username']}\">@{$contributor['username']}</a>";
	}
}

echo '</dl>';
