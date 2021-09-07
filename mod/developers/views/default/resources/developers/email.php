<?php

use Elgg\Email;
use Elgg\Notifications\Notification;

$user = elgg_get_logged_in_user_entity();
$site = elgg_get_site_entity();

$subject = elgg_echo('useradd:subject');
$plain_message = elgg_echo('useradd:body', [
	$site->getDisplayName(),
	$site->getURL(),
	$user->username,
	'test123',
]);
$email = Email::factory([
	'from' => $site,
	'to' => $user,
	'subject' => $subject,
	'body' => $plain_message,
]);

$formatter = elgg()->html_formatter;

$prepared_body = '';

elgg_register_plugin_hook_handler('send', 'notification:email', function (\Elgg\Hook $hook) use (&$prepared_body) {
	$notification = $hook->getParam('notification');
	if ($notification instanceof Notification) {
		$prepared_body = $notification->body;
	}

	return true;
}, 1);

notify_user($user->guid, $site->guid, $subject, $plain_message, [], 'email');

$options = [
	'subject' => $subject,
	'body' => $prepared_body,
	'language' => get_current_language(),
	'email' => $email,
];

$options['body'] = $formatter->formatBlock($options['body']);

// generate HTML mail body
$options['body'] = elgg_view('email/elements/body', $options);

$cssmin = new \CSSmin(false);
$css = $cssmin->run(_elgg_services()->cssCompiler->compile(elgg_view('email/email.css', $options)));

$options['css'] = $css;

$html = elgg_view('email/elements/html', $options);

$result = $formatter->inlineCss($html, $css);
$result = $formatter->normalizeUrls($result);

echo $result;
