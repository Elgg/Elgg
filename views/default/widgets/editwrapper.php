<?php

	/**
	 * Elgg edit widget layout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

?>

<form action="<?php echo $vars['url']; ?>action/widgets/save" method="post">

	<?php

		echo $vars['body'];
	
	?>

	<p>
		<input type="hidden" name="guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
		<input type="submit" value="<?php

			echo elgg_echo('save');			
		
		?>" />
	</p>

</form>