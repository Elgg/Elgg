<?php
/**
 * An interstitial page for newly created Twitter users.
 *
 * This prompts them to enter an email address and set a password in case Twitter goes down or they
 * want to disassociate their account from twitter.
 */

$title = elgg_echo('twitter_api:interstitial:settings');

$content = elgg_view_form('twitter_api/interstitial_settings');

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
