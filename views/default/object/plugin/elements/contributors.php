<?php
/**
 * Shows a list of contributors for ElggPlugin in $vars['plugin'].
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

$plugin = elgg_extract('plugin', $vars, false);
$contributors = $plugin->getManifest()->getContributors();

if (empty($contributors)) {
	return;
}

echo '<ul class="elgg-plugin-contributors">';

foreach ($contributors as $contributor) {
	echo '<li>';
	echo '<span class="elgg-plugin-contributor-name" title="' . $contributor['description'] . '">';
	echo elgg_view('output/text', array(
		'value' => $contributor['name'],
	));
	echo "</span>";
	
	if ($contributor['email'] || $contributor['username'] || $contributor['website']) {
		echo " - ";
	}
	
	if ($contributor['email']) {
		echo "&lt;".elgg_view('output/email', array('value' => $contributor['email']))."&gt;";
	}
	
	if ($contributor['email'] && ($contributor['website'])) {
		echo ",  ";
	}
	
	if ($contributor['website']) {
		echo elgg_view('output/url', array(
			'href' => $contributor['website'],
			'text' => $contributor['website'],
			'is_trusted' => true,
		));
	}
	
	if (($contributor['email'] || $contributor['website']) && $contributor['username']) {
		echo ",  ";
	}
	
	if ($contributor['username']) {
		echo elgg_view('output/url', array(
			'href' => "http://community.elgg.org/profile/{$contributor['username']}/",
			'text' => "@{$contributor['username']}",
			'is_trusted' => true,
		));
	}
	echo '</li>';
}

echo '</ul>';
