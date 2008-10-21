<?php

	/**
	 * Elgg standard tools drop down
	 * This will be populated depending on the plugins active - only plugin navigation will appear here
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */
	 
		$menu = get_register('menu');
		
		//var_export($menu);

		if (is_array($menu) && sizeof($menu) > 0) {
			$alphamenu = array();
			foreach($menu as $item) {
				$alphamenu[$item->name] = $item;
			}
			ksort($alphamenu);
		
?>

<ul id="topbardropdownmenu">
    <li class="drop"><a href="#"><?php echo(elgg_echo('tools')); ?><!--[if IE 7]></a><![endif]-->
    <!--[if lte IE 6]><table><tr><td><![endif]-->
      <ul>
      <?php

			foreach($alphamenu as $item) {
    			
    			echo "<li><a href=\"{$item->value}\">" . $item->name . "</a></li>";
    			
			} 
				
     ?>
      </ul>
      <!--[if lte IE 6]></td></tr></table></a><![endif]-->
    </li>
</ul>

<?php

		}

?>
