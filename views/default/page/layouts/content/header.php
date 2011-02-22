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
			if (elgg_instanceof($owner, 'group')) {
				$guid = $owner->getGUID();
			} else {
				$guid = elgg_get_logged_in_user_guid();
			}
			$new_link = elgg_extract('new_link', $vars, "pg/$context/add/$guid/");
			$params = array(
				'href' => $new_link = elgg_normalize_url($new_link),
				'text' => elgg_echo("$context:add"),
				'class' => 'elgg-button-action',
			);
			$buttons = elgg_view('output/url', $params);
		}
	}
	echo <<<HTML
<div class="elgg-head clearfix">
	<h2 class="elgg-heading-main">$title</h2>$buttons
</div>
HTML;
}
