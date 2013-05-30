<?php
/**
 * Main content header
 *
 * Title and title menu
 *
 * @uses $vars['header_override'] HTML for overriding the default header (override)
 * @uses $vars['title']           Title text (override)
 * @uses $vars['context']         Page context (override)
 */

if (isset($vars['header_override'])) {
	echo $vars['header_override'];
	return true;
}

$context = elgg_extract('context', $vars, elgg_get_context());

$title = elgg_extract('title', $vars, '');
if (!$title) {
	$title = elgg_echo($context);
}
// @todo .elgg-heading-main supports action buttons - maybe rename class name?
$title = elgg_view_title($title, array('class' => 'elgg-heading-main'));

$buttons = elgg_view_menu('title', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

echo <<<HTML
<div class="elgg-head clearfix">
	$title$buttons
</div>
HTML;
