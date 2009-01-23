<?php
	/**
	 * Elgg user search box.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
?>
<div id="search-box">
	<form action="<?php echo $vars['url']; ?>search/" method="get">
	<b><?php echo elgg_echo('admin:user:label:search'); ?></b>
	<?php

		echo elgg_view('input/text',array('internalname' => 'tag'));
	
	?>
	<input type="hidden" name="object" value="user" />
	<input type="submit" name="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" 
		value="<?php echo elgg_echo('admin:user:label:seachbutton'); ?>" />
	</form> 
</div>
