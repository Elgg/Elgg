<?php
/**
 * This view is used to create the plaintext version of the email message content.
 * It must output plaintext without any (html)markup
 *
 * @uses $vars['body'] the contents of the email message
 */

$body = elgg_extract('body', $vars);

$body = elgg_strip_tags($body);
$body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
$body = wordwrap($body);

echo $body;
