<?php
	/**
	 * Owner links
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */


	// edit
	if (($_SESSION['user']->getGUID() == $vars['entity']->owner_guid) && ($vars['full']))
	{
	?>
		<p><a href="<?php echo $vars['url']; ?>mod/groups/edit.php?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a></p>
	<?php 
	}
?>