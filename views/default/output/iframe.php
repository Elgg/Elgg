<?php
	/**
	 * Display a page in an embedded window
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] Source of the page
	 * 
	 */
?>
<iframe src="<?php echo $vars['value']; ?>">
</iframe>