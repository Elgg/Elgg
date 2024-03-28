<?php
/**
 * Layout body
 *
 * @uses $vars['title']   Optional title for main content area
 * @uses $vars['header']  Optional override for the header
 * @uses $vars['content'] Content
 * @uses $vars['footer']  Optional footer
 */

$result = elgg_view('page/layouts/elements/breadcrumbs', $vars);
$result .= elgg_view('page/layouts/elements/header', $vars);
$result .= elgg_view('page/layouts/elements/filter', $vars);
$result .= elgg_view('page/layouts/elements/content', $vars);
$result .= elgg_view('page/layouts/elements/footer', $vars);

$tag_name = elgg_extract('entity', $vars) instanceof \ElggEntity ? 'article' : 'div';

echo elgg_format_element($tag_name, ['class' => 'elgg-layout-body'], $result);
