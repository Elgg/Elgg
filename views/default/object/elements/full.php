<?php

/**
 * Object full view
 *
 * @uses $vars['entity']          ElggEntity
 * @uses $vars['icon']            HTML for the content icon
 * @uses $vars['summary']         HTML for the content summary
 * @uses $vars['body']            HTML for the content body
 * @uses $vars['attachments']     HTML for the attachments
 * @uses $vars['responses']       HTML for the responses
 * @uses $vars['show_navigation'] Boolean (default false) to determine if entity navigation should be shown
 * @uses $vars['class']           Optional additional class for the content wrapper
 * @uses $vars['header_params']   Vars to pass to the header image block wrapper
 * @uses $vars['body_params']     Attributes to pass to the body wrapper
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$class = elgg_extract_class($vars, ['elgg-listing-full', 'elgg-content', 'clearfix']);
unset($vars['class']);

$content = elgg_view('object/elements/full/header', $vars);
$content .= elgg_view('object/elements/full/body', $vars);
$content .= elgg_view('object/elements/full/attachments', $vars);
if (elgg_extract('show_navigation', $vars, false)) {
	$content .= elgg_view('object/elements/full/navigation', $vars);
}
$content .= elgg_view('object/elements/full/responses', $vars);

echo elgg_format_element('div', [
	'class' => $class,
	'data-guid' => $entity->guid,
], $content);
