<?php
/**
 * Elgg twitter widget
 * This plugin allows users to pull in their twitter feed to display on their profile
 *
 * @package ElggTwitter
 */

elgg_register_event_handler('init', 'system', 'twitter_init');

function twitter_init() {
	elgg_extend_view('css/elgg', 'twitter/css');
	elgg_register_widget_type('twitter', elgg_echo('twitter:title'), elgg_echo('twitter:info'));
}
