<?php
/**
 * Elgg list view switcher
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
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

<div class="contentWrapper">
	<?php echo elgg_echo("viewtype:change") ?>:
	<a href="<?php echo $baseurl; ?>"><?php echo elgg_echo("viewtype:{$viewtype}"); ?></a>
</div>