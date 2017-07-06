<?php

echo elgg_view('admin/develop_tools/inspect/events', [
	'data' => elgg_extract("data", $vars),
	'header' => elgg_echo('developers:inspect:pluginhooks'),
]);
