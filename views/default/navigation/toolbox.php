<?php

	/**
	 * Elgg standard toolbox
	 * The standard user toolbox that displays a users menu options
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

		if (is_array($menu) && sizeof($menu) > 0) {
		
?>

<ul>

<?php

			foreach($menu as $item) {
				
?>

	<li>
		<h2><?php echo $item->name ?></h2>
<?php

				if (sizeof($item->children) > 0 ) {
					echo "<ul>";
					foreach($item->children as $subitem) {
?>
		<li>
			<a href="<?php echo $item->value ?>"><?php echo $item->name; ?></a>
		</li>
<?php
					}
					echo "</ul>";
					
				}

?>
	</li>

<?php
				
			}

?>

</ul>    

<?php

		}

?>