<div class="contentWrapper">

<?php

	if (!empty($vars['invitations']) && is_array($vars['invitations'])) {
		$user = get_loggedin_user();
		foreach($vars['invitations'] as $group)
			if ($group instanceof ElggGroup) {

?>
	<div class="reportedcontent_content active_report">
		<div class="groups_membershiprequest_buttons">
			<?php
				echo "<div class=\"member_icon\"><a href=\"" . $group->getURL() . "\">";
				echo elgg_view("profile/icon", array(
					'entity' => $group,
					'size' => 'small',
					'override' => 'true'
				));
				echo "</a></div>{$group->name}<br />";

				echo str_replace('<a', '<a class="delete_report_button" ', elgg_view('output/confirmlink',array(
					'href' => $vars['url'] . "action/groups/killinvitation?user_guid={$user->getGUID()}&group_guid={$group->getGUID()}",
					'confirm' => elgg_echo('groups:invite:remove:check'),
					'text' => elgg_echo('delete'),
				)));
			$url = elgg_add_action_tokens_to_url("{$vars['url']}action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}");
			?>
			<a href="<?php echo $url; ?>" class="archive_report_button"><?php echo elgg_echo('accept'); ?></a>
			<br /><br />
		</div>
	</div>
<?php

			}

	} else {

		echo "<p>" . elgg_echo('groups:invitations:none') . "</p>";

	}

?>
</div>
