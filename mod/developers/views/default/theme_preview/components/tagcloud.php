<?php

$tags = array(
	(object)array('tag' => 'php', 'total' => 2),
	(object)array('tag' => 'elgg', 'total' => 8),
	(object)array('tag' => 'javascript', 'total' => 3),
	(object)array('tag' => 'css', 'total' => 4),
	(object)array('tag' => 'html', 'total' => 1),
	(object)array('tag' => 'framework', 'total' => 4),
	(object)array('tag' => 'social', 'total' => 3),
	(object)array('tag' => 'web', 'total' => 7),
	(object)array('tag' => 'code', 'total' => 2),
);

echo '<div style="width: 200px;">';
echo elgg_view('output/tagcloud', array('value' => $tags));
echo '</div>';
