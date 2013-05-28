<?php
/**
 * Elgg footer
 * The HTML footer that displays across the site
 *
 * @package Coopfunding
 * @subpackage Theme
 *
 */

$site = elgg_get_site_entity();
echo "<a class=\"coopfunding-footer-title mts\" href=\"$site->url\">".
		"<span>$site->name</span> $site->description".
		"</a>";

echo elgg_view_menu('footer', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));

$cic_img  = elgg_get_site_url() . "mod/coopfunding_theme/_graphics/cic.png";
$casx_img = elgg_get_site_url() . "mod/coopfunding_theme/_graphics/casx.png";

echo '<div class="mts clearfloat float-alt">';
echo elgg_view('output/url', array(
	'href' => 'http://cooperativa.cat',
	'text' => "<img src=\"$cic_img\" alt=\"Powered by Cooperativa Integral Catalana\" height=\"50\" />",
	'class' => '',
	'is_trusted' => true,
));
echo elgg_view('output/url', array(
	'href' => 'http://casx.cat',
	'text' => "<img src=\"$casx_img\" alt=\"Powered by CASX\" height=\"50\" />",
	'class' => '',
	'is_trusted' => true,
));
echo '</div>';
