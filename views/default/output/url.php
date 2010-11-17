<?php
/**
 * Elgg URL display
 * Displays a URL as a link
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['href'] The URL.
 * @uses string $vars['text'] The string between the <a></a> tags.
 * @uses string $vars['target'] Set the target="" attribute.
 * @uses bool $vars['encode_text'] Run $vars['text'] through htmlentities()?
 * @uses string $vars['class'] what to add in class=""
 * @uses string $vars['js'] Javascript to insert in <a> tag
 * @uses string $vars['title'] Title attribute to <a> tag
 * @uses bool $vars['is_action'] Is this a link to an action?
 *
 */

$url = trim($vars['href']);
if (!$url and isset($vars['value'])) {
	$url = trim($vars['value']);
}

if (!empty($url)) {
	if (isset($vars['target'])) {
		$target = "target = \"{$vars['target']}\"";
	} else {
		$target = '';
	}

	if (isset($vars['class'])) {
		$class = "class = \"{$vars['class']}\"";
	} else {
		$class = '';
	}

	if (isset($vars['internalid'])) {
		$id = "id = \"{$vars['internalid']}\"";
	} else {
		$id = '';
	}

	if (isset($vars['js'])) {
		$js = "{$vars['js']}";
	} else {
		$js = '';
	}

	if (isset($vars['text'])) {
		if (isset($vars['encode_text']) && $vars['encode_text']) {
			$text = htmlentities($vars['text'], ENT_QUOTES, 'UTF-8');
		} else {
			$text = $vars['text'];
		}
	} else {
		$text = htmlentities($url, ENT_QUOTES, 'UTF-8');
	}

	$url = elgg_normalize_url($url);

	if (isset($vars['is_action'])) {
		$url = elgg_add_action_tokens_to_url($url);
	}

	if (isset($vars['title'])) {
		$title = 'title="' . htmlentities($vars['title']) . '"';
	} else {
		$title = '';
	}

	echo "<a href=\"{$url}\" $target $class $id $js $title>$text</a>";
}