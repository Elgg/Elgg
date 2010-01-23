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
	<form action="<?php echo $vars['url']; ?>pg/search/" method="get">
	<b><?php echo elgg_echo('admin:user:label:search'); ?></b>
	<?php

		echo elgg_view('input/text',array('internalname' => 'q'));

	?>
	<input type="hidden" name="entity_type" value="user" />
	<input type="hidden" name="search_type" value="entities" />
	<input type="submit" name="<?php echo elgg_echo('admin:user:label:searchbutton'); ?>"
		value="<?php echo elgg_echo('admin:user:label:searchbutton'); ?>" />
	</form>
</div>
