<div class="contentWrapper">

<?php

	if (!empty($vars['requests']) && is_array($vars['requests'])) {
		
		foreach($vars['requests'] as $request)
			if ($request instanceof ElggUser) {
				
?>
	<div class="reportedcontent_content active_report">
		<div class="groups_membershiprequest_buttons">
			<?php
			
				echo str_replace('<a', '<a class="delete_report_button" ', elgg_view('output/confirmlink',array(
															'href' => $vars['url'] . 'action/groups/killrequest?user_guid='.$request->guid.'&group_guid=' . $vars['entity']->guid,
															'confirm' => elgg_echo('groups:joinrequest:remove:check'),
															'text' => elgg_echo('delete'),
														)));
			
			?>
			<a href="<?php echo $vars['url']; ?>action/groups/addtogroup?user_guid=<?php echo $request->guid; ?>&group_guid=<?php echo $vars['entity']->guid; ?>" class="archive_report_button"><?php echo elgg_echo('accept'); ?></a>
		</div>
		<p class="reportedcontent_detail">
			<a href="<?php echo $request->getURL(); ?>"><?php echo $request->name; ?></a>
		</p>
		<?php

			$status = elgg_view("profile/status", array("entity" => $request));
			if (!empty($status)) {
			
		?>
		<p class="reportedcontent_detail">
			<?php echo $status; ?>
		</p>
		<?php

			} else echo "<p class=\"reportedcontent_detail\">&nbsp;</p>";
		
		?>
	</div>
<?php
				
			}
		
	} else {
		
		echo "<p>" . elgg_echo('groups:requests:none') . "</p>";
		
	}

?>
</div>