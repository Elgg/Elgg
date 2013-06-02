<?php
/**
 * A view to load through ajax for the lightbox demo
 */

$ipsum = elgg_view('developers/ipsum');

echo '<div class="mam" style="width: 400px;">';
echo elgg_view_module('aside', 'Lightbox Test', $ipsum, array(
	'id' => 'elgg-lightbox-test'
));
echo '</div>';
