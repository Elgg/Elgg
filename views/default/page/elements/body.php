<?php
/**
 * Elgg page body wrapper
 *
 * @uses $vars['body'] The HTML of the page body
 */

$body = elgg_get_array_value('body', $vars, '');

echo <<<HTML
<div class="elgg-page-body">
	$body
</div>
HTML;
