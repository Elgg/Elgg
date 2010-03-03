<div class="contentWrapper">

<?php

	if (!empty($vars['requests']) && is_array($vars['requests'])) {

		foreach($vars['requests'] as $request)
			if ($request instanceof ElggUser) {

?>
	<div class="reportedcontent_content active_report">
		<div class="groups_membershiprequest_buttons">
			<?php
				echo "<div class=\"member_icon\"><a href=\"" . $request->getURL() . "\">";
				echo elgg_view("profile/icon", array(
					'entity' => $request,
					'size' => 'small',
					'override' => 'true'
				));
				echo "</a></div>{$request->name}<br />";

				echo str_replace('<a', '<a class="delete_report_button" ', elgg_view('output/confirmlink',array(
					'href' => $vars['url'] . 'action/groups/killrequest?user_guid='.$request->guid.'&group_guid=' . $vars['entity']->guid,
					'confirm' => elgg_echo('groups:joinrequest:remove:check'),
					'text' => elgg_echo('delete'),
				)));
			$url = elgg_add_action_tokens_to_url("{$vars['url']}action/groups/addtogroup?user_guid={$request->guid}&group_guid={$vars['entity']->guid}");
			?>
			<a href="<?php echo $url; ?>" class="archive_report_button"><?php echo elgg_echo('accept'); ?></a>
			<br /><br />
		</div>
	</div>
<?php

			}

	} else {

		echo "<p>" . elgg_echo('groups:requests:none') . "</p>";

	}

?>
</div>
