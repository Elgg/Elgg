<?php
	/**
	 * Elgg groups plugin
	 *
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
?>

<div id="content_header" class="clearfloat">
	<div class="content_header_title">
		<h2><?php echo elgg_echo("groups:forum"); ?></h2>
	</div>
	<?php // only show the add topic button if the user is a member
		if(page_owner_entity()->isMember($vars['user'])) {
	?>
		<div class="content_header_options">
			<a class="action_button" href="<?php echo $vars['url']; ?>mod/groups/addtopic.php?group_guid=<?php echo $vars['group_guid']; ?>"><?php echo elgg_echo("groups:addtopic"); ?></a>
		</div>
	<?php
		}
	?>
</div>
<?php
	if($vars['topics'])
		echo $vars['topics'];
	else
		echo "<p class='margin_top'>". elgg_echo("grouptopic:notcreated") . "</p>";

?>