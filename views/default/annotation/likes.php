<?php
/**
 * Elgg show the user who liked the object
 */

$owner = get_user($vars['annotation']->owner_guid);

?>
<div class="elgg-likes-user clearfix">
	<div class="entity-listing-icon">
		<?php
			echo elgg_view("profile/icon", array(
					'entity' => $owner,
					'size' => 'tiny'
					));
		?>
	</div>
	
	<div class="entity-listing-info">
		<?php
		// if the user looking at the like listing can edit, show the delete link
		if ($vars['annotation']->canEdit()) {
		?>
			<div class="entity-metadata"><span class="delete-button">
				<?php echo elgg_view("output/confirmlink",array(
						'href' => "action/likes/delete?annotation_id=" . $vars['annotation']->id,
						'text' => elgg_echo('remove'),
						'confirm' => elgg_echo('deleteconfirm')
						));
				?>
			</span></div>
		<?php
			} //end of can edit if statement
		?>
		<p class="elgg-likes-owner">
			<a href="<?php echo $owner->getURL(); ?>"><?php echo $owner->name; ?></a> <?php echo elgg_echo('likes:this') . 
			" <span class=\"entity-subtext\">" . elgg_view_friendly_time($vars['annotation']->time_created) . "</span>"; ?>
		</p>
	</div>
</div>