<?php
/**
 * Simple members search by tag form
 */

$params = array(
	'internalname' => 'tag',
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search')));
