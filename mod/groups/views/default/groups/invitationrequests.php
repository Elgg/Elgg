<?php

if (!empty($vars['invitations']) && is_array($vars['invitations'])) {
	$user = elgg_get_logged_in_user_entity();
	foreach($vars['invitations'] as $group)
		if ($group instanceof ElggGroup) {
		
		?>
		<div class="elgg-image-block group_invitations clearfix">
			<?php
				echo "<div class='elgg-image'>";
				echo elgg_view_entity_icon($group, 'tiny', array('override' => 'true'));
				echo "</div>";

			$url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}");
			?>
			<div class="elgg-body">
			<a href="<?php echo $url; ?>" class="elgg-button elgg-button-submit"><?php echo elgg_echo('accept'); ?></a>
			<?php		
				echo str_replace('<a', '<a class="elgg-button elgg-button-action elgg-state-disabled" ', elgg_view('output/confirmlink',array(
					'href' => "action/groups/killinvitation?user_guid={$user->getGUID()}&group_guid={$group->getGUID()}",
					'confirm' => elgg_echo('groups:invite:remove:check'),
					'text' => elgg_echo('delete'),
				)));
			
			echo "<p class='entity-title'><a href=\"" . $group->getUrl() . "\">" . $group->name . "</a></p>";
			echo "<p class='entity-subtext'>" . $group->briefdescription . "</p>";

			?>
		</div></div>
		<?php
		}

	} else {
		echo "<p class='default_string mtm'>" . elgg_echo('groups:invitations:none') . "</p>";
}
?>