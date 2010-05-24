<?php
/**
 * Displays the tabbed menu for editing site pages.
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$page_type = $vars['page_type'];
$url = $vars['url'] . 'pg/sitepages/edit/';
?>

<div class="elgg_horizontal_tabbed_nav margin_top">
<ul>
<?php
	// @todo let users be able to add static content pages.
	$pages = array('front', 'about', 'terms', 'privacy', 'seo');

	foreach ($pages as $page) {
		$selected = ($page_type == $page) ? 'class = "selected"' : '';
		echo "<li $selected><a href=\"{$url}{$page}\">" . elgg_echo("sitepages:$page") . "</a></li>";
	}
?>
</ul>
</div>