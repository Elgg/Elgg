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

$image_block = new \Elgg\Markup\Block(null, $vars);
$image_block->addClass('elgg-image-block', 'clearfix');

if ($image) {
	$image_block->append(new \Elgg\Markup\Block($image, ['class' => 'elgg-image']));
}

if ($alt_image) {
	$image_block->append(new \Elgg\Markup\Block($alt_image, ['class' => 'elgg-image-alt']));
}

$image_block->append(new \Elgg\Markup\Block($body, ['class' => 'elgg-body']));

echo $image_block;