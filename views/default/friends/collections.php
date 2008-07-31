<?php

	/**
	 * Elgg friends collections
	 * Lists a user's friends collections
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['collections'] The array of friends collections
	 */

		if (!isset($friendspicker)) $friendspicker = 0;

		if (is_array($vars['collections']) && sizeof($vars['collections'])) {
			
			echo "<div class=\"expandall\"><p>expand all</p></div>";
			echo "<ul id=\"friends_collections_accordian\">";
			
			foreach($vars['collections'] as $collection) {
				
				$friendspicker++;
				echo elgg_view('friends/collection',array('collection' => $collection, 'friendspicker' => $friendspicker));
				
			}
			
			echo "</ul>";
			
		} else {
			
			echo elgg_echo("friends:nocollections");
			
		}
    
?>

<script>
$(document).ready(function(){

$('#friends_collections_accordian h2').click(function () {
	$(this.parentNode).children("[class=friends_picker]").slideToggle("fast");
	return false;
});
    
// global more info expand all/close all
$('div.expandall p').click(function () {
	if (this.innerHTML == 'close all') {
		$('div.friends_picker').slideUp("fast");
		$('div.expandall p').html('expand all');
}
else {
		$('div.friends_picker:hidden').slideDown("fast");
		$('div.expandall p').html('close all');
	}
});

});  
</script>