<?php
/**
 * Elgg generic comment view
 *
 * @uses $vars['annotation']  ElggAnnotation object
 * @uses $vars['full']   Display fill view or brief view
 */

if (!isset($vars['annotation'])) {
	return true;
}

$full_view = elgg_get_array_value('full', $vars, true);

$comment = $vars['annotation'];

$entity = get_entity($comment->entity_guid);
$commenter = get_user($comment->owner_guid);
if (!$entity || !$commenter) {
	return true;
}

$friendlytime = elgg_view_friendly_time($comment->time_created);

$commenter_icon = elgg_view("profile/icon", array('entity' => $commenter, 'size' => 'tiny'));
$commenter_link = "<a href=\"{$commenter->getURL()}\">$commenter->name</a>";

$entity_title = $entity->title ? $entity->title : elgg_echo('untitled');
$entity_link = "<a href=\"{$entity->getURL()}\">$entity_title</a>";

if ($full_view) {

	$delete_button = '';
	if ($comment->canEdit()) {
		$delete_button = elgg_view("output/confirmlink",array(
							'href' => "action/comments/delete?annotation_id={$comment->id}",
							'text' => elgg_echo('delete'),
							'confirm' => elgg_echo('deleteconfirm')
						));
		$delete_button = "<span class=\"delete-button\">$delete_button</span>";
	}

	$comment_text = elgg_view("output/longtext", array("value" => $comment->value));

	$body = <<<HTML
<p class="mbn">
	$delete_button
	$commenter_link
	<span class="entity-subtext">
		$friendlytime
	</span>
	$comment_text
</p>
HTML;

	echo elgg_view_media($commenter_icon, $body, array('id' => "comment-$comment->id"));

} else {
	// brief view

	//@todo need link to actual comment!
	
	$on = elgg_echo('on');

	$body = <<<HTML
<span class="entity-subtext">
	$commenter_link $on <span class='entity-title'>$entity_link</span> ($friendlytime)
</span>
HTML;

	echo elgg_view_media($commenter_icon, $body);
}
