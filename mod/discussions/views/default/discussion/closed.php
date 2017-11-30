<?php
/**
 * Topic is closed
 */

echo elgg_view_message('notice', elgg_echo("discussion:topic:closed:desc"), [
	'title' => elgg_echo("discussion:topic:closed:title"),
]);
