<?php
/**
 * User summary
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity metadata and actions (optional)
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['tags']      HTML for the tags (optional)
 * @uses $vars['content']   HTML for the entity content (optional)
 */

$params = [
	'content' => elgg_view('user/elements/summary/content', $vars),
	'subtitle' => elgg_view('user/elements/summary/subtitle', $vars),
];
$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
