<?php
/**
 * Elgg friends collection
 * Lists one of a user's friends collections
 *
 * @package Elgg
 * @subpackage Core
 *
 * @see collections.php
 *
 * @uses $vars['collection'] The individual friends collection
 */

$coll = $vars['collection'];

if (is_array($vars['collection']->members)) {
	$count = sizeof($vars['collection']->members);
} else {
	$count = 0;
}

echo "<li><h2>";

//as collections are private, check that the logged in user is the owner
if ($coll->owner_guid == elgg_get_logged_in_user_guid()) {
	echo "<div class=\"friends_collections_controls\">";
	echo elgg_view('output/confirmlink', array(
			'href' => 'action/friends/deletecollection?collection=' . $coll->id,
			'class' => 'delete_collection'
		));
	echo "</div>";
}
echo $coll->name;
echo " (<span id=\"friends_membership_count{$vars['friendspicker']}\">{$count}</span>) </h2>";

// individual collection panels
if ($friends = $vars['collection']->entities) {
	$content = elgg_view('core/friends/collectiontabs', array('owner' => elgg_get_logged_in_user_entity(), 'collection' => $vars['collection'], 'friendspicker' => $vars['friendspicker']));

	echo elgg_view('core/friends/picker', array('entities' => $friends, 'value' => $members, 'content' => $content, 'replacement' => '', 'friendspicker' => $vars['friendspicker']));
	?>

	<script type="text/javascript">
	$(document).ready(function () {

			$('#friends-picker_placeholder<?php echo $vars['friendspicker']; ?>').load('<?php echo elgg_get_site_url(); ?>pages/friends/pickercallback.php?username=<?php echo elgg_get_logged_in_user_entity()->username; ?>&type=list&collection=<?php echo $vars['collection']->id; ?>');

	});
	</script>
	<?php
}

// close friends-picker div and the accordian list item
echo "</li>";
