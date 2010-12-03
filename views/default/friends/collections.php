<?php
/**
 * Elgg friends collections
 * Lists a user's friends collections
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['collections'] The array of friends collections
 */

if (!isset($friendspicker)) {
	$friendspicker = 0;
}


if (is_array($vars['collections']) && sizeof($vars['collections'])) {
	echo "<ul id=\"friends_collections_accordian\">";

	foreach($vars['collections'] as $collection) {
		$friendspicker++;
		echo elgg_view('friends/collection',array('collection' => $collection, 'friendspicker' => $friendspicker));
	}

	echo "</ul>";

} else {
	echo elgg_echo("friends:nocollections");
}

?>

<script>
$(document).ready(function(){
	$('#friends_collections_accordian h2').click(function () {
		$(this.parentNode).children("[class=friends-picker]").slideToggle("fast");
		//return false;
	});
});
</script>
