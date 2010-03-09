<?php
/**
 * Settings Site Pages
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */
?>

<p>
	<?php echo elgg_echo('sitepages:ownfront'); ?>
	<select name="params[ownfrontpage]">
		<option value="yes" <?php if ($vars['entity']->ownfrontpage == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
		<option value="no" <?php if ($vars['entity']->ownfrontpage != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
	</select>
</p>