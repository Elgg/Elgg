<?php

$class = elgg_extract('class', $vars);
$action = elgg_extract('action', $vars);
$body = elgg_extract('body', $vars);

echo elgg_format_element('form', [
	'class' => $class,
	'action' => $action,
		], $body);
