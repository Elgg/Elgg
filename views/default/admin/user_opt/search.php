<?php
/**
 * Elgg user search box.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
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
	<input type="submit" name="<?php echo elgg_echo('admin:user:label:searchbutton'); ?>"
		value="<?php echo elgg_echo('admin:user:label:searchbutton'); ?>" />
	</form>
</div>
