<?php
/**
 * Elgg content wrapper
 *
 * @uses $vars['body'] The main content HTML
 */

$body = elgg_get_array_value('body', $vars, '');

echo <<<HTML
<div class="elgg-page-body">
	$body
</div>
HTML;
