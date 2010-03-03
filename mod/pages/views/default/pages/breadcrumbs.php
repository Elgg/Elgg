<?php

		$page_owner = $vars['page_owner'];
		$parent = $vars['parent'];

		$breadcrumbs = '';
	    
	    $owner_url = $CONFIG->wwwroot . "pg/pages/owned/" . get_entity($page_owner)->username;
	    echo "<div id=\"pages_breadcrumbs\"><b><a href=\"{$owner_url}\">" . elgg_echo('pages:user') . "</a></b>";
	    
	    //see if the new page's parent has a parent
        $getparent = get_entity($parent->parent_guid);
        while ($getparent instanceof ElggObject){
             
             $breadcrumbs = " &gt; <a href=\"{$getparent->getURL()}\">$getparent->title</a>" . $breadcrumbs;
             $getparent = get_entity($getparent->parent_guid);
             
        }
        
        echo $breadcrumbs;
        //if it is adding a page, make the last page a link, otherwise, don't
        if($vars['add']){
	        echo " &gt; <a href=\"{$parent->getURL()}\">$parent->title</a></div>";
        }else{
            echo " &gt; $parent->title</div>";
        }

?>