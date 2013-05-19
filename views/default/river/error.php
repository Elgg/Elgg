<?php
/**
 * Unavailable (error) river item view
 *
 * Displays an error message instead of a river message that can not be rendered,
 * due to a missing view, subject or object
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['error'] string	An administrator message
 */
$item = elgg_extract('item', $vars);
$error = elgg_extract('error', $vars);

echo '<div class="ptm pbm">';
if (elgg_is_admin_logged_in()) {
	echo elgg_view('output/url', array(
		'href' => elgg_add_action_tokens_to_url("action/river/delete?id=$item->id"),
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'class' => 'float-alt'
	));
	echo '<p class="elgg-text-help">' . $error . '</p>';
} else {
	echo '<p class="elgg-text-help">' . elgg_echo('river:error:default') . '</p>';
}
echo '</div>';