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

elgg_deprecated_notice('The view output/confirmlink has been deprecated, please use output/url', '1.10');

if (!isset($vars['confirm'])) {
	$vars['confirm'] = true;
}
echo elgg_view('output/url', $vars);