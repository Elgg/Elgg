<?php
/**
 * Simple members search by tag form
 */

$params = array(
	'name' => 'tag',
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search')));
