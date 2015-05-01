<?php

$list = elgg_extract('list', $vars);

if (empty($list)) {
	echo elgg_echo('site_notifications:empty');
	return true;
}

echo "<div class='site-notifications-container'>";
echo $list;
echo "</div>";

echo '<div class="elgg-foot site-notifications-buttonbank">';

echo elgg_view('input/submit', array(
	'value' => elgg_echo('delete'),
	'name' => 'delete',
	'class' => 'elgg-button-delete',
	'title' => elgg_echo('deleteconfirm:plural'),
	'data-confirm' => elgg_echo('deleteconfirm:plural')
));

echo elgg_view('input/button', array(
	'value' => elgg_echo('site_notifications:toggle_all'),
	'class' => 'elgg-button elgg-button-cancel',
	'id' => 'site-notifications-toggle',
));

echo '</div>';