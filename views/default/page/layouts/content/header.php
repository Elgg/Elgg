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

if (isset($vars['buttons'])) {
	// it was a bad idea to implement buttons with a pass through
	elgg_deprecated_notice("Use elgg_register_menu_item() to register for the title menu", 1.0);
}

if (isset($vars['header_override'])) {
	echo $vars['header_override'];
	return true;
}

$title = elgg_extract('title', $vars, '');
if ($title === '') {
	$context = elgg_extract('context', $vars, elgg_get_context());

	$title = elgg_echo($context);
}
if (!empty($title)) {
	$title = elgg_view_title($title, array('class' => 'elgg-heading-main'));
}

if (isset($vars['buttons']) && $vars['buttons']) {
	$buttons = $vars['buttons'];
} else {
	$buttons = elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}
if (!empty($title) || !empty($buttons)) {
	echo "<div class='elgg-head clearfix'>";
	echo $title . $buttons;
	echo "</div>";
}