<?php
/**
 * Elgg generic comment view
 *
 * @uses $vars['annotation']    ElggAnnotation object
 * @uses $vars['full_view']          Display fill view or brief view
 */

if (!isset($vars['annotation'])) {
	return true;
}

$full_view = elgg_extract('full_view', $vars, true);

$comment = $vars['annotation'];

$entity = get_entity($comment->entity_guid);
$commenter = get_user($comment->owner_guid);
if (!$entity || !$commenter) {
	return true;
}

$friendlytime = elgg_view_friendly_time($comment->time_created);

$commenter_icon = elgg_view_entity_icon($commenter, 'tiny');
$commenter_link = "<a href=\"{$commenter->getURL()}\">$commenter->name</a>";

$entity_title = $entity->title ? $entity->title : elgg_echo('untitled');
$entity_link = "<a href=\"{$entity->getURL()}\">$entity_title</a>";

if ($full_view) {

	$delete_button = '';
	if ($comment->canEdit()) {
		$url = "action/comments/delete?annotation_id=$comment->id";
		$delete_button = elgg_view("output/confirmlink", array(
							'href' => $url,
							'text' => "<span class=\"elgg-icon elgg-icon-delete right\"></span>",
							'confirm' => elgg_echo('deleteconfirm'),
							'text_encode' => false,
						));
	}

	$comment_text = elgg_view("output/longtext", array("value" => $comment->value));

	$body = <<<HTML
<div class="mbn">
	$delete_button
	$commenter_link
	<span class="elgg-subtext">
		$friendlytime
	</span>
	$comment_text
</div>
HTML;

	echo elgg_view_image_block($commenter_icon, $body);

} else {
	// brief view

	//@todo need link to actual comment!

	$on = elgg_echo('on');

	$body = <<<HTML
<span class="elgg-subtext">
	$commenter_link $on $entity_link ($friendlytime)
</span>
HTML;

	echo elgg_view_image_block($commenter_icon, $body);
}
