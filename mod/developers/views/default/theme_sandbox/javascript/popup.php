<?php

$ipsum = elgg_view('developers/ipsum');

$link = elgg_view('output/url', array(
	'text' => 'Popup content',
	'href' => "#elgg-popup-test",
	'rel' => 'popup',
));

echo $link;
echo elgg_view_module('popup', 'Popup Test', $ipsum, array(
	'id' => 'elgg-popup-test',
	'class' => 'hidden theme-sandbox-content-thin',
));