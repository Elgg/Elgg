<?php
	/**
	 * Elgg tasklist test
	 * 
	 * @package ElggTasklist
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
?>
<form method="post">
	<textarea name="task"></textarea>
	<input type="text" name="tags" />
	<input type="hidden" name="action" value="newtask"/>
	<input type="hidden" name="owner_id" value="<?php echo $vars['owner_id']; ?>"/>
	<input type="submit" name="submit" />
</form>