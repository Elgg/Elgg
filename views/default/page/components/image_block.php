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
 * @uses $vars['class']       Optional additional class (or an array of classes) for the element
 * @uses $vars['id']          Optional id for the element
 * @uses $vars['tag_name']    Optional tag name for the element (default 'div')
 */

$body = elgg_extract('body', $vars, '');
unset($vars['body']);

$image = elgg_extract('image', $vars, '');
unset($vars['image']);

$alt_image = elgg_extract('image_alt', $vars, '');
unset($vars['image_alt']);

$vars['class'] = elgg_extract_class($vars, ['elgg-image-block']);

$content = '';
if ($image) {
	$content .= elgg_format_element('div', ['class' => 'elgg-image'], $image);
}

$content .= elgg_format_element('div', ['class' => 'elgg-body'], $body);

if ($alt_image) {
	$content .= elgg_format_element('div', ['class' => 'elgg-image-alt'], $alt_image);
}

$tag_name = elgg_extract('tag_name', $vars, 'div');
unset($vars['tag_name']);

echo elgg_format_element($tag_name, $vars, $content);
