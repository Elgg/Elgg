<?php
/**
 * A view to load through ajax for the lightbox demo
 */

$ipsum = elgg_view('developers/ipsum');

$resize_button = elgg_view('input/button', [
	'id' => 'elgg-lightbox-test-resize',
	'class' => 'elgg-button elgg-button-action',
	'value' => 'Add extra content and resize',
]);

echo '<div class="mam" style="width: 400px;">';
echo elgg_view_module('aside', 'Lightbox Test', $ipsum, [
	'id' => 'elgg-lightbox-test',
	'footer' => $resize_button,
]);
echo '</div>';
