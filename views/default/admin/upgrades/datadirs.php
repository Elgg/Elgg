<?php
/**
 * Data dirs upgrade page
 */

elgg_load_js('elgg.upgrades');

$helper = new Elgg_Upgrades_Helper2013022000(
	elgg_get_site_entity()->guid,
	elgg_get_config('dbprefix')
);

$helper->forgetFailures();
$count = $helper->countUnmigratedUsers();

$status_string = elgg_echo('upgrade:datadirs:status', array($count));

echo "<p>$status_string</p>
<span id=\"datadirs-upgrade-total\" class=\"hidden\">$count</span>
<span id=\"datadirs-upgrade-count\" class=\"hidden\">0</span>";

if ($count) {
	if ($count > 1000) {
		$warning_string = elgg_echo('upgrade:datadirs:warning');
		echo "<p>$warning_string</p>";
	}

	$success_count_string = elgg_echo('upgrade:datadirs:success_count');
	$error_count_string = elgg_echo('upgrade:datadirs:error_count');

	echo <<<HTML
		<div class="elgg-progressbar mvl"><span class="elgg-progressbar-counter" id="datadirs-upgrade-counter">0%</span></div>
		<ul class="mvl">
			<li>$success_count_string <span id="datadirs-upgrade-success-count">0</span></li>
			<li>$error_count_string <span id="datadirs-upgrade-error-count">0</span></li>
		</ul>
		<ul class="mvl" id="datadirs-upgrade-messages"></ul>
HTML;

	echo elgg_view('output/url', array(
		'text' => elgg_echo('upgrade'),
		'href' => 'admin/upgrades/upgrade_datadirs',
		'class' => 'elgg-button elgg-button-action mtl',
		'is_action' => true,
		'id' => 'datadirs-upgrade-run',
	));
	echo '<div id="datadirs-upgrade-spinner" class="elgg-ajax-loader hidden"></div>';
}
