<?php
/**
 * Elgg friends collection
 * Lists one of a user's friends collections
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
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
if($coll->owner_guid == $_SESSION['user']->getGUID()) {
	echo "<div class=\"friends_collections_controls\">";
	echo elgg_view('output/confirmlink', array(
			'href' => $vars['url'] . 'action/friends/deletecollection?collection=' . $coll->id,
			'class' => 'delete_collection'
		));
	echo "</div>";
}
echo $coll->name;
echo " (<span id=\"friends_membership_count{$vars['friendspicker']}\">{$count}</span>) </h2>";

// individual collection panels
if($friends = $vars['collection']->entities) {
	$content = elgg_view('friends/collectiontabs', array('owner' => $_SESSION['user'], 'collection' => $vars['collection'], 'friendspicker' => $vars['friendspicker']));

	echo elgg_view('friends/picker',array('entities' => $friends, 'value' => $members, 'content' => $content, 'replacement' => '', 'friendspicker' => $vars['friendspicker']));
	?>

	<script type="text/javascript">
	$(document).ready(function () {

	$('#friends_picker_placeholder<?php echo $vars['friendspicker']; ?>').load('<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=list&collection=<?php echo $vars['collection']->id; ?>');

	});
	</script>
	<?php
}

// close friends_picker div and the accordian list item
echo "</li>";
