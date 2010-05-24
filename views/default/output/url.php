<?php
/**
 * Elgg URL display
 * Displays a URL as a link
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses string $vars['href'] The string to display in the <a></a> tags
 * @uses string $vars['text'] The string between the <a></a> tags.
 * @uses string $vars['target'] Set the target="" attribute.
 * @uses bool $vars['encode_text'] Run $vars['text'] through htmlentities()?
 * @uses string $vars['class'] what to add in class=""
 * @uses string $vars['js'] Javascript to insert in <a> tag
 * @uses bool $vars['is_action'] Is this a link to an action?
 *
 */

$url = trim($vars['href']);
if (!$url and isset($vars['value'])) {
	$url = trim($vars['value']);
}

if (!empty($url)) {
	if (array_key_exists('target', $vars) && $vars['target']) {
		$target = "target = \"{$vars['target']}\"";
	} else {
		$target = '';
	}

	if (array_key_exists('class', $vars) && $vars['class']) {
		$class = "class = \"{$vars['class']}\"";
	} else {
		$class = '';
	}

	if (array_key_exists('js', $vars) && $vars['js']) {
		$js = "{$vars['target']}";
	} else {
		$js = '';
	}

	if (array_key_exists('text', $vars) && $vars['text']) {
		if (isset($vars['encode_text']) && $vars['encode_text']) {
			$text = htmlentities($vars['text'], ENT_QUOTES, 'UTF-8');
		} else {
			$text = $vars['text'];
		}
	} else {
		$text = htmlentities($url, ENT_QUOTES, 'UTF-8');
	}

	if ((substr_count($url, "http://") == 0) && (substr_count($url, "https://") == 0)) {
		$url = "http://" . $url;
	}

	if (array_key_exists('is_action', $vars) && $vars['is_action']) {
		$url = elgg_add_action_tokens_to_url($url);
	}

	echo "<a href=\"{$url}\" $target $class $js>$text</a>";
}