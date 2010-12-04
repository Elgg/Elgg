<?php
/**
 * Elgg content wrapper
 * This file holds the main content
 */

$body = elgg_get_array_value('body', $vars, '');

echo <<<HTML
<div id="elgg-page-body" class="elgg-body">
	<div id="elgg_page-body-inner" class="elgg-inner elgg-center elgg-width-classic clearfix">
		$body
	</div>
</div>
HTML;
