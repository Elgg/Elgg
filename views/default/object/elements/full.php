<?php

/**
 * Object full view
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['icon']          HTML for the content icon
 * @uses $vars['summary']       HTML for the content summary
 * @uses $vars['body']          HTML for the content body
 * @uses $vars['class']         Optional additional class for the content wrapper
 * @uses $vars['header_params'] Vars to pass to the header image block wrapper
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	elgg_log("object/elements/full expects an ElggEntity in \$vars['entity']", 'ERROR');
}

$class = elgg_extract_class($vars, ['elgg-listing-full', 'elgg-content', 'clearfix']);
unset($vars['class']);

$header = elgg_view('object/elements/full/header', $vars);
$body = elgg_view('object/elements/full/body', $vars);

echo elgg_format_element('div', [
	'class' => $class,
	'data-guid' => $entity->guid,
		], $header . $body);


