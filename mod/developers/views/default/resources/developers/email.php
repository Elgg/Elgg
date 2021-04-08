<?php

use Elgg\Email;

$user = elgg_get_logged_in_user_entity();
$site = elgg_get_site_entity();

$subject = elgg_echo('useradd:subject');
$plain_message = elgg_echo('useradd:body', [
	$user->getDisplayName(),
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

$options = [
	'subject' => $subject,
	'body' => $plain_message,
	'language' => get_current_language(),
	'email' => $email,
];

$options['body'] = $formatter->formatBlock($options['body']);

// generate HTML mail body
$options['body'] = elgg_view('email/elements/body', $options);

$cssmin = new \CSSmin();
$css = $cssmin->run(_elgg_services()->cssCompiler->compile(elgg_view('email/email.css', $options)));

$options['css'] = $css;

$html = elgg_view('email/elements/html', $options);

$result = $formatter->inlineCss($html, $css);
$result = $formatter->normalizeUrls($result);

echo $result;
