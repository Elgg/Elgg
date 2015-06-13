<?php
/**
 * Maintenance mode page shell
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$content = $vars['body'];

$title = elgg_extract('title', $vars, elgg_get_site_entity()->name);
$html5shiv = elgg_normalize_url('vendors/html5shiv.js');
$favicon = elgg_view('page/elements/shortcut_icon', $vars);
$css = elgg_get_simplecache_url('css', 'css/maintenance');
$head = <<<__HEAD
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>$title</title>
	$favicon
	<!--[if lt IE 9]>
		<script src="$html5shiv"></script>
	<![endif]-->
	<link href="$css" rel="stylesheet">
__HEAD;

$body = <<<__BODY
<div class="elgg-page elgg-page-maintenance" id="elgg-maintenance-page-wrapper">
	<div class="elgg-page-messages">
		$messages
	</div>
	<div class="elgg-body-maintenance">
		$content
	</div>
</div>
__BODY;

echo elgg_view("page/elements/html", array('head' => $head, 'body' => $body));

