<?php

	if (!empty($vars['requests']) && is_array($vars['requests'])) {

		foreach($vars['requests'] as $request)
				if ($request instanceof ElggUser) {
	
	?>
		<div class="entity-listing group_invitations clearfix">
				<?php
					echo "<div class='entity-listing-icon'>";
					echo elgg_view("profile/icon", array(
						'entity' => $request,
						'size' => 'small',
						'override' => 'true'
					));
					echo "</div>";
					
					$url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/groups/addtogroup?user_guid={$request->guid}&group_guid={$vars['entity']->guid}");
					?>
					<div class="entity-listing-info">
					<a href="<?php echo $url; ?>" class="submit-button"><?php echo elgg_echo('accept'); ?></a>
					<?php	
					echo str_replace('<a', '<a class="elgg-action-button disabled" ', elgg_view('output/confirmlink',array(
						'href' => 'action/groups/killrequest?user_guid='.$request->guid.'&group_guid=' . $vars['entity']->guid,
						'confirm' => elgg_echo('groups:joinrequest:remove:check'),
						'text' => elgg_echo('delete'),
					)));
				echo "<p class='entity-title'><a href=\"" . $request->getUrl() . "\">" . $request->name . "</a></p>";
				echo "<p class='entity-subtext'>" . $request->briefdescription . "</p>";
				?>
			</div>
		</div>
	<?php
		}
	} else {
		echo "<p>" . elgg_echo('groups:requests:none') . "</p>";
	}

?>