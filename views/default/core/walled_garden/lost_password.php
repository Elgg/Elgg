<?php
/**
 * Walled garden lost password
 */

$title = elgg_echo('user:password:lost');
$body = elgg_view_form('user/requestnewpassword');
echo <<<HTML
<div class="elgg-inner">
	<h3>$title</h3>
	$body
</div>
HTML;
