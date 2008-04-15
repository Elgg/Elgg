<?php

	/**
	 * Elgg text input
	 * Displays a text input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
	 */

?>

<input type="text" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" value="<?php echo $vars['value']; ?>" class="input-text"/> 