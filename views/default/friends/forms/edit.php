<?php

	/**
	 * Elgg friend collections add/edit 
	 * 
	 * @package ElggFriends
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Dave Tosh <dave@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['object'] Optionally, the collection edit
	 */
	 
	// var_export($vars['collection'][0]->id);
 
	// Set title, form destination
		if (isset($vars['collection'])) {
			$action = "friends/editcollection";
			$title = $vars['collection'][0]->name;
		} else  {
			$action = "friends/addcollection";
			$title = "";
	    }

?>

	<form action="<?php echo $vars['url']; ?>action/<?php echo $action; ?>" method="post">
		<p>
			<label><?php echo elgg_echo("friends:collectionname"); ?><br />
			<?php

				echo elgg_view("input/text", array(
									"internalname" => "collection_name",
									"value" => $title,
													));
			
			?>
			</label>
		</p>
		<p>
		<?php
		    if($vars['collection_members']){
    		    echo elgg_echo("friends:collectionfriends") . "<br />";
        	    foreach($vars['collection_members'] as $mem){
            	    
            	   echo elgg_view("profile/icon",array('entity' => $mem, 'size' => 'tiny'));
            	   echo $mem->name;
  
        	    }
    	    }
    	?>
    	</p>
		<p>
			<label><?php echo elgg_echo("friends:addfriends"); ?><br />
			<?php

				//echo elgg_view('friends/friendslist');
				echo elgg_view('friends/picker',array('entities' => $vars['friends'], 'internalname' => 'friends_collection'));
			
			?>
			</label>
		</p>
		<p>
			<?php

				if (isset($vars['collection'])) {
					?><input type="hidden" name="collection_id" value="<?php echo $vars['collection'][0]->id; ?>" /><?php
				}
			
			?>
			<input type="submit" name="submit" value="<?php echo elgg_echo('save'); ?>" />
		</p>
	
	</form>