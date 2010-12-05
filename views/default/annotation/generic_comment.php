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

} else {
	// brief view

	//@todo need link to actual comment!
	
	$on = elgg_echo('on');

	$body = <<<HTML
<span class="entity-subtext">$commenter_link $on <span class='entity-title'>$entity_link</span> ($friendlytime)</span>
HTML;

	echo elgg_view_media($commenter_icon, $body);
	
	// @todo remove this once the full view has been rewritten
	return true;
}


// @todo - below needs to be rewritten like the brief view

$owner = get_user($vars['annotation']->owner_guid);

?>
<a class="anchor_link" name="comment_<?php echo $vars['annotation']->id; ?>"></a>
<div class="generic-comment clearfix">
	<div class="generic-comment-icon">
		<?php
			echo elgg_view("profile/icon", array(
					'entity' => $owner,
					'size' => 'tiny'
					));
		?>
	</div>

	<div class="generic-comment-details">
		<?php
		// if the user looking at the comment can edit, show the delete link
		if ($vars['annotation']->canEdit()) {
		?>
			<span class="delete-button">
				<?php echo elgg_view("output/confirmlink",array(
						'href' => "action/comments/delete?annotation_id=" . $vars['annotation']->id,
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm')
						));
				?>
			</span>
		<?php
			} //end of can edit if statement
		?>
		<p class="generic-comment-owner">
			<a href="<?php echo $owner->getURL(); ?>"><?php echo $owner->name; ?></a>
			<span class="entity-subtext">
				<?php echo elgg_view_friendly_time($vars['annotation']->time_created); ?>
			</span>
		</p>
		<!-- output the actual comment -->
		<div class="generic-comment-body">
			<?php echo elgg_view("output/longtext",array("value" => $vars['annotation']->value)); ?>
		</div>
	</div>
</div>