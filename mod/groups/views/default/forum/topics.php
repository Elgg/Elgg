<?php
/**
 * Elgg groups plugin
 */
?>

<div id="content_header" class="clearfix">
	<div class="content-header-title">
		<h2><?php echo elgg_echo("groups:forum"); ?></h2>
	</div>
	<?php // only show the add topic button if the user is a member
		if(elgg_get_page_owner()->isMember(get_loggedin_user())) {
	?>
		<div class="content-header-options">
			<a class="action-button" href="<?php echo elgg_get_site_url(); ?>mod/groups/addtopic.php?group_guid=<?php echo $vars['group_guid']; ?>"><?php echo elgg_echo("groups:addtopic"); ?></a>
		</div>
	<?php
		}
	?>
</div>
<?php
if($vars['topics'])
	echo $vars['topics'];
else
	echo "<p class='margin-top'>". elgg_echo("grouptopic:notcreated") . "</p>";