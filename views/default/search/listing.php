<?php

	/**
	 * Elgg search listing
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

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