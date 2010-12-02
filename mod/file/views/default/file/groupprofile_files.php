<?php

    // Files on group profile page

    //check to make sure group files is activated
    if($vars['entity']->file_enable != 'no'){

?>
<div class="group_tool_widget files">
<h3><?php echo elgg_echo("file:group"); ?></h3>

<?php

	//the number of files to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 6;

	//get the group's files
	$files = elgg_get_entities(array('type' => 'object',
									'subtype' => 'file',
									'container_guid' => $vars['entity']->guid,
									'limit' => $number
	));

	//if there are some files, go get them
	if ($files) {

            //display in list mode
            foreach($files as $f){

                $mime = $f->mimetype;
                echo "<div class='entity-listing clearfix'>";
            	echo "<div class='entity-listing-icon'><a href=\"{$f->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $f->thumbnail, 'file_guid' => $f->guid)) . "</a></div>";
            	echo "<div class='entity-listing-info'>";
            	echo "<p class='entity-title'>" . $f->title . "</p>";
            	echo "<p class='entity-subtext'>" . elgg_view_friendly_time($f->time_created) . "</p>";
		        echo "</div></div>";

        	}


        //get a link to the users files
        $users_file_url = elgg_get_site_url() . "pg/file/" . elgg_get_page_owner()->username;

        echo "<p><a href=\"{$users_file_url}\">" . elgg_echo('file:more') . "</a></p>";

	} else {

		echo "<p class='margin-top'>" . elgg_echo("file:none") . "</p>";

	}

?>
</div>

<?php
	}//end of activate check statement
?>