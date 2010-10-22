<?php
/**
 * Lists available keywords
 *
 * @package ECML
 */

$keywords = $vars['config']->ecml_keywords;
$title = elgg_echo('ecml:keywords_title');
$instructions = elgg_echo('ecml:keywords_instructions');
$more_info = elgg_echo('ecml:keywords_instructions_more');

$keywords_html = '';
foreach ($keywords as $keyword => $info) {
	$desc = htmlentities($info['description']);
	$keywords_html .= "
<dt>[$keyword]</dt>
<dd>$desc</dd>";
}

echo "
<h3>$title</h3>
<p>$instructions</p>
$more_info
<dl>
	$keywords_html
</dl>
";