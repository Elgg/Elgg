<?php
try { 
	$override = get_input('refresh', false);
	$localRelease = ElggVersion::getVersion(true);
	$latestRelease = ElggVersion::getLatestRelease($override);
	$isLatest = ElggVersion::isLatestRelease($localRelease);
	$time = ElggVersion::getLatestReleaseLastChecked();
	
	if (!$isLatest) {
		echo '<p>';
		echo elgg_echo('admin:widget:version:newer_found');
		echo elgg_view('output/url', array(
			'href' => 'http://elgg.org/getelgg.php?forward=elgg-'.$latestRelease.'.zip',
			'class' => 'elgg-button elgg-button-action',
			'text' => elgg_echo('admin:version:download', array($latestRelease)),
		));
		echo '</p>';
		
		$class = "elgg-state-error elgg-version";
	} else {
		$class = "elgg-state-success elgg-version";
	}
	
	$data = array(
		'local_release' => $localRelease,
		'latest_release' => $latestRelease, 
		'last_checked' => elgg_view_friendly_time($time),
		'status' => elgg_echo('admin:widget:version:status:'.($isLatest?'ok':'bad')),
	);
	
	echo '<table class="elgg-table-alt elgg-version">';
	foreach ($data as $column => $value) {
		$column = elgg_echo("admin:version:$column");
		echo "<tr><td><strong>$column</strong></td><td class=\"$class\">$value</td></tr>";
	}
	echo '</table>';
	
	echo '<br/>';
	
	echo elgg_view('output/url', array(
		'href' => '?refresh=1',
		'class' => 'elgg-button elgg-button-action elgg-widget-refresh',
		'text' => elgg_echo('admin:widget:version:check'),
	));
	
} catch(IOException $e) {
	echo $e->getMessage();
}
