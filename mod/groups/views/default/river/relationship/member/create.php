<?php
/**
 * Group join river view.
 */

$object = $vars['item']->getObjectEntity();

$params = array(
	'href' => $object->getURL(),
	'text' => $object->name,
);
$link = elgg_view('output/url', $params);


echo elgg_echo('groups:river:join');

echo " $link";
