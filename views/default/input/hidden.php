<?php
	/**
	 * Create a hidden data field
	 * Use this view for forms rather than creating a hidden tag in the wild as it provides
	 * extra security which help prevent CSRF attacks.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
	 */
?>
<input type="hidden" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" /> 