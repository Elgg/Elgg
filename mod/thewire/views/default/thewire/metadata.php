<?php
/**
 *
 */

$entity = $vars['entity'];
$handler = elgg_extract('handler', $vars, '');

?>
<ul class="elgg-list-metadata">
<?php
if (elgg_is_logged_in()) {
	echo '<li>';
	echo elgg_view('output/url', array(
		'href' => "pg/thewire/reply/$entity->guid",
		'text' => elgg_echo('thewire:reply'),
	));
	echo '</li>';
}

if ($entity->reply) {
	echo '<li>';
	echo elgg_view('output/url', array(
		'href' => "pg/thewire/previous/$entity->guid",
		'text' => elgg_echo('thewire:previous'),
	));
	echo '</li>';
}

echo '<li>';
echo elgg_view('output/url', array(
	'href' => 'pg/thewire/thread/' . $entity->wire_thread,
	'text' => elgg_echo('thewire:thread'),
));
echo '</li>';


// links to delete or edit.
if ($entity->canEdit() && $handler) {

	$delete_url = "action/$handler/delete?guid={$entity->getGUID()}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
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