<?php
/**
 * Elgg user search box.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
 
if( (is_plugin_enabled('search')) && (is_plugin_enabled('profile')) ) { 
?>
	<div class="admin_settings user_search">
		<form action="<?php echo $vars['url']; ?>pg/search/" method="get">
			<h3><?php echo elgg_echo('admin:user:label:search'); ?></h3>
			<?php echo elgg_view('input/text',array('internalname' => 'q')); ?>
			<input type="hidden" name="entity_type" value="user" />
			<input type="hidden" name="search_type" value="entities" />
			<input type="submit" name="<?php echo elgg_echo('admin:user:label:searchbutton'); ?>"
				value="<?php echo elgg_echo('admin:user:label:searchbutton'); ?>" />
		</form>
	</div>
<?php
}
?>