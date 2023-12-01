<?php

$body = elgg_extract('body', $vars);
if (empty($body)) {
	return;
}

echo $body;
