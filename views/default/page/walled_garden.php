<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$body = elgg_view("page/elements/body/walled_garden", $vars);
$head = elgg_view('page/elements/head', $vars);

echo elgg_view("page/shell", array("head" => $head, "body" => $body));
