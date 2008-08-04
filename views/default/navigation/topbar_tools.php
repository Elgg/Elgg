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
		
?>

<ul id="tools_menu">
    <li><a href="#">Tools</a>
      <ul>
      <?php

			foreach($menu as $item) {
    			
    			echo "<li><a href=\"{$item->value}\">" . $item->name . "</a></li>";
    			
			} 
				
     ?>
      </ul>
    </li>
</ul>

<?php

		}

?>

<script type="text/javascript">
function tools_menu(){
$(" #tools_menu li").hover(function(){
        $(this).find('ul:first').slideDown("fast");
		$(this).parent().parent().parent().find("#tools_menu a").addClass('tools_menu_on');
		
		},function(){
		$(this).find('ul:first').slideUp("fast");
		$(this).parent().parent().parent().find("#tools_menu a").removeClass('tools_menu_on');
		});
}

 
 
 $(document).ready(function(){					
	tools_menu();
});
</script>

