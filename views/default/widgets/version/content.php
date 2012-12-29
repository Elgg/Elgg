<?php
echo '<p>'.elgg_echo('admin:widget:version:current', array(ElggVersion::getVersion(true))).'</p>';

try { 
	$override = get_input('refresh', false);
	$localRelease = '1.8.9';//ElggVersion::getVersion(true);
	$latestRelease = ElggVersion::getLatestRelease($override);
	$isLatest = ElggVersion::isLatestRelease($localRelease);
	
	if (!$isLatest) {
		echo '<p>'.elgg_echo('admin:widget:version:newer_found', array(ElggVersion::getLatestRelease())).'</p>';
	} else {
		echo '<p>'.elgg_echo('admin:widget:version:newest').'</p>';
	}
	
	$data = array(
		'local_release' => $localRelease,
		'latest_release' => $latestRelease, 
		'status' => '',
	);
	echo '<table class="elgg-table-alt elgg-version"><tr>';
	
	foreach (array_keys($data) as $column) {
		$column = elgg_echo("admin:version:$column");
		echo "<th class=\"pas\">$column</th>";
	}
	echo '</tr><tr>';
	
	if (ElggVersion::isLatestRelease($data['local_value'])) {
		$class = "elgg-state-success elgg-version";
	} else {
		$class = "elgg-state-error elgg-version";
	}
	
	foreach ($data as $column => $value) {
		echo "<td class=\"$class\">$value</td>";
	}
	
	echo '</tr></table>';

	echo '<p>';
	
	$time = datalist_get('version_last_checked');
	echo elgg_echo('admin:widget:version:last_checked', array(elgg_view_friendly_time($time)));
	
	echo '</p>';
	
	echo elgg_view('output/url', array(
		'href' => '?refresh=1',
		'class' => 'elgg-button elgg-button-action elgg-widget-refresh',
		'text' => elgg_echo('admin:widget:version:check'),
	));

} catch(IOException $e) {
	echo $e->getMessage();
}
