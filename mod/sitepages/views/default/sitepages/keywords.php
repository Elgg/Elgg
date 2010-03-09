<?php
/**
 * Lists available keywords
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$keywords = $vars['config']->sitepages_keywords;
$title = elgg_echo('sitepages:keywords_title');
$instructions = elgg_echo('sitepages:keywords_instructions');

$keywords_html = '';
foreach ($keywords as $keyword => $info) {
	$desc = htmlentities($info['description']);
	$keywords_html .= "<li><acronym title=\"$desc\">[[$keyword]]</acronym></li>";
}

echo "
<h3>$title</h3>
<p>$instructions</p>
<ul>
	$keywords_html
</ul>
";