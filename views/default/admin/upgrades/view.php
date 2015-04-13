<?php
/**
 * Views the progress bar, statistics and run button for an upgrade
 */

$count = elgg_extract('count', $vars);
$class_name = elgg_extract('class_name', $vars);

if (!$count) {
	echo elgg_echo('upgrade:finished');
	return;
}

elgg_load_js('elgg.upgrades');

$warning_string = '';
if ($count > 1000) {
	$warning_string = elgg_echo('upgrade:warning');
}

$status_string = elgg_echo('upgrade:item_count', array($count));

$success_count_string = elgg_echo('upgrade:success_count');
$error_count_string = elgg_echo('upgrade:error_count');

$action_link = elgg_view('output/url', array(
	'text' => elgg_echo('upgrade'),
	'href' => 'admin/upgrade',
	'class' => 'elgg-button elgg-button-action mtl',
	'is_action' => true,
	'id' => 'upgrade-run',
));

echo <<<HTML
	<p>$warning_string</p>
	<p>$status_string</p>
	<span id="upgrade" data-class-name="$class_name"></span>
	<span id="upgrade-total" class="hidden">$count</span>
	<span id="upgrade-count" class="hidden">0</span>
	<div class="elgg-progressbar mvl"><span class="elgg-progressbar-counter" id="upgrade-counter">0%</span></div>
	<ul class="mvl">
		<li>$success_count_string <span id="upgrade-success-count">0</span></li>
		<li>$error_count_string <span id="upgrade-error-count">0</span></li>
	</ul>
	<div id="upgrade-spinner" class="elgg-ajax-loader hidden"></div>
	<ul class="mvl" id="upgrade-messages"></ul>
	$action_link
HTML;
