<?php
/**
 * Embeddable content list item view
 *
 * @uses $vars['entity'] ElggEntity object
 */

$entity = $vars['entity'];

$image = elgg_view_entity_icon($entity, 'small');

$body = "<h4>" . $entity->title . "</h4>";

$icon = "<img src=\"{$entity->getIconURL('small')}\" />";

$embed_code = elgg_view('output/url', array(
	'href' => $entity->getURL(),
	'title' => $title,
	'text' => $icon,
	'encode_text' => FALSE
));


echo "<div class=\"embed_data\" id=\"embed_{$entity->getGUID()}\">";
echo elgg_view_image_block($image, $body);
echo '</div>';

// @todo JS 1.8: is this approach better than inline js?
echo "<script type=\"text/javascript\">
	$('#embed_{$entity->getGUID()}').data('embed_code', " . json_encode($embed_code) . ");
</script>";
