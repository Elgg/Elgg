<?php
/**
 * Page shell for all Email messages
 *
 * @uses $vars['body']    The main content of the email
 * @uses $vars['subject'] The subject of the email
 * @uses $vars['css']     The CSS to put in the head
 */

$subject = elgg_extract('subject', $vars);
$head = elgg_format_element('meta', [
	'http-equiv' => 'Content-Type',
	'content' => 'text/html; charset=UTF-8',
]);

$head .= elgg_format_element('base', ['target' => '_blank']);

if (!empty($subject)) {
	$head .= elgg_format_element('title', [], $subject);
}

$head .= elgg_format_element('style', [], elgg_extract('css', $vars));

$vars['head'] = $head;

echo elgg_view('page/elements/html', $vars);
