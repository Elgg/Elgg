<?php

	/**
	 * Elgg search listing
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	if (isset($vars['search_viewtype']) && $vars['search_viewtype'] == "gallery") {
		
		echo elgg_view("search/gallery_listing",$vars);
		
	} else {

?>

	<div class="search_listing">
	
		<div class="search_listing_icon">
			<?php

				echo $vars['icon'];
			
			?>
		</div>
		<div class="search_listing_info">
			<?php

				echo $vars['info'];
			
			?>
		</div>		
	
	</div>
	
<?php

	}

?>