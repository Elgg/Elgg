<?php
/**
 * Elgg list view switcher
 *
 * @package Elgg
 * @subpackage Core
 */

$baseurl = elgg_http_remove_url_query_element($vars['baseurl'], 'search_viewtype');

if ($vars['viewtype'] == "list") {
	$viewtype = "gallery";
} else {
	$viewtype = "list";
}

if (substr_count($baseurl,'?')) {
	$baseurl .= "&search_viewtype=" . $viewtype;
} else {
	$baseurl .= "?search_viewtype=" . $viewtype;
}

?>

<p class="margin_top">
	<?php echo elgg_echo("viewtype:change") ?>:
	<a href="<?php echo $baseurl; ?>"><?php echo elgg_echo("viewtype:{$viewtype}"); ?></a>
</p>