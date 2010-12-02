<?php

	/**
	 * Elgg Wire Posts Listings
	 *
	 * @package thewire
	 *
	 * @question - do we want users to be able to edit thewire?
	 *
	 * @uses $vars['entity'] Optionally, the note to view
	 */

if (isset($vars['entity'])) {
	$user_name = $vars['entity']->getOwnerEntity()->name;

	//if the note is a reply, we need some more info
	$note_url = '';
	$note_owner = elgg_echo("thewire:notedeleted");
?>
<div class="wire_post">
	<div class="wire_post_contents clearfix radius8">

		<div class="wire_post_icon">
		<?php
				echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'tiny'));
		?>
		</div>

		<div class="wire_post_options">
		<?php
			if(isloggedin()){
		?>
			<a href="<?php echo elgg_get_site_url(); ?>mod/thewire/add.php?wire_username=<?php echo $vars['entity']->getOwnerEntity()->username; ?>" class="action-button reply small"><?php echo elgg_echo('thewire:reply'); ?></a>
			<?php
			}//close reply if statement
			// if the user looking at thewire post can edit, show the delete link
			if ($vars['entity']->canEdit()) {
			echo "<span class='delete-button'>" . elgg_view("output/confirmlink",array(
					'href' => "action/thewire/delete?thewirepost=" . $vars['entity']->getGUID(),
					'text' => elgg_echo('delete'),
					'confirm' => elgg_echo('deleteconfirm'),
				)) . "</span>";
			}
		?>
		</div>

		<div class="wire_post_info">
			<a href="<?php echo elgg_get_site_url(); ?>pg/thewire/<?php echo $vars['entity']->getOwnerEntity()->username; ?>"><?php echo $user_name; ?></a>
			<?php
				$desc = $vars['entity']->description;
				//$desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . elgg_get_site_url() . 'pg/thewire/$1">$1</a>',$desc);
				echo parse_urls($desc);
			?>
			<p class="entity_subtext">
			<?php
				echo elgg_echo("thewire:wired") . " " . elgg_echo("thewire:strapline",
								array(elgg_view_friendly_time($vars['entity']->time_created))
				);
				echo ' ';
				echo elgg_echo('thewire:via_method', array(elgg_echo($vars['entity']->method)));
				echo '.';
			?>
			</p>
		</div>
	</div>
</div>
<?php
}
?>