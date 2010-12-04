<?php
/**
 * Displays the tabbed menu for editing site pages.
 *
 * @package SitePages
 */

$page_type = $vars['page_type'];
$url = elgg_get_site_url()."pg/admin/plugin_settings/sitepages/";
?>

<div class="elgg-horizontal-tabbed-nav margin-top">
<ul>
<?php
	// @todo let users be able to add static content pages.
	$pages = array('front', 'about', 'terms', 'privacy', 'seo');

	foreach ($pages as $page) {
		$selected = ($page_type == $page) ? 'class = "selected"' : '';
		echo "<li $selected><a href=\"$url$page\">" . elgg_echo("sitepages:$page") . "</a></li>";
	}
?>
</ul>
</div>