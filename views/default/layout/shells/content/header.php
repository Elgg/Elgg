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
			$guid = get_loggedin_userid();
			$new_link = elgg_get_array_value('new_link', $vars, "pg/$context/add/$guid/");
			$params = array(
				'href' => $new_link = elgg_normalize_url($new_link),
				'text' => elgg_echo("$context:add"),
				'class' => 'elgg-action-button',
			);
			$buttons = elgg_view('output/url', $params);
		}
	}
	echo <<<HTML
<div class="elgg-header clearfix">
	<h2>$title</h2>$buttons
</div>
HTML;
}
