<?php
/**
 * List metadata for objects
 *
 * @uses $vars['entity']   ElggEntity
 * @uses $vars['handler']  Page handler identifier
 * @uses $vars['links']    Array of extra links
 */

$entity = $vars['entity'];
$handler = elgg_extract('handler', $vars, '');

?>
<ul class="elgg-menu elgg-menu-hz elgg-menu-metadata">
	<li>
		<?php echo elgg_view('output/access', array('entity' => $entity)); ?>
	</li>
<?php

if (isset($vars['links']) && is_array($vars['links'])) {
	foreach ($vars['links'] as $link) {
		echo "<li>$link</li>";
	}
}

// pass <li>your data</li> back from the view
echo elgg_view('entity/metadata', array('entity' => $entity));

// links to delete or edit.
if ($entity->canEdit() && $handler) {

	$edit_url = "pg/$handler/edit/{$entity->getGUID()}";
	$edit_link = elgg_view('output/url', array(
		'href' => $edit_url,
		'text' => elgg_echo('edit'),
	));
	echo "<li>$edit_link</li>";

	$delete_url = "action/$handler/delete?guid={$entity->getGUID()}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'text_encode' => false,
	));
	echo "<li>$delete_link</li>";
}

$likes = elgg_view_likes($entity);
echo "<li>$likes</li>";

?>
</ul>
