<?php
	/**
	 * Elgg XML output pageshell for ODD
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */

	header("Content-Type: text/xml");

	
?>
<odd>
<?php 
echo $vars['body'];
?>
</odd>