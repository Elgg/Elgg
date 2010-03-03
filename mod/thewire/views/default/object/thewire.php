<?php

	/**
	 * Elgg thewire note view
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 *
	 * @question - do we want users to be able to edit thewire?
	 * 
	 * @uses $vars['entity'] Optionally, the note to view
	 */

	if (isset($vars['entity'])) {
    		
    		$user_name = $vars['entity']->getOwnerEntity()->name;
    		
    		//if the note is a reply, we need some more info
    		
			$note_url = '';
			$note_owner = elgg_echo("thewire:notedeleted");
    		
?>
<div class="thewire-singlepage">
	<div class="thewire-post">
			    
	    <!-- the actual shout -->
		<div class="note_body">

	    <div class="thewire_icon">
	    <?php
		        echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'small'));
	    ?>
	    </div>

			<div class="thewire_options">
			
			<a href="<?php echo $vars['url']; ?>mod/thewire/add.php?wire_username=<?php echo $vars['entity']->getOwnerEntity()->username; ?>" class="reply"><?php echo elgg_echo('thewire:reply'); ?></a>
		<?php
/*		    //only have a reply option for main notes, not other replies
		    if($vars['entity']->parent == 0){
        ?>
		<a href="<?php echo $vars['url']; ?>mod/thewire/reply.php?note_id=<?php echo $vars['entity']->guid; ?>" class="reply">reply</a>
		<?php
	        }
*/
	    ?>
	    <div class="clearfloat"></div>
	    		<?php
				   
			// if the user looking at thewire post can edit, show the delete link
			if ($vars['entity']->canEdit()) {
						
	  
					   echo "<div class='delete_note'>" . elgg_view("output/confirmlink",array(
															'href' => $vars['url'] . "action/thewire/delete?thewirepost=" . $vars['entity']->getGUID(),
															'text' => elgg_echo('delete'),
															'confirm' => elgg_echo('deleteconfirm'),
														)) . "</div>";
			
			} //end of can edit if statement
		?>
	    </div>
	    
		
		<?php
		    echo "<b>{$user_name}: </b>";
		    

		    $desc = $vars['entity']->description;

		    $desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'pg/thewire/$1">$1</a>',$desc);
			echo parse_urls($desc);
		?>
		
		
		<div class="clearfloat"></div>
		</div>
		<div class="note_date">
		
		<?php
			
				echo elgg_echo("thewire:wired") . " " . sprintf(elgg_echo("thewire:strapline"),
								friendly_time($vars['entity']->time_created)
				);
				
				echo " via " . elgg_echo($vars['entity']->method) . ".";
			
		?>
		</div>
		
		
	</div>
</div>
<?php

		}

?>