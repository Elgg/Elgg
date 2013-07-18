<?php

elgg_load_js('lightbox');
elgg_load_css('lightbox');

$link = elgg_view('output/url', array(
	'text' => 'Open lighbox',
	'href' => "ajax/view/developers/ajax",
	'class' => 'elgg-lightbox'
));

echo $link;
