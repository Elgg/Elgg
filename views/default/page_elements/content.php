<?php
/**
 * Elgg content wrapper
 * This file holds the main content
 */

$body = elgg_get_array_value('body', $vars, '');

echo <<<HTML
<div id="elgg-page-body">
	$body
</div>
HTML;
