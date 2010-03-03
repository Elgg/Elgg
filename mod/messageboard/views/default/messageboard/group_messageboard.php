<?php

     /**
	 * Elgg messageboard group profile view
	 *
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 // get the groups passed over here
	 
	 //get the full page owner entity
	 $entity = $vars['entity'];
     
     $num_display = 10;
	 
?>
<script type="text/JavaScript">
$(document).ready(function(){
     
    $("#postit").click(function(){
        
        //display the ajax loading gif at the start of the function call
        //$('#loader').html('<img src="<?php echo $vars['url']; ?>_graphics/ajax_loader.gif" />');
        $('#loader').html('<?php echo elgg_view('ajax/loader',array('slashes' => true)); ?>');
        
        
        //load the results back into the message board contents and remove the loading gif
        //remember that the actual div being populated is determined on views/default/messageboard/messageboard.php     
        $("#messageboard_wrapper").load("<?php echo $vars['url']; ?>mod/messageboard/ajax_endpoint/load.php", {messageboard_content:$("[name=message_content]").val(), pageOwner:$("[name=pageOwner]").val(), numToDisplay:<?php echo $num_display; ?>}, function(){
                    $('#loader').empty(); // remove the loading gif
                    $('[name=message_content]').val(''); // clear the input textarea
                }); //end 
                 
    }); // end of the main click function
        
}); //end of the document .ready function   
</script>

<div id="mb_input_wrapper"><!-- start of mb_input_wrapper div -->

    <h2><?php echo elgg_echo("messageboard:board"); ?></h2>
  
<?php 
    //if not a member don't display the add comment to messageboard
    if(is_group_member($entity->guid, $_SESSION['guid'])){
?>

    <!-- message textarea -->
    <textarea name="message_content" id="testing" value="" class="input_textarea"></textarea>
   
    <!-- the person posting an item on the message board -->
    <input type="hidden" name="guid" value="<?php echo $_SESSION['guid']; ?>" class="guid"  />
   
    <!-- the page owner, this will be the profile owner -->
    <input type="hidden" name="pageOwner" value="<?php echo page_owner(); ?>" class="pageOwner"  />
   
    <!-- submit button -->
    <input type="submit" id="postit" value="<?php echo elgg_echo('messageboard:postit'); ?>">
    
    <!-- menu options -->
    <div id="messageboard_widget_menu">
        <a href="<?php echo $vars['url']; ?>pg/messageboard/<?php echo get_entity(page_owner())->username; ?>"><?php echo elgg_echo("messageboard:viewall"); ?></a>
    </div>
    
    <!-- loading graphic -->
    <div id="loader" class="loading">  </div>
    
<?php
    }
?>

</div><!-- end of mb_input_wrapper div -->


	<?php
    
        //this for the first time the page loads, grab the latest 5 messages.
		$contents = $entity->getAnnotations('messageboard', $num_display, 0, 'desc');
		
		//as long as there is some content to display, display it
		if (!empty($contents)) {
    		
    		echo elgg_view('messageboard/messageboard',array('annotation' => $contents));
		
		} else {
    		
    		//put the required div on the page for the first message
    		echo "<div id=\"messageboard_wrapper\" /></div>";
	
    	}
	
	?>
