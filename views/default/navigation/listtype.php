<?php
/**
 * Elgg list view switcher
 *
 * @package Elgg
 * @subpackage Core
 */

$baseurl = elgg_http_remove_url_query_element($vars['baseurl'], 'search_listtype');

if ($vars['listtype'] == "list") {
	$listtype = "gallery";
} else {
	$listtype = "list";
}

if (substr_count($baseurl,'?')) {
	$baseurl .= "&search_listtype=" . $listtype;
} else {
	$baseurl .= "?search_listtype=" . $listtype;
}

?>

<p class="mtm">
	<?php echo elgg_echo("listtype:change") ?>:
	<a href="<?php echo $baseurl; ?>"><?php echo elgg_echo("listtype:{$listtype}"); ?></a>
</p>