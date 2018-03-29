<?php
/**
 * Page shell for all HTML pages
 *
 * @uses $vars['html_attrs'] Attributes of the <html> tag
 * @uses $vars['head']       Parameters for the <head> element
 * @uses $vars['body_attrs'] Attributes of the <body> tag
 * @uses $vars['body']       The main content of the page
 */
// Set the content type
elgg_set_http_header("Content-type: text/html; charset=UTF-8");

$lang = get_current_language();

$default_html_attrs = [
	'xmlns' => 'http://www.w3.org/1999/xhtml',
	'xml:lang' => $lang,
	'lang' => $lang,
];
$html_attrs = elgg_extract('html_attrs', $vars, []);
$html_attrs = array_merge($default_html_attrs, $html_attrs);

$body_attrs = elgg_extract('body_attrs', $vars, []);
?>
<!DOCTYPE html>
<html <?= elgg_format_attributes($html_attrs) ?>>
	<head>
		<?= elgg_extract('head', $vars, '') ?>
	</head>
	<body <?= elgg_format_attributes($body_attrs) ?>>
		<?= elgg_extract('body', $vars, '') ?>
	</body>
</html>
