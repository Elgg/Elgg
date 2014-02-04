<?php
/**
 * Discussion reply upgrade page
 */

elgg_load_js('elgg.discussion_upgrade');

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$options = array(
	'annotation_names' => 'group_topic_post',
	'limit' => false,
);

$options['count'] = true;
$count = elgg_get_annotations($options);
unset($options['count']);

$status_string = elgg_echo('discussion:upgrade:replies:status', array($count));

echo "<p>$status_string</p>
<span id=\"reply-upgrade-total\" class=\"hidden\">$count</span>
<span id=\"reply-upgrade-count\" class=\"hidden\">0</span>";

if ($count) {
	if ($count > 1000) {
		$warning_string = elgg_echo('discussion:upgrade:replies:warning');
		echo "<p>$warning_string</p>";
	}

	$success_count_string = elgg_echo('discussion:upgrade:replies:success_count');
	$error_count_string = elgg_echo('discussion:upgrade:replies:error_count');

	echo <<<HTML
		<div class="elgg-progressbar mvl"><span class="elgg-progressbar-counter" id="reply-upgrade-counter">0%</span></div>
		<ul class="mvl">
			<li>$success_count_string <span id="reply-upgrade-success-count">0</span></li>
			<li>$error_count_string <span id="reply-upgrade-error-count">0</span></li>
		</ul>
		<ul class="mvl" id="reply-upgrade-messages"></ul>
HTML;

	echo elgg_view('output/url', array(
		'text' => elgg_echo('upgrade'),
		'href' => 'action/discussion/upgrade/2013100401',
		'class' => 'elgg-button elgg-button-action mtl',
		'is_action' => true,
		'id' => 'reply-upgrade-run',
	));
	echo '<div id="reply-upgrade-spinner" class="elgg-ajax-loader hidden"></div>';
}

access_show_hidden_entities($access_status);