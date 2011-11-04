<?php
/**
 * Walled garden lost password
 */

$title = elgg_echo('user:password:lost');
$body = elgg_view_form('user/requestnewpassword');
$lost = <<<HTML
<div class="elgg-inner">
	<h3>$title</h3>
	$body
</div>
HTML;

echo elgg_view_module('walledgarden', '', $lost, array(
	'class' => 'elgg-walledgarden-single elgg-walledgarden-password hidden',
	'header' => ' ',
	'footer' => ' ',
));
