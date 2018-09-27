<?php

echo "<div class='site-notifications-container'>";
echo elgg_extract('list', $vars);
echo "</div>";

echo '<div class="elgg-foot site-notifications-buttonbank">';

echo elgg_view('input/submit', [
	'value' => elgg_echo('delete'),
	'name' => 'delete',
	'class' => 'elgg-button-delete',
	'title' => elgg_echo('deleteconfirm:plural'),
	'data-confirm' => elgg_echo('deleteconfirm:plural')
]);

echo elgg_view('input/button', [
	'value' => elgg_echo('site_notifications:toggle_all'),
	'class' => 'elgg-button elgg-button-cancel',
	'id' => 'site-notifications-toggle',
]);

echo '</div>';
