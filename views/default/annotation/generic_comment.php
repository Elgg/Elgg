<?php
/**
 * Elgg generic comment
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

$owner = get_user($vars['annotation']->owner_guid);

?>
<div class="generic_comment"><!-- start of generic_comment div -->

	<div class="generic_comment_icon">
		<?php
			echo elgg_view("profile/icon",
				array(
					'entity' => $owner,
					'size' => 'small'
				)
			);
		?>
	</div>
	<div class="generic_comment_details">

		<!-- output the actual comment -->
		<?php echo elgg_view("output/longtext",array("value" => $vars['annotation']->value)); ?>

		<p class="generic_comment_owner">
			<a href="<?php echo $owner->getURL(); ?>"><?php echo $owner->name; ?></a> <?php echo friendly_time($vars['annotation']->time_created); ?>
		</p>

		<?php

			// if the user looking at the comment can edit, show the delete link
			if ($vars['annotation']->canEdit()) {

			?>
		<p>
			<?php

				echo elgg_view("output/confirmlink",array(
					'href' => $vars['url'] . "action/comments/delete?annotation_id=" . $vars['annotation']->id,
					'text' => elgg_echo('delete'),
					'confirm' => elgg_echo('deleteconfirm'),
				));

			?>
		</p>

			<?php
			} //end of can edit if statement
		?>
	</div><!-- end of generic_comment_details -->
</div><!-- end of generic_comment div -->