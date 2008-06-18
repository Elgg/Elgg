<?php
	/**
	 * Elgg user search box.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
?>
<div id="search-box">
	<form>
	<b><?php echo elgg_echo('admin:user:label:search'); ?></b>
	<input type="text" name="s"  />
	<input type="submit" name="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" 
		value="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" />
	</form> 
</div>
