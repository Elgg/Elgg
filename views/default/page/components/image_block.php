<?php
/**
 * Elgg image block pattern
 *
 * Common pattern where there is an image, icon, media object to the left
 * and a descriptive block of text to the right.
 *
 * ---------------------------------------------------------------
 * |          |                                      |    alt    |
 * |  image   |               body                   |   image   |
 * |  block   |               block                  |   block   |
 * |          |                                      | (optional)|
 * ---------------------------------------------------------------
 *
 * @uses $vars['body']        HTML content of the body block
 * @uses $vars['image']       HTML content of the image block
 * @uses $vars['image_alt']   HTML content of the alternate image block
 * @uses $vars['class']       Optional additional class (or an array of classes) for media element
 * @uses $vars['id']          Optional id for the media element
 */

$body = elgg_extract('body', $vars, '');
unset($vars['body']);

$image = elgg_extract('image', $vars, '');
unset($vars['image']);

$alt_image = elgg_extract('image_alt', $vars, '');
unset($vars['image_alt']);

$class = elgg_extract_class($vars, ['elgg-image-block', 'clearfix']);
unset($vars['class']);

$body = elgg_format_element('div', [
	'class' => 'elgg-body',
], $body);

if ($image) {
	$image = elgg_format_element('div', [
		'class' => 'elgg-image',
	], $image);
}

if ($alt_image) {
	$alt_image = elgg_format_element('div', [
		'class' => 'elgg-image-alt',
	], $alt_image);
}

$params = $vars;
$params['class'] = $class;

echo elgg_format_element('div', $params, $image . $alt_image . $body);
