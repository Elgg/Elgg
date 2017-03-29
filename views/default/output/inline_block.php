<?php

/**
 * Elgg inline block display
 *
 * @uses string $vars['text']        The string between the <span></span> tags
 * @uses string $vars['tag']         Alternative tag to use (default: span)
 * @uses bool   $vars['encode_text'] Run $vars['text'] through htmlspecialchars() (false)
 * @uses string $vars['icon']        Name of the Elgg icon, or icon HTML, appended before the text label
 * @uses string $vars['badge']       HTML content of the badge appended after the text label
 * @uses string $vars['dropdown']    Contents of the dropdown this anchor toggles
 */
$tag = elgg_extract('tag', $vars, 'span', false);
unset($vars['tag']);

if (isset($vars['text'])) {
	if (elgg_extract('encode_text', $vars, false)) {
		$text = htmlspecialchars($vars['text'], ENT_QUOTES, 'UTF-8', false);
	} else {
		$text = $vars['text'];
	}
	unset($vars['text']);
}
unset($vars['encode_text']);

$vars['class'] = elgg_extract_class($vars, 'elgg-inline-block');

$dropdown = elgg_extract('dropdown', $vars);
unset($vars['dropdown']);

if ($dropdown) {
	$vars['class'][] = 'dropdown-toggle';
	$vars['data-toggle'] = 'dropdown';
	$vars['aria-haspopup'] = true;
	$vars['aria-expanded'] = false;
	$dropdown_class = (array) elgg_extract('dropdown_class', $vars, []);
	unset($dropdown_class);
	$dropdown_class[] = 'dropdown-menu';
	$dropdown = elgg_format_element('div', [
		'class' => $dropdown_class,
		'aria-labelledby' => elgg_extract('id', $vars),
			], $dropdown);
}

if ($text) {
	$text = elgg_format_element('span', [
		'class' => 'elgg-inline-block-label',
			], $text);
}

$icon = elgg_extract('icon', $vars, '');
unset($vars['icon']);

if ($icon && !preg_match('/^</', $icon)) {
	$icon = elgg_view_icon($icon, [
		'class' => 'elgg-inline-block-icon',
	]);
}

$badge = elgg_extract('badge', $vars);
unset($vars['badge']);

if (!is_null($badge)) {
	$badge = elgg_format_element([
		'#tag_name' => 'span',
		'#text' => $badge,
		'class' => 'elgg-badge badge badge-default',
	]);
}

echo elgg_format_element($tag, $vars, $icon . $text . $badge);
echo $dropdown;
