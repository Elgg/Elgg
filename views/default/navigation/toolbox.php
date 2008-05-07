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
<div class="elggtoolbar">
<ul class="drawers">

<?php

			foreach($menu as $item) {
				
?>

	<li class="drawer">
		<h2 class="drawer-handle"><?php echo $item->name ?></h2>
<?php

				if (sizeof($item->children) > 0 ) {
					echo "<ul>";
					foreach($item->children as $subitem) {
?>
		<li>
			<a href="<?php echo $subitem->value ?>"><?php echo $subitem->name; ?></a> |
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
</div>

<?php

		}

?>

<script type="text/javascript">
$(document).ready(function () {
  $('li.drawer ul:not(:first)').hide(); // hide all ULs inside LI.drawer except the first one
	$('h2.drawer-handle').click(function () {
	// hide all the drawer contents
	$('li.drawer ul:visible').slideUp().prev().removeClass('open');
	// show the associated drawer content to 'this' (this is the current H2 element)
	// since the drawer content is the next element after the clicked H2, we find
	// it and show it using this:
	$(this).addClass('open').next().slideDown();
  });
});
</script>


