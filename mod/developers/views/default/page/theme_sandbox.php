<?php
/**
 * Theme preview page shell
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The laid out content of the page
 * @uses $vars['sysmessages'] System message array
 */

$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$content = elgg_view('page/elements/body', $vars);
$title_link = elgg_view('output/url', array(
	'text' => 'Theme Sandbox',
	'href' => 'theme_preview/intro',
	'is_trusted' => true,
));

$header = "<h1 class=\"theme-sandbox-site-heading\">$title_link</h1>";
if (get_input("site_menu", false)) {
	$header .= elgg_view_menu('site');
}

$body = <<<__BODY
<div class="elgg-page theme-sandbox-page">
	<div class="elgg-page-messages">
		$messages
	</div>
__BODY;

$body .= <<<__BODY
	<div class="theme-sandbox-header">
		<div class="elgg-inner">
			$header
		</div>
	</div>
	<div class="theme-sandbox-body">
		<div class="elgg-inner">
			$content
		</div>
	</div>
</div>
__BODY;

$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', $vars);

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));
