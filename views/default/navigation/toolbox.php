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
		$contexts = $vars['config']->menucontexts;

		if (is_array($menu) && sizeof($menu) > 0) {
		
?>
<div class="elggtoolbar">
<div class="elggtoolbar_header"><h1>Your tools</h1></div>
<ul class="drawers">

<?php

			$key = 0;
			foreach($menu as $item) {
				
?>

	<li class="drawer">
		<h2 id="nav_<?php echo $contexts[$key]; ?>" class="drawer-handle"><?php echo $item->name ?></h2>
<?php

				if (sizeof($item->children) > 0 ) {
					echo "<ul>";
					foreach($item->children as $subitem) {
?>
		<li>
			<a href="<?php echo $subitem->value ?>"><?php echo $subitem->name; ?></a>
		</li>
<?php
					}
					echo "</ul>";
					
				}

?>
	</li>

<?php
				$key++;
				
			}

?>

</ul>    
</div><!-- /.elggtoolbar -->

<?php

		}

		if (in_array(get_context(),$contexts)) {
			$key = array_search(get_context(),$contexts);
?>


<script language="javascript">
 $(document).ready(function(){
   	$('h2#nav_<?php echo $contexts[$key]; ?>').click();
 });
</script>


<?php

		}

?>