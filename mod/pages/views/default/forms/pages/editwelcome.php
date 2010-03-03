<?php
	/**
	 * Elgg Pages Edit welcome page
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 //set some variables
	 if($vars['entity']){
    	 foreach($vars['entity'] as $welcome){
    	    $current_message = $welcome->description;
    	    $object_guid = $welcome->guid;
    	    $access_id = $welcome->access_id;
	    }
	 }else{
    	 $current_message = '';
    	 $object_guid = '';
    	 $access_id = ACCESS_PRIVATE;
	 }
	 
	 $page_owner = $vars['owner']->guid;
	 
?>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/pages/editwelcome" method="post">

    <p class="longtext_editarea">
		<label>
			<?php echo elgg_view("input/longtext",array(
				'internalname' => "pages_welcome",
				'value' => $current_message,
				'disabled' => $disabled
			)); ?>
		</label>
	</p>
	<p>
		<label>
			<?php echo elgg_echo('access'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id)); ?>
		</label>
	</p>
	<input type="hidden" name="owner_guid" value="<?php echo $page_owner; ?>" />
	
	<?php
		echo elgg_view('input/securitytoken');

	    //if it is editing, include the object guid
	    if($object_guid != ''){
    ?>
	    <input type="hidden" name="object_guid" value="<?php echo $object_guid; ?>" />
	<?php
        }
    ?>
    
	<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
</form>
</div>
