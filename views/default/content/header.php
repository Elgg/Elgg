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

$context = elgg_get_array_value('context', $vars, elgg_get_context());
if ($context) {
	$title = elgg_get_array_value('title', $vars, '');
	if (!$title) {
		$title = elgg_echo($context);
	}

	if (isset($vars['buttons'])) {
		$buttons = $vars['buttons'];
	} else {
		if (isloggedin() && $context) {
			$username = get_loggedin_user()->username;
			$new_link = elgg_get_array_value('new_link', $vars, "pg/$context/$username/new");
			$params = array(
				'href' => $new_link = elgg_normalize_url($new_link),
				'text' => elgg_echo("$context:new"),
				'class' => 'action-button right',
			);
			$buttons = elgg_view('output/url', $params);
		}
	}
	echo <<<HTML
<div class="elgg-main-header clearfix">
	<h2 class="elgg-main-heading">$title</h2>$buttons
</div>
HTML;
}
