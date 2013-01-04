<?php

elgg_load_js('lightbox');
elgg_load_css('lightbox');

$ipsum = elgg_view('developers/ipsum');

$link = elgg_view('output/url', array(
	'text' => 'Open lighbox',
	'href' => "#elgg-lightbox-test",
	'class' => 'elgg-lightbox'
));

echo $link;
echo '<div class="hidden">';
echo elgg_view_module('aside', 'Lightbox Test', $ipsum, array(
	'id' => 'elgg-lightbox-test'
));
echo '</div>';