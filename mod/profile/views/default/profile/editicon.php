<?php

	/**
	 * Elgg profile icon edit form
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
	 */

?>

	<form action="<?php echo $vars['url']; ?>action/profile/iconupload" method="post" enctype="multipart/form-data">
	<p>
		<?php echo elgg_echo("profile:editicon"); ?>:
	</p>
	<p>
		<?php

			echo elgg_view("input/file",array('internalname' => 'profileicon'));
		
		?>
	</p>
	<p>
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("upload"); ?>" />
	</p>
	</form>