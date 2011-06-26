<?php
/**
 * Main content header
 *
 * This includes a title and a new content button by default
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

$context = elgg_extract('context', $vars, elgg_get_context());
if ($context) {
	$title = elgg_extract('title', $vars, '');
	if (!$title) {
		$title = elgg_echo($context);
	}

	if (isset($vars['buttons']) && $vars['buttons']) {
		$buttons = $vars['buttons'];
	} else {
		$buttons = elgg_view_menu('title', array(
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
	}
	echo <<<HTML
<div class="elgg-head clearfix">
	<h2 class="elgg-heading-main">$title</h2>$buttons
</div>
HTML;
}
