<?php
/**
 * Elgg dashboard blurb
 *
 */

echo elgg_view('output/longtext', [
	'id' => 'dashboard-info',
	'class' => 'elgg-inner pam mhs mtn',
	'value' => elgg_echo('dashboard:nowidgets'),
]);
