<?php
/**
 * Specific keyword help.
 * 
 * Looks for a view ecml/help/keyword
 * 
 */

$keyword = elgg_get_array_value('keyword', $vars);
$content = elgg_view("ecml/help/$keyword");

if (!$keyword || !ecml_is_valid_keyword($keyword)) {
	echo elgg_echo('ecml:help:invalid_keyword');
} elseif (!$content) {
	echo elgg_echo('ecml:help:no_help');
} else {
	echo $content;
}