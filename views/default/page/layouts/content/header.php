<?php
/**
 * Main content header
 *
 * This includes a title and a new content button by default
 *
 * @uses $vars['header_override'] HTML for overriding the default header (override)
 * @uses $vars['title']           Title text (override)
 * @uses $vars['context']         Page context (override)
 * @uses $vars['buttons']         Content header buttons (override)
 */

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

	if (isset($vars['buttons'])) {
		$buttons = $vars['buttons'];
	} else {
		if (elgg_is_logged_in() && $context) {
			$owner = elgg_get_page_owner_entity();
			if (!$owner) {
				// this is probably an all page
				$owner = elgg_get_logged_in_user_entity();
			}
			if ($owner && $owner->canWriteToContainer()) {
				$guid = $owner->getGUID();
				elgg_register_menu_item('title', array(
					'name' => 'add',
					'href' => elgg_extract('new_link', $vars, "$context/add/$guid"),
					'text' => elgg_echo("$context:add"),
					'link_class' => 'elgg-button elgg-button-action',
				));
			}
		}
		
		$buttons = elgg_view_menu('title', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
	}
	echo <<<HTML
<div class="elgg-head clearfix">
	<h2 class="elgg-heading-main">$title</h2>$buttons
</div>
HTML;
}
