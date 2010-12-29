<?php
/**
 * List metadata for objects
 *
 * @uses $vars['entity']
 * @uses $vars['handler']
 */

$entity = $vars['entity'];
$handler = $vars['handler'];

?>
<ul class="elgg-list-metadata">
	<li>
		<?php echo elgg_view('output/access', array('entity' => $entity)); ?>
	</li>
<?php
if (isloggedin() && $entity->getOwnerGUID() != get_loggedin_userid()) {
	$likes = elgg_view_likes($file);
	echo "<li>$likes</li>";
}

// pass <li>your data</li> back from the view
echo elgg_view("entity/metadata", array('entity' => $entity));

// links to delete or edit.
if ($entity->canEdit()) {

	$edit_url = "pg/$handler/edit/{$entity->getGUID()}";
	$edit_link = elgg_view('output/url', array(
		'href' => $edit_url,
		'text' => elgg_echo('edit'),
	));
	echo "<li>$edit_link</li>";

	$delete_url = "action/$handler/delete?guid={$entity->getGUID()}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
		'title' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'encode' => false,
	));
	echo "<li>$delete_link</li>";
}
?>
</ul>
