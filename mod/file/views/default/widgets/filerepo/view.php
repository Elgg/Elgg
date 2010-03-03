<script type="text/javascript">
$(document).ready(function () {

$('a.show_file_desc').click(function () {
	$(this.parentNode).children("[class=filerepo_listview_desc]").slideToggle("fast");
	return false;
});

}); /* end document ready function */
</script>


<?php

    //the page owner
	$owner = $vars['entity']->owner_guid;
	
	//the number of files to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 1;
	
	//get the layout view which is set by the user in the edit panel
	$get_view = (int) $vars['entity']->gallery_list;
	if (!$get_view || $get_view == 1) {
	    $view = "list";
    }else{
        $view = "gallery";
    }

	//get the user's files
	$files = get_user_objects($vars['entity']->owner_guid, "file", $number, 0);
	
	//if there are some files, go get them
	if ($files) {
    	
    	echo "<div id=\"filerepo_widget_layout\">";
        
        if($view == "gallery"){
        
        echo "<div class=\"filerepo_widget_galleryview\">";
        	
            //display in gallery mode
            foreach($files as $f){
            	
                $mime = $f->mimetype;
                echo "<a href=\"{$f->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $f->thumbnail, 'file_guid' => $f->guid)) . "</a>";
            				
            }
            
            echo "</div>";
            
        }else{
        	    
            //display in list mode
            foreach($files as $f){
            	
                $mime = $f->mimetype;
                echo "<div class=\"filerepo_widget_singleitem\">";
            	echo "<div class=\"filerepo_listview_icon\"><a href=\"{$f->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $f->thumbnail, 'file_guid' => $f->guid)) . "</a></div>";
            	echo "<div class=\"filerepo_widget_content\">";
            	echo "<div class=\"filerepo_listview_title\"><p class=\"filerepo_title\">" . $f->title . "</p></div>";
            	echo "<div class=\"filerepo_listview_date\"><p class=\"filerepo_timestamp\"><small>" . friendly_time($f->time_created) . "</small></p></div>";
            	$description = $f->description;
		        if (!empty($description)) echo "<a href=\"javascript:void(0);\" class=\"show_file_desc\">". elgg_echo('more') ."</a><br /><div class=\"filerepo_listview_desc\">" . $description . "</div>";
		        echo "</div><div class=\"clearfloat\"></div></div>";
            				
        	}
        	    
        }
        	
        	
        //get a link to the users files
        $users_file_url = $vars['url'] . "pg/file/" . get_user($f->owner_guid)->username;
        	
        echo "<div class=\"filerepo_widget_singleitem_more\"><a href=\"{$users_file_url}\">" . elgg_echo('file:more') . "</a></div>";
        echo "</div>";
        	
				
	} else {
		
		echo "<div class=\"contentWrapper\">" . elgg_echo("file:none") . "</div>";
		
	}

?>