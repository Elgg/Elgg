<?php
/**
 * Elgg dashboard blurb
 *
 */

echo elgg_view('output/longtext', [
	'id' => 'dashboard-info',
	'class' => 'pam mbm elgg-border-plain',
	'value' => elgg_echo('dashboard:nowidgets'),
]);
