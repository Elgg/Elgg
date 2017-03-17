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
	if ($contributor['name']) {
		$contributor['name'] = elgg_view('output/text', [
			'value' => $contributor['name'],
		]);
	} else {
		continue;
	}
	
	if ($contributor['website']) {
		$contributor['website'] = elgg_view('output/url', [
			'href' => $contributor['website'],
			'text' => $contributor['website'],
			'is_trusted' => true,
		]);
	}
	
	if ($contributor['username']) {
		$contributor['username'] = elgg_view('output/url', [
			'href' => "http://community.elgg.org/profile/{$contributor['username']}/",
			'text' => "@{$contributor['username']}",
			'is_trusted' => true,
		]);
	}
	
	if ($contributor['description']) {
		$contributor['description'] = elgg_view('output/text', [
			'value' => $contributor['description'],
		]);
	}
	
	if ($contributor['name']) { // Name is requiried
		echo '<li><dl>';
		foreach ($contributor as $field => $value) {
			if ($value) {
				$dt = elgg_echo("admin:plugins:label:contributors:$field");
				echo "<dt class=\"elgg-plugin-contributor-$field\">$dt</dt>";
				echo "<dd class=\"elgg-plugin-contributor-$field\">$value</dd>";
			}
		}
		echo '</dl></li>';
	}
}

echo '</ul>';
