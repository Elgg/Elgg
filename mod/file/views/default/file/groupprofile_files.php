<?php
 
    // Latest forum discussion for the group home page

    //check to make sure this group forum has been activated
    if($vars['entity']->files_enable != 'no'){

?>

<script type="text/javascript">
$(document).ready(function () {

$('a.show_file_desc').click(function () {
	$(this.parentNode).children("[class=filerepo_listview_desc]").slideToggle("fast");
	return false;
});

}); /* end document ready function */
</script>

<div id="filerepo_widget_layout"> 
<h2><?php echo elgg_echo("file:group"); ?></h2>

<?php

	//the number of files to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 10;
	
	//get the group's files
	$files = elgg_get_entities(array('type' => 'object',
									'subtype' => 'file',
									'container_guid' => $vars['entity']->guid,
									'limit' => $number,
	));
	
	//if there are some files, go get them
	if ($files) {
    	       	    
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
        	
        	
        //get a link to the users files
        $users_file_url = $vars['url'] . "pg/file/" . page_owner_entity()->username;
        	
        echo "<div class=\"forum_latest\"><a href=\"{$users_file_url}\">" . elgg_echo('file:more') . "</a></div>";
       
	} else {
		
		echo "<div class=\"forum_latest\">" . elgg_echo("file:none") . "</div>";

	}

?>
<div class="clearfloat" /></div>
</div>

<?php
	}//end of activate check statement
?>