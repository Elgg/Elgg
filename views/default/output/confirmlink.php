<?php
/**
 * Elgg confirmation link
 * A link that displays a confirmation dialog before it executes
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['text']        The text of the link
 * @uses $vars['href']        The address
 * @uses $vars['title']       The title text (defaults to confirm text)
 * @uses $vars['confirm']     The dialog text
 * @uses $vars['encode_text'] Run $vars['text'] through htmlspecialchars() (false)
 */

$vars['rel'] = elgg_extract('confirm', $vars, elgg_echo('question:areyousure'));
$vars['rel'] = addslashes($vars['rel']);
$encode = elgg_extract('encode_text', $vars, false);

// always generate missing action tokens
$vars['href'] = elgg_add_action_tokens_to_url(elgg_normalize_url($vars['href']), true);

$text = elgg_extract('text', $vars, '');
if ($encode) {
	$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false);
}

if (!isset($vars['title']) && isset($vars['confirm'])) {
	$vars['title'] = $vars['rel'];
}

if (isset($vars['class'])) {
	if (!is_array($vars['class'])) {
		$vars['class'] = array($vars['class']);
	}
	$vars['class'][] = 'elgg-requires-confirmation';
} else {
	$vars['class'] = 'elgg-requires-confirmation';
}

unset($vars['encode_text']);
unset($vars['text']);
unset($vars['confirm']);
unset($vars['is_trusted']);

$attributes = elgg_format_attributes($vars);
echo "<a $attributes>$text</a>";
