<?php

elgg_require_js('no-data-demo');

$title = elgg_echo(get_current_language());

ob_start();
?>

<h3>PHP elgg_echo:</h3>

<?= elgg_echo('no-data-proof'); ?>

<hr class='mtl mbl'>

<h3>JS elgg.echo:</h3>

<div class='no-data-echo'></div>

<?php
$content = ob_get_clean();

$layout = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $layout);
